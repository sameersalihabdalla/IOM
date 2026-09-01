<?php
require_once 'db.php';

$message = "";




// 1. معالجة رفع الملف والتخزين في قاعدة البيانات بشكل آمن ومقاوم للأخطاء
if (isset($_POST['upload'])) {
    if ($_FILES['excel_file']['error'] == 0) {
        $filename = $_FILES['excel_file']['tmp_name'];
        
        // قراءة الملف بالكامل كنص
        $file_content = file_get_contents($filename);
        
        // أ) إزالة توقيع BOM المخفي الذي يسببه إكسيل لضمان قراءة أول عمود بشكل سليم
        if (substr($file_content, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $file_content = substr($file_content, 3);
        }
        
        // ب) معالجة وترميم الترميز العربي بشكل صارم ومضمون لسيرفر Wamp
        if (!mb_check_encoding($file_content, 'UTF-8')) {
            // المحاولة الأولى: تحويل ترميز إكسيل ويندوز العربي الشائع
            $converted_content = @iconv('CP1256', 'UTF-8//IGNORE', $file_content);
            if ($converted_content !== false) {
                $file_content = $converted_content;
            }
        }
        
        // ج) توحيد نهايات الأسطر وتقسيم الملف إلى أسطر فعلية
        $file_content = str_replace("\r\n", "\n", $file_content);
        $file_content = str_replace("\r", "\n", $file_content);
        $lines = explode("\n", $file_content);
        
        // تخطي السطر الأول (العناوين)
        array_shift($lines);
        
        $inserted = 0;
        $skipped = 0;

        // تجهيز استعلام الإدخال مع خاصية عدم التكرار والتحديث
        $sql = "INSERT INTO documents 
                (serial_number, insured_name, office, employee_name, system_user, insurance_type, vehicle_type, manufacturing_year, engine_number, plate_number, chassis_number, start_date, end_date, issue_date, total_premium, document_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                insured_name = VALUES(insured_name), 
                plate_number = VALUES(plate_number), 
                chassis_number = VALUES(chassis_number),
                office = VALUES(office);";
        
        $stmt = $pdo->prepare($sql);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue; // تخطي الأسطر الفارغة تماماً
            
            // د) تحديد الفاصلة ديناميكياً (إكسيل العربي يستخدم الفاصلة المنقوطة ";" غالباً)
            $delimiter = (strpos($line, ';') !== false) ? ';' : ',';
            
            // هـ) معالجة السطر بدقة متناهية وفصل الحقول وعلامات الاقتباس
            $data = str_getcsv($line, $delimiter, '"');
            
            // فحص مرن للتأكد من وجود البيانات الأساسية (الرقم التسلسلي والمؤمن له)
            if (isset($data[1]) && !empty(trim($data[1])) && strlen(trim($data[1])) > 5) { 
                try {
                    $stmt->execute([
                        trim($data[1]),                                       // الرقم التسلسلي
                        isset($data[2]) ? trim($data[2]) : '',                // المؤمن له
                        isset($data[3]) ? trim($data[3]) : '',                // المكتب
                        isset($data[4]) ? trim($data[4]) : '',                // إسم الموظف
                        isset($data[5]) ? trim($data[5]) : '',                // مستخدم النظام
                        isset($data[6]) ? trim($data[6]) : '',                // نوع التأمين
                        isset($data[7]) ? trim($data[7]) : '',                // نوع المركبة
                        isset($data[8]) ? intval($data[8]) : 0,               // تاريخ التصنيع
                        isset($data[9]) ? trim($data[9]) : '',                // الماكينة
                        isset($data[10]) ? trim($data[10]) : '',              // رقم اللوحة
                        isset($data[11]) ? trim($data[11]) : '',              // الشاسي
                        (isset($data[12]) && !empty($data[12])) ? trim($data[12]) : null, // تاريخ البداية
                        (isset($data[13]) && !empty($data[13])) ? trim($data[13]) : null, // تاريخ النهاية
                        (isset($data[14]) && !empty($data[14])) ? trim($data[14]) : null, // تاريخ الاصدار
                        isset($data[15]) ? floatval($data[15]) : 0,           // اجمالي القسط
                        isset($data[16]) ? trim($data[16]) : ''               // نوع الوثيقة
                    ]);
                    $inserted++;
                } catch (Exception $e) {
                    $skipped++;
                }
            } else {
                $skipped++; 
            }
        }
        
        $message = "<div class='alert alert-success'>تمت المعالجة بنجاح! تم إدخال/تحديث <strong>$inserted</strong> وثيقة بترميز سليم، وتخطي <strong>$skipped</strong> أسطر فارغة.</div>";
    } else {
        $message = "<div class='alert alert-danger'>خطأ أثناء رفع الملف، يرجى المحاولة مرة أخرى.</div>";
    }
}











// 2. معالجة عمليات البحث الفوري والدقيق
$results = [];
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search_query']);
    
    if (!empty($search_query)) {
        // البحث باستخدام LIKE ليعطي نتائج مرنة (بجزء من الاسم أو رقم اللوحة أو الشاسي أو الرقم التسلسلي)
        $sql = "SELECT * FROM documents WHERE 
                insured_name LIKE ? OR 
                plate_number LIKE ? OR 
                chassis_number LIKE ? OR 
                serial_number = ?";
        
        $stmt = $pdo->prepare($sql);
        $like_query = "%" . $search_query . "%";
        $stmt->execute([$like_query, $like_query, $like_query, $search_query]);
        $results = $stmt->fetchAll();
    } else {
        // عرض آخر 20 وثيقة تم رفعها في حال لم يتم كتابة شيء في خانة البحث
        $stmt = $pdo->query("SELECT * FROM documents ORDER BY id DESC LIMIT 20");
        $results = $stmt->fetchAll();
    }
} else {
    // العرض الافتراضي عند فتح الصفحة
    $stmt = $pdo->query("SELECT * FROM documents ORDER BY id DESC LIMIT 10");
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة وبحث وثائق التأمين</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body { font-family: 'Cairo', Arial, sans-serif; background-color: #f8f9fa; }
        .card { margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .table-responsive { max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">نظام استيراد وبحث وثائق الطرف الثالث</h2>
    
    <?php echo $message; ?>

    <div class="card p-4 mb-4">
        <h5 class="card-title">تحديث قاعدة البيانات (رفع ملف CSV الناتجة من الاكسيل)</h5>
        <form action="" method="POST" enctype="multipart/form-data" class="row g-3 align-items-center">
            <div class="col-auto">
                <input type="file" name="excel_file" accept=".csv" class="form-control" required>
            </div>
            <div class="col-auto">
                <button type="submit" name="upload" class="btn btn-primary">رفع وتحديث البيانات دون تكرار</button>
            </div>
            <div class="col-auto text-muted">
                <small>* النظام يتعرف تلقائياً على الوثائق المرفوعة مسبقاً عبر (الرقم التسلسلي) ولا يكررها.</small>
            </div>
        </form>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="card-title">البحث السريع والدقيق عن الوثائق</h5>
        <form action="" method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="search_query" class="form-control" placeholder="ابحث باسم العميل، رقم اللوحة، رقم الشاسي، أو الرقم التسلسلي..." value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" name="search" class="btn btn-success w-100">بحث فوري</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h5 class="card-title mb-3">نتائج البحث / الوثائق الحالية (إجمالي النتائج: <?php echo count($results); ?>)</h5>
        
        <?php if (!empty($results)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>الرقم التسلسلي</th>
                            <th>المؤمن له</th>
                            <th>المكتب</th>
                            <th>رقم اللوحة</th>
                            <th>الشاسي</th>
                            <th>نوع المركبة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>إجمالي القسط</th>
                            <th>نوع الوثيقة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td class="text-danger fw-bold"><?php echo htmlspecialchars($row['serial_number']); ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['insured_name']); ?></td>
                                Flats<td><?php echo htmlspecialchars($row['office']); ?></td>
                                <td class="table-primary fw-bold"><?php echo htmlspecialchars($row['plate_number']); ?></td>
                                <td class="table-warning"><?php echo htmlspecialchars($row['chassis_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                <td class="text-success fw-bold"><?php echo number_format($row['total_premium'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['document_type']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">لا توجد نتائج مطابقة للبحث أو قاعدة البيانات فارغة حالياً.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>