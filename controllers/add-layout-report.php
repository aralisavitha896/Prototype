<?php  include('../includes/config.php'); 
if(isset($_REQUEST['name'])){	
	$name = $_REQUEST['name'];
	$field = $_REQUEST['field'];
	$order = $_REQUEST['order'];
	$type = $_REQUEST['type'];
	$page = $_REQUEST['page'];

    $sqlins = "INSERT INTO REPORT_LAYOUT (Name,Field,Sort,Type,Page) VALUES ('$name','$field','$order','$type','$page')";
	$res = sqlsrv_query($conn, $sqlins);
	
	log_activity("New layout field($name)added to ($page)");
	$message = $res ? "New layout field($name)added to ($page)":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
	set_session($message,$status);
}
?>
