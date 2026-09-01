<?php
session_start();
require('db_conn.php');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}

$date1 = isset($_GET["date1"]) ? $_GET["date1"] : date("Y-m-01");
$date2 = isset($_GET["date2"]) ? $_GET["date2"] : date("Y-m-d");

// KPIs مع التوافق التام مع إصدارات PHP القديمة
$sqlTotalDocs = "SELECT COUNT(*) AS total_docs FROM document WHERE date BETWEEN '$date1' AND '$date2' AND cancel=0 AND type<>23";
$resDocs = mysqli_query($conn, $sqlTotalDocs);
$rowDocs = $resDocs ? mysqli_fetch_assoc($resDocs) : null;
$totalDocs = isset($rowDocs['total_docs']) ? $rowDocs['total_docs'] : 0;

$sqlTotalAmount = "SELECT SUM(TotalCost) AS total_amount FROM document WHERE date BETWEEN '$date1' AND '$date2' AND cancel=0 AND type<>23";
$resAmount = mysqli_query($conn, $sqlTotalAmount);
$rowAmount = $resAmount ? mysqli_fetch_assoc($resAmount) : null;
$totalAmount = isset($rowAmount['total_amount']) ? $rowAmount['total_amount'] : 0;

$sqlDebt = "SELECT SUM(TotalCost - commission_agent) AS total_debt FROM document WHERE date BETWEEN '$date1' AND '$date2' AND cancel=0 AND status=0 AND type<>23";
$resDebt = mysqli_query($conn, $sqlDebt);
$rowDebt = $resDebt ? mysqli_fetch_assoc($resDebt) : null;
$totalDebt = isset($rowDebt['total_debt']) ? $rowDebt['total_debt'] : 0;
$debtRate = ($totalAmount > 0) ? round(($totalDebt / $totalAmount) * 100, 2) : 0;

// نسبة النمو مقارنة بالشهر الماضي
$prevStart = date("Y-m-01", strtotime("-1 month", strtotime($date1)));
$prevEnd   = date("Y-m-t", strtotime("-1 month", strtotime($date1)));
$sqlPrevAmount = "SELECT SUM(TotalCost) AS prev_amount FROM document WHERE date BETWEEN '$prevStart' AND '$prevEnd' AND cancel=0 AND type<>23";
$resPrev = mysqli_query($conn, $sqlPrevAmount);
$rowPrev = $resPrev ? mysqli_fetch_assoc($resPrev) : null;
$prevAmount = isset($rowPrev['prev_amount']) ? $rowPrev['prev_amount'] : 0;
$growthRate = ($prevAmount > 0) ? round((($totalAmount - $prevAmount) / $prevAmount) * 100, 2) : 0;

// الاستعلامات للبيانات
$sqlDocsPerPerson = "SELECT c.name AS client_name, COUNT(d.id) AS doc_count, SUM(d.TotalCost) AS total_sum FROM document d JOIN clients c ON d.broker_id = c.id WHERE d.date BETWEEN '$date1' AND '$date2' AND d.cancel=0 AND d.type<>23 GROUP BY c.name ORDER BY doc_count DESC";
$resultDocsPerPerson = mysqli_query($conn, $sqlDocsPerPerson);

$sqlCategories = "SELECT cat.name AS cat_name, COUNT(d.id) AS doc_count, SUM(d.TotalCost) AS total_sum FROM document d JOIN cat ON d.type = cat.id WHERE d.date BETWEEN '$date1' AND '$date2' AND d.cancel=0 AND d.type<>23 GROUP BY cat.name ORDER BY doc_count DESC";
$resultCategories = mysqli_query($conn, $sqlCategories);

$sqlDebts = "SELECT c.name AS client_name, SUM(d.TotalCost - d.commission_agent) AS total_debt FROM document d JOIN clients c ON d.broker_id = c.id WHERE d.date BETWEEN '$date1' AND '$date2' AND d.cancel=0 AND d.status=0 AND d.type<>23 GROUP BY c.name ORDER BY total_debt DESC";
$resultDebts = mysqli_query($conn, $sqlDebts);

