<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Monitoring Anggaran Global Kendaraan (Tahun Ini)</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori Anggaran</th>
                                <th class="text-center">Jumlah Unit</th>
                                <th class="text-end">Limit Total (Plafon)</th>
                                <th class="text-end">Total Realisasi</th>
                                <th class="text-end">Sisa Anggaran</th>
                                <th class="text-center">Status</th>
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
                                        <?= $b['unit_count'] ?> Unit
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
                                        <div class="progress" style="height: 20px;">
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
                                                title="Atur Pagu">
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

        <?php if (!empty($unit_budget_monitoring)): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 fw-bold">Monitoring Limit Maksimal Per Unit (Khusus Roda 2)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kendaraan</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Limit Per Unit</th>
                                    <th class="text-end">Realisasi Per Unit</th>
                                    <th class="text-end">Sisa Limit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unit_budget_monitoring as $u): ?>
                                    <?php
                                    $percentage_u = $u['max_unit_budget'] > 0 ? ($u['realisasi_unit'] / $u['max_unit_budget']) * 100 : 0;
                                    $status_color_u = 'success';
                                    if ($percentage_u > 80)
                                        $status_color_u = 'danger';
                                    elseif ($percentage_u > 60)
                                        $status_color_u = 'warning';
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">
                                                <?= htmlspecialchars($u['plat']) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($u['merk']) ?>
                                            </small>
                                        </td>
                                        <td><span class="badge bg-secondary">
                                                <?= htmlspecialchars($u['category_name']) ?>
                                            </span></td>
                                        <td class="text-end">Rp
                                            <?= number_format($u['max_unit_budget'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-end">Rp
                                            <?= number_format($u['realisasi_unit'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-end text-<?= $status_color_u ?> fw-bold">Rp
                                            <?= number_format($u['sisa_budget_unit'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-<?= $status_color_u ?>" role="progressbar"
                                                    style="width: <?= min($percentage_u, 100) ?>%;"
                                                    aria-valuenow="<?= $percentage_u ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?= round($percentage_u, 1) ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>