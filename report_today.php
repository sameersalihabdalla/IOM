<?php
require('db_conn.php');

// الحصول على التواريخ من الطلب، أو تعيين التاريخ الحالي كقيمة افتراضية
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d');
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>insurance PLUS - تقرير وثائق الطرف الثالث</title>
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/main.css">
</head>
<body dir="rtl" class="m-0 p-2">

<h2 class="text-center"><b>تقرير وثائق الطرف الثالث</b></h2>

<form method="GET" class="row g-3 align-items-center justify-content-center my-3 print-hide">
    <div class="col-auto">
        <label>من تاريخ:</label>
        <input type="date" name="date_from" class="form-control" value="<?php echo $date_from; ?>">
    </div>
    <div class="col-auto">
        <label>إلى تاريخ:</label>
        <input type="date" name="date_to" class="form-control" value="<?php echo $date_to; ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">عرض التقرير</button>
    </div>
</form>

<h3 class="text-center">الفترة من <?php echo $date_from; ?> إلى <?php echo $date_to; ?></h3>
<hr>

<?php
// تعديل الاستعلام ليشمل النطاق الزمني
$sql = "SELECT * FROM sam WHERE date BETWEEN '$date_from' AND '$date_to' ORDER BY date";
$result = mysqli_query($conn, $sql);

echo '<table class="table table-responsive table-bordered text-center col-12 small">';
echo '<thead><tr><th>#</th><th>رقم الوثيقة</th><th>اسم المؤمن له</th><th>التاريخ</th><th>نوع التأمين</th><th>القسط</th><th>الدمغة</th><th>الإشراف</th><th>الإصدار</th><th>الركاب</th><th>أ.عمل</th><th>ض د</th><th>الإجمالي</th><th>عمولة الأفراد</th><th>عمولة المكتب</th><th>الفائدة</th></tr></thead>';

if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
       echo "<tr><td>".$row['id']."</td><td>".$row['document']."</td><td>".$row['name']."</td><td>".$row['date']."</td>
             <td>".$row['cat_name']."</td><td>".$row['premium']."</td><td>".$row['StampCost']."</td>
             <td>".$row['SuperVisionCost']."</td><td>".$row['issue']."</td><td>".$row['passengers']."</td>
             <td>0</td><td>".$row['SupportTax']."</td><td><b>".$row['TotalCost']."</b></td>
             <td>".$row['commission_office']."</td><td>".$row['commission_agent']."</td>
             <td>".($row['commission_agent'] - $row['commission_office'])."</td></tr>";
   }
}

// استعلام المجموع
$sql_sum = "SELECT sum(StampCost) as tsc, sum(premium) as tp, sum(SuperVisionCost) as tsvc, sum(issue) as tissue, 
            sum(passengers) as tpassengers, sum(SupportTax) as tst, sum(TotalCost) as ttc, 
            floor(sum(commission_office)) as tco, floor(sum(commission_agent)) as tca 
            FROM sam WHERE date BETWEEN '$date_from' AND '$date_to'";

$res_sum = mysqli_query($conn, $sql_sum);
$row_sum = mysqli_fetch_assoc($res_sum);

echo "<tr><td colspan='5'><b>المجموع</b></td>
      <td>".$row_sum['tp']."</td><td>".$row_sum['tsc']."</td><td>".$row_sum['tsvc']."</td>
      <td>".$row_sum['tissue']."</td><td>".$row_sum['tpassengers']."</td><td>0</td>
      <td>".$row_sum['tst']."</td><td><b>".$row_sum['ttc']."</b></td>
      <td>".$row_sum['tco']."</td><td>".$row_sum['tca']."</td>
      <td>".($row_sum['tca'] - $row_sum['tco'])."</td></tr>";
echo '</table>';

$conn->close();
?>

<button class="btn btn-secondary" onclick="window.print()">Print this page</button>

</body>
</html>