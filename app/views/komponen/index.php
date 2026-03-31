<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Data Komponen & Jasa</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formInput"
                        aria-expanded="false">
                        <i class="bi bi-plus-lg"></i> Tambah Data
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4" id="formInput">
                    <div class="card card-body border-0">
                        <h6 class="card-title mb-3">Input Komponen/Jasa Baru</h6>
                        <form method="post" action="/komponen/store" class="row g-3">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                            <div class="col-md-4">
                                <label class="form-label">Nama Barang / Jasa</label>
                                <input name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select" required>
                                    <option value="komponen">Komponen</option>
                                    <option value="jasa">Jasa</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Satuan</label>
                                <input name="satuan" class="form-control" placeholder="Pcs, Liter, dll">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Harga Standar (Rp)</label>
                                <input name="harga" type="number" step="0.01" class="form-control" value="0" required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Barang / Jasa</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                                <th>Harga Standar</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-end">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($komponen as $k): ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </td>
                                    <td>
                                        <?php if ($k['jenis'] == 'jasa'): ?>
                                            <span class="badge bg-info">Jasa</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Komponen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($k['satuan'] ?? '-') ?>
                                    </td>
                                    <td class="fw-bold">Rp
                                        <?= number_format($k['harga'], 0, ',', '.') ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="/komponen/edit?id=<?= $k['id'] ?>" class="btn btn-outline-secondary"
                                                    title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="post" action="/komponen/delete" class="d-inline"
                                                    onsubmit="return confirm('Hapus data komponen ini?')">
                                                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                                    <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                    <button class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($komponen)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data komponen / jasa.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>