$sqlDaily = "SELECT DATE(date) AS day, COUNT(*) AS docs FROM document WHERE date BETWEEN '$date1' AND '$date2' AND cancel=0 AND type<>23 GROUP BY day ORDER BY day";
$resultDaily = mysqli_query($conn, $sqlDaily);

$sqlWeekly = "SELECT WEEK(date) AS week, COUNT(*) AS docs FROM document WHERE date BETWEEN '$date1' AND '$date2' AND cancel=0 AND type<>23 GROUP BY week ORDER BY week";
$resultWeekly = mysqli_query($conn, $sqlWeekly);
?>
<!DOCTYPE html>
<html lang="ar">
  <?php include('head.php'); ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body dir="rtl" class="bg-light">
<?php include('navbar.php'); ?>

<div class="container py-5">
  <div class="row align-items-center mb-5">
    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
      <h2 class="fw-bold text-dark m-0"><i class="bi bi-speedometer2 text-success me-2"></i>لوحة متابعة الأعمال</h2>
      <p class="text-muted small m-0 mt-1">نظرة شاملة وتحليلات دقيقة لأداء العمليات والمبيعات</p>
    </div>
    <div class="col-md-6">
      <form method="get" class="bg-white p-3 rounded-4 shadow-sm d-flex flex-wrap align-items-center justify-content-end gap-2">
        <div class="d-flex align-items-center gap-1">
          <span class="text-muted small">من:</span>
          <input type="date" name="date1" value="<?=$date1?>" class="form-control form-control-sm border-0 bg-light rounded-3">
        </div>
        <div class="d-flex align-items-center gap-1">
          <span class="text-muted small">إلى:</span>
          <input type="date" name="date2" value="<?=$date2?>" class="form-control form-control-sm border-0 bg-light rounded-3">
        </div>
        <button type="submit" class="btn btn-success btn-sm px-3 rounded-3 fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>تحديث</button>
      </form>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-lg-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">إجمالي الوثائق</span>
            <h3 class="fw-bold text-dark m-0"><?=$totalDocs?></h3>
          </div>
          <div class="bg-primary-subtle text-primary p-3 rounded-4 fs-4"><i class="bi bi-file-earmark-text"></i></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">إجمالي المبالغ</span>
            <h3 class="fw-bold text-dark m-0"><?=number_format($totalAmount, 2)?></h3>
          </div>
          <div class="bg-success-subtle text-success p-3 rounded-4 fs-4"><i class="bi bi-currency-dollar"></i></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">نسبة النمو الشهري</span>
            <h3 class="fw-bold text-dark m-0"><?=$growthRate?>%</h3>
          </div>
          <div class="bg-warning-subtle text-warning p-3 rounded-4 fs-4"><i class="bi bi-graph-up-arrow"></i></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-danger">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small d-block mb-1">نسبة المديونية</span>
            <h3 class="fw-bold text-dark m-0"><?=$debtRate?>%</h3>
          </div>
          <div class="bg-danger-subtle text-danger p-3 rounded-4 fs-4"><i class="bi bi-exclamation-triangle"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-bar-chart-fill text-primary me-2"></i>عدد الوثائق لكل شخص</h5>
        <div style="position: relative; height: 300px;"><canvas id="docsPerPerson"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pie-chart-fill text-success me-2"></i>الفئات الأكثر طلبًا</h5>
        <div style="position: relative; height: 300px;"><canvas id="categoriesChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-wallet2 text-danger me-2"></i>المديونيات</h5>
        <div style="position: relative; height: 300px;"><canvas id="debtsChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-activity text-success me-2"></i>الأداء اليومي</h5>
        <div style="position: relative; height: 300px;"><canvas id="dailyChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-calendar-week text-warning me-2"></i>الأداء الأسبوعي</h5>
        <div style="position: relative; height: 300px;"><canvas id="weeklyChart"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-trophy-fill text-info me-2"></i>أعلى الأشخاص إنتاجًا</h5>
        <div style="position: relative; height: 300px;"><canvas id="topProducers"></canvas></div>
      </div>
    </div>
  </div>
