<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];

$sql="SELECT Name,Page FROM REPORT_LAYOUT WHERE Layout_ID = '$id'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array($res );
$name = $row['Name'];
$Page = $row['Page'];

$sql="DELETE FROM REPORT_LAYOUT WHERE Layout_ID = '$id'";
$res = sqlsrv_query($conn, $sql); 

log_activity("Field($name) deleted from $Page");
$message = $res ? "Field($name) deleted from $Page":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status); 

}

?>

