<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Data Kendaraan</h3>
  <?php if (Core\Auth::role() === 'admin'): ?><a href="/vehicles/create" class="btn btn-primary">+ Tambah</a><?php endif; ?>
</div>
<form class="row g-2 mb-3">
  <div class="col-md-2"><select name="jenis" class="form-select">
      <option value="">Semua Jenis</option>
      <option value="roda2" <?= ($filters['jenis'] ?? '') === 'roda2' ? 'selected' : '' ?>>Roda 2</option>
      <option value="roda4" <?= ($filters['jenis'] ?? '') === 'roda4' ? 'selected' : '' ?>>Roda 4</option>
    </select></div>
  <div class="col-md-3"><select name="status_kendaraan" class="form-select">
      <option value="">Semua Status</option>
      <option value="aktif">Aktif</option>
      <option value="non-aktif">Non-aktif</option>
      <option value="rusak">Rusak</option>
      <option value="perbaikan">Sedang diperbaiki</option>
    </select></div>
  <div class="col-md-3"><select name="status_pajak" class="form-select">
      <option value="">Status Pajak</option>
      <option value="sudah">Sudah bayar</option>
      <option value="belum">Belum bayar</option>
    </select></div>
  <div class="col-md-3"><input name="penanggung" class="form-control" placeholder="Penanggung jawab"></div>
  <div class="col-md-1"><button class="btn btn-secondary w-100">Filter</button></div>
</form>
<div class="table-responsive">
  <table class="table table-striped table-hover align-middle">
    <thead>
      <tr>
        <th>Foto</th>
        <th>Plat</th>
        <th>Merk</th>
        <th>Tipe</th>
        <th>Tahun</th>
        <th>Jenis</th>
        <th>Penggunaan</th>
        <th>Status</th>
        <th>Penanggung Jawab</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($vehicles as $v): ?>
        <tr>
          <td style="width:80px;"><?php if ($v['foto_path']): ?><img src="<?= $v['foto_path'] ?>" class="img-thumbnail" style="width:70px; height:50px; object-fit:cover;" /><?php endif; ?></td>
          <td><?= htmlspecialchars($v['plat']) ?></td>
          <td><?= htmlspecialchars($v['merk']) ?></td>
          <td><?= htmlspecialchars($v['tipe']) ?></td>
          <td><?= htmlspecialchars($v['tahun']) ?></td>
          <td><span class="badge bg-primary"><?= $v['jenis'] ?></span></td>
          <td><?= $v['status_penggunaan'] ?></td>
          <td><?= $v['status_kendaraan'] ?></td>
          <td><?= $v['current_responsible'] ?></td>
          <td>
            <a href="/vehicles/show?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-secondary">Lihat</a>
            <?php if (Core\Auth::role() === 'admin'): ?>
              <a href="/vehicles/edit?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <form method="post" action="/vehicles/delete" class="d-inline" onsubmit="return confirm('Hapus data?')">
                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>