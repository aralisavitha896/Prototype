<?php  include('../includes/config.php'); 

if(isset($_REQUEST['editid']))
{
$setparamorder = $_REQUEST['setparamorder'];
$editid = $_REQUEST['editid'];


$sql="UPDATE PARAMETERS_DETAILS set Param_Order = '$setparamorder' WHERE Parameter_ID = '$editid'";
$res= sqlsrv_query($conn, $sql);
echo json_encode($res);
}

?>

