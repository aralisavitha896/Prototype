<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$Parameter_ID = $_REQUEST['hide_id'];
$sql="SELECT Param_Name FROM PARAMETERS_DETAILS WHERE Parameter_ID = '$Parameter_ID'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Param_Name = $row['Param_Name'];

$sql="DELETE FROM PARAMETERS_DETAILS WHERE Parameter_ID = '$Parameter_ID'";
$res = sqlsrv_query($conn, $sql); 
 
log_activity("Parameter($Param_Name) deleted");
}

?>

