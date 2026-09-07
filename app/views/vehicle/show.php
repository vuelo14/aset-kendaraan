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
    $maintenance_list = $maintenance_list ?? \Models\Maintenance::byVehicle($v['id']);
    ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <div class="d-flex align-items-center gap-2">
        <a href="/maintenance?vehicle_id=<?= $v['id'] ?>" class="btn btn-sm btn-primary">
          <i class="bi bi-plus-circle me-1"></i> Input Servis
        </a>
        <span class="badge bg-secondary-subtle text-secondary-emphasis border">
          Total: <?= count($maintenance_list) ?> Riwayat Servis
        </span>
      </div>
      <?php if (!empty($maintenance_list)): ?>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-secondary" id="btnExpandAllMaint">
            <i class="bi bi-chevron-bar-expand me-1"></i> Buka Semua Rincian
          </button>
          <button type="button" class="btn btn-outline-secondary" id="btnCollapseAllMaint">
            <i class="bi bi-chevron-bar-contract me-1"></i> Tutup Semua
          </button>
        </div>
      <?php endif; ?>
    </div>

    <?php if (empty($maintenance_list)): ?>
      <div class="alert alert-light text-center border p-4 text-muted my-2">
        <i class="bi bi-tools fs-1 d-block mb-2 text-secondary"></i>
        Belum ada riwayat servis untuk kendaraan ini.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;" class="text-center"></th>
              <th style="width: 110px;">Tanggal</th>
              <th>Jenis Servis</th>
              <th>Bengkel</th>
              <th class="text-center" style="width: 140px;">Komponen</th>
              <th class="text-end" style="width: 150px;">Total Biaya</th>
              <th class="text-center" style="width: 130px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($maintenance_list as $m): 
              $details = $m['details'] ?? \Models\MaintenanceDetail::byMaintenance($m['id']);
              $countDetails = count($details);
              $collapseId = 'collapse-detail-' . $m['id'];
              $isRealisasi = (stripos($m['notes'] ?? '', 'realisasi') !== false);
            ?>
              <!-- Baris Ringkasan Servis -->
              <tr class="maintenance-main-row">
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary rounded-circle btn-toggle-maint p-0 d-inline-flex align-items-center justify-content-center" 
                          style="width: 28px; height: 28px;"
                          type="button" 
                          data-bs-toggle="collapse" 
                          data-bs-target="#<?= $collapseId ?>" 
                          aria-expanded="false" 
                          aria-controls="<?= $collapseId ?>"
                          title="Buka / Tutup Rincian Komponen">
                    <i class="bi bi-chevron-down maint-chevron"></i>
                  </button>
                </td>
                <td class="fw-medium text-nowrap">
                  <i class="bi bi-calendar-event me-1 text-muted small"></i>
                  <?= !empty($m['date']) ? date('d/m/Y', strtotime($m['date'])) : '-' ?>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold text-dark"><?= htmlspecialchars($m['jenis']) ?></span>
                    <?php if ($isRealisasi): ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                        <i class="bi bi-lock-fill me-1"></i>Realisasi
                      </span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($m['notes'])): ?>
                    <small class="text-muted text-truncate d-block" style="max-width: 250px;" title="<?= htmlspecialchars($m['notes']) ?>">
                      <?= htmlspecialchars($m['notes']) ?>
                    </small>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="text-secondary small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($m['bengkel'] ?: '-') ?></span>
                </td>
                <td class="text-center">
                  <button class="badge <?= $countDetails > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' ?> btn-detail-badge"
                          type="button"
                          data-bs-toggle="collapse" 
                          data-bs-target="#<?= $collapseId ?>" 
                          aria-expanded="false" 
                          aria-controls="<?= $collapseId ?>"
                          style="cursor: pointer; border: none; font-size: 0.8rem;">
                    <i class="bi bi-list-check me-1"></i><?= $countDetails ?> Komponen
                  </button>
                </td>
                <td class="text-end fw-bold text-dark text-nowrap">
                  Rp <?= number_format($m['biaya'], 0, ',', '.') ?>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-sm" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#<?= $collapseId ?>" 
                            aria-expanded="false" 
                            aria-controls="<?= $collapseId ?>"
                            title="Lihat Rincian Komponen">
                      <i class="bi bi-eye"></i>
                    </button>
                    <?php if (Core\Auth::role() === 'admin'): ?>
                      <?php if ($isRealisasi): ?>
                        <form method="post" action="/maintenance/unlock" class="d-inline" 
                              onsubmit="return confirm('Buka kunci status Realisasi agar data servis ini dapat diedit kembali?')">
                          <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                          <input type="hidden" name="id" value="<?= $m['id'] ?>">
                          <input type="hidden" name="redirect" value="/vehicles/show?id=<?= $v['id'] ?>#maintenance">
                          <button type="submit" class="btn btn-outline-success btn-sm" title="Terkunci: Klik untuk Buka Kunci (Unlock)">
                            <i class="bi bi-unlock"></i>
                          </button>
                        </form>
                      <?php else: ?>
                        <a href="/maintenance/edit?id=<?= $m['id'] ?>&redirect=<?= urlencode('/vehicles/show?id=' . $v['id'] . '#maintenance') ?>" 
                           class="btn btn-outline-warning btn-sm" 
                           title="Edit Riwayat Servis">
                          <i class="bi bi-pencil"></i>
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($isRealisasi): ?>
                      <a href="/maintenance/details?id=<?= $m['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Lihat Rincian (Terkunci)">
                        <i class="bi bi-list-check"></i>
                      </a>
                    <?php else: ?>
                      <a href="/maintenance/details?id=<?= $m['id'] ?>" class="btn btn-outline-info btn-sm" title="Kelola Komponen / Tambah">
                        <i class="bi bi-gear"></i>
                      </a>
                    <?php endif; ?>
                    <a href="/maintenance/nota?id=<?= $m['id'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Cetak Nota">
                      <i class="bi bi-printer"></i>
                    </a>
                  </div>
                </td>
              </tr>

              <!-- Baris Collapsible Rincian Komponen -->
              <tr class="maintenance-detail-row">
                <td colspan="7" class="p-0 border-0">
                  <div class="collapse maint-collapse" id="<?= $collapseId ?>">
                    <div class="p-3 bg-light border-start border-4 border-primary m-2 rounded-2 shadow-sm">
                      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 pb-2 border-bottom">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                          <strong class="text-primary"><i class="bi bi-tools me-1"></i> Rincian Komponen & Jasa</strong>
                          <span class="text-muted small">
                            (ID Servis: #<?= str_pad($m['id'], 5, '0', STR_PAD_LEFT) ?> - <?= htmlspecialchars($m['jenis']) ?>)
                          </span>
                          <?php if ($isRealisasi): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                              <i class="bi bi-lock-fill me-1"></i>Terkunci (Realisasi)
                            </span>
                          <?php endif; ?>
                        </div>
                        <div>
                          <?php if (Core\Auth::role() === 'admin'): ?>
                            <?php if ($isRealisasi): ?>
                              <form method="post" action="/maintenance/unlock" class="d-inline" 
                                    onsubmit="return confirm('Buka kunci status Realisasi agar data servis ini dapat diedit dan komponen dapat ditambah kembali?')">
                                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <input type="hidden" name="redirect" value="/vehicles/show?id=<?= $v['id'] ?>#maintenance">
                                <button type="submit" class="btn btn-sm btn-outline-success py-1 px-2 me-1" style="font-size: 0.8rem;" title="Buka Kunci agar dapat diedit">
                                  <i class="bi bi-unlock me-1"></i> Buka Kunci Realisasi
                                </button>
                              </form>
                            <?php else: ?>
                              <a href="/maintenance/edit?id=<?= $m['id'] ?>&redirect=<?= urlencode('/vehicles/show?id=' . $v['id'] . '#maintenance') ?>" 
                                 class="btn btn-sm btn-outline-warning py-1 px-2" 
                                 style="font-size: 0.8rem;"
                                 title="Edit Data Servis">
                                <i class="bi bi-pencil me-1"></i> Edit Servis
                              </a>
                              <a href="/maintenance/details?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary py-1 px-2 ms-1" style="font-size: 0.8rem;">
                                <i class="bi bi-plus-circle me-1"></i> Kelola / Tambah Komponen
                              </a>
                            <?php endif; ?>
                          <?php endif; ?>
                          <a href="/maintenance/nota?id=<?= $m['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2 ms-1" style="font-size: 0.8rem;">
                            <i class="bi bi-printer me-1"></i> Cetak Nota
                          </a>
                        </div>
                      </div>

                      <?php if (!empty($details)): ?>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered bg-white mb-0 align-middle">
                            <thead class="table-secondary text-secondary" style="font-size: 0.85rem;">
                              <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th>Deskripsi Komponen / Jasa</th>
                                <th style="width: 120px;" class="text-center">Tipe</th>
                                <th style="width: 80px;" class="text-center">Qty</th>
                                <th style="width: 90px;" class="text-center">Satuan</th>
                                <th style="width: 140px;" class="text-end">Harga Satuan</th>
                                <th style="width: 140px;" class="text-end">Subtotal</th>
                              </tr>
                            </thead>
                            <tbody style="font-size: 0.9rem;">
                              <?php 
                              $no = 1;
                              $subtotalSum = 0;
                              foreach ($details as $d): 
                                $subtotalSum += $d['subtotal'];
                              ?>
                                <tr>
                                  <td class="text-center text-muted"><?= $no++ ?></td>
                                  <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($d['nama']) ?></span>
                                  </td>
                                  <td class="text-center">
                                    <?php if ($d['jenis'] === 'jasa'): ?>
                                      <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                        <i class="bi bi-wrench me-1"></i>JASA
                                      </span>
                                    <?php else: ?>
                                      <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
                                        <i class="bi bi-box-seam me-1"></i>KOMPONEN
                                      </span>
                                    <?php endif; ?>
                                  </td>
                                  <td class="text-center fw-medium">
                                    <?= rtrim(rtrim(number_format($d['jumlah'], 2, '.', ''), '0'), '.') ?>
                                  </td>
                                  <td class="text-center text-muted">
                                    <?= htmlspecialchars($d['satuan'] ?? '-') ?>
                                  </td>
                                  <td class="text-end text-muted">
                                    Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?>
                                  </td>
                                  <td class="text-end fw-bold text-dark">
                                    Rp <?= number_format($d['subtotal'], 0, ',', '.') ?>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light" style="font-size: 0.9rem;">
                              <tr>
                                <th colspan="6" class="text-end">Total Rincian Komponen:</th>
                                <th class="text-end text-primary fw-bold">
                                  Rp <?= number_format($subtotalSum, 0, ',', '.') ?>
                                </th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      <?php else: ?>
                        <div class="alert <?= $isRealisasi ? 'alert-secondary' : 'alert-warning' ?> border mb-0 py-2 d-flex align-items-center justify-content-between">
                          <span class="small">
                            <i class="bi <?= $isRealisasi ? 'bi-lock-fill text-secondary' : 'bi-exclamation-circle' ?> me-1"></i>
                            <?= $isRealisasi ? 'Belum ada rincian komponen untuk servis ini (status Realisasi: penambahan komponen dikunci).' : 'Belum ada komponen atau jasa yang dirinci untuk servis ini.' ?>
                          </span>
                          <?php if (!$isRealisasi): ?>
                            <a href="/maintenance/details?id=<?= $m['id'] ?>" class="btn btn-sm btn-primary py-0 px-2" style="font-size: 0.8rem;">
                              <i class="bi bi-plus me-1"></i> Tambah Rincian
                            </a>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($m['notes'])): ?>
                        <div class="mt-2 text-muted small bg-white p-2 border rounded">
                          <strong><i class="bi bi-chat-left-text me-1"></i> Catatan Servis:</strong>
                          <?= nl2br(htmlspecialchars($m['notes'])) ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
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

