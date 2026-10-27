<?php  include('../includes/config.php'); 
if(isset($_REQUEST['vessel_id']))
{
    $vessel = $_REQUEST['vessel_id'];
	$batch_id = $_REQUEST['batch_id'];
	
	$sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID ='$vessel' AND Batch_Type = '$batch_id'";
	$seqq= sqlsrv_query($conn, $sql);
	while($rows = sqlsrv_fetch_array($seqq,SQLSRV_FETCH_ASSOC)) {
		$batch=$rows['Batch_Type'];
	}
	
	$a=0;
	$sqldatabase= "SELECT name FROM master.sys.databases WHERE name LIKE '%ALG%' ORDER BY create_date";
    $resdatabase= sqlsrv_query($conn, $sqldatabase);			
    while($rowdatabase = sqlsrv_fetch_array($resdatabase,SQLSRV_FETCH_ASSOC)){ 
		$dbname= $rowdatabase["name"];
		  $sqls= "SELECT * FROM [$dbname].[dbo].[AlgViewEx] WHERE  Class = 11 AND PText6= '$batch' AND State = 1 AND MsgNr != 5010 ORDER BY PText7 DESC ";
		$res= sqlsrv_query($conn, $sqls);
		$html = '';	
		while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
			$z=urlencode($row['DateTime']->format("Y-m-d H:i:s"));
			
			$a++;
			
			$html .= "<tr><td><input type='checkbox' class='mybacthes'  name='batches[]' id='batches".$a."'  value='".urlencode($dbname)."' rel='".$z."'></td><td>".date($dateformat,strtotime($row['PText7']))."</td><td>". date($dateformat,strtotime($row['PText8']))."</td><td>".$row['PText2']."</td><td>".$row['PText6']."</td></tr>";		
		}
		echo $html; 
	} 
      
}

?>