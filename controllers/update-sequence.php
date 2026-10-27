<?php  include('../includes/config.php'); 

if(isset($_REQUEST['seqname']))
{
$seqname = $_REQUEST['seqname'];
$batch = $_REQUEST['batch'];
$Vessel = $_REQUEST['Vessel_ID'];
$book_id = $_REQUEST['Sequence_ID'];
$seqtype = $_REQUEST['seqtype'];
$group_id = $_REQUEST['group_id'];
$compno = $_REQUEST['compno'];

 $sql="UPDATE SEQUENCE_NUMBER_DETAIL set Seqname = '$seqname', Vessel_ID= '$Vessel',seqtype= '$seqtype', Batch_Type = '$batch', group_id='$group_id',compno='$compno' WHERE ID = '$book_id'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
log_activity("Sequence details updated");
$message = $res ? "Sequence details updated":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);
}

?>

