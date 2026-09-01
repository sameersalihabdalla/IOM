<div id="content" class="p-5 m-5">
  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>التاريخ</th>
        <th>الاسم</th>
        <th>نوع العملية</th>
        <th>المبلغ</th>
      </tr>
    </thead>
    <tbody>
<?php
require('db_conn.php');
$q = $_REQUEST["q"];
$d1 = $_REQUEST["date1"];
$d2 = $_REQUEST["date2"];
$phone = $_REQUEST["phone"];

$total_docs = 0;     // مجموع الوثائق (تأمين)
$total_printing = 0; // مجموع الطباعة
$total_all = 0;      // المجموع الكلي

// الترجمة إلى الإنجليزية
$text = 'Your invoice from : '.$d1.' *-* '.$d2.urlencode("\n");
$text2 = 'Your invoice from : '.$d1.' *-* '.$d2."<br>";

if ($q !== "") {
  $sql = "SELECT * FROM sam 
          WHERE date >= '".$d1."' AND date <= '".$d2."' 
          AND status='0' AND broker_id='".$q."' 
          ORDER BY id";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $amount = $row['TotalCost'] - $row['commission_agent'];

      // جمع حسب النوع
      if ($row['cat_name'] == 'تأمين') {
        $total_docs += (ceil($amount / 1000) * 1000);
      } elseif ($row['cat_name'] == 'طباعة') {
        $total_printing += (ceil($amount / 1000) * 1000);
      }

      $total_all += (ceil($amount / 1000) * 1000);

      echo "<tr>
              <td>".$row['date']."</td>
              <td>".$row['name']."</td>
              <td>".$row['cat_name']."</td>
              <td><u>".(ceil($amount / 1000) * 1000)."</u></td>
            </tr>";

      $text .= $row['name'].urlencode("\n");
      $text2 .= $row['name']." <br>";
    }
  }
}
echo "</tbody></table>";

$conn->close();

// تقريب المجموع لأقرب ألف
$total_all = ceil($total_all / 1000) * 1000;

// تجهيز النصوص باللغة الإنجليزية بالكامل وتغيير العملة إلى SDG
$text .= "_*Other Services:*_ *".number_format(($total_all)-($total_printing), 2, '.', ',').'* SDG'.urlencode("\n");
$text .= "_*Printing Services:*_ *".$total_printing."* SDG".urlencode("\n");
$text .= "_*Grand Total:*_ *".$total_all."* SDG".urlencode("\n");

$text2 .= "<br><b> Other Services:</b> ".number_format(($total_all)-($total_printing), 0, '.', ',')." SDG<br>";
$text2 .= "<b> Printing Services:</b> ".number_format($total_printing, 0, '.', ',')." SDG<br>";
$text2 .= "<b>Grand Total:</b> ".number_format($total_all, 0, '.', ',')." SDG<br>";

echo '<code dir="ltr">'.$text2.'</code><br>';
echo '<a class="btn btn-success" href="whatsapp://send?phone='.$phone.'&text='.$text.'">Send</a>';
?>
</div>