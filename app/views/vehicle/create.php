<h3>Tambah Kendaraan</h3>
<form method="post" action="/vehicles/store" enctype="multipart/form-data" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <div class="col-md-3"><label class="form-label">Plat</label><input name="plat" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Merk</label><input name="merk" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Tipe</label><input name="tipe" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">Tahun</label><input name="tahun" type="number" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Kondisi</label><select name="kondisi" class="form-select">
      <option value="B">Baik</option>
      <option value="KB">Kurang Baik</option>
      <option value="RR">Rusak Ringan</option>
      <option value="RB">Rusak Berat</option>
    </select></div>
  <div class="col-md-3"><label class="form-label">Jenis</label><select name="jenis" class="form-select">
      <option value="roda2">Roda 2</option>
      <option value="roda4">Roda 4</option>
    </select></div>
  <div class="col-md-3"><label class="form-label">Penggunaan</label><select name="status_penggunaan" class="form-select">
      <option value="jabatan">Jabatan</option>
      <option value="operasional">Operasional</option>
    </select></div>
  <div class="col-md-3"><label class="form-label">Status Kendaraan</label><select name="status_kendaraan" class="form-select">
      <option>aktif</option>
      <option>non-aktif</option>
      <option>rusak</option>
      <option>perbaikan</option>
    </select></div>
  <div class="col-md-3"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
  <div class="col-md-3">
    <label class="form-label">Kategori Anggaran</label>
    <select name="budget_category_id" class="form-select">
      <option value="">-- Pilih Kategori --</option>
      <?php if (!empty($categories)): foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>">
          <?= htmlspecialchars($cat['category_name']) ?>
          <?php if ($cat['max_unit_budget']): ?>
            (Pagu: Rp <?= number_format($cat['max_unit_budget'], 0, ',', '.') ?>)
          <?php endif; ?>
        </option>
      <?php endforeach; endif; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Pagu Anggaran Khusus (Rp)</label>
    <div class="input-group">
      <span class="input-group-text">Rp</span>
      <input type="number" step="1000" min="0" name="budget_limit" class="form-control fw-bold" placeholder="Kosongkan jika ikut kategori">
    </div>
    <small class="text-muted">Opsional (pagu spesifik tahunan)</small>
  </div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button> <a href="/vehicles" class="btn btn-secondary">Batal</a></div>
</form>