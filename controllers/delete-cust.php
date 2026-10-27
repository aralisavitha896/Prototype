<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];
$sql="DELETE FROM [CUSTOMER_DATA] WHERE Id = '$id'";
$res = sqlsrv_query($conn, $sql); 
}

?>

	