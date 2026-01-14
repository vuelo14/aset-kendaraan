
<h3>Edit Kendaraan</h3>
<form method="post" action="/vehicles/update" enctype="multipart/form-data" class="row g-3">
  <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
  <input type="hidden" name="id" value="<?= $v['id'] ?>">
  <div class="col-md-3"><label class="form-label">Plat</label><input name="plat" class="form-control" value="<?= $v['plat'] ?>" required></div>
  <div class="col-md-3"><label class="form-label">Merk</label><input name="merk" class="form-control" value="<?= $v['merk'] ?>" required></div>
  <div class="col-md-3"><label class="form-label">Tipe</label><input name="tipe" class="form-control" value="<?= $v['tipe'] ?>"></div>
  <div class="col-md-3"><label class="form-label">Tahun</label><input name="tahun" type="number" class="form-control" value="<?= $v['tahun'] ?>" required></div>
  <div class="col-md-3"><label class="form-label">Jenis</label><select name="jenis" class="form-select"><option value="roda2" <?= $v['jenis']==='roda2'?'selected':'' ?>>Roda 2</option><option value="roda4" <?= $v['jenis']==='roda4'?'selected':'' ?>>Roda 4</option></select></div>
  <div class="col-md-3"><label class="form-label">Penggunaan</label><select name="status_penggunaan" class="form-select"><option value="jabatan" <?= $v['status_penggunaan']==='jabatan'?'selected':'' ?>>Jabatan</option><option value="operasional" <?= $v['status_penggunaan']==='operasional'?'selected':'' ?>>Operasional</option></select></div>
  <div class="col-md-3"><label class="form-label">Status Kendaraan</label><select name="status_kendaraan" class="form-select"><option <?= $v['status_kendaraan']==='aktif'?'selected':'' ?>>aktif</option><option <?= $v['status_kendaraan']==='non-aktif'?'selected':'' ?>>non-aktif</option><option <?= $v['status_kendaraan']==='rusak'?'selected':'' ?>>rusak</option><option <?= $v['status_kendaraan']==='perbaikan'?'selected':'' ?>>perbaikan</option></select></div>
  <div class="col-md-3"><label class="form-label">Penanggung Jawab</label><input name="current_responsible" class="form-control" value="<?= $v['current_responsible'] ?>"></div>
  <div class="col-md-3"><label class="form-label">Foto</label><input type="file" name="foto" class="form-control" accept="image/*"></div>
  <div class="col-12"><button class="btn btn-primary">Simpan</button> <a href="/vehicles" class="btn btn-secondary">Batal</a></div>
</form>
