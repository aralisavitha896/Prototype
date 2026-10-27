<?php  include('../includes/config.php'); 
$res =true;
if(isset($_REQUEST['Sequence_ID'])){
$step_no = $_REQUEST['step_no'];
$step_desc = $_REQUEST['step_desc'];
$seq_id = $_REQUEST['Sequence_ID'];
$paramcount = count($step_no);
for($x = 0; $x < $paramcount; $x++){
	    if($step_no[$x] || $step_desc[$x]){
        $sql="INSERT INTO STEP_LOGS(Sequence_ID,Step_No,Step_Tag) VALUES('$seq_id','$step_no[$x]','$step_desc[$x]')";
		$res= sqlsrv_query($conn, $sql);
  }
		}
}

$sql="SELECT Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$seq_id'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Seqname'];


log_activity("Step logs added for sequence ($Sequence_Name)");
$message = $res ? "Something went wrong..!":"Step logs added successfully";
$status = $res ? FALSE:TRUE;
set_session($message,$status);
?>

