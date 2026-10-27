<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$Batch_report_id = $_REQUEST['Batch_report_id'];

$sql="UPDATE SEQUENCE_NUMBER_DETAIL SET Batch_report_id = '$Batch_report_id' WHERE Sequence_ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
	
$sql="SELECT Sequence_Name FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$editid'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Sequence_Name'];

log_activity("Parameter($Sequence_Name) Batch report id updated");
}


?>

