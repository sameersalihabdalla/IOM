
<?php
$q = $_REQUEST["id"];
if ($q !== "") {
require('db_conn.php');
$sql = "update document
set cancel='1' 
 where  id='".$q."'";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php?msg=update");

} else {
	 header("Location: production.php?error=حدث خطأ ما");
	 echo ($conn->error);

}
$conn->close();

}
else
{
    
}

?>
