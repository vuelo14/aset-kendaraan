<div class="mb-5">
    <h3 class="fw-bold text-dark mb-2">Selamat Datang Kembali</h3>
    <p class="text-muted">Silakan masukkan detail akun Anda untuk masuk.</p>
</div>

<?php if(!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
        <div><?= $_SESSION['error'] ?></div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="post" action="/login">
    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
    
    <div class="form-floating mb-3">
        <input type="text" name="username" class="form-control bg-light border-0" id="usernameInput" placeholder="Username" required style="border-radius: 12px;">
        <label for="usernameInput" class="text-secondary">Username</label>
    </div>
    
    <div class="form-floating mb-4">
        <input type="password" name="password" class="form-control bg-light border-0" id="passwordInput" placeholder="Password" required style="border-radius: 12px;">
        <label for="passwordInput" class="text-secondary">Password</label>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe">
            <label class="form-check-label text-secondary small" for="rememberMe">Ingat saya</label>
        </div>
        <!-- <a href="#" class="text-decoration-none small text-primary fw-medium">Lupa Password?</a> -->
    </div>

    <button class="btn btn-primary w-100 py-3 mb-4 fw-bold shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); border:none;">
        Masuk <i class="bi bi-box-arrow-in-right ms-2"></i>
    </button>
</form>
