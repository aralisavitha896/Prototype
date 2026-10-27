<?php  include('../includes/config.php'); 

if(isset($_REQUEST['Suit_Name']))
{
$Suit_Name = $_REQUEST['Suit_Name'];	
$Suit_Order = $_REQUEST['Suit_Order'];	
$refid = $_REQUEST['refid'];

$sql="INSERT INTO SUIT_DETAILS (Suit_Name,Suit_Order,Refid) VALUES('$Suit_Name','$Suit_Order','$refid')";
$res= sqlsrv_query($conn, $sql);

log_activity("Suit($Suit_Name) details added");
$message = $res ? "Suit($Suit_Name) details added successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);
}

?>

