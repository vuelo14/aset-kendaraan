<?php

namespace Helpers;

class GeminiAI
{
    /**
     * Dapatkan rekomendasi komponen/jasa pemeliharaan dari Google Gemini AI
     * dengan dukungan multi-tier model fallback (jika model utama high demand / error).
     */
    public static function getRecommendation(array $context): array
    {
        $apiKey = defined('GEMINI_API_KEY') && GEMINI_API_KEY ? GEMINI_API_KEY : env('GEMINI_API_KEY', '');
        if (empty($apiKey)) {
            return [
                'success' => false,
                'error' => 'API Key Gemini belum disetel. Silakan tambahkan GEMINI_API_KEY di file .env Anda.'
            ];
        }

        $primaryModel = defined('GEMINI_MODEL') && GEMINI_MODEL ? GEMINI_MODEL : env('GEMINI_MODEL', 'gemini-2.5-flash');

        // Daftar fallback models dari .env atau default cascade
        $fallbackEnv = defined('GEMINI_FALLBACK_MODELS') && GEMINI_FALLBACK_MODELS ? GEMINI_FALLBACK_MODELS : env('GEMINI_FALLBACK_MODELS', 'gemini-2.0-flash,gemini-1.5-flash');
        $fallbackList = array_map('trim', explode(',', (string)$fallbackEnv));

        // Susun urutan model yang akan dicoba tanpa duplikasi
        $modelsToTry = array_values(array_unique(array_filter(array_merge([$primaryModel], $fallbackList))));

        $prompt = self::buildPrompt($context);
        $attempts = [];

        foreach ($modelsToTry as $index => $model) {
            $result = self::callApi($model, $apiKey, $prompt);

            if ($result['success']) {
                $result['used_model'] = $model;
                $result['primary_model'] = $primaryModel;
                $result['attempts'] = $attempts;

                if ($index > 0) {
                    $result['fallback_used'] = true;
                    $result['fallback_message'] = "Model utama ({$primaryModel}) sedang mengalami kendala / high demand. Rekomendasi berhasil dialihkan ke model {$model}.";
                } else {
                    $result['fallback_used'] = false;
                }

                return $result;
            }

            // Catat kegagalan model ini
            $httpCode = $result['http_code'] ?? 0;
            $errMsg = $result['error'] ?? 'Unknown error';
            $attempts[$model] = [
                'http_code' => $httpCode,
                'error' => $errMsg
            ];

            // Jika API Key memang salah total (API_KEY_INVALID), hentikan agar tidak membuang waktu
            if ($httpCode === 400 && stripos($errMsg, 'API_KEY_INVALID') !== false) {
                return [
                    'success' => false,
                    'error' => 'API Key Gemini tidak valid. Silakan periksa kembali GEMINI_API_KEY pada file .env.'
                ];
            }
        }

        // Jika semua model gagal
        $errSummary = [];
        foreach ($attempts as $mod => $att) {
            $errSummary[] = "{$mod} (" . ($att['http_code'] ? "HTTP {$att['http_code']}: " : "") . "{$att['error']})";
        }

        return [
            'success' => false,
            'error' => 'Semua model Gemini yang dicoba (' . implode(', ', $modelsToTry) . ') gagal merespon atau sedang overload/high demand: ' . implode(' | ', $errSummary),
            'attempts' => $attempts
        ];
    }


