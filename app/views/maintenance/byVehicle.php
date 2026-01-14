
<h3>Riwayat Pemeliharaan Kendaraan #<?= htmlspecialchars($_GET['vehicle_id']) ?></h3>
<table class="table table-striped">
  <thead><tr><th>Tanggal</th><th>Jenis</th><th>Biaya</th><th>Bengkel</th><th>Catatan</th></tr></thead>
  <tbody>
  <?php foreach($list as $r): ?>
    <tr><td><?= $r['date'] ?></td><td><?= $r['jenis'] ?></td><td>Rp <?= number_format($r['biaya'],0,',','.') ?></td><td><?= $r['bengkel'] ?></td><td><?= $r['notes'] ?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
