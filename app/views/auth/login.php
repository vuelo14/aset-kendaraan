
<div class="container" style="max-width:420px; margin-top:80px;">
  <div class="card shadow">
    <div class="card-body">
      <h5 class="card-title mb-3">Masuk</h5>
      <?php if(!empty($_SESSION['error'])){ echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); } ?>
      <form method="post" action="/login">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
        <button class="btn btn-primary w-100">Masuk</button>
      </form>
    </div>
  </div>
</div>
