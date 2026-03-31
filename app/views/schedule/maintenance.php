<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Jadwal Pemeliharaan</h5>
                <?php if (Core\Auth::role() === 'admin'): ?>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formInput"
                        aria-expanded="<?= isset($selected_id) ? 'true' : 'false' ?>">
                        <i class="bi bi-plus-lg"></i> Buat Jadwal
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="collapse mb-4 <?= isset($selected_id) ? 'show' : '' ?>" id="formInput">
                    <div class="card card-body border-0">
                        <h6 class="card-title mb-3">Buat Jadwal Baru</h6>
                        <form method="post" action="/schedule/maintenance/store" class="row g-3">
                            <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">

                            <div class="col-md-4">
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
                            <div class="col-md-3"><label class="form-label">Deskripsi</label><input name="description"
                                    class="form-control" placeholder="Servis Berkala" required></div>
                            <div class="col-md-2"><label class="form-label">Interval (hari)</label><input
                                    name="interval_days" type="number" class="form-control" required></div>
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
                                <th>Deskripsi</th>
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
                                                    <i class="bi bi-calendar-event text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($r['plat']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($r['merk']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($r['description']) ?></td>
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
                                                <a href="/schedule/maintenance/edit?id=<?= $r['id'] ?>"
                                                    class="btn btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="post" action="/schedule/maintenance/delete" class="d-inline"
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
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada jadwal pemeliharaan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>