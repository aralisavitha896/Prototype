<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$Vessel_ID = $_REQUEST['hide_id'];

//VESSEL DETAILS DELETE
$sql="DELETE FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
$res = sqlsrv_query($conn, $sql);
 
log_activity("Vessel details  deleted");
$message = $res ? "Vessel details deleted successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status); 

}

?>

