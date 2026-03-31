<h3>Edit Pengguna</h3>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form method="post" action="/users/update">
            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input name="password" type="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                <small class="text-muted">Isi hanya jika ingin mengganti password.</small>
            </div>

            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="/users" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>