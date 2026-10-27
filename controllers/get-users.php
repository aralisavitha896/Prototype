<?php  include('../includes/config.php'); 
if(isset($_REQUEST['id']))
{
  $id = $_REQUEST['id'];
  $query = "SELECT * FROM Users WHERE id = '$id'";  
  $result = mysqli_query($link, $query);  
  $row = mysqli_fetch_array($result);  
  $row['password'] = base64_decode( $row['password'] );  
  echo json_encode($row);  
 
 
 
}

?>

