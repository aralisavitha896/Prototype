<?php  include('../includes/config.php'); 

if ($_POST && isset($_POST['value'])) {

	$value = $_POST['value'];
	
	
$sql="SELECT * FROM SENSORS_HIGHLIMITS WHERE ID = '$value'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array($res); 
	if($row['Sensor_Selected'] == 1)
	{
		$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Selected= '0' WHERE ID = '$value'";
		$res= sqlsrv_query($conn, $sql);
		echo json_encode($res);
	}
	else
	{
		$sql="UPDATE SENSORS_HIGHLIMITS SET Sensor_Selected= '1' WHERE ID = '$value'";
		$res= sqlsrv_query($conn, $sql);
		echo json_encode($res);
	}		
}

?>

