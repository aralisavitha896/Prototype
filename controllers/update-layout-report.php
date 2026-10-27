<?php  include('../includes/config.php'); 
if(isset($_REQUEST['edit_id'])){	
	$edit_id = $_REQUEST['edit_id'];
	$name = $_REQUEST['name'];
	$field = $_REQUEST['field'];
	$order = $_REQUEST['order'];
	$type = $_REQUEST['type'];
	$page = $_REQUEST['page'];

    $sqlins = "UPDATE REPORT_LAYOUT SET Name = '$name',Field='$field',Sort= '$order',Type='$type',Page='$page' WHERE Layout_ID = '$edit_id'";
	$res = sqlsrv_query($conn, $sqlins);

	log_activity("Layout management field($name) of page($page) updated");
	$message = $res ? "Layout management field($name) of page($page) updated":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
	set_session($message,$status);
}
?>
