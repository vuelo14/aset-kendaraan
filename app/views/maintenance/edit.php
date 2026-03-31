<h3>Edit Data Pemeliharaan</h3>
<div class="card card-body bg-light mt-3">
    <form method="post" action="/maintenance/update" class="row g-3">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <input type="hidden" name="id" value="<?= $maintenance['id'] ?>">

        <div class="col-md-4">
            <label class="form-label">Kendaraan</label>
            <select name="vehicle_id" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= ($maintenance['vehicle_id'] == $v['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['plat'] . ' - ' . $v['merk']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input name="date" type="date" class="form-control" value="<?= $maintenance['date'] ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jenis Pemeliharaan</label>
            <input name="jenis" class="form-control" value="<?= htmlspecialchars($maintenance['jenis']) ?>" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Biaya (Rp)</label>
            <input name="biaya" type="number" step="0.01" class="form-control" value="<?= $maintenance['biaya'] ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Bengkel</label>
            <input name="bengkel" class="form-control" value="<?= htmlspecialchars($maintenance['bengkel']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Catatan</label>
            <input name="notes" class="form-control" value="<?= htmlspecialchars($maintenance['notes']) ?>">
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary">Update</button>
            <a href="/maintenance" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
