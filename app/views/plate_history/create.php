<?php 
use Helpers\CSRF;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">
        <a href="/plate-history" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i> Kembali</a>
        Tambah Riwayat Ganti Plat
    </h2>
</div>

<div class="card shadow-sm border-0 mb-4 h-100">
    <div class="card-body">
        <form action="/plate-history/store" method="POST">
            <input type="hidden" name="csrf" value="<?= CSRF::token() ?>">
            
            <div class="mb-3">
                <label class="form-label">Kendaraan</label>
                <select name="vehicle_id" class="form-select" required id="vehicleSelect">
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v['id'] ?>" data-plat="<?= htmlspecialchars($v['plat']) ?>">
                            <?= htmlspecialchars($v['merk'] . ' ' . $v['tipe'] . ' (' . $v['plat'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Plat Lama</label>
                <input type="text" name="plat_lama" id="platLama" class="form-control" required readonly>
                <small class="text-muted">Otomatis terisi dari data kendaraan yang dipilih.</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Plat Baru</label>
                <input type="text" name="plat_baru" class="form-control" required placeholder="Contoh: E 1234 XY">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Tanggal Ganti</label>
                <input type="date" name="tanggal_ganti" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan jika ada..."></textarea>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="updateVehicle" name="update_vehicle" value="1" checked>
                <label class="form-check-label" for="updateVehicle">Perbarui plat nomor di data kendaraan juga</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-save me-2"></i> Simpan Riwayat
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleSelect = document.getElementById('vehicleSelect');
        const platLamaInput = document.getElementById('platLama');
        
        vehicleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const plat = selectedOption.getAttribute('data-plat');
            
            if (plat) {
                platLamaInput.value = plat;
            } else {
                platLamaInput.value = '';
            }
            
            // Allow manual edit if needed
            platLamaInput.readOnly = !plat;
        });
    });
</script>
