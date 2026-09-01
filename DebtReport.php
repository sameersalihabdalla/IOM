<!DOCTYPE html>
<html lang="ar">

<head>
<?php
    require('db_conn.php');
?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=1220">

  <title>insurance PLUS</title>
  <link href="./css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
</head>

<body dir="rtl" class="m-0 p-2">
<h2 class="text-center" style="font-family:arial;font-size:24px;"><b>تقرير مديونية وثائق الطرف الثالث</b></h2>
<hr>
<h2 class="text-center" style="font-family:arial;font-size:24px;">الفترة من </h2>

<?php
$s = 0;

// تم إزالة عمود status من الـ SELECT لأنه يسبب خطأ مع GROUP BY لعدم استخدامه في العرض
$sql = "SELECT `client_name`, SUM(`TotalCost`) AS sa 
        FROM `sam` 
        WHERE `status`='0' 
        GROUP BY `client_name`";

$result = mysqli_query($conn, $sql);

// إضافة شرط للتحقق من نجاح الاستعلام قبل تنفيذ mysqli_num_rows
if (!$result) {
    // سيتم طباعة الخطأ الفعلي من قاعدة البيانات لمعرفة المشكلة بدقة
    die("<div class='alert alert-danger text-center'>خطأ في الاستعلام: " . mysqli_error($conn) . "</div>");
}

echo'
<table class="table table-responsive table-bordered text-center col-12 small" style="font-family:arial;font-size:14px;">
 <tr>
    <th class="small">العميل</th>
    <th class="small w-10">جملة المبلغ</th>
 </tr>
';

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td class='p-0 m-0'>".$row['client_name']."</td>" ;
        echo "<td class='p-0 m-0'>".$row['sa']."</td></tr>" ;
        $s += $row['sa'];
    }
} else {
    echo "<tr><td colspan='2' class='text-center'>لا توجد بيانات للعرض</td></tr>";
}

echo '<tr class="table-danger"><td class="fw-bold">الجملة</td>';
echo '<td class="fw-bold">'.$s.'</td></tr>';
echo '</table>';
?>

<div class="text-center mt-3">
    <button class="btn btn-primary" onclick="window.print()">طباعة التقرير</button>
</div>

</body>
</html>