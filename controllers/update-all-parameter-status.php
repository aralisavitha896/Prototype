<?php  include('../includes/config.php'); 
if ($_POST && isset($_POST['checked'])) {
$checked = $_POST['checked'];
$seqid = $_POST['seqid'];
if($checked != 'false'){
$sql="UPDATE PARAMETERS_DETAILS SET Check_Param= '1' WHERE Sequence_ID = '$seqid'";	
$selected = "selected";
}else{
$sql="UPDATE PARAMETERS_DETAILS SET Check_Param= '0' WHERE Sequence_ID = '$seqid'";	
$selected = "unselected";
}
$res= sqlsrv_query($conn, $sql);

$sql="SELECT Sequence_Name FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$seqid'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Sequence_Name'];

log_activity("All Parameters of sequence($Sequence_Name) $selected");
echo json_encode($res);
}
?>

