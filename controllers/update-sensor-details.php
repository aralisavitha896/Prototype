<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$sensorname = $_REQUEST['sensorname'];

$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Name = '$sensorname' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

