<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];

$sql="DELETE FROM Config_details WHERE id = '$id'";
$res = mysqli_query($link, $sql); 
echo json_encode($res); 

}

?>

