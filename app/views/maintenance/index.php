<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Data Pemeliharaan</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formInput"
                        aria-expanded="<?= isset($selected_id) ? 'true' : 'false' ?>">
                        <i class="bi bi-plus-lg"></i> Input Pemeliharaan
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4 <?= isset($selected_id) ? 'show' : '' ?>" id="formInput">
                    <div class="card card-body border-0">
                        <h6 class="card-title mb-3">Input Pemeliharaan Baru</h6>
                        <form method="post" action="/maintenance/store" class="row g-3">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">

                            <div class="col-md-4"> <label class="form-label">Kendaraan</label>
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
                            <div class="col-md-3"><label class="form-label">Jenis Pemeliharaan</label><input
                                    name="jenis" class="form-control" placeholder="Ganti Oli, Service Rutin, dll"
                                    required></div>
                            <div class="col-md-2"><label class="form-label">Biaya (Rp)</label><input name="biaya"
                                    type="number" step="0.01" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Bengkel</label><input name="bengkel"
                                    class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Catatan</label><input name="notes"
                                    class="form-control"></div>
                            <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kendaraan</th>
                                <th>Penanggung Jawab</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Bengkel</th>
                                <th>Biaya</th>
                                <th>Catatan</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-end">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($maintenances as $m): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($m['foto_path'])): ?>
                                                <img src="<?= $m['foto_path'] ?>" class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-truck text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($m['plat']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($m['merk']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($m['current_responsible'] ?? '-') ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($m['date'])) ?></td>
                                    <td><?= htmlspecialchars($m['jenis']) ?></td>
                                    <td><?= htmlspecialchars($m['bengkel'] ?? '-') ?></td>
                                    <td class="fw-bold">Rp <?= number_format($m['biaya'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($m['notes'] ?? '-') ?></td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="/maintenance/details?id=<?= $m['id'] ?>" class="btn btn-outline-info"
                                                title="Rincian">
                                                <i class="bi bi-list-check"></i>
                                            </a>
                                            <a href="/maintenance/nota?id=<?= $m['id'] ?>" target="_blank"
                                                class="btn btn-outline-success" title="Cetak Nota">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <?php if (Core\Auth::role() === 'admin'): ?>
                                                <a href="/maintenance/edit?id=<?= $m['id'] ?>" class="btn btn-outline-secondary"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="post" action="/maintenance/delete" class="d-inline"
                                                    onsubmit="return confirm('Hapus data pemeliharaan ini?')">
                                                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                    <button class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($maintenances)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data pemeliharaan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>