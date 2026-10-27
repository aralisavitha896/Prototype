<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$Suit_ID = $_REQUEST['hide_id'];

$sql="SELECT Suit_Name FROM SUIT_DETAILS WHERE Suit_ID = '$Suit_ID'";
$res = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($res);
$Suit_Name = $row['Suit_Name'];

$sql="DELETE FROM SUIT_DETAILS WHERE Suit_ID = '$Suit_ID'";
$res = sqlsrv_query($conn, $sql);

$sql="DELETE FROM SUIT_DETAILS WHERE Refid = '$Suit_ID'";
$res = sqlsrv_query($conn, $sql); 



log_activity("Suit($Suit_Name) details  deleted");
$message = $res ? "Suit($Suit_Name) details deleted successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);
}

?>

