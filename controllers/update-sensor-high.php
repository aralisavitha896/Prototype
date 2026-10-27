<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$sensor_high = $_REQUEST['sensor_high'];


$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_highvalue = '$sensor_high' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

