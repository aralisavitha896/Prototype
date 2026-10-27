<?php  include('../includes/config.php'); 
if(isset($_REQUEST['parameter']))
{
$sequence = $_REQUEST['sequence'];
$sensors = $_REQUEST['parameter'];
}
$selectedsensors = implode(',', $sensors);
$sql="UPDATE SEQUENCE_NUMBER_DETAIL SET Sensor_Selection = '$selectedsensors' WHERE ID = '$sequence'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);

?>

