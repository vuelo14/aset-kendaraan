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

<h4 class="mt-4">Riwayat Penanggung Jawab</h4>
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Pemakai</th>
        <th>Jabatan</th>
        <th>Periode</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($history)): foreach ($history as $h): ?>
          <tr>
            <td><?= htmlspecialchars($h['pemakai']) ?></td>
            <td><?= htmlspecialchars($h['jabatan'] ?? '-') ?></td>
            <td>
              <?= htmlspecialchars($h['start_date']) ?> s/d
              <?= htmlspecialchars($h['end_date'] ?? 'sekarang') ?>
            </td>
          </tr>
        <?php endforeach;
      else: ?>
        <tr>
          <td colspan="3" class="text-muted">Belum ada riwayat.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>