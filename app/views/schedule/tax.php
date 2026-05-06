<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Jadwal Pajak</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <div>
                        <a href="/schedule/tax/export" class="btn btn-outline-danger me-2" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </a>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formInput"
                            aria-expanded="<?= isset($selected_id) ? 'true' : 'false' ?>">
                            <i class="bi bi-plus-lg"></i> Buat Jadwal
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4 <?= isset($selected_id) ? 'show' : '' ?>" id="formInput">
                    <div class="card card-body bg-light border-0">
                        <h6 class="card-title mb-3">Buat Jadwal Pajak Baru</h6>
                        <form method="post" action="/schedule/tax/store" class="row g-3">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">

                            <div class="col-md-3">
                                <label class="form-label">Kendaraan</label>
                                <select name="vehicle_id" class="form-select" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    <?php foreach ($vehicles as $v): ?>
                                        <option value="<?= $v['id'] ?>" <?= (isset($selected_id) && $selected_id == $v['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v['plat'] . ' - ' . $v['merk']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jenis</label>
                                <select name="tax_type" class="form-select" required>
                                    <option value="tahunan">Tahunan</option>
                                    <option value="5_tahunan">5 Tahunan</option>
                                </select>
                            </div>
                            <div class="col-md-3"><label class="form-label">Deskripsi</label><input name="description"
                                    class="form-control" placeholder="Pajak Tahunan" required></div>
                            <div class="col-md-2"><label class="form-label">Interval (hari)</label><input
                                    name="interval_days" type="number" class="form-control" required value="365"></div>
                            <div class="col-md-2"><label class="form-label">Est. Biaya (Rp)</label><input
                                    name="estimated_cost" type="number" class="form-control" placeholder="0" value="0">
                            </div>
                            <div class="col-md-3"><label class="form-label">Tanggal Berikutnya</label><input
                                    name="next_date" type="date" class="form-control" required></div>
                            <div class="col-12"><button class="btn btn-primary">Simpan Jadwal</button></div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kendaraan</th>
                                <th>Jenis</th>
                                <th>Penanggung Jawab</th>
                                <th>Deskripsi</th>
                                <th>Perkiraan Biaya</th>
                                <th>Interval</th>
                                <th>Tanggal Berikutnya</th>
                                <th>Status</th>
                                <?php if (Core\Auth::role() === 'admin'): ?>
                                    <th class="text-end">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $r): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($r['foto_path'])): ?>
                                                <img src="<?= $r['foto_path'] ?>" class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-calendar-check text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($r['plat']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($r['merk']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (isset($r['tax_type']) && $r['tax_type'] == '5_tahunan'): ?>
                                            <span class="badge bg-purple text-white" style="background-color: #6f42c1;">5
                                                Tahunan</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tahunan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($r['current_responsible'] ?? '-') ?>
                                    </td>
                                    <td><?= htmlspecialchars($r['description']) ?></td>
                                    <td>Rp <?= number_format($r['estimated_cost'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= $r['interval_days'] ?> hari</td>
                                    <td><?= $r['next_date'] ?></td>
                                    <td>
                                        <?php
                                        $today = new DateTime();
                                        $next = new DateTime($r['next_date']);
                                        $diff = $today->diff($next);
                                        $isOverdue = $today > $next;
                                        $days = $diff->days;
                                        ?>
                                        <?php if ($isOverdue): ?>
                                            <span class="badge bg-danger">Telat <?= $days ?> hari a.l.</span>
                                        <?php elseif ($days <= 7): ?>
                                            <span class="badge bg-warning text-dark"><?= $days ?> hari lagi</span>
                                        <?php else: ?>
                                            <span class="badge bg-info text-dark">Akan datang</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (Core\Auth::role() === 'admin'): ?>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="/schedule/tax/edit?id=<?= $r['id'] ?>"
                                                    class="btn btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-success" title="Bayar / Selesaikan"
                                                    onclick="openPayModal('<?= $r['id'] ?>', '<?= htmlspecialchars($r['plat']) ?>', '<?= htmlspecialchars($r['description']) ?>', '<?= $r['estimated_cost'] ?>')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <form method="post" action="/schedule/tax/delete" class="d-inline"
                                                    onsubmit="return confirm('Hapus jadwal ini?')">
                                                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                                    <button class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($list)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada jadwal pajak.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bayar Pajak -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran Pajak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/schedule/tax/complete">
                <div class="modal-body">
                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                    <input type="hidden" name="id" id="payScheduleId">

                    <div class="alert alert-info py-2">
                        <small>Memproses pajak untuk: <b id="payPlat"></b> - <span id="payDesc"></span></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Bayar</label>
                        <input type="date" name="paid_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Aktual (Rp)</label>
                        <input type="number" name="cost" id="payCost" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan & Perbarui Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPayModal(id, plat, desc, cost) {
        document.getElementById('payScheduleId').value = id;
        document.getElementById('payPlat').innerText = plat;
        document.getElementById('payDesc').innerText = desc;
        document.getElementById('payCost').value = cost; // Pre-fill with estimated cost

        new bootstrap.Modal(document.getElementById('payModal')).show();
    }
</script>