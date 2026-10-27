<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];
$vessel = $_REQUEST['vessel'];
$batch = $_REQUEST['batch'];
$starttime = $_REQUEST['start'];
$endtime = $_REQUEST['end'];


	$sqlsequence = "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Batch_Type = '$batch'";
	$ressequence = sqlsrv_query($conn, $sqlsequence);
	$rowsequence = sqlsrv_fetch_array($ressequence,SQLSRV_FETCH_ASSOC);
	$sequence = $rowsequence['ID'];
	$seqtype = $rowsequence['seqtype'];
	$sequence_name = $rowsequence['Seqname'];
			
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb,SQLSRV_FETCH_ASSOC);
	$vessel_name = $rowdb['Vessel_Name'];
	$Vessel_Code = $rowdb['Vessel_Code'];
	
	if ($seqtype == "1") {
	$vcode= str_replace("O",0,$Vessel_Code);
	$table = "SubRptFloat_".$vcode;
	$tablemain2 = "SubRptString_".$vcode;
	$tableindex = "SubRptIndex_".$vcode;
	$datatable="SensorFloat_".$vcode;	
}
else {
	
	$vcode= str_replace("O",0,$Vessel_Code);
	$table = "MainRptFloat_".$vcode;
	$tablemain2 = "MainRptString_".$vcode;
	$tableindex = "MainRptIndex_".$vcode;
	$datatable="SensorFloat_".$vcode;
}
	
$sqldel="DELETE FROM $table WHERE Millitm = '$id'";
$resdel = sqlsrv_query($conn, $sqldel); 
$sql="DELETE FROM $tablemain2 WHERE Millitm = '$id'";
$res = sqlsrv_query($conn, $sql);

$sqlstart = "DELETE from $datatable where (DateAndTime between '$starttime' and '$endtime')"; 
$resstart = sqlsrv_query($conn, $sqlstart);


	
log_activity("Report details deleted for sequence($sequence_name) of ($vessel_name)");
$message = $res ? "Report details deleted for sequence($sequence_name) of ($vessel_name)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status); 

}

?>

