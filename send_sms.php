<?php
$q = $_REQUEST["id"];
?>

<!DOCTYPE html>
<html lang="ar">
  <?php include('head.php'); ?>


<body dir="rtl">
<?php include('navbar.php'); ?>
  <div class="container-fluid p-3 p-md-4">
   <h1 class="mt-3 fs-3 fs-md-2">كشف الحساب و الرسائل القصيرة</h1>
   <hr>
   <div class="row g-3">

    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
      <label class="form-label">تاريخ البداية</label>
      <input type="date" id="date1" value="<?=date('Y-m-d')?>" class="form-control" onchange="get_data()">
    </div>
    <div class="col-12 col-sm-6 col-md-3 col-lg-2">
      <label class="form-label">تاريخ النهاية</label>
      <input type="date" id="date2" value="<?=date('Y-m-d')?>" class="form-control" onchange="get_data()">
    </div>

    <?php
    require('db_conn.php');
    $sql = "SELECT * FROM clients WHERE id='".$q."' ";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        echo '
        <div class="col-12 col-sm-6 col-md-2 col-lg-2">
          <label class="form-label">معرف العميل</label>
          <input type="text" disabled id="id" value="'.$row['id'].'" class="form-control">
        </div> 
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <label class="form-label">اسم العميل</label>
          <input type="text" disabled id="name" value="'.$row['name'].'" class="form-control">
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-3">
          <label class="form-label">رقم الهاتف</label>
          <input type="text" disabled id="phone" value="'.$row['phone'].'" class="form-control">
        </div>';
      }
    }
    ?>
   </div>

   <div id="goal" class="table-responsive mt-4"></div>
  </div>
</body>

<script>
function get_data() {
  var idElem = document.getElementById("id");
  if (!idElem) return; // تأكد من وجود العناصر قبل قراءتها
  
  var strrr = idElem.value;
  var d1 = document.getElementById("date1").value;
  var d2 = document.getElementById("date2").value;
  var phone = document.getElementById("phone").value;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("goal").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "get_sms.php?q=" + strrr + "&date1=" + d1 + "&date2=" + d2 + "&phone=" + phone, true);
  xmlhttp.send();
}

window.onload = function() {
  get_data();
};
</script>

</html>