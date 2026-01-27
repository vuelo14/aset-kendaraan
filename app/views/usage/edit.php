<h3>Edit Penanggung Jawab Kendaraan</h3>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title mb-2">Informasi Kendaraan</h5>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <th>Plat</th>
                        <td><?= htmlspecialchars($vehicle['plat']) ?></td>
                    </tr>
                    <tr>
                        <th>Merk</th>
                        <td><?= htmlspecialchars($vehicle['merk']) ?></td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td><?= htmlspecialchars($vehicle['tipe']) ?></td>
                    </tr>
                    <tr>
                        <th>Tahun</th>
                        <td><?= htmlspecialchars($vehicle['tahun']) ?></td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td><?= htmlspecialchars($vehicle['jenis']) ?></td>
                    </tr>
                    <tr>
                        <th>Penggunaan</th>
                        <td><?= htmlspecialchars($vehicle['status_penggunaan']) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?= htmlspecialchars($vehicle['status_kendaraan']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <?php if (!empty($vehicle['foto_path'])): ?>
                    <img src="<?= $vehicle['foto_path'] ?>" class="img-fluid rounded" />
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<form method="post" action="/usage/update" class="row g-3">
    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
    <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
    <input type="hidden" name="id" value="<?= $usage['id'] ?>">

    <div class="col-md-4">
        <label class="form-label">Pemakai / Penanggung Jawab</label>
        <input name="pemakai" class="form-control" <?php if (isset($usage['pemakai'])) echo 'value="' . htmlspecialchars($usage['pemakai']) . '"'; ?> required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Jabatan</label>
        <input name="jabatan" class="form-control" <?php if (isset($usage['jabatan'])) echo 'value="' . htmlspecialchars($usage['jabatan']) . '"'; ?>>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="start_date" class="form-control" required <?php if (isset($usage['start_date'])) echo 'value="' . htmlspecialchars($usage['start_date']) . '"'; ?>>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tanggal Selesai (opsional)</label>
        <input type="date" name="end_date" class="form-control" <?php if (isset($usage['end_date'])) echo 'value="' . htmlspecialchars($usage['end_date']) . '"'; ?>>
        <small class="text-muted">Kosongkan jika masih aktif.</small>
    </div>

    <div class="col-12">
        <button class="btn btn-primary">Simpan</button>
        <a href="/vehicles/show?id=<?= $vehicle['id'] ?>" class="btn btn-secondary">Batal</a>
    </div>
</form>