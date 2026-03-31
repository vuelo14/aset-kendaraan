<h3>Edit Data Pajak</h3>
<div class="card card-body bg-light mt-3">
    <form method="post" action="/tax/update" class="row g-3">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <input type="hidden" name="id" value="<?= $tax['id'] ?>">

        <div class="col-md-4">
            <label class="form-label">Kendaraan</label>
            <select name="vehicle_id" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= ($tax['vehicle_id'] == $v['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['plat'] . ' - ' . $v['merk']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input name="date" type="date" class="form-control" value="<?= $tax['date'] ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Jenis</label>
            <select name="jenis" class="form-select">
                <option value="tahunan" <?= ($tax['jenis'] == 'tahunan') ? 'selected' : '' ?>>Tahunan</option>
                <option value="5_tahunan" <?= ($tax['jenis'] == '5_tahunan') ? 'selected' : '' ?>>5 Tahunan</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Biaya (Rp)</label>
            <input name="biaya" type="number" step="0.01" class="form-control" value="<?= $tax['biaya'] ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="sudah" <?= ($tax['status'] == 'sudah') ? 'selected' : '' ?>>Sudah bayar</option>
                <option value="belum" <?= ($tax['status'] == 'belum') ? 'selected' : '' ?>>Belum bayar</option>
            </select>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary">Update</button>
            <a href="/tax" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
