<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$sensor_unit = $_REQUEST['sensor_unit'];


$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Unit = '$sensor_unit' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

