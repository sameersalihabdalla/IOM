<?php
include('config.php');
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>window.location.href='./login.php';</script>";
  exit;
}

// معالجة الإضافة أو التعديل
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $name   = $_POST['name'];
    $phone  = $_POST['phone'];
    $status = $_POST['status'];

    if (!empty($id)) {
        // تحديث عميل موجود
        $sql = "UPDATE clients SET name='$name', phone='$phone', status='$status' WHERE id='$id'";
        $msg = "تم تحديث بيانات العميل بنجاح";
    } else {
        // إضافة عميل جديد
        $sql = "INSERT INTO clients (name, phone, status) VALUES ('$name', '$phone', '$status')";
        $msg = "تمت إضافة العميل بنجاح";
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
        .form-control:focus, .form-select:focus {
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
          <h2 class="page-title mb-1"><i class="fa-solid fa-users text-primary me-2"></i> إدارة العملاء</h2>
          <p class="text-muted mb-0">إضافة، تعديل، ومتابعة حالات العملاء بكل سهولة.</p>
      </div>
  </div>

  <!-- بطاقة النموذج (إضافة / تعديل) -->
  <div class="card mb-4">
      <div class="card-header">
          <h5 class="mb-0 text-secondary" id="formTitle"><i class="fa-solid fa-user-plus me-2"></i> إضافة عميل جديد</h5>
      </div>
      <div class="card-body p-4">
          <form method="POST" id="clientForm" class="row g-3" onsubmit="return validatePhone()">
            <input type="hidden" name="id" id="client_id">
            
            <div class="col-md-4">
              <label class="form-label fw-bold">اسم العميل</label>
              <input type="text" name="name" id="client_name" class="form-control" placeholder="أدخل اسم العميل" required>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-bold">رقم الهاتف</label>
              <input type="text" name="phone" id="client_phone" class="form-control" placeholder="249912345678" required>
              <small class="text-muted">يجب أن يبدأ الرقم بـ 249 ويتبعه 9 أرقام (مثال: 249912345678)</small>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-bold">الحالة</label>
              <select name="status" id="client_status" class="form-select">
                <option value="نشط">نشط</option>
                <option value="موقوف">موقوف</option>
              </select>
            </div>
            
            <div class="col-12 mt-4" id="formButtons">
              <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                  <i class="fa-solid fa-check me-1"></i> حفظ البيانات
              </button>
            </div>
          </form>
      </div>
  </div>

  <!-- بطاقة جدول العملاء -->
  <div class="card">
      <div class="card-header">
          <h5 class="mb-0 text-secondary"><i class="fa-solid fa-table-list me-2"></i> قائمة العملاء المسجلين</h5>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table id="clientsTable" class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM clients ORDER BY id DESC";
                  $result = $link->query($sql);
                  $counter = 1;
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      $statusBadge = ($row['status'] == 'نشط') ? 'bg-success' : 'bg-danger';
                      echo "<tr>
                        <td class='fw-bold'>".$counter++."</td>
                        <td>".$row['name']."</td>
                        <td dir='ltr'>+".$row['phone']."</td>
                        <td><span class='badge ".$statusBadge." px-3 py-2'>".$row['status']."</span></td>
                        <td>
                          <button class='btn btn-warning btn-sm text-white px-2 py-1 shadow-sm' onclick='editClient(".json_encode($row).")' title='تعديل'>
                              <i class='fa-solid fa-pen-to-square'></i>
                          </button>
                          <button class='btn btn-danger btn-sm px-2 py-1 shadow-sm' onclick='deleteClient(".$row['id'].")' title='حذف'>
                              <i class='fa-solid fa-trash-can'></i>
                          </button>
                        </td>
                      </tr>";
                    }
                  } else {
                      echo "<tr><td colspan='5' class='text-center py-4 text-muted'>لا توجد بيانات عملاء متاحة حالياً</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
          </div>
      </div>
  </div>

</div>

<script>
function validatePhone() {
    let phoneInput = document.getElementById('client_phone').value.trim();
    // التحقق من أن الرقم يبدأ بـ 249 ويكون إجمالي طوله 12 رقماً (249 + 9 أرقام)
    let phoneRegex = /^249[0-9]{9}$/;
    
    if (!phoneRegex.test(phoneInput)) {
        swal("تنبيه!", "يرجى إدخال رقم هاتف سوداني صحيح يبدأ بـ 249 ويتبعه 9 أرقام (مثال: 249912345678)", "warning");
        return false;
    }
    return true;
}

function editClient(row) {
    document.getElementById('client_id').value = row.id;
    document.getElementById('client_name').value = row.name;
    document.getElementById('client_phone').value = row.phone;
    document.getElementById('client_status').value = row.status;
    
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2 text-warning"></i> تعديل بيانات العميل: ' + row.name;
    
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
    document.getElementById('clientForm').reset();
    document.getElementById('client_id').value = '';
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-user-plus me-2"></i> إضافة عميل جديد';
    
    let submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> حفظ البيانات';
    submitBtn.className = "btn btn-primary px-4 shadow-sm";
    
    let cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) cancelBtn.remove();
}

function deleteClient(id) {
  swal({
    title: "هل أنت متأكد؟",
    text: "سيتم حذف هذا العميل نهائيًا ولا يمكنك استرجاع البيانات!",
    icon: "warning",
    buttons: ["إلغاء", "نعم، احذف"],
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          swal("تم الحذف!", "تم حذف العميل بنجاح.", "success").then(() => {
            location.reload();
          });
        }
      };
      xmlhttp.open("GET","delete_client.php?id="+id,true);
      xmlhttp.send();
    }
  });
}
</script>

</body>
</html>