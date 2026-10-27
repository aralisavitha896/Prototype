<?php  include('../includes/config.php'); 

if(isset($_REQUEST['id']))
{
$id = $_REQUEST['id'];
$sql="SELECT * FROM REPORT_LAYOUT WHERE Layout_ID = '$id'";
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

