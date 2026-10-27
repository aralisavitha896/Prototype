<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$batch = $_REQUEST['hide_id'];

$sql="DELETE FROM STEP_LOGS WHERE Step_ID = '$batch'";
$res = sqlsrv_query($conn, $sql); 
 echo json_encode($res); 

}

?>

