<?php include APP_PATH . '/views/layouts/message.php'; ?>

<h3>Manajemen Pengguna</h3>
<div class="table-responsive mb-4">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><span class="badge bg-info"><?= $u['role'] ?></span></td>
          <td>
            <a href="/users/edit?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i>
            </a>

            <form method="post" action="/users/delete" class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">
              <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
              <input type="hidden" name="id" value="<?= $u['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" <?= ($u['id'] == $_SESSION['user']['id']) ? 'disabled title="Tidak bisa hapus diri sendiri"' : '' ?>>
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h5 class="mt-4">Tambah Pengguna Baru</h5>
<form method="post" action="/users/store" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">Nama</label><input name="name" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Role</label><select name="role" class="form-select">
      <option value="admin">admin</option>
      <option value="user">user</option>
    </select></div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
</form>