<?php
include('config.php');
session_start();

# إذا لم يكن المستخدم مسجل الدخول
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}

# إذا تم الإرسال عبر POST نحفظ البيانات مباشرة
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doc                = $_POST['doc'];
    $name               = $_POST['name'];
    $date               = $_POST['date'];
    $type               = $_POST['type'];
    $Plate_no           = $_POST['Plate_no'];
    $chassis            = $_POST['chassis'];
    $broker_id          = $_POST['broker'];
    $premium            = $_POST['premium'];
    $passengers         = $_POST['passengers'];
    $StampCost        = $_POST['StampCost'];
    $SuperVisionCost  = $_POST['SuperVisionCost'];
    $issue              = $_POST['issue'];
    $SupportTax         = $_POST['SupportTax'];
    $commission_office= $_POST['commission_office'];
    $commission_agent = $_POST['commission_agent'];
    $TotalCost        = $_POST['TotalCost'];
    $note               = "insurance";

    try {
        $pdo = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO document 
            (name, document, date, type, premium, passengers, commission_office, commission_agent, issue, StampCost, SupportTax, SuperVisionCost, TotalCost, broker_id, note, Plate_no, chassis)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $success = $stmt->execute([
            $name, $doc, $date, $type, $premium, $passengers, $commission_office, $commission_agent,
            $issue, $StampCost, $SupportTax, $SuperVisionCost, $TotalCost, $broker_id, $note, $Plate_no, $chassis
        ]);

        echo $success ? "success" : "error";

    } catch (PDOException $e) {
        echo "db_error: " . $e->getMessage();
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
  <?php include('head.php'); ?>
  <!-- إضافة مكتبة SweetAlert2 لضمان ظهور الرسائل -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <body dir="rtl">
<?php include('navbar.php'); ?>
<div class="container-fluid p-2">
  <div class="row g-3 m-0">
    
    <!-- قسم الإطار (Iframe) -->
    <div class="col-12 col-lg-9 p-0">
      <div class="ratio ratio-16x9" style="height: 900px; max-height: 80vh;">
        <iframe src="https://tp2.nira.gov.sd/" class="w-100 h-100 border-0"></iframe>
      </div>
    </div>

    <!-- نموذج الإدخال -->
    <div class="col-12 col-lg-3 pt-lg-5" dir="rtl">
      <form id="docForm" class="p-2 border rounded shadow-sm bg-light">
        <input type="text" class="form-control mb-2" required id="doc" name="doc" value="-">
        <input type="text" class="form-control mb-2" required id="name" name="name" placeholder="اسم المؤمن له">
        <input type="date" class="form-control mb-2" required id="date" name="date" value="<?=date("Y-m-d");?>">

        <select required class="form-select mb-2" name="type" id="type" onChange="get_data()">
          <?php
          $sql = "SELECT * from cat";
          $result = $link->query($sql);
          while($row = $result->fetch_assoc()) {
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
          }
          ?>
        </select>

        <input type="text" class="form-control mb-2" hidden required id="chassis" name="chassis" placeholder="رقم الشاسيه" value="0">
        <input type="text" class="form-control mb-2" hidden required id="Plate_no" name="Plate_no" placeholder="رقم اللوحة" value="0">

        <select class="form-select mb-2" id="broker" name="broker" required>
          <?php
          $sql = "SELECT * from clients order by name";
          $result = $link->query($sql);
          while($row = $result->fetch_assoc()) {
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
          }
          ?>
        </select>

        <div class="table-responsive">
          <table id="tbl" class="table table-bordered m-0 small bg-white"></table>
        </div>
        
        <input type="submit" value="حفظ" class="btn btn-success w-100 mt-3">
      </form>
    </div>

  </div>
</div>

<script>
function get_data() {
  var strrr = document.getElementById("type").value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("tbl").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET", "get_data.php?q=" + strrr, true);
  xmlhttp.send();
}

window.onload = function() { get_data(); };

document.getElementById("docForm").addEventListener("submit", function(e) {
  e.preventDefault();
  var formData = new FormData(this);

  fetch("", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    if (data.trim() === "success") {
      Swal.fire({
        icon: 'success',
        title: 'تم الحفظ',
        text: 'تم حفظ البيانات بنجاح',
        confirmButtonText: 'حسناً'
      });
      // إعادة تعيين الحقول بعد الحفظ الناجح
      document.getElementById("name").value = "";
      document.getElementById("date").value = "<?=date("Y-m-d");?>";
      document.getElementById("type").selectedIndex = 0;
      document.getElementById("broker").selectedIndex = 0;
      get_data();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'خطأ',
        text: data,
        confirmButtonText: 'موافق'
      });
    }
  })
  .catch(error => {
    Swal.fire({
      icon: 'error',
      title: 'خطأ في الاتصال',
      text: 'تعذر الاتصال بالسيرفر',
      confirmButtonText: 'موافق'
    });
  });
});
</script>
</body>
</html>