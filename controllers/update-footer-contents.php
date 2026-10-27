<?php  include('../includes/config.php'); 

if(isset($_REQUEST['column'])){
	
$column = $_REQUEST['column'];
$value = $_REQUEST['value'];

$sql="UPDATE FOOTER_DETAILS SET $column = '$value'";
$res= sqlsrv_query($conn, $sql);

log_activity("Footer column($column) updated");
}


?>

