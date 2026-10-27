<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$tagdecimal = $_REQUEST['tagdecimal'];

$sql="UPDATE SENSORS_HIGHLIMITS SET Tag_Decimal = '$tagdecimal' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

