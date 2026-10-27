<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$hide_id = $_REQUEST['hide_id'];

$sql="DELETE FROM CONDUCTIVITY_DETAILS WHERE ID = '$hide_id'";
$res = sqlsrv_query($conn, $sql); 
 echo json_encode($res); 

}

?>

