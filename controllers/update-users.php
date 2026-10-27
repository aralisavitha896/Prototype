<?php  include('../includes/config.php'); 
if(isset($_REQUEST['editid'])){	
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$editid = $_REQUEST['editid'];
    $usertype = $_REQUEST['usertype'];
	$menu = isset($_REQUEST['menu'])? implode(",",$_REQUEST['menu']):'';
	$lmenu = isset($_REQUEST['lmenu'])? implode(",",$_REQUEST['lmenu']):'';
	$sensorselection = isset($_REQUEST['sensorselection'])? $_REQUEST['sensorselection']:false;
	$intervalselection = isset($_REQUEST['interval'])? $_REQUEST['interval']:false;
	
	if($password)
    $sqlins = "UPDATE Users SET username = '$username',password='".base64_encode($password)."',usertype='$usertype',menu='$menu',lmenu='$lmenu',sensorselection='$sensorselection',intervalselection='$intervalselection' WHERE id='$editid'";
    else
    $sqlins = "UPDATE Users SET username = '$username',usertype='$usertype',menu='$menu',lmenu='$lmenu',sensorselection='$sensorselection',intervalselection='$intervalselection' WHERE id='$editid'";		
	$res = mysqli_query($link, $sqlins);
	
	log_activity("user ($username) details updated");
	$message = $res ? "User ($username) details updated successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
	set_session($message,$status);

}
header("Location:".$baseurl."user-management.php");
?>
