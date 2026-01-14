
<h3>Import/Export</h3>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <h6>Import CSV Kendaraan</h6>
      <p>Kolom wajib: plat, merk, tipe, tahun, jenis, status_penggunaan, status_kendaraan</p>
      <form method="post" action="/import/csv" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <input type="file" name="csv" class="form-control" accept=".csv" required>
        <button class="btn btn-primary mt-2">Import</button>
      </form>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <h6>Export CSV/PDF Kendaraan</h6>
      <a href="/export/vehicles/csv" class="btn btn-outline-secondary">Export CSV</a>
      <a href="/export/vehicles/pdf" class="btn btn-outline-secondary">Export PDF</a>
    </div></div>
  </div>
</div>
