<?php
// Array with names
// get the q parameter from URL
$q = $_REQUEST["q"];
if ($q !== "") {

  require('config.php');
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  }
  else
  {}
  $sql = "SELECT * from cost_s where id='".$q."'";
  $result = $link->query($sql);
  
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {	  
    echo'  <th class="p-0 m-0" style="width:50%;">البيان</th>
      <th class="p-0 m-0">المبلغ</th><tr>
        <td class="p-0 m-0" hidden >قسط التأمين</td>
        <td class="p-0 m-0" hidden ><input type="text" class="form-control p-0 m-0" value="'.$row['premium'].'" id="premium" name="premium"  ></td><tr>
        <td class="p-0 m-0" hidden >قسط اضافة ركاب</td>
        <td class="p-0 m-0" hidden ><input type="number" value="'.$row['passengers'].'" class="form-control p-0 m-0" id="passengers" name="passengers"  ></td><tr>
        <td class="p-0 m-0" hidden >دمغة</td>
        <td class="p-0 m-0" hidden >  <input type="number"  id="StampCost" name="StampCost" value="'.$row['StampCost'].'" class="form-control p-0 m-0"></td><tr>
        <td class="p-0 m-0" hidden >الاشراف</td>
        <td class="p-0 m-0" hidden ><input type="number"  id="SuperVisionCost" name="SuperVisionCost" value="'.$row['SuperVisionCost'].'" class="form-control p-0 m-0"></td><tr>
        <td class="p-0 m-0" hidden >اصحاب العمل</td>
        <td class="p-0 m-0" hidden ><input type="number"  vlaue="0" disabled class="form-control p-0 m-0"></td><tr>
        <td class="p-0 m-0" hidden >إصدار</td>
        <td class="p-0 m-0" hidden >  <input type="number"  id="issue" name="issue" value="'.$row['issue'].'" class="form-control p-0 m-0"></td><tr>
        <td class="p-0 m-0" hidden >ضريبة دعم</td>
        <td class="p-0 m-0" hidden >  <input type="number"  id="SupportTax" name="SupportTax" value="'.$row['SupportTax'].'" class="form-control small p-0 m-0"></td><tr>
        <td class="p-0 m-0" hidden >عمولة المكتب</td>
        <td class="p-0 m-0" hidden>  <input type="number"  id="commission_office" name="commission_office" value="'.$row['commission_office'].'" class="form-control p-0 m-0">      </td><tr>
        <td class="p-0 m-0" hidden >عمولة الشركة</td>
        <td class="p-0 m-0"  hidden>  <input type="number"  id="commission_agent" name="commission_agent" value="'.$row['commission_agent'].'" class="form-control p-0 m-0">        </td><tr>
        <th class="p-0 m-0 table-danger" style="font-size:20px;">الاجمالي</th>
        <td class="p-0 m-0 table-danger" ><u><b >  <input type="number"  id="TotalCost" name="TotalCost"  style="font-size:20px;" value="'.$row['TotalCost'].'" class="form-control p-0 m-0"> </b><u></td> ';

    }
    $link->close();
  } else {
    echo "0 results";
  }

  
  }


  

?>