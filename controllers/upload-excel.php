<?php  include('../includes/config.php');
error_reporting(E_ALL);
require_once ('../excel/src/SimpleXLSX.php'); 
if(isset($_POST["Sequence_ID"])){
		$Sequence_ID = $_POST["Sequence_ID"];
    if ( $xlsx = SimpleXLSX::parse($_FILES["file"]["tmp_name"]) ) {
	    foreach($xlsx->rows()  as $row){
		 	$duplicates= "SELECT Step_No FROM STEP_LOGS WHERE Step_No = '$row[0]' AND Sequence_ID = '$Sequence_ID'";
			$resdup= sqlsrv_query($conn, $duplicates);
			$rowdup = sqlsrv_fetch_array($resdup);
			$row_count = count($rowdup['Step_No']);	
			if($row_count <= 0){ 
	            $sql = "INSERT into STEP_LOGS(Step_No,Step_Tag, Sequence_ID)values('$row[0]','$row[1]','$Sequence_ID')";
	            $result = sqlsrv_query( $conn, $sql);
			} 
	    }   
    }else{
	   echo $error = SimpleXLSX::parseError();
    }
$sql="SELECT Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$Sequence_ID'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array( $res);
$Sequence_Name = $row['Seqname'];


log_activity("Step logs uploaded for sequence ($Sequence_Name)");
$message = $res ? "Step logs uploaded successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);
}			 
	

?>

