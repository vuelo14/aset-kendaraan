<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3">
                <h5 class="mb-0 fw-bold">Edit Komponen & Jasa</h5>
            </div>
            <div class="card-body">
                <form method="post" action="/komponen/update" class="row g-3">
                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                    <input type="hidden" name="id" value="<?= $k['id'] ?>">

                    <div class="col-md-12">
                        <label class="form-label">Nama Barang / Jasa</label>
                        <input name="nama" class="form-control" value="<?= htmlspecialchars($k['nama']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="komponen" <?= $k['jenis'] == 'komponen' ? 'selected' : '' ?>>Komponen</option>
                            <option value="jasa" <?= $k['jenis'] == 'jasa' ? 'selected' : '' ?>>Jasa</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan</label>
                        <input name="satuan" class="form-control" value="<?= htmlspecialchars($k['satuan']) ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Harga Standar (Rp)</label>
                        <input name="harga" type="number" step="0.01" class="form-control"
                            value="<?= htmlspecialchars($k['harga']) ?>" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button class="btn btn-primary" type="submit">Update Data</button>
                        <a href="/komponen" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>