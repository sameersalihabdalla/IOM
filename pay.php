<p id="demo"></p>

<?php
$q = $_REQUEST["id"];


if ($q !== "") {
require('db_conn.php');
$sql = "UPDATE document SET status='1' WHERE id=".$q."";

if ($conn->query($sql) === TRUE) {
    
	echo'<script>
	function myFunction() {
	  var txt;
	  if (confirm("Operation Done")) {
		txt = "You pressed OK!";
	  } else {
		txt = "You pressed Cancel!";
	  }
	  document.getElementById("demo").innerHTML = txt;
	}
	</script>';

} else {
	 echo ($conn->error);

}
$conn->close();

}
else
{
    
}

?>