    /**
     * Melakukan HTTP request ke Gemini API via cURL
     */
    private static function callApi(string $model, string $apiKey, string $prompt): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'responseMimeType' => 'application/json'
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'error' => 'Gagal menghubungi Gemini API: ' . $curlError
            ];
        }

        if ($httpCode !== 200) {
            $errData = json_decode($response, true);
            $errMsg = $errData['error']['message'] ?? "HTTP Error {$httpCode}";
            return [
                'success' => false,
                'http_code' => $httpCode,
                'error' => "Gemini API ({$httpCode}): {$errMsg}"
            ];
        }

        $resJson = json_decode($response, true);
        $rawText = $resJson['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Bersihkan formatting markdown jika ada
        $cleanText = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
        $cleanText = preg_replace('/\s*```$/', '', $cleanText);

        $parsed = json_decode($cleanText, true);
        if (!$parsed || !isset($parsed['rekomendasi'])) {
            return [
                'success' => false,
                'error' => 'Respon AI tidak sesuai format JSON yang diharapkan.',
                'raw' => $rawText
            ];
        }

        return [
            'success' => true,
            'data' => $parsed
        ];
    }

    /**
     * Membangun instruksi prompt yang kaya konteks untuk Gemini AI
     */
    private static function buildPrompt(array $c): string
    {
        $v = $c['vehicle'] ?? [];
        $m = $c['maintenance'] ?? [];
        $currentDetails = $c['current_details'] ?? [];
        $history = $c['history'] ?? [];
        $budget = $c['budget'] ?? [];
        $catalog = $c['catalog'] ?? [];

        // Format current details
        $currentDetailsList = [];
        foreach ($currentDetails as $cd) {
            $currentDetailsList[] = "- " . $cd['nama'] . " (Qty: " . $cd['jumlah'] . " " . ($cd['satuan'] ?? '') . ", Subtotal: Rp " . number_format($cd['subtotal'], 0, ',', '.') . ")";
        }
        $currentDetailsStr = !empty($currentDetailsList) ? implode("\n", $currentDetailsList) : "Belum ada rincian komponen/jasa yang diinput.";

        // Format history
        $historyList = [];
        foreach ($history as $h) {
            $subItems = [];
            if (!empty($h['details'])) {
                foreach ($h['details'] as $hd) {
                    $subItems[] = $hd['nama'] . " (" . $hd['jumlah'] . " " . ($hd['satuan'] ?? '') . ")";
                }
            }
            $historyList[] = sprintf(
                "- Tanggal: %s | Jenis: %s | Biaya: Rp %s | Bengkel: %s | Catatan: %s | Rincian: %s",
                $h['date'] ?? '-',
                $h['jenis'] ?? '-',
                number_format($h['biaya'] ?? 0, 0, ',', '.'),
                $h['bengkel'] ?? '-',
                $h['notes'] ?? '-',
                !empty($subItems) ? implode(', ', $subItems) : 'Tidak ada rincian komponen tercatat'
            );
        }
        $historyStr = !empty($historyList) ? implode("\n", $historyList) : "Belum ada riwayat servis sebelumnya.";

        // Format catalog components
        $catalogList = [];
        foreach ($catalog as $item) {
            $catalogList[] = sprintf(
                "ID:%d | [%s] %s | Satuan: %s | Harga: Rp %s",
                $item['id'],
                strtoupper($item['jenis']),
                $item['nama'],
                $item['satuan'] ?? '-',
                number_format($item['harga'] ?? 0, 0, ',', '.')
            );
        }
        $catalogStr = implode("\n", $catalogList);

        $maxBudgetNum = (float)($budget['max_unit_budget'] ?? 0);
        $realisasiBudgetNum = (float)($budget['realisasi_unit'] ?? 0);
        $sisaBudgetNum = (float)($budget['sisa_budget_unit'] ?? 0);
        $hasUnitBudget = ($maxBudgetNum > 0);
        $categoryName = $budget['category_name'] ?? '-';
        $isCustom = !empty($budget['custom_budget']);

        $budgetSection = "";
        if ($hasUnitBudget) {
            $budgetSection = "- Tipe Pagu: " . ($isCustom ? "Pagu Presisi Khusus Unit" : "Standar Kategori") . "\n"
                . "- Pagu Maksimal per Unit: Rp " . number_format($maxBudgetNum, 0, ',', '.') . "\n"
                . "- Realisasi Pengeluaran Saat Ini: Rp " . number_format($realisasiBudgetNum, 0, ',', '.') . "\n"
                . "- Sisa Anggaran Tersedia: Rp " . number_format($sisaBudgetNum, 0, ',', '.');
            $budgetRule = "- Sisa anggaran unit saat ini: Rp " . number_format($sisaBudgetNum, 0, ',', '.') . ".\n"
                . "   - Usahakan total biaya rekomendasi Anda berada dalam batas sisa anggaran yang tersedia.\n"
                . "   - Nyatakan status anggaran: \"Aman\" (jika total rekomendasi <= sisa anggaran), atau \"Mendekati Batas\", atau \"Melebihi Anggaran\".";
        } else {
            $budgetSection = "- Tipe Pagu: Belum Ditentukan per Unit (Kategori: {$categoryName})\n"
                . "- Realisasi Pengeluaran Saat Ini: Rp " . number_format($realisasiBudgetNum, 0, ',', '.') . "\n"
                . "- Sisa Anggaran: Belum dibatasi pagu unit individual (Prioritaskan efisiensi anggaran dinas).";
            $budgetRule = "- Pagu spesifik unit belum ditentukan. Berikan rekomendasi yang paling efisien, wajar, dan ekonomis sesuai kebutuhan armada dinas.\n"
                . "   - Nyatakan status anggaran: \"Aman\" atau \"Mendekati Batas\" berdasarkan kewajaran total biaya servis.";
        }

        return <<<PROMPT
Anda adalah asisten ahli mekanik armada otomotif dan pengelola anggaran pemeliharaan aset kendaraan dinas operasional (Disnaker).
Tugas Anda:
Analisis servis yang sedang diproses untuk kendaraan berikut, periksa riwayat servis masa lalu, pertimbangkan sisa pagu anggaran unit, lalu rekomendasikan rincian komponen (sparepart) dan jasa yang tepat untuk ditambahkan ke servis ini.

=== DATA KENDARAAN ===
- Plat Nomor: {$v['plat']}
- Merk & Tipe: {$v['merk']} {$v['tipe']}
- Tahun Pembuatan: {$v['tahun']}
- Kategori/Jenis: {$v['jenis']}
- Kondisi Saat Ini: {$v['kondisi']}
- Status Penggunaan: {$v['status_penggunaan']}

=== SERVIS YANG SEDANG DIPROSES ===
- ID Pemeliharaan: {$m['id']}
- Tanggal Servis: {$m['date']}
- Jenis Pemeliharaan: {$m['jenis']}
- Bengkel: {$m['bengkel']}
- Catatan / Keluhan: {$m['notes']}
- Rincian yang SUDAH diinput pada servis ini:
{$currentDetailsStr}
(PERINGATAN: JANGAN rekomendasikan ulang komponen atau jasa yang SUDAH ada di rincian di atas!)

=== KETERSEDIAAN ANGGARAN UNIT KENDARAAN INI ===
- Kategori Anggaran: {$categoryName}
{$budgetSection}

=== RIWAYAT PEMELIHARAAN SEBELUMNYA PADA KENDARAAN INI ===
{$historyStr}

=== DAFTAR KATALOG MASTER KOMPONEN & JASA RESMI TERSEDIA DI DATABASE ===
{$catalogStr}

=== INSTRUKSI ANALISIS & REKOMENDASI ===
1. Analisis jenis servis dan keluhan pada servis ini.
2. Periksa riwayat pemeliharaan sebelumnya:
   - Komponen berkala apa yang sudah lama tidak diganti (misal: oli mesin, oli gardan, filter oli, busi, kampas rem, servis CVT/rantai, air radiator, dsb) sesuai usia kendaraan ({$v['tahun']}) dan kategori ({$v['jenis']}).
   - Jangan rekomendasikan komponen yang baru saja diganti di riwayat servis terdekat.
3. KETENTUAN ANGGARAN:
   {$budgetRule}
4. PENCOCOKAN KATALOG MASTER:
   - UTAMAKAN memilih item dari "DAFTAR KATALOG MASTER KOMPONEN & JASA RESMI".
   - Jika cocok, isi "komponen_id" dengan ID angka yang persis sama dari katalog, serta gunakan nama, jenis, satuan, dan harga_satuan dari katalog tersebut.
   - Jika ada item esensial yang tidak ada di katalog, Anda boleh menyarankan dengan "komponen_id": null.
5. FORMAT OUTPUT:
   Keluarkan HANYA format JSON valid tanpa tanda kutip markdown pembungkus di luar JSON, dengan struktur berikut:
{
  "analisis": "Penjelasan ringkas analisis kondisi kendaraan, riwayat pemeliharaan sebelumnya, dan alasan pemilihan komponen/jasa yang direkomendasikan.",
  "status_anggaran": "Aman",
  "catatan_anggaran": "Catatan singkat mengenai ketersediaan dan dampak terhadap sisa pagu anggaran.",
  "sisa_anggaran_saat_ini": {$sisaBudgetNum},
  "estimasi_total_rekomendasi": 0,
  "proyeksi_sisa_anggaran": 0,
  "rekomendasi": [
    {
      "komponen_id": 123,
      "nama": "Nama Komponen/Jasa",
      "jenis": "komponen",
      "jumlah": 1,
      "satuan": "Botol",
      "harga_satuan": 50000,
      "subtotal": 50000,
      "alasan": "Alasan singkat mengapa komponen ini direkomendasikan"
    }
  ]
}
PROMPT;
    }
}
