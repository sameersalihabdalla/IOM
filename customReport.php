<!DOCTYPE html>
<html lang="ar">
<head>
<?php
$date1 = isset($_GET["datee"]) ? $_GET["datee"] : '';
$date2 = isset($_GET["dateee"]) ? $_GET["dateee"] : '';
require('db_conn.php');
?>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=3508, height=2480">
<title>Insurance PLUS</title>
<link href="./css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link rel="stylesheet" href="./css/main.css">
<!-- SweetAlert -->
<script src="./js/sweetalert.js"></script>

<style>
@media print {
  #printBtn { display: none; }
  body { margin: 0; }
  @page {
    size: A4 landscape;
    margin: 10mm;
  }
}




table {
    border-collapse: collapse !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}
th, td {
    padding: 2px !important;
    margin: 0 !important;
}



@media print {
    .no-print {
        display: none !important;
    }
    body {
        margin: 0;
    }
    @page {
        size: A4 landscape;
        margin: 10mm;
    }
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    /* ترقيم الصفحات في أسفل الصفحة */
    @page {
        @bottom-center {
            content: "صفحة " counter(page) " من " counter(pages);
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
    }
}


</style>
</head>

<body dir="rtl" class="m-0 p-0">

<!-- زر الطباعة -->
<div class="text-center">
  <button id="printBtn" class="btn btn-primary m-2" onclick="window.print()">🖨️ طباعة</button>
</div>

<h4 class="text-center" style="font-family:arial;font-size:24px;"><b>كشف وثائق الطرف الثالث</b></h4>
<hr>
<h4 class="text-center" style="font-family:arial;font-size:24px;">
الفترة من <span class="small"> <?=$date1?> حتى <?=$date2?></span>
</h4>

<?php
if (!empty($date1) && !empty($date2)) {
    $counter = 1;
    $sql = "
    SELECT 
        d.id,
        d.document,
        d.name AS insured_name,
        d.date,
        c.name AS client_name,
        c.phone,
        cat.name AS cat_name,
        d.premium,
        d.StampCost,
        d.SuperVisionCost,
        d.issue,
        d.passengers,
        d.SupportTax,
        d.TotalCost,
        d.commission_office,
        d.commission_agent,
        d.cancel
    FROM document d
    JOIN clients c ON d.broker_id = c.id
    JOIN cat ON d.type = cat.id
    WHERE d.date >= '".$date1."' AND d.date <= '".$date2."' and type<>23
    ORDER BY d.date
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("خطأ في الاستعلام: " . mysqli_error($conn));
    }

    // متغيرات لحفظ المجاميع
    $sumPremium = $sumStamp = $sumSuperVision = $sumIssue = $sumPassengers = $sumSupportTax = $sumTotal = $sumCommission = 0;

    echo '<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered text-center small" style="font-family:arial;font-size:12px;">
    <thead class="table-dark">
    <tr>
    <th>#</th>
    <th>رقم الوثيقة</th>
    <th>اسم المؤمن له</th>
    <th>التاريخ</th>
    <th>نوع التأمين</th>
    
    <th>الإجمالي</th>
    <th>خصم</th>
    </tr>
    </thead>
    <tbody>';

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $rowClass = ($row['cancel'] == 1) ? "class='table-danger fw-bold'" : "";
            echo "<tr $rowClass>
            <td>".$counter."</td>
            <td>".$row['document']."</td>
            <td>".$row['insured_name']."</td>
            <td>".$row['date']."</td>
            <td>".($row['cat_name']=="تمديد وثيقة" ? "<span class='text-danger fw-bold'>".$row['cat_name']."</span>" : $row['cat_name'])."</td>
              <td><b><u>".number_format($row['TotalCost'], 2, '.', ',')."</u></b></td>
            <td><b><u>".number_format($row['commission_office'], 2, '.', ',')."</u></b></td>
            </tr>";

            // جمع المجاميع
            $sumPremium += $row['premium'];
            $sumStamp += $row['StampCost'];
            $sumSuperVision += $row['SuperVisionCost'];
            $sumIssue += $row['issue'];
            $sumPassengers += $row['passengers'];
            $sumSupportTax += $row['SupportTax'];
            $sumTotal += $row['TotalCost'];
            $sumCommission += $row['commission_office'];

            $counter++;
        }

        // صف المجاميع
        echo "<tr class='table-info fw-bold'>
        <td colspan='4'>الإجمالي الكلي</td>
        <td>المطلوب حاليا هو : ".number_format(($sumTotal-$sumCommission), 2, '.', ',')."</td>
       <td>".number_format($sumTotal, 2, '.', ',')."</td>
        <td>".number_format($sumCommission, 2, '.', ',')."</td>
        </tr>";
    } else {
        echo "<tr><td colspan='14' class='text-danger'>لا توجد بيانات للفترة المحددة</td></tr>";
    }

  
    // بعد إغلاق الجدول الأول
