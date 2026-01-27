<?php if (isset($_SESSION['success'])): ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); // Hapus agar tidak muncul lagi saat refresh 
    ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>