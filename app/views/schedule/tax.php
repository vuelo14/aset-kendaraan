<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Jadwal Pajak</h3>
</div>
<form method="post" action="/schedule/tax/store" class="row g-3 mb-4">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">ID Kendaraan</label>
    <input name="vehicle_id" type="number" class="form-control" required>
  </div>
  <div class="col-md-3"><label class="form-label">Deskripsi</label><input name="description" class="form-control" placeholder="Pajak Tahunan"></div>
  <div class="col-md-3"><label class="form-label">Interval (hari)</label><input name="interval_days" type="number" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Tanggal Berikutnya</label><input name="next_date" type="date" class="form-control" required></div>
  <div class="col-12"><button class="btn btn-primary">Simpan Jadwal</button></div>
</form>

<table class="table table-hover">
  <thead>
    <tr>
      <th>Kendaraan</th>
      <th>Deskripsi</th>
      <th>Tanggal Berikutnya</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($list as $r): ?>
      <tr>
        <td><?= $r['plat'] ?> - <?= $r['merk'] ?></td>
        <td><?= $r['description'] ?></td>
        <td><?= $r['next_date'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>