
<h3>Backup & Restore Database</h3>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <h6>Backup</h6>
      <a href="/backup/run" class="btn btn-primary">Download SQL Backup</a>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card"><div class="card-body">
      <h6>Restore</h6>
      <form method="post" action="/restore/upload" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <input type="file" name="sql" class="form-control" accept=".sql" required>
        <button class="btn btn-danger mt-2" onclick="return confirm('Pastikan file backup benar. Lanjutkan restore?')">Restore</button>
      </form>
    </div></div>
  </div>
</div>