</div>

<script>
const docsLabels = [<?php $docsData=[];$labels=[];if($resultDocsPerPerson){mysqli_data_seek($resultDocsPerPerson,0);while($r=mysqli_fetch_assoc($resultDocsPerPerson)){$labels[]="'".$r['client_name']."'";$docsData[]=$r['doc_count'];}}echo implode(",",$labels);?>];
const docsData = [<?=implode(",",$docsData)?>];

const catLabels = [<?php $catData=[];$labels=[];if($resultCategories){mysqli_data_seek($resultCategories,0);while($r=mysqli_fetch_assoc($resultCategories)){$labels[]="'".$r['cat_name']."'";$catData[]=$r['doc_count'];}}echo implode(",",$labels);?>];
const catData = [<?=implode(",",$catData)?>];

const debtLabels = [<?php $debtData=[];$labels=[];if($resultDebts){mysqli_data_seek($resultDebts,0);while($r=mysqli_fetch_assoc($resultDebts)){$labels[]="'".$r['client_name']."'";$debtData[]=$r['total_debt'];}}echo implode(",",$labels);?>];
const debtData = [<?=implode(",",$debtData)?>];

const dailyLabels = [<?php $dailyData=[];$labels=[];if($resultDaily){mysqli_data_seek($resultDaily,0);while($r=mysqli_fetch_assoc($resultDaily)){$labels[]="'".$r['day']."'";$dailyData[]=$r['docs'];}}echo implode(",",$labels);?>];
const dailyData = [<?=implode(",",$dailyData)?>];

const weeklyLabels = [<?php 
  $weeklyData=[]; $labels=[];
  if($resultWeekly){
    mysqli_data_seek($resultWeekly,0);
    while($r=mysqli_fetch_assoc($resultWeekly)){
      $labels[]="'أسبوع ".$r['week']."'";
      $weeklyData[]=$r['docs'];
    }
  }
  echo implode(",",$labels);
?>];
const weeklyData = [<?=implode(",",$weeklyData)?>];

Chart.defaults.font.family = 'Cairo, sans-serif';
Chart.defaults.maintainAspectRatio = false;

new Chart(document.getElementById('docsPerPerson'), {
  type: 'bar',
  data: { labels: docsLabels, datasets: [{ label: 'عدد الوثائق', data: docsData, backgroundColor: '#0d6efd', borderRadius: 6 }] }
});

new Chart(document.getElementById('categoriesChart'), {
  type: 'doughnut',
  data: { labels: catLabels, datasets: [{ label: 'عدد الوثائق', data: catData, backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#20c997'] }] },
  options: { plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('debtsChart'), {
  type: 'bar',
  data: { labels: debtLabels, datasets: [{ label: 'إجمالي المديونية', data: debtData, backgroundColor: '#dc3545', borderRadius: 6 }] }
});

new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: { labels: dailyLabels, datasets: [{ label: 'الأداء اليومي', data: dailyData, borderColor: '#198754', backgroundColor: 'rgba(25, 135, 84, 0.1)', fill: true, tension: 0.3 }] }
});

new Chart(document.getElementById('weeklyChart'), {
  type: 'bar',
  data: { labels: weeklyLabels, datasets: [{ label: 'الأداء الأسبوعي', data: weeklyData, backgroundColor: '#ffc107', borderRadius: 6 }] }
});

new Chart(document.getElementById('topProducers'), {
  type: 'bar',
  data: { labels: docsLabels, datasets: [{ label: 'إنتاج الوثائق', data: docsData, backgroundColor: '#6f42c1', borderRadius: 6 }] }
});
</script>

</body>
</html>