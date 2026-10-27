<?php  include('../includes/config.php'); 
if ($_POST && isset($_POST['checked'])) {
$checked = $_POST['checked'];
if($checked != 'false'){
$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Selected= '1'";	
}else{
$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Selected= '0'";	
}
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}
?>

