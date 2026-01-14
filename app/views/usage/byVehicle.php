<h3>Riwayat Pemakai Kendaraan #<?= htmlspecialchars($_GET['vehicle_id']) ?></h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Pemakai</th>
            <th>Jabatan</th>
            <th>Mulai</th>
            <th>Selesai</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $r): ?>
            <tr>
                <td><?= $r['pemakai'] ?></td>
                <td><?= $r['jabatan'] ?></td>
                <td><?= $r['start_date'] ?></td>
                <td><?= $r['end_date'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>