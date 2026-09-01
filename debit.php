<!DOCTYPE html>
<html lang="ar">
<head>
<?php
$date1 = $_GET["datee"];
$date2 = $_GET["dateee"];
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
<h2 class="text-center" style="font-family:arial;font-size:24px;"><b>كشف  وثائق الطرف الثالث</b></h2>
<hr>
<h2 class="text-center" style="font-family:arial;font-size:24px;">الفترة من <span class="small"> <?=$date1?> حتى <?=$date2?></span></h2>
<?php
if ($date1 !== "") {

$counter=1;
$sql = "SELECT * FROM sam where   date>='".$date1."' AND date<='".$date2."' order by date ";
echo'
<table class="table table table-striped table-responsive table-bordered text-center col-12 " style="font-family:arial;font-size:14px;">
<thead>
<tr>
<th   class="small">#</th>
<th   class="small w-10" >العميل </th>
<th   class="small w-10">اسم المؤمن له</th>
<th   class="small" style="width:100px;" >التاريخ</th>
<th   class="small">نوع التأمين</th>
<th   class="small" hidden style="width:60px;">القسط</th>
<th   class="small" hidden style="width:60px;">الدمغة</th>
<th   class="small" hidden>الإشراف</th>
<th   class="small" hidden>الإصدار</th>
<th   class="small" hidden>الركاب</th>
<th   class="small" hidden>أ.عمل</th>
<th   class="small " hidden>َض د</th>
<th   class="small" >الإجمالي</th>
<th   class="small" hidden>عمولة الأفراد</th>
<th    class="small" hidden style="width:50px;">العمولة</th>
<th    class="small" hidden>الفائدة</th>
<th   class="small">حالة السداد</th>
</thead></tr>';
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
echo"<tr><td class='p-1 m-0'>".$counter."</td>" ;
//echo"<td class='p-0 m-1'>".$row['client_name']."</td>" ;
echo"<td>-</td>";
echo"<td class='p-0 m-1'>".$row['name']."</td>" ;
echo"<td class='p-0 m-1'><span>".$row['date']."<span></td>" ;
echo"<td class='p-0 m-1'>".$row['cat_name']."</td>" ;
echo"<td class='p-0 m-1' hidden >".$row['premium']."</td>" ;
echo"<td class='p-0 m-1' hidden>".$row['StampCost']."</td>" ;
echo"<td class='p-0 m-1' hidden>".$row['SuperVisionCost']."</td>" ;
echo"<td class='p-0 m-1' hidden>".$row['issue']."</td>" ;
echo"<td class='p-0 m-1' hidden>".$row['passengers']."</td>" ;
echo"<td class='p-0 m-1' hidden>0</td>" ;
echo"<td class='p-0 m-1' hidden>".$row['SupportTax']."</td>" ;
echo"<td class='p-0 m-1'>".number_format($row['TotalCost'])."</td>" ;
echo"<td  class='p-0 m-1' hidden><b><u>".$row['commission_office']."</u></b></td>" ;
echo"<td  class='p-0 m-1' hidden><b><u>".$row['commission_agent']."</u></b></td>" ;
echo"<td  class='p-0 m-1' hidden><b><u>".($row['commission_agent']-$row['commission_office'])."</u></b></td>" ;

$counter=$counter+1;
if ($row['status']==0)
{
echo"<td  class='p-0 m-1  table-danger lg ' ><b><u>غير مسددة</u></b></td>" ;


}
else
{
echo"<td  class='p-0 m-1  table-success '><b><u> مسددة</u></b></td>" ;

}






echo'</tr>';
}
}



$conn->close();

require('db_conn.php');

$sql = "SELECT  sum(StampCost)  as tsc,sum(premium)as tp,sum(SuperVisionCost)  as tsvc  ,sum(issue) tissue,sum(passengers) as tpassengers,sum(SupportTax) as tst,sum(TotalCost) as ttc ,floor(sum(commission_office)) as tco  ,floor(sum(commission_agent)) as tca FROM sam where  date>='".$date1."' AND date<='".$date2."' order by date";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
echo"<tr  style='font-size:16px;' class='p-0 m-0'><td class=''></td>" ;
echo"<td class=' table-info'><u><b>الجملة</b></u></td>" ;
echo"<td class=' table-info'>-</td>" ;
echo"<td class='table-info'>-</td>" ;
echo"<td class=' table-info'>-</td>" ;
echo"<td hidden class=''>".$row['tp']."</td>" ;
echo"<td hidden class=''>".$row['tsc']."</td>" ;
echo"<td hidden class=''>".$row['tsvc']."</td>" ;
echo"<td hidden class=''>".$row['tissue']."</td>" ;
echo"<td hidden class=''>".$row['tpassengers']."</td>" ;
echo"<td hidden class=''>0</td>" ;
echo"<td hidden class=''>".$row['tst']."</td>" ;
echo"<td class='table-info'><u><b>".number_format($row['ttc'])."   ج.س</b></u></td>" ;
echo'<td  hidden>'.$row['tco'].'</td>';
echo'<td  hidden class="">'.$row['tca'].'</td>';
echo'<td  hidden class="">'.($row['tca']-$row['tco']).'</td>';

echo'<td >-</td></tr>';


echo'</table><h4 class="p-2 m-2 text-center">';
echo '<b>المطلوب: </b><u>';
$total=$row['ttc'];
$t_com=$row['tco'];
echo "<b>".number_format(($total)-($t_com))."</b>";
echo'</u>      جنيه سوداني'.'  | ';
echo'جملة التقرير : <u><b>'.number_format($row['ttc']).'</b></u> </h4> <br> ';
//echo'جملة العمولة : <u>'.$row['tco'] .'</u>    </h4> <br>    ';


}


}}

echo"<div class='col-lg-12 text-center' style='align:center;'><h4>تفاصيل الأنواع</h4><div class='text-center'>";
echo'<table class="table small table-bordered  p-0 m-0" >';
echo'<th class="p-0 m-0">النوع</th>';
echo'<th class="p-0 m-0">العدد</th><tr>';


$sql="SELECT
`type`
, `cat_name`
, sum(`Employertax`) as Employertax
, sum(`SuperVisionCost`) as SuperVisionCost
, sum(`SupportTax`) as SupportTax
, sum(`StampCost`) as StampCost
, sum(`TotalCost`) as TotalCost
, sum(`premium`) as premium



, `id`
,COUNT(`id`) AS COUN

FROM
`extra_insurance`.`sam` where  date>='".$date1."' AND date<='".$date2."' 
GROUP BY `type`";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
echo'<td class="p-0 m-1">'. $row['cat_name'].' </td>';
echo'<td class="p-0 m-1">'.$row['COUN'].'</td>';


echo'</tr>';


}}
echo'</table';
echo'</div></div>';

?>

</section>




</body>

</html>

