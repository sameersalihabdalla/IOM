<div class="row g-4">
  <!-- قسم الوثائق والطباعة -->
  <div class="col-lg-9">
    <?php
    require('db_conn.php');


    $q = isset($_REQUEST["q"]) ? mysqli_real_escape_string($conn, $_REQUEST["q"]) : '';
$d1 = isset($_REQUEST["date1"]) ? mysqli_real_escape_string($conn, $_REQUEST["date1"]) : '';
$d2 = isset($_REQUEST["date2"]) ? mysqli_real_escape_string($conn, $_REQUEST["date2"]) : '';
$st = isset($_REQUEST["st"]) ? mysqli_real_escape_string($conn, $_REQUEST["st"]) : '';
    if ($q !== "") {
        // دالة مساعدة لعرض الجداول بشكل أنيق
        function renderTable($conn, $sql, $title, $badgeClass, $isPrint = false) {
            $result = mysqli_query($conn, $sql);
            echo '<div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">';
            echo '<div class="card-header bg-white py-3 px-4 border-0 d-flex align-items-center justify-content-between">';
            echo '<h5 class="m-0 fw-bold text-dark"><i class="bi bi-folder2-open text-success me-2"></i>' . $title . '</h5>';
            echo '</div>';
            
            echo '<div class="table-responsive m-0">';
            echo '<table class="table table-hover align-middle mb-0 text-center" style="font-size:13px;">';
            echo '<thead class="' . $badgeClass . ' text-white">';
            echo '<tr>';
            echo '<th class="py-3">#</th>';
            if ($isPrint) echo '<th class="py-3">رقم الوثيقة</th>';
            echo '<th class="py-3">اسم المؤمن له</th>';
            echo '<th class="py-3">بواسطة</th>';
            echo '<th class="py-3">التاريخ</th>';
            echo '<th class="py-3">نوع التأمين</th>';
            echo '<th class="py-3">الإجمالي</th>';
            echo '<th class="py-3">الحالة</th>';
            echo '<th class="py-3">الإجراءات</th>';
            echo '<th class="py-3"><i class="bi bi-whatsapp"></i></th>';
            echo '</tr></thead><tbody>';

            $counter = 1;
            $total_cost = 0;

            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $total_cost += $row['TotalCost'];
                    $statusBadge = ($row['status'] == 1) 
                        ? "<span class='badge bg-success-subtle text-success px-2 py-1 rounded-pill'>مسددة</span>" 
                        : "<span class='badge bg-danger-subtle text-danger px-2 py-1 rounded-pill'>غير مسددة</span>";
                    
                    $cancelBtn = ($row['cancel'] == 0) 
                        ? "<button class='btn btn-light btn-sm text-secondary border' onclick='cance(".$row['id'].");' title='إلغاء'><i class='bi bi-x-circle'></i></button>" 
                        : "<button class='btn btn-success btn-sm' onclick='cance(".$row['id'].");' style='font-size:11px;'>إعادة تفعيل</button>";

                    echo "<tr>";
                    echo "<td class='fw-semibold text-muted'>".$counter."</td>";
                    if ($isPrint) echo "<td class='fw-bold text-primary'>".$row['document']."</td>";
                    echo "<td class='text-start fw-medium'>".$row['name']."</td>";
                    echo "<td><span class='badge bg-light text-dark border'>".$row['client_name']."</span></td>";
                    echo "<td class='text-muted'>".$row['date']."</td>";
                    echo "<td>".$row['cat_name']."</td>";
                    echo "<td class='fw-bold text-dark'>".number_format($row['TotalCost'], 2, '.', ',')."</td>";
                    echo "<td>".$statusBadge."</td>";
                    
                    echo "<td>
                            <div class='btn-group gap-1'>
                                <button class='btn btn-outline-success btn-sm border-0 bg-success-subtle' onclick='pay(".$row['id'].");' title='تسديد'><i class='bi bi-credit-card-2-front'></i></button>
                                <button class='btn btn-outline-danger btn-sm border-0 bg-danger-subtle' onclick='dele(".$row['id'].");' title='حذف'><i class='bi bi-trash'></i></button>
                                ".$cancelBtn."
                            </div>
                          </td>";
                    
                    echo "<td><a class='btn btn-success btn-sm rounded-circle' style='width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center;' href='send_sms.php?id=".$row['broker_id']."'><i class='bi bi-whatsapp'></i></a></td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                $colSpan = $isPrint ? 11 : 10;
                echo "<tr><td colspan='".$colSpan."' class='py-4 text-muted'>لا توجد بيانات متاحة</td></tr>";
            }
            echo "</tbody></table></div>";
            
            // شريط الإجمالي السفلي داخل الكارد
            echo '<div class="card-footer bg-light py-3 px-4 d-flex justify-content-between align-items-center border-0">';
            echo '<span class="fw-bold text-secondary">إجمالي ' . $title . ':</span>';
            echo '<span class="fs-5 fw-bold text-success">'.number_format($total_cost, 2, '.', ',').'</span>';
            echo '</div>';
            echo '</div>';
        }

        // 1. استعلام وثائق (type <> 23)
        $sql_docs = ($q == 0) 
            ? "SELECT * FROM sam WHERE date >= '$d1' AND date <= '$d2' AND type<>23 ORDER BY id" 
            : "SELECT * FROM sam WHERE date >= '$d1' AND date <= '$d2' AND broker_id='$q' AND status='$st' AND type<>23 ORDER BY id";
        renderTable($conn, $sql_docs, "الوثائق", "bg-success", false);

        // 2. استعلام الطباعة (type = 23)
        $sql_print = ($q == 0) 
            ? "SELECT * FROM sam WHERE date >= '$d1' AND date <= '$d2' AND type=23 ORDER BY id" 
            : "SELECT * FROM sam WHERE date >= '$d1' AND date <= '$d2' AND broker_id='$q' AND status='$st' AND type=23 ORDER BY id";
        renderTable($conn, $sql_print, "الطباعة", "bg-primary", true);
    }
    ?>
  </div>

  <!-- قسم المديونية الجانبي -->
  <div class="col-lg-3">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden sticky-top" style="top: 20px;">
      <div class="card-header bg-success text-white py-3 px-4 border-0">
        <h5 class="m-0 fw-bold fs-6"><i class="bi bi-wallet2 me-2"></i>متابعة المديونية</h5>
      </div>
      
      <div class="card-body p-0">
        <?php
        $sql_debt = "SELECT broker_id, client_name, SUM(TotalCost) AS sa FROM sam WHERE STATUS='0' GROUP BY client_name ORDER BY sa DESC";
        $result_debt = mysqli_query($conn, $sql_debt);
        $clients = [];
        $total_debt = 0;

        if ($result_debt && mysqli_num_rows($result_debt) > 0) {
            while($row = mysqli_fetch_assoc($result_debt)) {
                $clients[] = $row;
                $total_debt += $row['sa'];
            }
        }
        ?>
        <div class="table-responsive m-0">
          <table class="table table-hover align-middle mb-0 text-center" style="font-size:12px;">
            <thead class="table-light text-secondary">
              <tr>
                <th class="py-2">#</th>
                <th class="py-2 text-start">العميل</th>
                <th class="py-2">المديونية</th>
                <th class="py-2"><i class="bi bi-whatsapp"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              if (!empty($clients)) {
                  foreach ($clients as $row) {
                      $percentage = ($total_debt > 0) ? round(($row['sa'] / $total_debt) * 100, 1) : 0;
                      echo "<tr>";
                      echo "<td class='text-muted'>".$i."</td>";
                      echo "<td class='text-start fw-semibold text-dark'>
                              <div class='d-flex flex-column'>
                                <span>".$row['client_name']."</span>
                                <div class='progress mt-1' style='height: 3px;'>
                                  <div class='progress-bar bg-success' role='progressbar' style='width: {$percentage}%;' aria-valuenow='{$percentage}' aria-valuemin='0' aria-valuemax='100'></div>
                                </div>
                              </div>
                            </td>";

echo "<td class='fw-bold text-danger'>".number_format($row['sa'], 2, '.', ',')."</td>";
                            echo "<td><a class='btn btn-success btn-sm rounded-circle' style='width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;' href='send_sms.php?id=".$row['broker_id']."'><i class='bi bi-whatsapp' style='font-size:11px;'></i></a></td>";
                      echo "</tr>";
                      $i++;
                  }
              } else {
                  echo "<tr><td colspan='4' class='py-3 text-muted'>لا توجد مديونيات</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- إجمالي المديونية الكلي -->
      <div class="card-footer bg-light py-3 px-3 d-flex justify-content-between align-items-center border-0">
        <span class="fw-bold text-secondary fs-7">إجمالي المديونية:</span>
        <span class="fw-bold text-danger fs-6"><?php echo number_format($total_debt, 2, '.', ',')." ج.س"; ?></span>
      </div>
    </div>
  </div>
</div>