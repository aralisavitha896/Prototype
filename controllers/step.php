
<?php  include('../includes/config.php'); 

if(isset($_REQUEST['sequence']))
{
$sequence = $_REQUEST['sequence'];

$sql="SELECT * FROM STEP_LOGS WHERE Sequence_ID = '$sequence'";
$res = sqlsrv_query($conn, $sql); 

    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){   
    	$Step_No = $row['Step_No'];
    	echo "<option value='$Step_No'>".$Step_No."</option>";
		}
}
?>



