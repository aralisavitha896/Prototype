<?php  include('../includes/config.php'); 
if(isset($_REQUEST['sequence']))
{
    $sequence = $_REQUEST['sequence'];
  	$sql= "SELECT Step_No,Step_Tag FROM STEP_LOGS WHERE Sequence_ID='$sequence'";
	$seqq= sqlsrv_query($conn, $sql);
	$html = '<option value="">Select</option>';
    while($row = sqlsrv_fetch_array($seqq,SQLSRV_FETCH_ASSOC)){
		$html .= "<option value='".$row['Step_No']."'>".$row['Step_Tag']."</option>";
	}
    echo $html;  
}

?>