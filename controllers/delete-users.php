 <?php  include('../includes/config.php'); 

if(isset($_REQUEST['hide_id']))
{
$id = $_REQUEST['hide_id'];
$sql="SELECT username FROM Users WHERE id = '$id'";
$res = mysqli_query($link, $sql);
$row  = mysqli_fetch_array($res);
$username  = $row['username'];


$sql="DELETE FROM Users WHERE id = '$id'";
$res = mysqli_query($link, $sql); 
echo json_encode($res); 

log_activity("user($username) details  deleted");
$message = $res ? "user($username) details deleted successfully":"Something went wrong..!";
$status = $res ? TRUE:FALSE;
set_session($message,$status);

}

?>

