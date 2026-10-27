<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$tag = $_REQUEST['tag'];

$sql="UPDATE CONDUCTIVITY_DETAILS SET Tag = '$tag' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

