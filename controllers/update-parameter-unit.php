<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$param_unit = $_REQUEST['param_unit'];

$sql="UPDATE PARAMETERS_DETAILS SET Units = '$param_unit' WHERE Parameter_ID ='$editid'";
$res= sqlsrv_query($conn, $sql);

$sql="SELECT Param_Name FROM PARAMETERS_DETAILS WHERE Parameter_ID = '$editid'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Param_Name = $row['Param_Name'];

log_activity("Parameter($Param_Name) unit updated");	
}


?>

