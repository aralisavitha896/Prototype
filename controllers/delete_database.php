<?php  include('../includes/config.php'); 

if(isset($_REQUEST['starttimeclear']))
{
	 $starttimeclear = $_REQUEST['starttimeclear'];
	 $endtimeclear = $_REQUEST['endtimeclear'];
	 $vessel = $_REQUEST['vesselclear'];
	 
			
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb,SQLSRV_FETCH_ASSOC);
	$vessel_name = $rowdb['Vessel_Name'];
	$Vessel_Code = $rowdb['Vessel_Code'];
	

	$vcode= str_replace("O",0,$Vessel_Code);
	$table = "SubRptFloat_".$vcode;
	$tablemain2 = "SubRptString_".$vcode;
	//$tableindex = "SubRptIndex_".$vcode;
	$datatable="SensorFloat_".$vcode;	

	$table2 = "MainRptFloat_".$vcode;
	$tablemain22 = "MainRptString_".$vcode;
	//$tableindex2 = "MainRptIndex_".$vcode;
	#$datatable2="SensorFloat_".$vcode;

 $sqldel="DELETE FROM $table where DateAndTime between '$starttimeclear' and '$endtimeclear'";
$resdel = sqlsrv_query($conn, $sqldel); 
 $sqldel1="DELETE FROM $table2 where DateAndTime between '$starttimeclear' and '$endtimeclear'";
$resdel1 = sqlsrv_query($conn, $sqldel1); 
 $sqldel2="DELETE FROM $tablemain2 where DateAndTime between '$starttimeclear' and '$endtimeclear'";
$resdel2 = sqlsrv_query($conn, $sqldel2); 
 $sqldel3="DELETE FROM $tablemain22 where DateAndTime between '$starttimeclear' and '$endtimeclear'";
$resdel3 = sqlsrv_query($conn, $sqldel3); 
 $sqldel4="DELETE FROM $datatable where DateAndTime between '$starttimeclear' and '$endtimeclear'";
$resdel4 = sqlsrv_query($conn, $sqldel4); 
 


/*	
$sqldel="DELETE FROM $table WHERE Millitm = '$id'";
$resdel = sqlsrv_query($conn, $sqldel); 
$sql="DELETE FROM $cccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc WHERE Millitm = '$id'";
$res = sqlsrv_query($conn, $sql);

$sqlstart = "DELETE from $datatable where (DateAndTime between '$starttime' and '$endtime')"; 
$resstart = sqlsrv_query($conn, $sqlstart);


	
log_activity("Report details deleted for sequence($sequence_name) of ($vessel_name)");
$message = $res ? "Report details deleted for sequence($sequence_name) of ($vessel_name)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status); */

}

?>

