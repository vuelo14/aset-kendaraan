<div class="row">
    <div class="col-12">
        <a href="/maintenance" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pemeliharaan
        </a>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0 fw-bold">Informasi Pemeliharaan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <th width="150" class="text-muted">Kendaraan</th>
                        <td>:
                            <?= htmlspecialchars($vehicle['plat'] . ' - ' . $vehicle['merk']) ?>
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
                        <th class="text-muted">Total Tagihan</th>
                        <td>: <span class="fw-bold text-danger">Rp
                                <?= number_format($maintenance['biaya'] ?? 0, 0, ',', '.') ?>
                            </span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Rincian Komponen / Jasa</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#formDetailAdd" aria-expanded="false">
                        <i class="bi bi-plus-lg"></i> Tambah Rincian
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4" id="formDetailAdd">
                    <div class="card card-body bg-light border-0">
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
                                            [
                                            <?= strtoupper($k['jenis']) ?>]
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
                                <?php if (Core\Auth::role() === 'admin'): ?>
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
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-center">
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
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada rincian komponen/jasa.
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

<script>
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
</script>