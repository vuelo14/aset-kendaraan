
<h3>Manajemen Pengguna</h3>
<table class="table table-striped">
  <thead><tr><th>Nama</th><th>Username</th><th>Role</th></tr></thead>
  <tbody>
  <?php foreach($users as $u): ?>
    <tr><td><?= $u['name'] ?></td><td><?= $u['username'] ?></td><td><span class="badge bg-info"><?= $u['role'] ?></span></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>

<h5 class="mt-4">Tambah Pengguna</h5>
<form method="post" action="/users/store" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">Nama</label><input name="name" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Role</label><select name="role" class="form-select"><option value="admin">admin</option><option value="user">user</option></select></div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
</form>
