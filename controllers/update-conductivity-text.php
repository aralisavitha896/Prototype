<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$text = $_REQUEST['text'];

$sql="UPDATE CONDUCTIVITY_DETAILS SET Text = '$text' WHERE ID ='$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}


?>

