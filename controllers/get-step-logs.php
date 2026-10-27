<?php  include('../includes/config.php'); 

if(isset($_REQUEST['batch']))
{
$batch = $_REQUEST['batch'];
$limit = $_REQUEST['limit'];

$sql="SELECT TOP $limit * FROM STEP_LOGS WHERE seq_id = '$batch'";
$res = sqlsrv_query($conn, $sql);
$html=''; 
while($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){
 $html= $html.'<div class="row"><div class="col-md-2"><input class="form-control"  type="number" value="'.$row["Step_No"].'"></div><div class="col-md-10"><input class="form-control" type="text" value="'.$row["Step_Tag"].'"></div></div>';
}

echo $html; 

}

?>