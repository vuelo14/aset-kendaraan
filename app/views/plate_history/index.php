<?php 
use Core\Auth; 
use Helpers\CSRF;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">
        <i class="bi bi-card-heading me-2"></i> Riwayat Ganti Plat Nomor
    </h2>
    <?php if (Auth::role() === 'admin'): ?>
        <a href="/plate-history/create" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Riwayat
        </a>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kendaraan</th>
                        <th>Plat Lama</th>
                        <th>Plat Baru</th>
                        <th>Tanggal Ganti</th>
                        <th>Keterangan</th>
                        <?php if (Auth::role() === 'admin'): ?>
                            <th class="text-end">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($histories)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data riwayat ganti plat.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($histories as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?></div>
                                    <small class="text-muted">Plat Saat Ini: <?= htmlspecialchars($row['current_plat']) ?></small>
                                </td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['plat_lama']) ?></span></td>
                                <td><span class="badge bg-primary"><?= htmlspecialchars($row['plat_baru']) ?></span></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal_ganti']))) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['keterangan'] ?? '-')) ?></td>
                                <?php if (Auth::role() === 'admin'): ?>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="/plate-history/edit?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/plate-history/delete" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');">
                                                <input type="hidden" name="csrf" value="<?= CSRF::token() ?>">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