echo '</tbody></table></div>';

// ✅ استعلام جديد لتجميع الوثائق حسب الفئة
$sqlSummary = "
    SELECT 
        cat.name AS cat_name,
        COUNT(d.id) AS doc_count,
        SUM(d.TotalCost) AS total_sum
    FROM document d
    JOIN cat ON d.type = cat.id
    WHERE d.date >= '".$date1."' AND d.date <= '".$date2."' and type<>23
    GROUP BY cat.name
    ORDER BY cat.name
";

$resultSummary = mysqli_query($conn, $sqlSummary);

if ($resultSummary && mysqli_num_rows($resultSummary) > 0) {
    echo '<h4 class="text-center">تفاصيل اضافية</h4>
    <div class="table-responsive mt-4">
    <table class="table table-bordered table-striped text-center small" style="font-family:arial;font-size:12px;">
    <thead class="table-secondary">
    <tr>
        <th>الفئة</th>
        <th>عدد الوثائق</th>
        <th>مجموع مبالغ الفئة</th>
    </tr>
    </thead>
    <tbody>';

    while($row = mysqli_fetch_assoc($resultSummary)) {
        echo "<tr>
            <td>".$row['cat_name']."</td>
            <td>".$row['doc_count']."</td>
            <td>".number_format($row['total_sum'], 2, '.', ',')."</td>
        </tr>";
    }

    echo '</tbody></table></div>';
}










// ✅ استعلام خاص بالشهادات الملغية مع الحوافز
$sqlCanceled = "
    SELECT 
        d.document,
        d.name AS insured_name,
        d.date,
        cat.name AS cat_name,
        d.TotalCost,
        d.commission_office
    FROM document d
    JOIN cat ON d.type = cat.id
    WHERE d.date >= '".$date1."' AND d.date <= '".$date2."' 
      AND d.cancel = 1
    ORDER BY d.date
";

$resultCanceled = mysqli_query($conn, $sqlCanceled);

if ($resultCanceled && mysqli_num_rows($resultCanceled) > 0) {
    echo '<h4 class="text-center mt-4">الوثائق الملغية</h4>
    <div class="table-responsive">
    <table class="table table-bordered table-striped text-center small" style="font-family:arial;font-size:12px;">
    <thead class="table-danger">
    <tr>
        <th>#</th>
        <th>رقم الوثيقة</th>
        <th>اسم المؤمن له</th>
        <th>التاريخ</th>
        <th>نوع التأمين</th>
        <th>التكلفة</th>
        <th>خصم</th>
    </tr>
    </thead>
    <tbody>';

    $counter = 1;
    $sumCanceledCost = 0;
    $sumCanceledCommission = 0;

    while($row = mysqli_fetch_assoc($resultCanceled)) {
        echo "<tr class='table-danger fw-bold'>
            <td>".$counter."</td>
            <td>".$row['document']."</td>
            <td>".$row['insured_name']."</td>
            <td>".$row['date']."</td>
            <td>".$row['cat_name']."</td>
            <td>".number_format($row['TotalCost'], 2, '.', ',')."</td>
            <td>".number_format($row['commission_office'], 2, '.', ',')."</td>
        </tr>";
        $sumCanceledCost += $row['TotalCost'];
        $sumCanceledCommission += $row['commission_office'];
        $counter++;
    }

    echo "<tr class='table-warning fw-bold'>
        <td colspan='5'>إجمالي الوثائق الملغية</td>
        <td>".number_format($sumCanceledCost, 2, '.', ',')."</td>
        <td>".number_format($sumCanceledCommission, 2, '.', ',')."</td>
    </tr>";

    echo '</tbody></table></div>';

    // ✅ حساب المطلوب بعد خصم الملغيات
    $netRequired = ($sumTotal - $sumCommission) - ($sumCanceledCost - $sumCanceledCommission);

    echo "<h4 class='text-center mt-4 text-success fw-bold'>
        المطلوب بعد خصم الوثائق الملغية: ".number_format($netRequired, 2, '.', ',')."
    </h4>";
}






}




