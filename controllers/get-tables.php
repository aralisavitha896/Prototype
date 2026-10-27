<?php  include('../includes/config.php'); 

$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE' AND TABLE_NAME LIKE '%UA#%' ORDER BY TABLE_NAME ASC";
$res = sqlsrv_query($conn, $sql); 
$result = array(); 
do {
    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){   
    $result[] = $row;  
    }	
} while (sqlsrv_next_result($res));
  sqlsrv_free_stmt($res);
  echo json_encode($result); 



?>

