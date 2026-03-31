<style>
    /* CSS khusus untuk nota */
    .nota-container {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .nota-header {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .nota-header h2 {
        margin: 0;
        font-weight: bold;
        text-transform: uppercase;
    }

    .nota-info {
        margin-bottom: 30px;
    }

    .nota-table th {
        background-color: #f8f9fa !important;
    }

    @media print {
        body {
            background: #fff !important;
        }

        #sidebar,
        .navbar,
        .btn-print,
        .alert {
            display: none !important;
        }

        #main-content {
            margin: 0 !important;
            padding: 0 !important;
        }

        .nota-container {
            box-shadow: none;
            padding: 0;
            margin: 0;
            max-width: 100%;
        }
    }
</style>

<div class="mb-3 btn-print text-center">
    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Nota</button>
    <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
</div>

<div class="nota-container">
    <div class="nota-header">
        <h2>Nota Pemeliharaan Kendaraan</h2>
        <p class="mb-0 text-muted">Aset Kendaraan Dinas</p>
    </div>

    <div class="row nota-info">
        <div class="col-sm-6">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="120"><strong>No. Ref</strong></td>
                    <td>: #MNT-
                        <?= str_pad($maintenance['id'], 5, '0', STR_PAD_LEFT) ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>:
                        <?= date('d M Y', strtotime($maintenance['date'])) ?? $maintenance['date'] ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Jenis Service</strong></td>
                    <td>:
                        <?= htmlspecialchars($maintenance['jenis']) ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="table table-sm table-borderless">
                <tr>
                    <td width="120"><strong>Kendaraan</strong></td>
                    <td>:
                        <?= htmlspecialchars($vehicle['plat']) ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Merk/Tipe</strong></td>
                    <td>:
                        <?= htmlspecialchars($vehicle['merk']) ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Bengkel</strong></td>
                    <td>:
                        <?= htmlspecialchars($maintenance['bengkel'] ?? '-') ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table class="table table-bordered nota-table">
        <thead>
            <tr>
                <th class="text-center" width="50">No</th>
                <th>Deskripsi Komponen / Jasa</th>
                <th class="text-center" width="80">Qty</th>
                <th class="text-end" width="150">Harga Satuan</th>
                <th class="text-end" width="150">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grand_total = 0;
            if (!empty($details)):
                foreach ($details as $d):
                    ?>
                    <tr>
                        <td class="text-center">
                            <?= $no++ ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($d['nama']) ?>
                        </td>
                        <td class="text-center">
                            <?= rtrim(rtrim(number_format($d['jumlah'], 2, '.', ''), '0'), '.') ?>
                            <?= htmlspecialchars($d['satuan'] ?? '') ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format($d['harga_satuan'], 0, ',', '.') ?>
                        </td>
                        <td class="text-end">Rp
                            <?= number_format($d['subtotal'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php
                    $grand_total += $d['subtotal'];
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada rincian tercatat</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end fw-bold">TOTAL BIAYA</td>
                <td class="text-end fw-bold fs-5">Rp
                    <?= number_format($grand_total > 0 ? $grand_total : $maintenance['biaya'], 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php if (!empty($maintenance['notes'])): ?>
        <div class="mt-4">
            <strong>Catatan:</strong><br>
            <?= nl2br(htmlspecialchars($maintenance['notes'])) ?>
        </div>
    <?php endif; ?>

    <div class="row mt-5 text-center">
        <div class="col-6">
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>( ______________________ )</strong></p>
        </div>
        <div class="col-6">
            <p>Petugas Bengkel / Service,</p>
            <br><br><br>
            <p><strong>( ______________________ )</strong></p>
        </div>
    </div>
</div>