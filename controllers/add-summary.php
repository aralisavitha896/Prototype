<?php 
include('../includes/config.php'); 
$vessel= $_REQUEST['vessel'];	
$sequence= $_REQUEST['sequence'];
$selection= $_REQUEST['selection']; //Summary TYpe
$pht_alowable= $_REQUEST['pht_alowable1']; //Allowable_Drop
//$result_type = $_REQUEST['f0result_type']; //Result_for
$result_type= $_REQUEST['result_type']; //Result_Count
$initial1= $_REQUEST['initial1'];
$initial2= $_REQUEST['initial2'];
$sensor1 = $_REQUEST['Sensor1'];
$sensor2 = $_REQUEST['Sensor2'];
$sensor3 = $_REQUEST['Sensor3'];
$sensor4 = $_REQUEST['Sensor4'];
$stepno = $_REQUEST['stepno'];
$final2 = $_REQUEST['final2'];
$final1= $_REQUEST['final1'];
$con_tag= $_REQUEST['con_tag'];
$max_min= isset($_REQUEST['res'])?1:0;
$tem= $_REQUEST['tem'];
$z= $_REQUEST['z'];
$timedur= $_REQUEST['timedur'];


	  $sqlins = "INSERT INTO SUMMARY_DETAILS (Vessel_ID,Summary_Type,Sequence_ID,Initial_1,Initial_2,Final_1,Final_2,Allowable_Drop,Result_Count,Step_no,Sensor1,Sensor2,Sensor3,Sensor4,Temperature,z,Result_for,timedur,con_tag) VALUES ('$vessel', '$selection','$sequence', '$initial1','$initial2','$final1','$final2','$pht_alowable','$result_type','$stepno','$sensor1','$sensor2','$sensor3','$sensor4','$tem','$z','$max_min','$timedur','$con_tag')";
	if(sqlsrv_query($conn, $sqlins)){ echo "Data INSERTED";}else{echo "No insertion";}

$sql="SELECT Vessel_Name FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Vessel_Name = $row['Vessel_Name'];

$sql="SELECT Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$sequence'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Seqname'];


log_activity("New summary details added for sequence($Sequence_Name) - vessel ($Vessel_Name)");
$message = $res ? "New summary details successfully added for sequence($Sequence_Name) - vessel ($Vessel_Name)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);		
		
?>