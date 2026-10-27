
<?php  include('../includes/config.php'); 

if ($_POST && isset($_POST['value'])) {

	$value = $_POST['value'];
	
	
$sql="SELECT * FROM [PARAMETERS_DETAILS] WHERE Parameter_ID = '$value'";
$res = sqlsrv_query($conn, $sql); 
$row = sqlsrv_fetch_array($res); 
	if($row['checkparam'] == 1)
	{
		$sql="UPDATE PARAMETERS_DETAILS SET checkparam= '0' WHERE Parameter_ID = '$value'";
		$res= sqlsrv_query($conn, $sql);
		echo json_encode($res);
	}
	else
	{
		$sql="UPDATE PARAMETERS_DETAILS SET checkparam= '1' WHERE Parameter_ID = '$value'";
		$res= sqlsrv_query($conn, $sql);
		echo json_encode($res);
	}		
}

?>


