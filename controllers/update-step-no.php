<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid'])){
	
$editid = $_REQUEST['editid'];
$stepno = $_REQUEST['stepno'];

	$sql="UPDATE STEP_LOGS SET Step_No = '$stepno' WHERE Step_ID ='$editid'";
	$res= sqlsrv_query($conn, $sql);
	echo json_encode($res);
}


?>

