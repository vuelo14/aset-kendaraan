<h3>Log Aktivitas Sistem (100 Terakhir)</h3>
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Tabel</th>
                <th>ID Record</th>
                <th>Detail Perubahan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['created_at'] ?></td>
                    <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?> (<?= $log['username'] ?? '-' ?>)</td>
                    <td><span class="badge bg-secondary"><?= strtoupper($log['action']) ?></span></td>
                    <td><?= $log['table_name'] ?></td>
                    <td><?= $log['record_id'] ?></td>
                    <td>
                        <small style="font-family:monospace; font-size:0.8em;">
                            <?= htmlspecialchars(substr($log['changes'], 0, 100)) ?>...
                        </small>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>