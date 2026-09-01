<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="bi bi-shield-lock"></i> IOM 
    </a>
    <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" aria-controls="mynavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-2 mt-lg-0" id="mynavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php" title="الرئيسية"><i class="bi bi-house-door fs-5"></i> <span class="d-lg-none ms-2">الرئيسية</span></a></li>
        <li class="nav-item"><a class="nav-link" href="production.php" title="الإنتاج"><i class="bi bi-gear fs-5"></i> <span class="d-lg-none ms-2">الإنتاج</span></a></li>
        <li class="nav-item"><a class="nav-link" href="cat.php" title="التصنيفات"><i class="bi bi-list-ul fs-5"></i> <span class="d-lg-none ms-2">التصنيفات</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php" title="العملاء"><i class="bi bi-people fs-5"></i> <span class="d-lg-none ms-2">العملاء</span></a></li>
        <li class="nav-item"><a class="nav-link" href="report.php" title="التقارير"><i class="bi bi-file-earmark-text fs-5"></i> <span class="d-lg-none ms-2">التقارير</span></a></li>
        <li class="nav-item"><a class="nav-link" href="report_today.php" title="تقرير اليوم"><i class="bi bi-calendar-day fs-5"></i> <span class="d-lg-none ms-2">تقرير اليوم</span></a></li>
        <li class="nav-item"><a class="nav-link" href="DebtReport.php" title="المديونيات"><i class="bi bi-cash-stack fs-5"></i> <span class="d-lg-none ms-2">المديونيات</span></a></li>
        <li class="nav-item"><a class="nav-link" href="dashboard.php" title="الإحصائيات"><i class="bi bi-graph-up fs-5"></i> <span class="d-lg-none ms-2">الإحصائيات</span></a></li>
        
        <li class="nav-item"><a href="./logout.php" class="nav-link logout-link text-danger" title="خروج"><i class="bi bi-box-arrow-right fs-5"></i> <span class="d-lg-none ms-2">تسجيل خروج</span></a></li>
      </ul>
      <form class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-3 mt-lg-0 py-2 py-lg-0 border-top border-light border-opacity-10 border-top-lg-0" id="form" action="CustomReport.php" method="get">
        <div class="d-flex align-items-center gap-1">
          <label class="text-white text-nowrap mb-0 small">من</label>
          <input class="form-control form-control-sm bg-white" name="datee" type="date" value="<?=date('Y-m-d')?>">
        </div>
        <div class="d-flex align-items-center gap-1">
          <label class="text-white text-nowrap mb-0 small">إلى</label>
          <input class="form-control form-control-sm bg-white" type="date" name="dateee" value="<?=date('Y-m-d')?>">
        </div>
        <input class="btn btn-light btn-sm text-nowrap mt-1 mt-lg-0 fw-semibold" type="submit" value="كشف حساب ">
      </form>
    </div>
  </div>
</nav>