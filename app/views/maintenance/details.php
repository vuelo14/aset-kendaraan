<?php
$isRealisasi = (stripos($maintenance['notes'] ?? '', 'realisasi') !== false);

$maxBudget = (float)($budget['max_unit_budget'] ?? 0);
$realisasiBudget = (float)($budget['realisasi_unit'] ?? 0);
$sisaBudget = (float)($budget['sisa_budget_unit'] ?? 0);
$hasBudget = ($maxBudget > 0);
$budgetPercent = $hasBudget ? min(100, round(($realisasiBudget / $maxBudget) * 100)) : 0;
$budgetBadgeClass = 'bg-success';
if ($budgetPercent >= 90) {
    $budgetBadgeClass = 'bg-danger';
} elseif ($budgetPercent >= 70) {
    $budgetBadgeClass = 'bg-warning text-dark';
}
?>

<style>
.btn-gradient-ai {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
    color: #fff !important;
    border: none;
    font-weight: 500;
    transition: all 0.25s ease-in-out;
}
.btn-gradient-ai:hover {
    filter: brightness(1.12);
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.4);
    transform: translateY(-1px);
}
.bg-gradient-ai-subtle {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(124, 58, 237, 0.08) 100%);
}
</style>

<div class="row">
    <div class="col-12">
        <a href="/maintenance" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pemeliharaan
        </a>

        <?php if ($isRealisasi): ?>
            <div class="alert alert-warning border d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-lock-fill fs-3 text-warning-emphasis me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold text-dark"><i class="bi bi-shield-lock me-1"></i> Status Terkunci (Realisasi)</h6>
                        <p class="mb-0 small text-muted">
                            Catatan pemeliharaan ini berstatus <strong>Realisasi</strong>. Rincian komponen dan jasa pada pemeliharaan ini telah dikunci sehingga <strong>tidak dapat ditambah, diubah, atau dihapus</strong>.
                        </p>
                    </div>
                </div>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <form method="post" action="/maintenance/unlock" class="ms-auto" onsubmit="return confirm('Buka kunci status Realisasi agar komponen dapat ditambah/diedit kembali?')">
                        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                        <input type="hidden" name="id" value="<?= $maintenance['id'] ?>">
                        <input type="hidden" name="redirect" value="/maintenance/details?id=<?= $maintenance['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-unlock me-1"></i> Buka Kunci Realisasi
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Baris Informasi Pemeliharaan & Monitoring Anggaran Unit -->
        <div class="row g-3 mb-4">
            <!-- Informasi Pemeliharaan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header py-3 bg-white">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Pemeliharaan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="150" class="text-muted">Kendaraan</th>
                                <td>:
                                    <?= htmlspecialchars($vehicle['plat'] . ' - ' . $vehicle['merk'] . ' ' . ($vehicle['tipe'] ?? '')) ?>
                                    <?php if (!empty($vehicle['current_responsible'])): ?>
                                        <br><small class="text-primary ms-2"><i class="bi bi-person"></i>
                                            <?= htmlspecialchars($vehicle['current_responsible']) ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Service</th>
                                <td>:
                                    <?= date('d M Y', strtotime($maintenance['date'])) ?? $maintenance['date'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Jenis</th>
                                <td>:
                                    <?= htmlspecialchars($maintenance['jenis']) ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Bengkel</th>
                                <td>:
                                    <?= htmlspecialchars($maintenance['bengkel'] ?? '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Catatan</th>
                                <td>:
                                    <?= htmlspecialchars($maintenance['notes'] ?: '-') ?>
                                    <?php if ($isRealisasi): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">
                                            <i class="bi bi-lock-fill me-1"></i>Realisasi (Terkunci)
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Total Tagihan</th>
                                <td>: <span class="fw-bold text-danger fs-6">Rp
                                        <?= number_format($maintenance['biaya'] ?? 0, 0, ',', '.') ?>
                                    </span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Monitoring Anggaran Unit Kendaraan -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-wallet2 text-success me-2"></i>Pagu Anggaran Unit</h5>
                            <?php if (!empty($budget['custom_budget'])): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle" title="Pagu presisi telah ditentukan untuk kendaraan ini">Presisi</span>
                            <?php endif; ?>
                        </div>
                        <span class="badge bg-light text-dark border">
                            <?= htmlspecialchars($budget['category_name'] ?? 'Kategori Umum') ?>
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <?php if ($hasBudget): ?>
                            <div>
                                <div class="d-flex justify-content-between align-items-baseline mb-1">
                                    <span class="text-muted small">Sisa Anggaran Tersedia:</span>
                                    <span class="badge <?= $budgetBadgeClass ?>">Terpakai <?= $budgetPercent ?>%</span>
                                </div>
                                <div class="h3 fw-bold text-<?= $sisaBudget >= 0 ? 'success' : 'danger' ?> mb-2">
                                    Rp <?= number_format($sisaBudget, 0, ',', '.') ?>
                                </div>

                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar <?= $budgetBadgeClass ?>" role="progressbar"
                                        style="width: <?= min($budgetPercent, 100) ?>%" aria-valuenow="<?= $budgetPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="bg-light p-2 rounded-2 small">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Pagu Maksimal Unit</span>
                                    <span class="fw-semibold">Rp <?= number_format($maxBudget, 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">Realisasi Berjalan</span>
                                    <span class="fw-semibold text-danger">Rp <?= number_format($realisasiBudget, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-light border text-center py-3 mb-0 my-auto">
                                <i class="bi bi-wallet2 text-secondary fs-3 d-block mb-1"></i>
                                <div class="fw-bold text-dark">Pagu Unit Belum Ditetapkan</div>
                                <p class="text-muted small mb-2">Pagu spesifik untuk kendaraan ini belum disetel sehingga perhitungan anggaran masih bersifat umum.</p>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <a href="/budget" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-sliders me-1"></i> Atur Pagu di Menu Anggaran
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Rincian Komponen / Jasa -->
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0 fw-bold"><i class="bi bi-tools text-primary me-2"></i>Rincian Komponen / Jasa</h5>
                <div class="d-flex align-items-center gap-2">
                    <?php if (Core\Auth::role() === 'admin' && !$isRealisasi): ?>
                        <button class="btn btn-sm btn-gradient-ai shadow-sm" type="button" onclick="openAiModal()">
                            <i class="bi bi-stars text-warning me-1"></i> Rekomendasi AI (Gemini)
                        </button>
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#formDetailAdd" aria-expanded="false">
                            <i class="bi bi-plus-lg"></i> Tambah Manual
                        </button>
                    <?php elseif ($isRealisasi): ?>
                        <span class="badge bg-secondary-subtle text-secondary border py-2 px-3">
                            <i class="bi bi-lock-fill me-1"></i> Terkunci (Realisasi)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="collapse mb-4" id="formDetailAdd">
                    <div class="card card-body bg-light border-0">
                        <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle me-1"></i> Form Tambah Rincian Manual</h6>
                        <form method="post" action="/maintenance/details/store" class="row g-3 align-items-end">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                            <input type="hidden" name="maintenance_id" value="<?= $maintenance['id'] ?>">

                            <div class="col-md-4">
                                <label class="form-label">Komponen / Jasa</label>
                                <select name="komponen_id" id="komponen_id" class="form-select select2" required
                                    onchange="setHarga()">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($komponen as $k): ?>
                                        <option value="<?= $k['id'] ?>" data-harga="<?= $k['harga'] ?>"
                                            data-satuan="<?= $k['satuan'] ?>">
                                            [<?= strtoupper($k['jenis']) ?>]
                                            <?= htmlspecialchars($k['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Jumlah / Qty</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="jumlah" id="jumlah" class="form-control"
                                        value="1" required oninput="calcSubtotal()">
                                    <span class="input-group-text" id="satuan_label">-</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Harga Satuan (Rp)</label>
                                <input type="number" step="0.01" name="harga_satuan" id="harga_satuan"
                                    class="form-control" required oninput="calcSubtotal()">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Subtotal (Rp)</label>
                                <input type="text" id="subtotal_view" class="form-control" readonly>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan
                                    Rincian</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Deskripsi (Komponen/Jasa)</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                                <?php if (Core\Auth::role() === 'admin' && !$isRealisasi): ?>
                                    <th class="text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $grand_total = 0;
                            foreach ($details as $d): ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            <?= htmlspecialchars($d['nama']) ?>
                                        </div>
                                        <span class="badge bg-<?= $d['jenis'] == 'jasa' ? 'info' : 'secondary' ?>"><small>
                                                <?= strtoupper($d['jenis']) ?>
                                            </small></span>
                                    </td>
                                    <td>
                                        <?= rtrim(rtrim(number_format($d['jumlah'], 2, '.', ''), '0'), '.') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($d['satuan'] ?? '-') ?>
                                    </td>
                                    <td class="text-end">Rp
                                        <?= number_format($d['harga_satuan'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-end fw-bold">Rp
                                        <?= number_format($d['subtotal'], 0, ',', '.') ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin' && !$isRealisasi): ?>
                                        <td class="text-center text-nowrap">
                                            <button type="button" class="btn btn-outline-primary btn-sm me-1" title="Edit Harga"
                                                onclick="openEditHargaModal(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['nama']), ENT_QUOTES) ?>', <?= (float)$d['jumlah'] ?>, '<?= htmlspecialchars(addslashes($d['satuan'] ?? ''), ENT_QUOTES) ?>', <?= (float)$d['harga_satuan'] ?>)">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <form method="post" action="/maintenance/details/delete" class="d-inline"
                                                onsubmit="return confirm('Hapus rincian ini?')">
                                                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                <input type="hidden" name="maintenance_id" value="<?= $maintenance['id'] ?>">
                                                <button class="btn btn-outline-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <?php $grand_total += $d['subtotal']; ?>
                            <?php endforeach; ?>

                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary-subtle"></i>
                                        Belum ada rincian komponen/jasa yang diinput.
                                        <?php if (Core\Auth::role() === 'admin' && !$isRealisasi): ?>
                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-gradient-ai shadow-sm" type="button" onclick="openAiModal()">
                                                    <i class="bi bi-stars text-warning me-1"></i> Gunakan Rekomendasi AI Gemini
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr class="table-light fw-bold">
                                    <td colspan="5" class="text-end text-uppercase">Total Tagihan</td>
                                    <td class="text-end text-danger fs-5">Rp
                                        <?= number_format($grand_total, 0, ',', '.') ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Harga Komponen / Jasa -->
<div class="modal fade" id="modalEditHarga" tabindex="-1" aria-labelledby="modalEditHargaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="/maintenance/details/update">
                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                <input type="hidden" name="maintenance_id" value="<?= $maintenance['id'] ?>">
                <input type="hidden" name="id" id="edit_detail_id" value="">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalEditHargaLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Harga Komponen / Jasa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nama Komponen / Jasa</label>
                        <div class="p-2 bg-light rounded border fw-bold text-dark" id="edit_nama_komponen">
                            -
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label text-muted small fw-semibold">Jumlah (Qty)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" name="jumlah" id="edit_jumlah" class="form-control" required oninput="calcEditHargaSubtotal()">
                                <span class="input-group-text" id="edit_satuan_label">-</span>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <label class="form-label text-muted small fw-semibold">Harga Satuan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="1" min="0" name="harga_satuan" id="edit_harga_satuan" class="form-control fw-bold" required oninput="calcEditHargaSubtotal()">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-semibold">Subtotal Perhitungan (Rp)</label>
                            <input type="text" id="edit_subtotal_view" class="form-control bg-light fw-bold text-primary fs-6" readonly>
                            <small class="text-muted">Total tagihan pemeliharaan akan otomatis disesuaikan setelah disimpan.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Harga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rekomendasi AI -->
<div class="modal fade" id="modalAiRecommendation" tabindex="-1" aria-labelledby="modalAiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary bg-opacity-25 p-2 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                        <i class="bi bi-stars fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold mb-0" id="modalAiLabel">Rekomendasi Pemeliharaan AI</h5>
                            <span class="badge bg-primary bg-opacity-50 text-white border border-light-subtle" id="aiModelBadge">
                                <i class="bi bi-cpu me-1"></i> <?= htmlspecialchars(GEMINI_MODEL) ?>
                            </span>
                        </div>
                        <small class="text-white-50">Didukung Google Gemini &bull; Multi-Model Fallback &bull; Pagu Anggaran Unit</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Loading State -->
                <div id="aiLoading" class="text-center py-5">
                    <div class="spinner-grow text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="fw-bold text-dark">Gemini AI Sedang Menganalisis...</h5>
                    <p class="text-muted small mx-auto" style="max-width: 540px;">
                        Sedang memeriksa riwayat servis kendaraan sebelumnya, keluhan servis ini, katalog resmi, dan sisa pagu anggaran unit yang tersedia.
                    </p>
                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-4 text-start small text-muted">
                        <div><i class="bi bi-check2-circle text-success me-1"></i> Riwayat Servis Terdahulu</div>
                        <div><i class="bi bi-check2-circle text-success me-1"></i> Ketersediaan Anggaran Unit</div>
                        <div><i class="bi bi-check2-circle text-success me-1"></i> Katalog Suku Cadang Resmi</div>
                    </div>
                </div>

                <!-- Error State -->
                <div id="aiError" class="d-none py-3">
                    <div class="alert alert-danger border-danger-subtle d-flex align-items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">Gagal Memuat Rekomendasi AI</h6>
                            <p class="mb-2" id="aiErrorMessage"></p>
                            <div class="small bg-white p-3 rounded border border-danger-subtle text-muted mb-3 font-monospace">
                                <strong>Panduan Pengaturan API Key:</strong><br>
                                1. Dapatkan API Key gratis di <a href="https://aistudio.google.com/" target="_blank" class="text-primary text-decoration-underline">Google AI Studio (aistudio.google.com)</a>.<br>
                                2. Buka file <code>.env</code> pada root proyek ini.<br>
                                3. Isi nilai: <code>GEMINI_API_KEY=AIzaSy...</code><br>
                                4. Simpan file <code>.env</code> lalu klik tombol Coba Lagi di bawah.
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="fetchAiRecommendation(true)">
                                <i class="bi bi-arrow-clockwise me-1"></i> Coba Lagi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Result State -->
                <div id="aiResult" class="d-none">
                    <!-- Fallback Alert Banner jika model dialihkan -->
                    <div id="aiFallbackAlert" class="alert alert-warning border-warning-subtle py-2 px-3 small d-none mb-3 d-flex align-items-center">
                        <i class="bi bi-arrow-repeat fs-5 text-warning-emphasis me-2 flex-shrink-0"></i>
                        <div>
                            <span class="fw-semibold">Fallback Model Aktif:</span>
                            <span id="aiFallbackText"></span>
                        </div>
                    </div>

                    <!-- Ringkasan Anggaran & Status -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <div class="small text-muted mb-1">Sisa Anggaran Unit Saat Ini</div>
                                <div class="h5 fw-bold text-dark mb-0" id="aiCurrentBudgetView">Rp 0</div>
                                <small class="text-muted" id="aiBudgetCategoryName">-</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-primary-subtle rounded-3 border border-primary-subtle h-100">
                                <div class="small text-primary-emphasis mb-1">Total Biaya Rekomendasi Terpilih</div>
                                <div class="h5 fw-bold text-primary mb-0" id="aiTotalSelectedView">Rp 0</div>
                                <small class="text-primary fw-semibold" id="aiCountSelectedView">0 item dipilih</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 border h-100 bg-light" id="aiProjectedCard">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-muted">Proyeksi Sisa Anggaran</span>
                                    <span class="badge" id="aiStatusBadge">Aman</span>
                                </div>
                                <div class="h5 fw-bold mb-0" id="aiProjectedBudgetView">Rp 0</div>
                                <small class="text-muted" id="aiCatatanAnggaran">-</small>
                            </div>
                        </div>
                    </div>

                    <!-- Analisis Mekanik AI -->
                    <div class="card border-0 bg-light mb-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-lightbulb-fill text-warning fs-5"></i>
                                <h6 class="fw-bold mb-0 text-dark">Analisis Kondisi & Pertimbangan AI</h6>
                            </div>
                            <p class="card-text text-secondary mb-0 small" id="aiAnalisisText" style="line-height: 1.6; white-space: pre-line;"></p>
                        </div>
                    </div>

                    <!-- Tabel Item Rekomendasi -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2"></i>Rekomendasi Komponen & Jasa</h6>
                            <div class="form-check form-check-inline mb-0">
                                <input class="form-check-input" type="checkbox" id="aiSelectAll" checked onchange="toggleSelectAll(this)">
                                <label class="form-check-label small fw-semibold" for="aiSelectAll">Pilih Semua</label>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th width="40" class="text-center">#</th>
                                        <th>Nama Komponen / Jasa & Alasan Rekomendasi</th>
                                        <th width="110">Tipe</th>
                                        <th width="110">Qty</th>
                                        <th width="150" class="text-end">Harga Satuan</th>
                                        <th width="150" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="aiItemsTableBody">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fetchAiRecommendation(true)">
                    <i class="bi bi-arrow-clockwise me-1"></i> Analisis Ulang
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-sm btn-success" id="btnApplyAiBatch" onclick="applyAiRecommendations()" disabled>
                        <i class="bi bi-plus-circle me-1"></i> Terapkan Rekomendasi Terpilih (<span id="btnApplyCount">0</span>)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const CSRF_TOKEN = '<?= Helpers\CSRF::token() ?>';
    const MAINTENANCE_ID = '<?= $maintenance['id'] ?>';
    let aiModalInstance = null;
    let aiLoadedData = null;
    let aiSelectedIndices = new Set();
    const currentUnitBudgetRemaining = <?= (float)$sisaBudget ?>;

    function openAiModal() {
        var modalEl = document.getElementById('modalAiRecommendation');
        if (!aiModalInstance) {
            aiModalInstance = new bootstrap.Modal(modalEl);
        }
        aiModalInstance.show();

        if (!aiLoadedData) {
            fetchAiRecommendation();
        }
    }

    function fetchAiRecommendation(force = false) {
        if (!force && aiLoadedData) {
            return;
        }

        const loadingEl = document.getElementById('aiLoading');
        const errorEl = document.getElementById('aiError');
        const resultEl = document.getElementById('aiResult');
        const btnApply = document.getElementById('btnApplyAiBatch');

        loadingEl.classList.remove('d-none');
        errorEl.classList.add('d-none');
        resultEl.classList.add('d-none');
        btnApply.disabled = true;

        const formData = new FormData();
        formData.append('csrf', CSRF_TOKEN);
        formData.append('maintenance_id', MAINTENANCE_ID);

        fetch('/maintenance/details/ai-recommendation', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan jaringan atau server (' + response.status + ')');
            }
            return response.json();
        })
        .then(data => {
            loadingEl.classList.add('d-none');

            if (!data.success) {
                errorEl.classList.remove('d-none');
                document.getElementById('aiErrorMessage').innerText = data.error || 'Terjadi kesalahan tidak terduga saat memproses AI.';
                return;
            }

            aiLoadedData = data.data;
            renderAiResults(aiLoadedData, data);
            resultEl.classList.remove('d-none');
        })
        .catch(err => {
            loadingEl.classList.add('d-none');
            errorEl.classList.remove('d-none');
            document.getElementById('aiErrorMessage').innerText = err.message || 'Gagal menghubungi server.';
        });
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function renderAiResults(data, meta = {}) {
        // Update model badge di header modal
        if (meta && meta.used_model) {
            document.getElementById('aiModelBadge').innerHTML = '<i class="bi bi-cpu me-1"></i> ' + escapeHtml(meta.used_model);
        }

        // Tampilkan notifikasi jika fallback model terpicu
        const fallbackAlert = document.getElementById('aiFallbackAlert');
        if (meta && meta.fallback_used) {
            fallbackAlert.classList.remove('d-none');
            document.getElementById('aiFallbackText').innerText = meta.fallback_message || 'Model dialihkan karena keterbatasan kapasitas.';
        } else {
            fallbackAlert.classList.add('d-none');
        }

        document.getElementById('aiAnalisisText').innerText = data.analisis || '-';
        document.getElementById('aiCurrentBudgetView').innerText = formatRupiah(currentUnitBudgetRemaining);
        document.getElementById('aiBudgetCategoryName').innerText = '<?= addslashes($budget['category_name'] ?? 'Pagu Kendaraan') ?>';
        document.getElementById('aiCatatanAnggaran').innerText = data.catatan_anggaran || '-';

        const tbody = document.getElementById('aiItemsTableBody');
        tbody.innerHTML = '';
        aiSelectedIndices.clear();

        const items = data.rekomendasi || [];

        if (items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada rekomendasi komponen tambahan untuk kondisi saat ini.</td></tr>';
            updateAiSelectionSummary();
            return;
        }

        items.forEach((item, index) => {
            aiSelectedIndices.add(index);

            const tr = document.createElement('tr');
            const isJasa = (item.jenis || '').toLowerCase() === 'jasa';
            const badgeClass = isJasa ? 'bg-info text-dark' : 'bg-secondary';
            const badgeText = isJasa ? 'JASA' : 'KOMPONEN';

            tr.innerHTML = `
                <td class="text-center">
                    <input class="form-check-input ai-item-check" type="checkbox" data-index="${index}" checked onchange="toggleItemCheck(${index}, this)">
                </td>
                <td>
                    <div class="fw-bold text-dark">${escapeHtml(item.nama)}</div>
                    ${item.alasan ? `<small class="text-muted"><i class="bi bi-info-circle me-1"></i>${escapeHtml(item.alasan)}</small>` : ''}
                </td>
                <td>
                    <span class="badge ${badgeClass}"><small>${badgeText}</small></span>
                </td>
                <td>
                    ${item.jumlah} ${escapeHtml(item.satuan || '')}
                </td>
                <td class="text-end">
                    ${formatRupiah(item.harga_satuan)}
                </td>
                <td class="text-end fw-bold text-dark">
                    ${formatRupiah(item.subtotal || (item.jumlah * item.harga_satuan))}
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('aiSelectAll').checked = true;
        updateAiSelectionSummary();
    }

    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.ai-item-check');
        aiSelectedIndices.clear();

        checkboxes.forEach(cb => {
            cb.checked = master.checked;
            if (master.checked) {
                aiSelectedIndices.add(parseInt(cb.getAttribute('data-index')));
            }
        });

        updateAiSelectionSummary();
    }

    function toggleItemCheck(index, cb) {
        if (cb.checked) {
            aiSelectedIndices.add(index);
        } else {
            aiSelectedIndices.delete(index);
        }

        const totalItems = (aiLoadedData && aiLoadedData.rekomendasi) ? aiLoadedData.rekomendasi.length : 0;
        document.getElementById('aiSelectAll').checked = (aiSelectedIndices.size === totalItems && totalItems > 0);

        updateAiSelectionSummary();
    }

    function updateAiSelectionSummary() {
        if (!aiLoadedData || !aiLoadedData.rekomendasi) {
            return;
        }

        let totalSelected = 0;
        aiSelectedIndices.forEach(idx => {
            const item = aiLoadedData.rekomendasi[idx];
            if (item) {
                totalSelected += (item.subtotal || (item.jumlah * item.harga_satuan));
            }
        });

        const count = aiSelectedIndices.size;
        const projected = currentUnitBudgetRemaining - totalSelected;

        document.getElementById('aiTotalSelectedView').innerText = formatRupiah(totalSelected);
        document.getElementById('aiCountSelectedView').innerText = count + ' item dipilih';
        document.getElementById('aiProjectedBudgetView').innerText = formatRupiah(projected);
        document.getElementById('btnApplyCount').innerText = count;

        const badge = document.getElementById('aiStatusBadge');
        const projCard = document.getElementById('aiProjectedCard');

        if (projected < 0) {
            badge.className = 'badge bg-danger';
            badge.innerText = 'Melebihi Anggaran';
            document.getElementById('aiProjectedBudgetView').className = 'h5 fw-bold mb-0 text-danger';
        } else if (projected < (currentUnitBudgetRemaining * 0.2)) {
            badge.className = 'badge bg-warning text-dark';
            badge.innerText = 'Mendekati Batas';
            document.getElementById('aiProjectedBudgetView').className = 'h5 fw-bold mb-0 text-warning';
        } else {
            badge.className = 'badge bg-success';
            badge.innerText = 'Aman';
            document.getElementById('aiProjectedBudgetView').className = 'h5 fw-bold mb-0 text-success';
        }

        document.getElementById('btnApplyAiBatch').disabled = (count === 0);
    }

    function applyAiRecommendations() {
        if (!aiLoadedData || !aiLoadedData.rekomendasi || aiSelectedIndices.size === 0) {
            alert('Silakan pilih minimal 1 item rekomendasi.');
            return;
        }

        const selectedItems = [];
        aiSelectedIndices.forEach(idx => {
            if (aiLoadedData.rekomendasi[idx]) {
                selectedItems.push(aiLoadedData.rekomendasi[idx]);
            }
        });

        const confirmMsg = `Tambahkan ${selectedItems.length} komponen/jasa rekomendasi AI ke dalam servis ini?`;
        if (!confirm(confirmMsg)) {
            return;
        }

        const btn = document.getElementById('btnApplyAiBatch');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';

        const formData = new FormData();
        formData.append('csrf', CSRF_TOKEN);
        formData.append('maintenance_id', MAINTENANCE_ID);
        formData.append('items', JSON.stringify(selectedItems));

        fetch('/maintenance/details/ai-apply-batch', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (!res.success) {
                alert('Gagal menerapkan rekomendasi: ' + (res.error || 'Terjadi kesalahan.'));
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                return;
            }

            alert(res.message || 'Rincian rekomendasi berhasil ditambahkan!');
            window.location.reload();
        })
        .catch(err => {
            alert('Gagal mengirim data: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setHarga() {
        var sel = document.getElementById('komponen_id');
        var idx = sel.selectedIndex;
        if (idx > 0) {
            var opt = sel.options[idx];
            document.getElementById('harga_satuan').value = opt.getAttribute('data-harga');
            var satuan = opt.getAttribute('data-satuan');
            document.getElementById('satuan_label').innerText = satuan ? satuan : '-';
        } else {
            document.getElementById('harga_satuan').value = '';
            document.getElementById('satuan_label').innerText = '-';
        }
        calcSubtotal();
    }

    function calcSubtotal() {
        var qty = parseFloat(document.getElementById('jumlah').value) || 0;
        var harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
        var sub = qty * harga;
        document.getElementById('subtotal_view').value = 'Rp ' + sub.toLocaleString('id-ID');
    }

    let editHargaModalInstance = null;

    function openEditHargaModal(id, nama, jumlah, satuan, hargaSatuan) {
        document.getElementById('edit_detail_id').value = id;
        document.getElementById('edit_nama_komponen').innerText = nama;
        document.getElementById('edit_jumlah').value = jumlah;
        document.getElementById('edit_satuan_label').innerText = satuan ? satuan : '-';
        document.getElementById('edit_harga_satuan').value = hargaSatuan;

        calcEditHargaSubtotal();

        const modalEl = document.getElementById('modalEditHarga');
        if (!editHargaModalInstance) {
            editHargaModalInstance = new bootstrap.Modal(modalEl);
        }
        editHargaModalInstance.show();

        setTimeout(() => {
            const inputHarga = document.getElementById('edit_harga_satuan');
            if (inputHarga) {
                inputHarga.focus();
                inputHarga.select();
            }
        }, 350);
    }

    function calcEditHargaSubtotal() {
        const qty = parseFloat(document.getElementById('edit_jumlah').value) || 0;
        const harga = parseFloat(document.getElementById('edit_harga_satuan').value) || 0;
        const sub = qty * harga;
        document.getElementById('edit_subtotal_view').value = 'Rp ' + sub.toLocaleString('id-ID');
    }
</script>