<?php  include('../includes/config.php'); 

if(isset($_REQUEST['dbid']))
{
$dbid = $_REQUEST['dbid'];

$vessel = $_REQUEST['vessel'];	
$sequence = $_REQUEST['sequence'];
$selection	= $_REQUEST['selection']; //Summary TYpe
$pht_alowable= $_REQUEST['pht_alowable1']; //Allowable_Drop
$result_type=$_REQUEST['result_type']; //Result_Count
$initial1 = $_REQUEST['initial1'];
$initial2= $_REQUEST['initial2'];
$final2 = $_REQUEST['final2'];
$final1= $_REQUEST['final1'];
$con_tag= $_REQUEST['con_tag'];
  //f0

$z= $_REQUEST['z'];
$tem= $_REQUEST['tem'];
$max_min= isset($_REQUEST['res'])?1:0;

$stepno = $_REQUEST['stepno'];
$sensor1 = $_REQUEST['Sensor1'];
$sensor2 = $_REQUEST['Sensor2'];
$sensor3 = $_REQUEST['Sensor3'];
$sensor4 = $_REQUEST['Sensor4'];
$timedur = $_REQUEST['timedur'];
//$result_type = $_REQUEST['f0result_type'];


  $sql="UPDATE SUMMARY_DETAILS SET Vessel_ID = '$vessel',  Sequence_ID = '$sequence', Summary_Type='$selection',Allowable_Drop='$pht_alowable',Result_Count='$result_type',Final_1='$final1',Final_2='$final2',Initial_1='$initial1',Initial_2='$initial2',Sensor4='$sensor4',Sensor3='$sensor3',Sensor2='$sensor2',Sensor1='$sensor1',Step_no='$stepno',Temperature='$tem',z='$z',Result_for='$max_min',timedur='$timedur',con_tag='$con_tag' WHERE ID = '$dbid'";  
$res= sqlsrv_query($conn, $sql);


$sql="SELECT Vessel_Name FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Vessel_Name = $row['Vessel_Name'];

$sql="SELECT Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$sequence'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Seqname'];

log_activity("Summary details updated for sequence($Sequence_Name) - vessel ($Vessel_Name)");
$message = $res ? "Summary details successfully updated for sequence($Sequence_Name) - vessel ($Vessel_Name)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);	
}

?>