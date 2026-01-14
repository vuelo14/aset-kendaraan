
<h3>Riwayat Pajak Kendaraan #<?= htmlspecialchars($_GET['vehicle_id']) ?></h3>
<table class="table table-striped">
  <thead><tr><th>Tanggal</th><th>Jenis</th><th>Biaya</th><th>Status</th></tr></thead>
  <tbody>
  <?php foreach($list as $r): ?>
    <tr><td><?= $r['date'] ?></td><td><?= $r['jenis'] ?></td><td>Rp <?= number_format($r['biaya'],0,',','.') ?></td><td><?= $r['status'] ?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
