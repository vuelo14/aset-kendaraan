<?php if (Core\Auth::role() === 'admin'): ?>
    <h3>Tambah Riwayat Pemakai Kendaraan</h3>
    <form method="post" action="/usage/store" class="row g-3">
        <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
        <div class="col-md-3"><label>ID Kendaraan</label><input name="vehicle_id" type="number" class="form-control" required></div>
        <div class="col-md-3"><label>Nama Pemakai</label><input name="pemakai" class="form-control" required></div>
        <div class="col-md-3"><label>Jabatan</label><input name="jabatan" class="form-control"></div>
        <div class="col-md-3"><label>Tanggal Mulai</label><input name="start_date" type="date" class="form-control" required></div>
        <div class="col-md-3"><label>Tanggal Selesai</label><input name="end_date" type="date" class="form-control"></div>
        <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
    </form>
<?php endif; ?>
<!-- <h3>Riwayat Pemakai Kendaraan #<?= isset($vehicle_id) ? htmlspecialchars($vehicle_id) : '' ?></h3>
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
        <?php foreach ((array)($list ?? []) as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['pemakai']) ?></td>
                <td><?= htmlspecialchars($r['jabatan']) ?></td>
                <td><?= $r['start_date'] ?></td>
                <td><?= $r['end_date'] ?: '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table> -->


<!-- <h5 class="mt-4">Tambah Riwayat</h5>
<form method="post" action="/usage/store" class="row g-3">
    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
    <input type="hidden" name="vehicle_id" value="<?= $vehicle_id ?>">
    <div class="col-md-3"><label>Pemakai</label><input name="pemakai" class="form-control" required></div>
    <div class="col-md-3"><label>Jabatan</label><input name="jabatan" class="form-control"></div>
    <div class="col-md-3"><label>Mulai</label><input name="start_date" type="date" class="form-control" required></div>
    <div class="col-md-3"><label>Selesai</label><input name="end_date" type="date" class="form-control"></div>
    <div class="col-12"><button class="btn btn-primary">Simpan</button></div>
</form> -->