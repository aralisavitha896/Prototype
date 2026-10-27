<?php  
include('../includes/config.php'); 
if(isset($_REQUEST['id']))
{
  $id = $_REQUEST['id'];
  $query = "SELECT * FROM [CUSTOMER_DATA] WHERE [Id] = '$id'";  
  $result = sqlsrv_query($conn, $query);  
  $rows = sqlsrv_fetch_array($result); 
  echo json_encode($rows);  
}	

?>