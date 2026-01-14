
<h3>Input Pemeliharaan</h3>
<form method="post" action="/maintenance/store" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">ID Kendaraan</label><input name="vehicle_id" type="number" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Tanggal</label><input name="date" type="date" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Jenis Pemeliharaan</label><input name="jenis" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Biaya</label><input name="biaya" type="number" step="0.01" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Bengkel</label><input name="bengkel" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Catatan</label><input name="notes" class="form-control"></div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
</form>
