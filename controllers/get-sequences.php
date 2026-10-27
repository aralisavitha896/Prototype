<?php  include('../includes/config.php'); 
if(isset($_REQUEST['vessel_id']))
{
    $vessel_id = $_REQUEST['vessel_id'];
  	$sql= "SELECT ID,Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID='$vessel_id'";
	$seqq= sqlsrv_query($conn, $sql);
	$seqq_arr = array();
	$html = '<option value="">Select</option>';
    while($row = sqlsrv_fetch_array($seqq)){
		$html .= "<option value='".$row['ID']."'>".$row['Seqname']."</option>";
	}
    echo $html;  
}

?>