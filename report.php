




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
  <link href="./css/bootstrap.min.css" rel="stylesheet" >
  <link rel="stylesheet" href="./css/main.css">
<style>
body {
    font-family: Arial, sans-serif !important;
    margin: 0;
    padding: 0;
}

/* زر الطباعة يظهر فقط على الشاشة */
.no-print {
    display: block;
}
@media print {
    .no-print {
        display: none !important;
    }
    @page {
        size: A4 landscape;
        margin: 10mm;
    }
    /* ترقيم الصفحات أسفل كل صفحة */
    body::after {
        content: "صفحة " counter(page) " من " counter(pages);
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 8px;
        font-family: Arial, sans-serif;
    }
}

/* الجدول بدون تباعد */
table {
    border-collapse: collapse !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    font-family: Arial, sans-serif !important;
}

th, td {
    padding: 1px !important;
    margin: 0 !important;
    font-family: Arial, sans-serif !important;
    font-size: 8px !important;
}

/* رقم الوثيقة بخط صغير جداً */
td.document-number {
    font-size: 9px !important;
}
</style>


</head>

<body dir="rtl" class="m-0 p-2 a4">
<h2 class="text-center" style="font-family:arial;font-size:24px;"><b>تقرير وثائق الطرف الثالث</b></h2>
<hr>
<h2 class="text-center" style="font-family:arial;font-size:24px;">الفترة من </h2>

 
<?php
$sql = "SELECT * FROM sam order by id";
echo'
<table class="table table-responsive table-bordered text-center col-12 small" style="font-family:arial;font-size:14px;">
<thead>
 <tr>
 <th  class="small w-10" >رقم الوثيقة</th>
<th   class="small">#</th>
<th class="small w-10">اسم المؤمن له</th>
<th class="small w-30" >التاريخ</th>
<th   class="small">نوع التأمين</th>
<th   class="small">القسط</th>
<th   class="small">الدمغة</th>
<th   class="small">الإشراف</th>
<th   class="small">الإصدار</th>
<th  class="small">الركاب</th>
<th   class="small">أ.عمل</th>
<th  class="small">َض د</th>
<th   class="small">الإجمالي</th>
<th   class="small">الحالة</th>
<th   class="small">الوسيط</th>




</thead>
</tr>
';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
echo"<td class='p-0 m-0'>".$row['id']."</td>" ;
echo"<td class='p-0 m-0'  style='font-size: 9px !important; max-width: 60px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>  ".$row['document']."</td>" ;
echo"<td class='p-0 m-0'  style='font-size: 9px !important; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>".$row['name']."</td>" ;
echo"<td class='p-0 m-0'><span>".$row['date']."<span></td>" ;
echo"<td class='p-0 m-0'>".$row['cat_name']."</td>" ;
echo"<td class='p-0 m-0'>".$row['premium']."</td>" ;
echo"<td class='p-0 m-0'>".$row['StampCost']."</td>" ;
echo"<td class='p-0 m-0'>".$row['SuperVisionCost']."</td>" ;
echo"<td class='p-0 m-0'>".$row['issue']."</td>" ;
echo"<td class='p-0 m-0'>".$row['passengers']."</td>" ;
echo"<td class='p-0 m-0'>0</td>" ;
echo"<td class='p-0 m-0'>".$row['SupportTax']."</td>" ;
echo"<td class='p-0 m-0'><b><u>".$row['TotalCost']."</u></b></td>" ;
echo"<td class='p-0 m-0'>";

if($row['status']==0)
{
echo"<b class='text-danger'>غير مسددة</b>";
}
else
{
echo"مسددة";
}

echo"</td>" ;
echo"<td class='p-0 m-0'>".$row['client_name']."</td>" ;






echo'<tr>';
   }
}

$conn->close();

require('db_conn.php');

$sql = "SELECT  sum(StampCost)  as tsc,sum(premium)as tp,sum(SuperVisionCost)  as tsvc  ,sum(issue) tissue,sum(passengers) as tpassengers,sum(SupportTax) as tst,sum(TotalCost) as ttc  FROM sam order by id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
echo"<td class='p-0 m-0'></td>" ;
echo"<td class='p-0 m-0'></td>" ;
echo"<td class='p-0 m-0'></td>" ;
echo"<td class='p-0 m-0'></td>" ;
echo"<td class='p-0 m-0'></td>" ;
echo"<td class='p-0 m-0'>".$row['tp']."</td>" ;
echo"<td class='p-0 m-0'>".$row['tsc']."</td>" ;
echo"<td class='p-0 m-0'>".$row['tsvc']."</td>" ;
echo"<td class='p-0 m-0'>".$row['tissue']."</td>" ;
echo"<td class='p-0 m-0'>".$row['tpassengers']."</td>" ;
echo"<td class='p-0 m-0'>0</td>" ;
echo"<td class='p-0 m-0'>".$row['tst']."</td>" ;
echo"<td class='p-0 m-0'><b><u>".$row['ttc']."</u></b></td>" ;
echo'<td></td>';


echo'</table>';



   }}

?>
<button onclick="window.print()">Print this page</button>

            </section>
            



</body>

</html>

