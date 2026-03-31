<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="/budget" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Monitoring
        </a>

        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 bg-secondary text-white">
                <h5 class="mb-0 fw-bold">Atur Pagu Anggaran -
                    <?= htmlspecialchars($category['category_name']) ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="/budget/update" class="row g-3">
                    <input type="hidden" name="csrf" value="<?= Helpers\CSRF::token() ?>">
                    <input type="hidden" name="id" value="<?= $category['id'] ?>">

                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Batas Anggaran Per Unit (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input name="max_unit_budget" type="number" step="0.01"
                                class="form-control form-control-lg fw-bold"
                                value="<?= htmlspecialchars($category['max_unit_budget'] ?? '') ?>"
                                placeholder="Kosongkan jika tidak ada batas per unit">
                        </div>
                        <small class="text-danger">* Biasanya untuk roda 2 (Motor)</small>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-bold">Plafon Anggaran Maksimal / Total Global</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input name="max_total_budget" type="number" step="0.01"
                                class="form-control form-control-lg text-primary fw-bold"
                                value="<?= htmlspecialchars($category['max_total_budget']) ?>" required>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>