<style>
  .maint-chevron {
    display: inline-block;
    transition: transform 0.2s ease-in-out;
  }
  .maintenance-main-row:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.03);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btnExpandAll = document.getElementById('btnExpandAllMaint');
    const btnCollapseAll = document.getElementById('btnCollapseAllMaint');
    const collapseElements = document.querySelectorAll('.maint-collapse');

    if (btnExpandAll) {
      btnExpandAll.addEventListener('click', function() {
        collapseElements.forEach(function(el) {
          const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
          bsCollapse.show();
        });
      });
    }

    if (btnCollapseAll) {
      btnCollapseAll.addEventListener('click', function() {
        collapseElements.forEach(function(el) {
          const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
          bsCollapse.hide();
        });
      });
    }

    collapseElements.forEach(function(collapseEl) {
      collapseEl.addEventListener('show.bs.collapse', function() {
        const tr = collapseEl.closest('tr').previousElementSibling;
        if (tr) {
          const chevron = tr.querySelector('.maint-chevron');
          if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
      });
      collapseEl.addEventListener('hide.bs.collapse', function() {
        const tr = collapseEl.closest('tr').previousElementSibling;
        if (tr) {
          const chevron = tr.querySelector('.maint-chevron');
          if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
      });
    });

    // Otomatis aktifkan tab jika ada anchor di URL (misal #maintenance)
    if (window.location.hash) {
      const triggerEl = document.querySelector('button[data-bs-target="' + window.location.hash + '"]');
      if (triggerEl) {
        const tab = bootstrap.Tab.getOrCreateInstance(triggerEl);
        tab.show();
      }
    }
  });
</script>