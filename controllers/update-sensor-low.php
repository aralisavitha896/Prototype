<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$sensor_low = $_REQUEST['sensor_low'];


$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_lowvalue = '$sensor_low' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

