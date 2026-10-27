<?php  include('../includes/config.php'); 
if(isset($_REQUEST['id']))
{
$id = $_REQUEST['id'];
  $query = "SELECT * FROM config_details WHERE id = '$id'";  
  $result = mysqli_query($link, $query);  
  $row = mysqli_fetch_array($result); 
 // $row['server']= base64_decode($row['server']);  
  //$row['rserver']= base64_decode($row['rserver']);  
  echo json_encode($row);  
 
 
 
}

?>

