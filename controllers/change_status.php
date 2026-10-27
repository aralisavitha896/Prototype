<?php  include('../includes/config.php'); 

if(isset($_REQUEST['id']))
{
$id = $_REQUEST['id'];

$sqlcount = "SELECT db_active FROM Config_details WHERE id = '$id'";
$rescount = mysqli_query($link, $sqlcount);
$rowcount=mysqli_fetch_array($rescount);
	
$status = $rowcount['db_active'];
if($status == 1){
	$sqlu="UPDATE Config_details set db_active = '0' WHERE id = '$id'";
	mysqli_query($link, $sqlu);
}
else 
{
	$sqlu="UPDATE Config_details set db_active = '1' WHERE id = '$id'";
	mysqli_query($link, $sqlu);
}

}

?>

