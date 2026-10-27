<?php  include('../includes/config.php'); 
if(isset($_REQUEST['vessel']))
{
    $vessel = $_REQUEST['vessel'];
    $legendcolor = $_REQUEST['legendcolor'];
  	$sql= "SELECT Count(*) AS count FROM SENSORS_HIGHLIMITS WHERE Legend_Color='$legendcolor' AND Vessel_ID ='$vessel'";
	$seqq= sqlsrv_query($conn, $sql);
	$row = sqlsrv_fetch_array($seqq);
    echo $row['count'];  
}

?>