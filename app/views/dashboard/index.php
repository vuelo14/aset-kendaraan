
<h2 class="mb-3">Dashboard Ringkasan</h2>
<div class="row g-3">
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="fw-bold">Total</div><div class="fs-4"><?= $counts['total'] ?></div></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="fw-bold">Roda 2</div><div class="fs-4"><?= $counts['roda2'] ?></div></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="fw-bold">Roda 4</div><div class="fs-4"><?= $counts['roda4'] ?></div></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><div class="fw-bold">Jabatan</div><div class="fs-4"><?= $counts['jabatan'] ?></div></div></div></div>
</div>

<div class="row mt-4 g-3">
  <div class="col-lg-6">
    <div class="card shadow-sm"><div class="card-body">
      <h6 class="card-title">Kendaraan jatuh tempo pajak (≤30 hari)</h6>
      <ul class="list-group">
        <?php foreach($upcomingTax as $t): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= $t['plat'] ?> - <?= $t['merk'] ?> <small class="text-muted"><?= $t['description'] ?></small></span>
            <span class="badge bg-danger"><?= $t['next_date'] ?></span>
          </li>
        <?php endforeach; ?>
        <?php if(empty($upcomingTax)) echo '<li class="list-group-item">Tidak ada.</li>'; ?>
      </ul>
    </div></div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm"><div class="card-body">
      <h6 class="card-title">Kendaraan jatuh tempo pemeliharaan (≤30 hari)</h6>
      <ul class="list-group">
        <?php foreach($upcomingMaint as $m): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><?= $m['plat'] ?> - <?= $m['merk'] ?> <small class="text-muted"><?= $m['description'] ?></small></span>
            <span class="badge bg-warning text-dark"><?= $m['next_date'] ?></span>
          </li>
        <?php endforeach; ?>
        <?php if(empty($upcomingMaint)) echo '<li class="list-group-item">Tidak ada.</li>'; ?>
      </ul>
    </div></div>
  </div>
</div>

<div class="row mt-4 g-3">
  <div class="col-lg-6">
    <div class="card shadow-sm"><div class="card-body">
      <h6 class="card-title">Grafik Biaya Pemeliharaan per Bulan</h6>
      <canvas id="maintChart" height="150"></canvas>
    </div></div>
  </div>
  <div class="col-lg-6">
    <div class="card shadow-sm"><div class="card-body">
      <h6 class="card-title">Grafik Biaya Pajak per Tahun</h6>
      <canvas id="taxChart" height="150"></canvas>
    </div></div>
  </div>
</div>
<script>
const maintLabels = <?= json_encode(array_map(fn($r)=>$r['ym'], $maintCosts)) ?>;
const maintData = <?= json_encode(array_map(fn($r)=>floatval($r['total']), $maintCosts)) ?>;
new Chart(document.getElementById('maintChart'), { type:'line', data:{ labels:maintLabels, datasets:[{ label:'Biaya Pemeliharaan', data:maintData, borderColor:'#0d6efd', fill:false }] }, options:{ responsive:true } });
const taxLabels = <?= json_encode(array_map(fn($r)=>$r['y'], $taxCosts)) ?>;
const taxData = <?= json_encode(array_map(fn($r)=>floatval($r['total']), $taxCosts)) ?>;
new Chart(document.getElementById('taxChart'), { type:'bar', data:{ labels:taxLabels, datasets:[{ label:'Biaya Pajak', data:taxData, backgroundColor:'#198754' }] }, options:{ responsive:true } });
</script>
