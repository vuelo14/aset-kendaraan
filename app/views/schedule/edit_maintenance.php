<h3>Edit Jadwal Pemeliharaan</h3>
<div class="card card-body bg-light mt-3">
    <form method="post" action="/schedule/maintenance/update" class="row g-3">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <input type="hidden" name="id" value="<?= $schedule['id'] ?>">

        <div class="col-md-4">
            <label class="form-label">Kendaraan</label>
            <select name="vehicle_id" class="form-select" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= ($schedule['vehicle_id'] == $v['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['plat'] . ' - ' . $v['merk']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Deskripsi</label>
            <input name="description" class="form-control" value="<?= htmlspecialchars($schedule['description']) ?>" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Interval (hari)</label>
            <input name="interval_days" type="number" class="form-control" value="<?= $schedule['interval_days'] ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal Berikutnya</label>
            <input name="next_date" type="date" class="form-control" value="<?= $schedule['next_date'] ?>" required>
        </div>
        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary">Update Jadwal</button>
            <a href="/schedule/maintenance" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
