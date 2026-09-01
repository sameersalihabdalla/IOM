

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>فاتورة مبدئية - شركة الارتقاء للتأمين</title>
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <script src="./js/sweetalert.js"></script>
  <link rel="stylesheet" href="./css/main.css">

  <style>
    body { background:#f8f9fa; font-family:"Tahoma", sans-serif; }
    .invoice-box { background:#fff; padding:25px; border:1px solid #ddd; border-radius:8px; max-width:800px; margin:auto; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; background:#eef2f7; padding:15px; border-radius:6px; }
    .header img { height:80px; }
    .header .company { text-align:right; }
    h4, h3 { color:#2c3e50; }
    table th { background:#f1f1f1; }
    .signature { margin-top:30px; display:flex; justify-content:space-between; }
    .footer { margin-top:20px; text-align:center; font-size:14px; color:#333; background:#eef2f7; padding:12px; border-radius:6px; }
    .footer strong { color:#000; }
    .footer hr { margin:8px auto; width:60%; border:1px solid #ccc; }

    /* بيانات العميل */
    .invoice-details { display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; margin-bottom:20px; }
    .detail .label { color:#1a5276; font-weight:bold; border-bottom:2px solid #333; border-right:3px solid #333; padding:4px; margin:0; }
    .detail .value { margin-top:4px; font-size:14px; color:#000; }
    .invoice-meta { text-align:left; font-size:14px; margin-bottom:10px; }
  </style>
</head>
<body dir="rtl" class="p-4">

<div class="container mt-4">
  <h3 class="mb-3 text-center">إصدار فاتورة مبدئية</h3>
  <form id="invoiceForm" class="row g-2">
    <div class="col-6">
      <label>اسم العميل</label>
      <input type="text" id="customer_name" class="form-control" placeholder="اسم المؤمن له">
    </div>
    <div class="col-6">
      <label>قيمة السيارة (جنيه)</label>
      <input type="number" id="car_value" class="form-control" placeholder="مثال: 10000000">
    </div>
    <div class="col-6">
      <label>فترة التأمين (أشهر)</label>
      <input type="number" id="period" class="form-control" placeholder="مثال: 12">
    </div>
    <div class="col-6">
      <label>ماركة السيارة</label>
      <input type="text" id="car_brand" class="form-control" placeholder="مثال: ميتسوبيشي">
    </div>
    <div class="col-6">
      <label>طراز السيارة</label>
      <input type="text" id="car_model" class="form-control" placeholder="مثال: لوري">
    </div>
    <div class="col-12 mt-3 text-center">
      <button type="button" class="btn btn-success px-4" onclick="calculateInvoice()">💰 احسب الفاتورة</button>
    </div>
  </form>
</div>

<div id="invoice" class="invoice-box mt-4" style="display:none;">

  <!-- رأس الفاتورة -->
  <div class="header">
    <div class="logo">
      <img src="./img/logo.png" alt="شعار الشركة"/>
    </div>
    <div class="company">
      <h4>شركة الارتقاء للتأمين المحدودة</h4>
      <p>مكتب الفحص الآلي شرق النيل</p>
      <p><strong>0999249900 | 0912230352</strong></p>
    </div>
  </div>

  <!-- رقم الفاتورة -->
  <div class="invoice-meta">
    <p><strong>رقم الفاتورة:</strong> <span id="inv_number"></span></p>
  </div>

  <h4 class="text-center mb-4">فاتورة مبدئية للتأمين التكميلي</h4>

  <!-- بيانات العميل -->
  <div class="invoice-details">
    <div class="detail">
      <p class="label">اسم العميل</p>
      <p class="value" id="inv_name"></p>
    </div>
    <div class="detail">
      <p class="label">قيمة السيارة</p>
      <p class="value" id="inv_value"></p>
    </div>
    <div class="detail">
      <p class="label">فترة التأمين</p>
      <p class="value" id="inv_period"></p>
    </div>
    <div class="detail">
      <p class="label">ماركة السيارة</p>
      <p class="value" id="inv_brand"></p>
    </div>
    <div class="detail">
      <p class="label">طراز السيارة</p>
      <p class="value" id="inv_model"></p>
    </div>
  </div>

  <!-- جدول التكاليف -->
  <table class="table table-bordered text-center">
    <thead class="table-light">
      <tr><th>نوع التكلفة</th><th>المبلغ (جنيه)</th></tr>
    </thead>
    <tbody>
      <tr><td>القسط الأساسي</td><td id="inv_premium"></td></tr>
      <tr><td>الضريبة</td><td id="inv_tax"></td></tr>
      <tr><td>المالية</td><td id="inv_finance"></td></tr>
      <tr><td>الرقابة</td><td id="inv_control"></td></tr>
      <tr><td>السلامة المرورية</td><td id="inv_safety"></td></tr>
      <tr><td>تغطية السائق</td><td id="inv_driver"></td></tr>
      <tr><td>تغطية الركاب</td><td id="inv_passenger"></td></tr>
    </tbody>
    <tfoot>
      <tr class="table-warning"><th>الإجمالي</th><th id="inv_total"></th></tr>
    </tfoot>
  </table>

  <p class="text-muted">هذه فاتورة مبدئية لغرض التقدير فقط، وليست وثيقة تأمين نهائية.</p>

  <!-- التوقيع والتاريخ -->
  <div class="signature border-top pt-3 mt-4 d-flex justify-content-between">
    <div>
      <p><strong>الموظف:</strong> سمير صالح عبدالله عثمان</p>
      <p><strong>التوقيع:</strong> ________________</p>
    </div>
    <div>
      <p><strong>التاريخ:</strong> <span id="inv_date"></span></p>
    </div>
  </div>

  <!-- التذييل -->
  <div class="footer">
    <p><strong>العنوان الرئيسي:</strong> شرق النيل القادسية شمال الفحص الآلي شرق النيل</p>
    <p><strong>فرع أمدرمان:</strong> حي الروضة - شارع الوادي</p>
    <hr>
    <p><strong>📧 البريد الإلكتروني:</strong> sameerssaom@gmail.com</p>
    <p><strong>💬 واتساب أعمال:</strong> 0999249900</p>
    <p><strong>📞 مكالمات:</strong> 0912230352 / 0118539900</p>
  </div>
</div>

<div class="text-center">
  <button id="printBtn" class="btn btn-secondary mt-3" style="display:none;" onclick="printInvoice()">🖨️ طباعة الفاتورة</button>
</div>
</body>
</html>

<script>
function calculateInvoice() {
  var name = document.getElementById("customer_name").value;
  var carValue = parseFloat(document.getElementById("car_value").value);
  var months = parseInt(document.getElementById("period").value);
  var brand = document.getElementById("car_brand").value;
  var model = document.getElementById("car_model").value;

  if (!carValue || !months || !name || !brand || !model) {
    swal("خطأ", "الرجاء إدخال جميع البيانات المطلوبة", "error");
    return;
  }

  var premium = carValue * 0.015 * (months / 12);
  var tax = premium * 0.10;
  var finance = premium * 0.02;
  var control = premium * 0.015;
  var safety = premium * 0.015;
  var driver = 1000;
  var passenger = 1000;
  var total = premium + tax + finance + control + safety + driver + passenger;

  document.getElementById("inv_name").innerText = name;
  document.getElementById("inv_value").innerText = carValue.toLocaleString();
  document.getElementById("inv_period").innerText = months;
  document.getElementById("inv_brand").innerText = brand;
  document.getElementById("inv_model").innerText = model;

  document.getElementById("inv_premium").innerText = premium.toFixed(2);
  document.getElementById("inv_tax").innerText = tax.toFixed(2);
  document.getElementById("inv_finance").innerText = finance.toFixed(2);
  document.getElementById("inv_control").innerText = control.toFixed(2);
  document.getElementById("inv_safety").innerText = safety.toFixed(2);
  document.getElementById("inv_driver").innerText = driver.toFixed(2);
  document.getElementById("inv_passenger").innerText = passenger.toFixed(2);
  document.getElementById("inv_total").innerText = total.toFixed(2);

  var today = new Date();
  var dateStr = today.getFullYear() + "/" +
                (today.getMonth()+1).toString().padStart(2,'0') + "/" +
                today.getDate().toString().padStart(2,'0') + " " +
                today.getHours().toString().padStart(2,'0') + ":" +
                today.getMinutes().toString().padStart(2,'0');
  document.getElementById("inv_date").innerText = dateStr;

  document.getElementById("invoice").style.display = "block";
  document.getElementById("printBtn").style.display = "inline-block";
}

function printInvoice() {
  var printContents = document.getElementById("invoice").innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
}
</script>
