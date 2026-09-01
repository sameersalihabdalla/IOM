<?php
include('config.php');
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="ar">

<?php include('head.php'); ?>


<body dir="rtl" class="bg-white">
<?php include('navbar.php'); ?>

<div class="container-fluid p-3">
  <div class="row g-3">
    <div class="col-12 col-md-4 p-1">
      <h6 class="text-primary fw-bold">
        <i class="bi bi-search"></i> بحث 
      </h6>
      <form action="result.php" method="get" class="row g-2 align-items-center card shadow-lg border-0 rounded-3 p-3 mt-3">
        <div class="col-12">
          <input type="text" id="insured_name" name="insured_name" class="form-control" placeholder="اسم المؤمن له">
        </div>
        <div class="col-12">
          <input type="submit" value="بحث" class="btn btn-sm btn-primary w-100">
        </div>
      </form>
    </div>

    <div class="col-12 col-md-8">
      <h6 class="mt-3 text-primary fw-bold">
        <i class="bi bi-file-earmark-text"></i> قائمة الوثائق
      </h6>

      <div class="card shadow-lg border-0 rounded-3 mt-3">
        <div class="card-body">
          <div class="row g-3">
            
            <div class="col-12 col-md-3">
              <label for="client" class="form-label fw-semibold">
                <i class="bi bi-person"></i> اسم العميل
              </label>
              <select id="client" name="client" class="form-select" onchange="get_data();">
                <option value="0">-</option>
                <?php
                if ($link->connect_error) {
                  die("Connection failed: ");
                } else {
                  $sql = "SELECT * from clients ORDER BY name";
                  $result = $link->query($sql);
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      echo '<option value='.$row["id"].'>'.$row["name"].'</option>';
                    }
                  } else {
                    echo "0 results";
                  }
                  $link->close();
                }
                ?>
              </select>
            </div>

            <div class="col-12 col-sm-6 col-md-2">
              <label class="form-label fw-semibold">
                <i class="bi bi-calendar-event"></i> تاريخ البداية
              </label>
              <input type="date" id="date1" value="<?=date('Y-m-d')?>" class="form-control" onchange="get_data();">
            </div>

            <div class="col-12 col-sm-6 col-md-2">
              <label class="form-label fw-semibold">
                <i class="bi bi-calendar-check"></i> تاريخ النهاية
              </label>
              <input type="date" id="date2" value="<?=date('Y-m-d')?>" class="form-control" onchange="get_data();">
            </div>

            <div class="col-12 col-md-5 d-flex align-items-center justify-content-start justify-content-md-end flex-wrap gap-2 pt-md-4" dir="ltr">
              <div class="form-check me-3">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="r1" checked onclick="get_data();">
                <label class="form-check-label fw-semibold text-danger" for="r1"> 
                  <i class="bi bi-x-circle"></i> غير المسددة
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="r2" onclick="get_data();">
                <label class="form-check-label fw-semibold text-success" for="r2">
                  <i class="bi bi-check-circle"></i> المسددة
                </label>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<main class="cd__main p-3 p-md-5">
  <div id="goal" class="table-responsive"></div>
</main>

<script>
function get_data() {
  var strrr = document.getElementById("client").value;
  var d1 = document.getElementById("date1").value;
  var d2 = document.getElementById("date2").value;
  var st = document.getElementById("r1").checked ? 0 : 1;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("goal").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "get_details.php?q=" + strrr + "&date1=" + d1 + "&date2=" + d2 + "&st=" + st, true);
  xmlhttp.send();
}
get_data();

// ✅ الدفع
function pay($x) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      get_data();
      swal("نجاح!", "تم حفظ عملية الدفع بنجاح", "success");
    }
  };
  xmlhttp.open("GET", "pay.php?id=" + $x, true);
  xmlhttp.send();
}

// ✅ الحذف مع تأكيد
function dele($xx) {
  swal({
    title: "هل أنت متأكد؟",
    text: "لن تتمكن من استرجاع هذه الوثيقة بعد الحذف!",
    icon: "warning",
    buttons: ["إلغاء", "نعم، احذف"],
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          get_data();
          swal("تم الحذف!", "الوثيقة تم حذفها بنجاح.", "success");
        }
      };
      xmlhttp.open("GET","delete.php?id="+$xx,true);
      xmlhttp.send();
    }
  });
}

// ✅ الإلغاء (تم إصلاح الخطأ البرمجي هنا)
function cance($xx) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      get_data();
      swal("تم التحديث", "تم تغيير حالة الوثيقة إلى ملغاة", "info");
    }
  };
  xmlhttp.open("GET","cancel.php?id="+$xx,true);
  xmlhttp.send();
}
</script>

</body>
</html>