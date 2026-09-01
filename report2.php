




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
<h2 class="text-center" style="font-family:arial;font-size:24px;"><b>تقرير وثائق الطرف الثالث</b></h2>
<hr>
<h2 class="text-center" style="font-family:arial;font-size:24px;">الفترة من </h2>

 
<?php
$sql = "SELECT * FROM sam order by id";
echo'
<table class="table table-responsive table-bordered text-center col-12 small" style="font-family:arial;font-size:14px;">
<thead>
 <tr>
<th   class="small">#</th>
<th  class="small w-10" >رقم الوثيقة</th>
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

<th   class="small">عمولة الأفراد</th>
<th   class="small">عمولة المكتب</th>
<th   class="small">الفائدة</th>




</thead>
</tr>
';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
echo"<tr><td class='p-0 m-0'>".$row['id']."</td>" ;
echo"<td class='p-0 m-0'>".$row['document']."</td>" ;
echo"<td class='p-0 m-0'>".$row['name']."</td>" ;
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
echo"<td class='p-0 m-0'><b><u>".$row['commission_office']."</u></b></td>" ;
echo"<td class='p-0 m-0'><b><u>".$row['commission_agent']."</u></b></td>" ;
echo"<td class='p-0 m-0'><b><u>".(($row['commission_agent'])-($row['commission_office']))."</u></b></td>" ;






echo'</tr>';
   }
}

$conn->close();

require('db_conn.php');

$sql = "SELECT  sum(StampCost)  as tsc,sum(premium)as tp,sum(SuperVisionCost)  as tsvc  ,sum(issue) tissue,sum(passengers) as tpassengers,sum(SupportTax) as tst,sum(TotalCost) as ttc ,floor(sum(commission_office)) as tco  ,floor(sum(commission_agent)) as tca FROM sam order by id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
echo"<tr><td class='p-0 m-0'></td>" ;
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
echo'<td>'.$row['tco'].'</td>';
echo'<td>'.$row['tca'].'</td>';
echo'<td>'.(($row['tca'])-($row['tco'])).'</td></tr>';


echo'</table>';



   }}

?>
<button onclick="window.print()">Print this page</button>

            </section>
            



</body>

</html>

