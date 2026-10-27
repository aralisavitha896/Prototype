<?php  include('../includes/config.php'); 

if(isset($_REQUEST['Vessel_ID']))
{
$Vessel_ID = $_REQUEST['Vessel_ID'];

$sql="SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
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

