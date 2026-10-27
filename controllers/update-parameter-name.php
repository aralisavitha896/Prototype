<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$param_name = $_REQUEST['param_name'];

$sql="UPDATE PARAMETERS_DETAILS SET Param_Name = '$param_name'WHERE Parameter_ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);

$sql="SELECT Param_Name FROM PARAMETERS_DETAILS WHERE Parameter_ID = '$editid'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Param_Name = $row['Param_Name'];

log_activity("Parameter($Param_Name) name updated");
}


?>

