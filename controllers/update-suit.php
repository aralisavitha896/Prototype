<?php  include('../includes/config.php'); 
if(isset($_REQUEST['Suit_ID']))
{	
$Suit_ID = $_REQUEST['Suit_ID'];	
$Suit_Name = $_REQUEST['Suit_Name'];	
$Suit_Order = $_REQUEST['Suit_Order'];

$sql="UPDATE SUIT_DETAILS SET Suit_Name= '$Suit_Name', Suit_Order = '$Suit_Order' WHERE Suit_ID = '$Suit_ID'";
$res= sqlsrv_query($conn, $sql);

log_activity("Suit($Suit_Name) details  updated");
$message = $res ? "Suit($Suit_Name) details updated successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);
}

?>

