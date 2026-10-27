<?php  include('../includes/config.php'); 

if(isset($_REQUEST['column']))
{
$column = $_REQUEST['column'];
$value = $_REQUEST['value'];
if($value == 1)$update = 0;
else $update = 1;

$sql="UPDATE [HEADER_DETAILS] SET $column = '$update'";
$res = sqlsrv_query($conn, $sql); 
 
log_activity("Layout Status Updated");
}

?>

