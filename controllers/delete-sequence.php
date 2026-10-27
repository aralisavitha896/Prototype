<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$batch = $_REQUEST['hide_id'];
$sql="SELECT Sequence_Name FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$batch'";
$res = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($res);
$Sequence_Name = $row['Sequence_Name'];

$sql="DELETE FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$batch'";
$res = sqlsrv_query($conn, $sql); 

log_activity("sequence($Sequence_Name) deleted");
$message = $res ? "sequence($Sequence_Name) deleted":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);

}

?>

