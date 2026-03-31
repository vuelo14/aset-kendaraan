<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Data Pajak</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formInput"
                        aria-expanded="<?= isset($selected_id) ? 'true' : 'false' ?>">
                        <i class="bi bi-plus-lg"></i> Input Pajak
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4 <?= isset($selected_id) ? 'show' : '' ?>" id="formInput">
                    <div class="card card-body border-0">
                        <h6 class="card-title mb-3">Input Pajak Baru</h6>
                        <form method="post" action="/tax/store" class="row g-3">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">

                            <div class="col-md-4">
                                <label class="form-label">Kendaraan</label>
                                <select name="vehicle_id" class="form-select" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    <?php foreach ($vehicles as $v): ?>
                                        <option value="<?= $v['id'] ?>" <?= (isset($selected_id) && $selected_id == $v['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v['plat'] . ' - ' . $v['merk']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3"><label class="form-label">Tanggal</label><input name="date"
                                    type="date" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Jenis</label><select name="jenis"
                                    class="form-select">
                                    <option value="tahunan">Tahunan</option>
                                    <option value="5_tahunan">5 Tahunan</option>
                                </select></div>
                            <div class="col-md-3"><label class="form-label">Biaya (Rp)</label><input name="biaya"
                                    type="number" step="0.01" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Status</label><select name="status"
                                    class="form-select">
                                    <option value="sudah">Sudah bayar</option>
                                    <option value="belum">Belum bayar</option>
                                </select></div>
                            <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kendaraan</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-end">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($taxList as $t): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($t['foto_path'])): ?>
                                                <img src="<?= $t['foto_path'] ?>" class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-truck text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($t['plat']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($t['merk']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $t['date'] ?></td>
                                    <td>
                                        <span
                                            class="badge <?= $t['jenis'] == '5_tahunan' ? 'bg-warning text-dark' : 'bg-info text-dark' ?>">
                                            <?= str_replace('_', ' ', strtoupper($t['jenis'] ?? '')) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold">Rp <?= number_format($t['biaya'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($t['status'] == 'sudah'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lunas</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Belum</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="/tax/edit?id=<?= $t['id'] ?>" class="btn btn-outline-secondary"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="post" action="/tax/delete" class="d-inline"
                                                    onsubmit="return confirm('Hapus data pajak ini?')">
                                                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                                    <button class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($taxList)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data pajak.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>