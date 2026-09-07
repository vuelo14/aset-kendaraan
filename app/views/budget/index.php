<div class="row">
    <div class="col-12">
        <!-- Monitoring Anggaran Global -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Monitoring Anggaran Global Kendaraan (Tahun Ini)</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori Anggaran</th>
                                <th class="text-center">Jumlah Unit</th>
                                <th class="text-end">Limit Total (Plafon)</th>
                                <th class="text-end">Total Realisasi</th>
                                <th class="text-end">Sisa Anggaran</th>
                                <th class="text-center" width="200">Status</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($budget_monitoring as $b): ?>
                                <?php
                                $percentage = $b['max_total_budget'] > 0 ? ($b['total_realisasi'] / $b['max_total_budget']) * 100 : 0;
                                $status_color = 'success';
                                if ($percentage > 80)
                                    $status_color = 'danger';
                                elseif ($percentage > 60)
                                    $status_color = 'warning';
                                ?>
                                <tr>
                                    <td class="fw-bold">
                                        <?= htmlspecialchars($b['category_name']) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary border"><?= $b['unit_count'] ?> Unit</span>
                                    </td>
                                    <td class="text-end fw-bold">Rp
                                        <?= number_format($b['max_total_budget'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-end">Rp
                                        <?= number_format($b['total_realisasi'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-end text-<?= $status_color ?> fw-bold">Rp
                                        <?= number_format($b['sisa_anggaran'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 18px;">
                                            <div class="progress-bar bg-<?= $status_color ?>" role="progressbar"
                                                style="width: <?= min($percentage, 100) ?>%;"
                                                aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                                <?= round($percentage, 1) ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-center">
                                            <a href="/budget/edit?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-secondary"
                                                title="Atur Pagu Global">
                                                <i class="bi bi-gear"></i> Atur
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monitoring & Pengaturan Anggaran Presisi Per Unit Kendaraan -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header py-3 bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-1 fw-bold"><i class="bi bi-car-front-fill text-success me-2"></i>Pagu Anggaran & Realisasi Per Unit Kendaraan</h5>
                    <small class="text-muted">Kelola pagu anggaran spesifik untuk setiap unit kendaraan (Roda 4 Jabatan, Roda 4 Operasional, & Roda 2)</small>
                </div>
                <!-- Filter Tabs -->
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary btn-filter-budget active" id="filterBtn_all" onclick="filterVehicleTable('all')">Semua (<?= count($unit_budget_monitoring) ?>)</button>
                    <button type="button" class="btn btn-outline-secondary btn-filter-budget" id="filterBtn_r4_jabatan" onclick="filterVehicleTable('r4_jabatan')">R4 Jabatan</button>
                    <button type="button" class="btn btn-outline-secondary btn-filter-budget" id="filterBtn_r4_operasional" onclick="filterVehicleTable('r4_operasional')">R4 Operasional</button>
                    <button type="button" class="btn btn-outline-secondary btn-filter-budget" id="filterBtn_r2" onclick="filterVehicleTable('r2')">Roda 2</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kendaraan</th>
                                <th>Penanggung Jawab</th>
                                <th>Kategori</th>
                                <th class="text-end">Pagu Unit</th>
                                <th class="text-end">Realisasi Tahun Ini</th>
                                <th class="text-end">Sisa Anggaran</th>
                                <th class="text-center" width="160">Status</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($unit_budget_monitoring as $u): 
                                $isCustom = !empty($u['custom_budget']);
                                $maxUnit = (float)$u['max_unit_budget'];
                                $realisasi = (float)$u['realisasi_unit'];
                                $sisa = (float)$u['sisa_budget_unit'];
                                
                                $percentage_u = ($maxUnit > 0) ? ($realisasi / $maxUnit) * 100 : 0;
                                $status_color_u = 'success';
                                if ($percentage_u > 80) {
                                    $status_color_u = 'danger';
                                } elseif ($percentage_u > 60) {
                                    $status_color_u = 'warning';
                                }

                                // Kategori filter
                                $filterType = 'r2';
                                if ($u['jenis'] === 'roda4') {
                                    $filterType = ($u['status_penggunaan'] === 'jabatan') ? 'r4_jabatan' : 'r4_operasional';
                                }
                            ?>
                                <tr class="row-vehicle-budget" data-filter="<?= $filterType ?>">
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">
                                            <?= htmlspecialchars($u['plat']) ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($u['merk'] . ' ' . ($u['tipe'] ?? '')) ?>
                                        </small>
                                        <div class="mt-1">
                                            <span class="badge bg-<?= $u['jenis'] === 'roda4' ? 'primary' : 'info' ?>-subtle text-<?= $u['jenis'] === 'roda4' ? 'primary' : 'info' ?> border">
                                                <?= $u['jenis'] === 'roda4' ? 'R4' : 'R2' ?>
                                            </span>
                                            <span class="badge bg-secondary-subtle text-secondary border text-capitalize">
                                                <?= htmlspecialchars($u['status_penggunaan'] ?? '-') ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($u['current_responsible'])): ?>
                                            <i class="bi bi-person text-primary me-1"></i>
                                            <span class="fw-medium"><?= htmlspecialchars($u['current_responsible']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($u['category_name'] ?? 'Umum') ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($maxUnit > 0): ?>
                                            <div class="fw-bold text-dark">
                                                Rp <?= number_format($maxUnit, 0, ',', '.') ?>
                                            </div>
                                            <?php if ($isCustom): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle" title="Pagu Khusus Unit Ditetapkan">
                                                    <i class="bi bi-check-circle me-1"></i>Presisi
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border" title="Pagu Mengikuti Kategori">
                                                    Standar Kategori
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                                Belum Diatur
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        Rp <?= number_format($realisasi, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-end text-<?= ($maxUnit > 0 ? ($sisa >= 0 ? 'success' : 'danger') : 'muted') ?> fw-bold">
                                        <?php if ($maxUnit > 0): ?>
                                            Rp <?= number_format($sisa, 0, ',', '.') ?>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($maxUnit > 0): ?>
                                            <div class="progress" style="height: 18px;">
                                                <div class="progress-bar bg-<?= $status_color_u ?>" role="progressbar"
                                                    style="width: <?= min($percentage_u, 100) ?>%;"
                                                    aria-valuenow="<?= $percentage_u ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?= round($percentage_u, 1) ?>%
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted text-center d-block">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-center text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="openEditPaguModal(<?= $u['vehicle_id'] ?>, '<?= htmlspecialchars(addslashes($u['plat']), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($u['merk'] . ' ' . ($u['tipe'] ?? '')), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($u['jenis'] === 'roda4' ? 'Roda 4' : 'Roda 2'), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($u['status_penggunaan'] ?? ''), ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($u['current_responsible'] ?? ''), ENT_QUOTES) ?>', <?= (int)($u['budget_category_id'] ?? 0) ?>, <?= $u['custom_budget'] !== null ? (float)$u['custom_budget'] : 'null' ?>)">
                                                <i class="bi bi-sliders me-1"></i> Atur Pagu
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Atur Pagu Anggaran Unit Kendaraan -->
<div class="modal fade" id="modalEditVehicleBudget" tabindex="-1" aria-labelledby="modalEditVehicleBudgetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="/budget/vehicle/update">
                <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                <input type="hidden" name="vehicle_id" id="edit_v_id" value="">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalEditVehicleBudgetLabel">
                        <i class="bi bi-wallet2 me-2"></i>Atur Pagu Anggaran Presisi Unit
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="fw-bold text-dark mb-0" id="edit_v_plat">-</h5>
                                <span class="badge bg-secondary" id="edit_v_badge">-</span>
                            </div>
                            <div class="text-secondary small fw-medium" id="edit_v_desc">-</div>
                            <div class="mt-2 small pt-2 border-top" id="edit_v_responsible_wrap">
                                <span class="text-muted">Penanggung Jawab:</span> <span class="fw-semibold text-dark" id="edit_v_responsible">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Kategori Anggaran</label>
                        <select name="budget_category_id" id="edit_v_category_id" class="form-select">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                    <?php if ($cat['max_unit_budget']): ?>
                                        (Pagu Standar: Rp <?= number_format($cat['max_unit_budget'], 0, ',', '.') ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-muted small fw-semibold mb-0">Pagu Anggaran Khusus Unit (Rp)</label>
                            <button type="button" class="btn btn-link p-0 text-decoration-none small text-danger" onclick="clearBudgetInput()">
                                <i class="bi bi-x-circle me-1"></i>Kosongkan (Ikut Kategori)
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="1000" min="0" name="budget_limit" id="edit_v_budget_limit" class="form-control form-control-lg fw-bold text-primary" placeholder="0" oninput="updateFormattedPreview()">
                        </div>
                        <div class="form-text text-muted small mt-1">
                            Nilai Terbaca: <strong class="text-dark" id="edit_v_budget_preview">Rp 0</strong>
                        </div>

                        <!-- Quick Presets -->
                        <div class="mt-3 pt-2 border-top">
                            <small class="text-muted d-block mb-1">Pilihan Cepat Pagu Tahunan:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(1000000)">1 Juta</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(5000000)">5 Juta</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(10000000)">10 Juta</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(15000000)">15 Juta</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(20000000)">20 Juta</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="setBudgetPreset(25000000)">25 Juta</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Pagu Anggaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let editPaguModalInstance = null;

    function openEditPaguModal(vehicleId, plat, desc, jenis, penggunaan, responsible, categoryId, budgetLimit) {
        document.getElementById('edit_v_id').value = vehicleId;
        document.getElementById('edit_v_plat').innerText = plat;
        document.getElementById('edit_v_desc').innerText = desc;
        document.getElementById('edit_v_badge').innerText = jenis + ' - ' + (penggunaan ? penggunaan.toUpperCase() : '-');
        document.getElementById('edit_v_responsible').innerText = responsible ? responsible : 'Belum tercatat';

        if (categoryId) {
            document.getElementById('edit_v_category_id').value = categoryId;
        }

        const inputBudget = document.getElementById('edit_v_budget_limit');
        if (budgetLimit !== null && budgetLimit !== undefined && budgetLimit !== '') {
            inputBudget.value = budgetLimit;
        } else {
            inputBudget.value = '';
        }

        updateFormattedPreview();

        const modalEl = document.getElementById('modalEditVehicleBudget');
        if (!editPaguModalInstance) {
            editPaguModalInstance = new bootstrap.Modal(modalEl);
        }
        editPaguModalInstance.show();

        setTimeout(() => {
            inputBudget.focus();
            inputBudget.select();
        }, 350);
    }

    function setBudgetPreset(val) {
        document.getElementById('edit_v_budget_limit').value = val;
        updateFormattedPreview();
    }

    function clearBudgetInput() {
        document.getElementById('edit_v_budget_limit').value = '';
        updateFormattedPreview();
    }

    function updateFormattedPreview() {
        const val = parseFloat(document.getElementById('edit_v_budget_limit').value);
        if (!isNaN(val) && val > 0) {
            document.getElementById('edit_v_budget_preview').innerText = 'Rp ' + val.toLocaleString('id-ID');
        } else {
            document.getElementById('edit_v_budget_preview').innerText = 'Mengikuti Standar Pagu Kategori';
        }
    }

    function filterVehicleTable(type) {
        document.querySelectorAll('.btn-filter-budget').forEach(b => {
            b.classList.remove('btn-primary', 'active');
            b.classList.add('btn-outline-secondary');
        });

        const activeBtn = document.getElementById('filterBtn_' + type);
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline-secondary');
            activeBtn.classList.add('btn-primary', 'active');
        }

        const rows = document.querySelectorAll('.row-vehicle-budget');
        rows.forEach(r => {
            if (type === 'all' || r.getAttribute('data-filter') === type) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }
</script>