// ✅ استعلام خاص بالوثائق من النوع 23
$sqlType23 = "
    SELECT 
        d.document,
        d.name AS insured_name,
        d.date,
        cat.name AS cat_name,
        d.TotalCost,
        d.commission_office
    FROM document d
    JOIN cat ON d.type = cat.id
    WHERE d.date >= '".$date1."' AND d.date <= '".$date2."' 
      AND d.type = 23
    ORDER BY d.date
";

$resultType23 = mysqli_query($conn, $sqlType23);

if ($resultType23 && mysqli_num_rows($resultType23) > 0) {
    echo '<h4 class="text-center mt-4">وثائق الطباعة (type=23)</h4>
    <div class="table-responsive">
    <table class="table table-bordered table-striped text-center small" style="font-family:arial;font-size:12px;">
    <thead class="table-primary">
    <tr>
        <th>#</th>
        <th>رقم الوثيقة</th>
        <th>اسم المؤمن له</th>
        <th>التاريخ</th>
        <th>نوع التأمين</th>
        <th>التكلفة</th>
        <th>خصم</th>
    </tr>
    </thead>
    <tbody>';

    $counter = 1;
    $sumType23Cost = 0;
    $sumType23Commission = 0;

    while($row = mysqli_fetch_assoc($resultType23)) {
                $sumType23Cost += $row['TotalCost'];
        $sumType23Commission += $row['commission_office'];
        $counter++;
    }

    echo "<tr class='table-warning fw-bold'>
        <td colspan='5'>إجمالي وثائق الطباعة</td>
        <td>".number_format($sumType23Cost, 2, '.', ',')."</td>
        <td>".number_format($sumType23Commission, 2, '.', ',')."</td>
    </tr>";

    echo '</tbody></table></div>';
}














?>




<?php
// ✅ استعلام خاص بالمديونيات (الوثائق غير المسددة)
$sqlDebts = "
    SELECT 
        c.name AS client_name,
        SUM(d.TotalCost - d.commission_agent) AS total_debt
    FROM document d
    JOIN clients c ON d.broker_id = c.id
    WHERE d.date >= '".$date1."' AND d.date <= '".$date2."' 
      AND d.cancel = 0
      AND d.status = 0   -- هنا الشرط الأساسي: الوثائق غير المسددة
    GROUP BY c.name
    ORDER BY total_debt DESC
";

$resultDebts = mysqli_query($conn, $sqlDebts);

if ($resultDebts && mysqli_num_rows($resultDebts) > 0) {
    echo '<h4 class="text-center mt-4">جدول المديونيات (الوثائق غير المسددة)</h4>
    <div class="table-responsive">
    <table class="table table-bordered table-striped text-center small" style="font-family:arial;font-size:12px;">
    <thead class="table-dark">
    <tr>
        <th>اسم الشخص</th>
        <th>جملة المديونية</th>
    </tr>
    </thead>
    <tbody>';

    $sumDebts = 0;

    while($row = mysqli_fetch_assoc($resultDebts)) {
        echo "<tr>
            <td>".$row['client_name']."</td>
            <td>".number_format($row['total_debt'], 2, '.', ',')."</td>
        </tr>";
        $sumDebts += $row['total_debt'];
    }

    // ✅ صف الإجمالي الكلي
    echo "<tr class='table-info fw-bold'>
        <td>إجمالي المديونيات</td>
        <td>".number_format($sumDebts, 2, '.', ',')."</td>
    </tr>";

    echo '</tbody></table></div>';
}
?>









</body>
</html>
