<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Data Kendaraan</h3>
  <?php if (Core\Auth::role() === 'admin'): ?>
    <a href="/vehicles/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kendaraan</a>
  <?php endif; ?>
</div>

<div class="card card-body mb-4 shadow-sm border-0">
  <form class="row g-2">
    <div class="col-md-2">
      <select name="jenis" class="form-select">
        <option value="">Semua Jenis</option>
        <option value="roda2" <?= ($filters['jenis'] ?? '') === 'roda2' ? 'selected' : '' ?>>Roda 2</option>
        <option value="roda4" <?= ($filters['jenis'] ?? '') === 'roda4' ? 'selected' : '' ?>>Roda 4</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status_penggunaan" class="form-select">
        <option value="">Semua Penggunaan</option>
        <option value="operasional" <?= ($filters['status_penggunaan'] ?? '') === 'operasional' ? 'selected' : '' ?>>
          Operasional</option>
        <option value="jabatan" <?= ($filters['status_penggunaan'] ?? '') === 'jabatan' ? 'selected' : '' ?>>Jabatan
        </option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status_kendaraan" class="form-select">
        <option value="">Semua Status</option>
        <option value="aktif" <?= ($filters['status_kendaraan'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
        <option value="non-aktif" <?= ($filters['status_kendaraan'] ?? '') === 'non-aktif' ? 'selected' : '' ?>>Non-aktif
        </option>
        <option value="rusak" <?= ($filters['status_kendaraan'] ?? '') === 'rusak' ? 'selected' : '' ?>>Rusak</option>
        <option value="perbaikan" <?= ($filters['status_kendaraan'] ?? '') === 'perbaikan' ? 'selected' : '' ?>>Sedang
          diperbaiki</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status_pajak" class="form-select">
        <option value="">Status Pajak</option>
        <option value="sudah" <?= ($filters['status_pajak'] ?? '') === 'sudah' ? 'selected' : '' ?>>Sudah bayar</option>
        <option value="belum" <?= ($filters['status_pajak'] ?? '') === 'belum' ? 'selected' : '' ?>>Belum bayar</option>
      </select>
    </div>
    <div class="col-md-3">
      <input name="penanggung" class="form-control" placeholder="Cari Penanggung Jawab..."
        value="<?= htmlspecialchars($filters['penanggung'] ?? '') ?>">
    </div>
    <div class="col-md-1">
      <button class="btn btn-secondary w-100"><i class="bi bi-search"></i></button>
    </div>
  </form>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
  <?php foreach ($vehicles as $v): ?>
    <div class="col">
      <div class="card h-100 shadow-sm border-0 vehicle-card">
        <div class="position-relative">
          <?php if ($v['foto_path']): ?>
            <img src="<?= $v['foto_path'] ?>" class="card-img-top" alt="Foto Kendaraan"
              style="height: 200px; object-fit: cover;">
          <?php else: ?>
            <div class="bg-secondary text-white d-flex justify-content-center align-items-center card-img-top"
              style="height: 200px;">
              <i class="bi bi-truck fs-1"></i>
            </div>
          <?php endif; ?>
          <div class="position-absolute top-0 end-0 p-2">
            <span class="badge text-dark fw-bold shadow-sm border"><?= $v['tahun'] ?></span>
          </div>
        </div>

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="card-title fw-bold mb-0 text-primary"><?= htmlspecialchars($v['plat']) ?></h5>
            <span class="badge rounded-pill <?= $v['jenis'] === 'roda2' ? 'bg-info text-dark' : 'bg-success' ?>">
              <?= $v['jenis'] === 'roda2' ? 'Motor' : 'Mobil' ?>
            </span>
          </div>
          <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($v['merk']) ?> -
            <?= htmlspecialchars($v['tipe']) ?>
          </h6>

          <div class="mb-3">
            <div class="d-flex flex-wrap gap-1">
              <span class="badge bg-secondary"><?= $v['status_penggunaan'] ?></span>
              <?php if ($v['status_kendaraan'] === 'aktif'): ?>
                <span class="badge bg-success">Aktif</span>
              <?php elseif ($v['status_kendaraan'] === 'rusak'): ?>
                <span class="badge bg-danger">Rusak</span>
              <?php elseif ($v['status_kendaraan'] === 'perbaikan'): ?>
                <span class="badge bg-warning text-dark">Servis</span>
              <?php else: ?>
                <span class="badge bg-secondary">Non-Aktif</span>
              <?php endif; ?>
            </div>
          </div>

          <p class="card-text small text-muted mb-0">
            <i class="bi bi-person-badge me-1"></i> Penanggung Jawab:
          </p>
          <p class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($v['current_responsible']) ?>">
            <?= htmlspecialchars($v['current_responsible'] ?: '-') ?>
          </p>
        </div>

        <div class="card-footer border-top-0 d-flex justify-content-between align-items-center pb-3">
          <small class="text-muted">Kondisi: <?= $v['kondisi'] ?>%</small>
          <div class="btn-group">
            <a href="/vehicles/show?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary" title="Detail">
              <i class="bi bi-eye"></i>
            </a>
            <?php if (Core\Auth::role() === 'admin'): ?>
              <a href="/vehicles/edit?id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="post" action="/vehicles/delete" class="d-inline" onsubmit="return confirm('Hapus data?')">
                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                <button class="btn btn-sm btn-outline-danger" title="Hapus"
                  style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if (empty($vehicles)): ?>
  <div class="alert alert-info text-center mt-5">
    <i class="bi bi-info-circle display-4 d-block mb-3"></i>
    Belum ada dataaraan yang sesuai dengan filter.
  </div>
<?php endif; ?>