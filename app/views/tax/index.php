
<h3>Input Pajak</h3>
<form method="post" action="/tax/store" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">ID Kendaraan</label><input name="vehicle_id" type="number" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Tanggal</label><input name="date" type="date" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Jenis</label><select name="jenis" class="form-select"><option value="tahunan">Tahunan</option><option value="5_tahunan">5 Tahunan</option></select></div>
  <div class="col-md-3"><label class="form-label">Biaya</label><input name="biaya" type="number" step="0.01" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="sudah">Sudah bayar</option><option value="belum">Belum bayar</option></select></div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
</form>
