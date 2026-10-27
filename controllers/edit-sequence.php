<?php  include('../includes/config.php'); 

if(isset($_REQUEST['batch']))
{
$batch = $_REQUEST['batch'];
$sql="SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$batch'";
$res = sqlsrv_query($conn, $sql); 
$result = array(); 
do {
    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){   
    $result[] = $row;  
    }	
} while (sqlsrv_next_result($res));
  sqlsrv_free_stmt($res);

 echo json_encode($result); 

}

?>

