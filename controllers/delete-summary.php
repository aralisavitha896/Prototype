<?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];
/*
$sql="SELECT Vessel_ID,Sequence_ID FROM SUMMARY_DETAILS WHERE Summary_ID = '$id'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$vessel = $row['Vessel_ID'];
$sequence = $row['sequence'];
*/
$sql="DELETE FROM SUMMARY_DETAILS WHERE ID = '$id'";
$res = sqlsrv_query($conn, $sql); 
/*
$sql="SELECT Vessel_Name FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Vessel_Name = $row['Vessel_Name'];

$sql="SELECT Sequence_Name FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$sequence'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Sequence_Name'];

log_activity("summary deleted for sequence($Sequence_Name) - vessel ($Vessel_Name)");
$message = $res ? "summary successfully deleted for sequence($Sequence_Name) - vessel ($Vessel_Name)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status); 
*/
}

?>

