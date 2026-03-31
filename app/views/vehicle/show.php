<?php include APP_PATH . '/views/layouts/message.php'; ?>

<h3>Detail Kendaraan</h3>
<div class="row g-3">
  <div class="col-md-4">
    <?php if ($v['foto_path']): ?>
      <img src="<?= $v['foto_path'] ?>" class="img-fluid rounded" />
    <?php endif; ?>
  </div>
  <div class="col-md-8">
    <table class="table">
      <tr>
        <th>Plat</th>
        <td><?= htmlspecialchars($v['plat']) ?></td>
      </tr>
      <tr>
        <th>Merk</th>
        <td><?= htmlspecialchars($v['merk']) ?></td>
      </tr>
      <tr>
        <th>Tipe</th>
        <td><?= htmlspecialchars($v['tipe']) ?></td>
      </tr>
      <tr>
        <th>Tahun</th>
        <td><?= htmlspecialchars($v['tahun']) ?></td>
      </tr>
      <tr>
        <th>Jenis</th>
        <td><?= htmlspecialchars($v['jenis']) ?></td>
      </tr>
      <tr>
        <th>Penggunaan</th>
        <td><?= htmlspecialchars($v['status_penggunaan']) ?></td>
      </tr>
      <tr>
        <th>Status</th>
        <td><?= htmlspecialchars($v['status_kendaraan']) ?></td>
      </tr>
      <tr>
        <th>Kondisi</th>
        <td><?= htmlspecialchars($v['kondisi']) ?></td>
      </tr>
      <tr>
        <th>Penanggung Jawab (saat ini)</th>
        <td>
          <?php if (!empty($current)): ?>
            <strong><?= htmlspecialchars($current['pemakai']) ?></strong>
            <small class="text-muted">(<?= htmlspecialchars($current['jabatan'] ?? '-') ?>)</small>
          <?php else: ?>
            <span class="text-muted">Belum ditetapkan</span>
          <?php endif; ?>
        </td>
      </tr>
    </table>

    <?php if (Core\Auth::role() === 'admin'): ?>
      <a href="/usage/create?vehicle_id=<?= $v['id'] ?>" class="btn btn-primary">
        + Tambah Penanggung Jawab
      </a>
    <?php endif; ?>
  </div>
</div>

<ul class="nav nav-tabs mt-4" id="vehicleTab" role="tablist">
  <li class="nav-item">
    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#usage" type="button">Riwayat Penanggung Jawab</button>
  </li>
  <li class="nav-item">
    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance" type="button">Riwayat Servis</button>
  </li>
  <li class="nav-item">
    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tax" type="button">Riwayat Pajak</button>
  </li>
</ul>

<div class="tab-content p-3 border border-top-0 bg-white">
  <div class="tab-pane fade show active" id="usage">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Pemakai</th>
          <th>Jabatan</th>
          <th>Periode</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($history)): foreach ($history as $h): ?>
            <tr>
              <td><?= htmlspecialchars($h['pemakai']) ?></td>
              <td><?= htmlspecialchars($h['jabatan'] ?? '-') ?></td>
              <td><?= $h['start_date'] ?> s/d <?= $h['end_date'] ?? 'skrg' ?></td>
              <td>
                <a href="/usage/edit?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              </td>
            </tr>
        <?php endforeach;
        else: echo '<tr><td colspan="4">Belum ada data.</td></tr>';
        endif; ?>
      </tbody>
    </table>
  </div>

  <div class="tab-pane fade" id="maintenance">
    <?php
    // Kita perlu fetch data ini di Controller nanti
    $maintenance_list = \Models\Maintenance::byVehicle($v['id']);
    ?>
    <a href="/maintenance?vehicle_id=<?= $v['id'] ?>" class="btn btn-sm btn-primary mb-2">+ Input Servis</a>
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Bengkel</th>
          <th>Biaya</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($maintenance_list as $m): ?>
          <tr>
            <td><?= $m['date'] ?></td>
            <td><?= $m['jenis'] ?></td>
            <td><?= $m['bengkel'] ?></td>
            <td>Rp <?= number_format($m['biaya'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="tab-pane fade" id="tax">
    <?php
    $tax_list = \Models\Tax::byVehicle($v['id']);
    ?>
    <a href="/tax?vehicle_id=<?= $v['id'] ?>" class="btn btn-sm btn-primary mb-2">+ Input Pajak</a>
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Status</th>
          <th>Biaya</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tax_list as $t): ?>
          <tr>
            <td><?= $t['date'] ?></td>
            <td><?= $t['jenis'] ?></td>
            <td><?= $t['status'] ?></td>
            <td>Rp <?= number_format($t['biaya'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>