<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$param_tag = $_REQUEST['param_tag'];

$sql="UPDATE PARAMETERS_DETAILS SET Parmaters_Tagname = '$param_tag' WHERE Parameter_ID ='$editid'";
$res= sqlsrv_query($conn, $sql);

$sql="SELECT Param_Name FROM PARAMETERS_DETAILS WHERE Parameter_ID = '$editid'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Param_Name = $row['Param_Name'];

log_activity("Parameter($Param_Name) tag updated");	

}


?>

