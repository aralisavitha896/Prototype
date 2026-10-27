<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$legendcolor = $_REQUEST['legendcolor'];


$sql="UPDATE SENSORS_HIGHLIMITS SET Legend_Color = '$legendcolor' WHERE Sensor_ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

