
<h3>Detail Kendaraan</h3>
<div class="row g-3">
  <div class="col-md-4">
    <?php if($v['foto_path']): ?><img src="<?= $v['foto_path'] ?>" class="img-fluid rounded" /><?php endif; ?>
  </div>
  <div class="col-md-8">
    <table class="table">
      <tr><th>Plat</th><td><?= $v['plat'] ?></td></tr>
      <tr><th>Merk</th><td><?= $v['merk'] ?></td></tr>
      <tr><th>Tipe</th><td><?= $v['tipe'] ?></td></tr>
      <tr><th>Tahun</th><td><?= $v['tahun'] ?></td></tr>
      <tr><th>Jenis</th><td><?= $v['jenis'] ?></td></tr>
      <tr><th>Penggunaan</th><td><?= $v['status_penggunaan'] ?></td></tr>
      <tr><th>Status</th><td><?= $v['status_kendaraan'] ?></td></tr>
      <tr><th>Penanggung Jawab</th><td><?= $v['current_responsible'] ?></td></tr>
    </table>
  </div>
</div>
