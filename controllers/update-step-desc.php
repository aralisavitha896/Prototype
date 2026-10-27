<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$stepdesc = $_REQUEST['stepdesc'];

	$sql="UPDATE STEP_LOGS SET Step_Tag = '$stepdesc' WHERE Step_ID ='$editid'";
	$res= sqlsrv_query($conn, $sql);
	echo json_encode($res);
}


?>

