<?php  include('../includes/config.php'); 

if(isset($_REQUEST['seqname']))
{
$seqname = $_REQUEST['seqname'];
$batch = $_REQUEST['batch'];
$Vessel = $_REQUEST['Vessel_ID'];
$seqtype = $_REQUEST['seqtype'];
$group_id = $_REQUEST['group_id'];
$compno = $_REQUEST['compno'];

 $sql="INSERT INTO SEQUENCE_NUMBER_DETAIL (Batch_Type,Seqname,Vessel_ID,seqtype,group_id,compno) VALUES('$batch','$seqname','$Vessel','$seqtype','$group_id','$compno')";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);

log_activity("New sequence details added");
$message = $res ? "New sequence details added)":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);	
}

?>

