<?php
include('config.php');
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}

// معالجة الإضافة أو التعديل
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = $_POST['name'];
    $cost = $_POST['cost'];
    $commission_office = $_POST['commission_office'];
    $premium = $_POST['premium'];
    $passengers = $_POST['passengers'];
    $issue = $_POST['issue'];
    $commission_agent = $_POST['commission_agent'];

    if (!empty($id)) {
        // تحديث سجل موجود
        $sql = "UPDATE cat SET name='$name', cost='$cost', commission_office='$commission_office', premium='$premium', passengers='$passengers', issue='$issue', commission_agent='$commission_agent' WHERE id='$id'";
        $msg = "تم تحديث البيانات بنجاح";
    } else {
        // إضافة سجل جديد
        $sql = "INSERT INTO cat (name, cost, commission_office, premium, passengers, issue, commission_agent)
                VALUES ('$name', '$cost', '$commission_office', '$premium', '$passengers', '$issue', '$commission_agent')";
        $msg = "تمت إضافة نوع التأمين بنجاح";
    }

    if ($link->query($sql) === TRUE) {
        echo "<script>swal('نجاح!', '$msg', 'success');</script>";
    } else {
        echo "<script>swal('خطأ!', 'حدث خطأ أثناء المعالجة', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<?php include('head.php'); ?>
<head>
    <!-- استدعاء خط Cairo و FontAwesome لتصميم احترافي -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #dce2e6;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .table th {
            background-color: #2c3e50 !important;
            color: #fff;
            font-weight: 600;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .page-title {
            font-weight: 700;
            color: #2c3e50;
        }
    </style>
</head>

<body dir="rtl">
<?php include('navbar.php'); ?>

<div class="container-fluid py-4 px-4">
  
  <!-- عنوان الصفحة -->
  <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
          <h2 class="page-title mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i> إدارة أنواع التأمين والفئات</h2>
          <p class="text-muted mb-0">إضافة، تعديل، وحذف الفئات والعمولات الخاصة بالتأمين بكل سهولة.</p>
      </div>
  </div>

  <!-- بطاقة النموذج (إضافة / تعديل) -->
  <div class="card mb-4">
      <div class="card-header">
          <h5 class="mb-0 text-secondary" id="formTitle"><i class="fa-solid fa-plus-circle me-2"></i> إضافة نوع تأمين جديد</h5>
      </div>
      <div class="card-body p-4">
          <form method="POST" id="catForm" class="row g-3">
            <input type="hidden" name="id" id="cat_id">
            
            <div class="col-md-4">
              <label class="form-label fw-bold">اسم النوع / الفئة</label>
              <input type="text" name="name" id="cat_name" class="form-control" placeholder="أدخل اسم الفئة" required>
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">التكلفة</label>
              <input type="number" step="0.01" name="cost" id="cat_cost" class="form-control" placeholder="0.00" required>
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">عمولة المكتب</label>
              <input type="number" step="0.01" name="commission_office" id="cat_commission_office" class="form-control" placeholder="0.00">
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">القسط</label>
              <input type="number" step="0.01" name="premium" id="cat_premium" class="form-control" placeholder="0.00">
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">الركاب</label>
              <input type="number" step="0.01" name="passengers" id="cat_passengers" class="form-control" placeholder="0">
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">الإصدار</label>
              <input type="number" step="0.01" name="issue" id="cat_issue" class="form-control" value="500">
            </div>
            
            <div class="col-md-2">
              <label class="form-label fw-bold">عمولة الوكيل</label>
              <input type="number" step="0.01" name="commission_agent" id="cat_commission_agent" class="form-control" placeholder="0.00">
            </div>
            
            <div class="col-12 mt-4" id="formButtons">
              <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                  <i class="fa-solid fa-check me-1"></i> حفظ البيانات
              </button>
            </div>
          </form>
      </div>
  </div>

  <!-- بطاقة الجدول -->
  <div class="card">
      <div class="card-header">
          <h5 class="mb-0 text-secondary"><i class="fa-solid fa-table-list me-2"></i> قائمة الأنواع والفئات المسجلة</h5>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table id="typesTable" class="table table-striped table-hover table-bordered align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>التكلفة</th>
                    <th>عمولة المكتب</th>
                    <th>القسط</th>
                    <th>الركاب</th>
                    <th>الإصدار</th>
                    <th>عمولة الوكيل</th>
                    <th>الإجراءات</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM cat ORDER BY id DESC";
                  $result = $link->query($sql);
                  $counter = 1;
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      echo "<tr>
                        <td class='fw-bold'>".$counter++."</td>
                        <td>".$row['name']."</td>
                        <td><span class='badge bg-light text-dark'>".$row['cost']."</span></td>
                        <td>".$row['commission_office']."</td>
                        <td>".$row['premium']."</td>
                        <td>".$row['passengers']."</td>
                        <td>".$row['issue']."</td>
                        <td>".$row['commission_agent']."</td>
                        <td>
                          <button class='btn btn-warning btn-sm text-white px-2 py-1 shadow-sm' onclick='editType(".json_encode($row).")' title='تعديل'>
                              <i class='fa-solid fa-pen-to-square'></i>
                          </button>
                          <button class='btn btn-danger btn-sm px-2 py-1 shadow-sm' onclick='deleteType(".$row['id'].")' title='حذف'>
                              <i class='fa-solid fa-trash-can'></i>
                          </button>
                        </td>
                      </tr>";
                    }
                  } else {
                      echo "<tr><td colspan='9' class='text-center py-4 text-muted'>لا توجد بيانات متاحة حالياً</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
          </div>
      </div>
  </div>

</div>

<script>
function editType(row) {
    document.getElementById('cat_id').value = row.id;
    document.getElementById('cat_name').value = row.name;
    document.getElementById('cat_cost').value = row.cost;
    document.getElementById('cat_commission_office').value = row.commission_office;
    document.getElementById('cat_premium').value = row.premium;
    document.getElementById('cat_passengers').value = row.passengers;
    document.getElementById('cat_issue').value = row.issue;
    document.getElementById('cat_commission_agent').value = row.commission_agent;
    
    // تغيير عنوان البطاقة وزر الحفظ
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2 text-warning"></i> تعديل بيانات الفئة: ' + row.name;
    
    let submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fa-solid fa-rotate-right me-1"></i> تحديث البيانات';
    submitBtn.className = "btn btn-success px-4 shadow-sm";
    
    if (!document.getElementById('cancelBtn')) {
        let cancelBtn = document.createElement('button');
        cancelBtn.type = "button";
        cancelBtn.id = "cancelBtn";
        cancelBtn.className = "btn btn-secondary px-4 ms-2 shadow-sm";
        cancelBtn.innerHTML = '<i class="fa-solid fa-xmark me-1"></i> إلغاء';
        cancelBtn.onclick = resetForm;
        document.getElementById('formButtons').appendChild(cancelBtn);
    }
    
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('catForm').reset();
    document.getElementById('cat_id').value = '';
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-2"></i> إضافة نوع تأمين جديد';
    
    let submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> حفظ البيانات';
    submitBtn.className = "btn btn-primary px-4 shadow-sm";
    
    let cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) cancelBtn.remove();
}

function deleteType(id) {
  swal({
    title: "هل أنت متأكد؟",
    text: "سيتم حذف هذا النوع نهائيًا وليمكنك استرجاع البيانات!",
    icon: "warning",
    buttons: ["إلغاء", "نعم، احذف"],
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          swal("تم الحذف!", "تم حذف النوع بنجاح.", "success").then(() => {
            location.reload();
          });
        }
      };
      xmlhttp.open("GET","delete_type.php?id="+id,true);
      xmlhttp.send();
    }
  });
}
</script>

</body>
</html>