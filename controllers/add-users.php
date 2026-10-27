<?php  include('../includes/config.php'); 
if(isset($_REQUEST['username'])){	
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$usertype = $_REQUEST['usertype'];
	$menu = isset($_REQUEST['menu'])? implode(",",$_REQUEST['menu']):'';
	$lmenu = isset($_REQUEST['lmenu'])? implode(",",$_REQUEST['lmenu']):'';
	$sensorselection = isset($_REQUEST['sensorselection'])?$_REQUEST['sensorselection']:false;
	$intervalselection = isset($_REQUEST['interval'])?$_REQUEST['interval']:false;

    $sqlins = "INSERT INTO Users (username,password,usertype,menu,lmenu,sensorselection,intervalselection) VALUES ('$username', '".base64_encode($password)."','$usertype','$menu','$lmenu','$sensorselection','$intervalselection')";
	$res = mysqli_query($link, $sqlins);
	
	log_activity("New user ($username) added");
	$message = $res ? "New user added successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
	set_session($message,$status);
}
header("Location:".$baseurl."user-management.php");
?>
