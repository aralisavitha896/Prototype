<?php  include('../includes/config.php'); 
if(isset($_REQUEST['vessel_id']))
{
    $vessel_id = $_REQUEST['vessel_id'];
		//for online data
		$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel_id'";
		$resdb= sqlsrv_query($conn, $sqldb);
		$rowdb = sqlsrv_fetch_array($resdb);
		$Online_msgno = $rowdb['Online_msgno'];
//for identifying sequene end or not
date_default_timezone_set("Asia/Kolkata");
$mydatetime=date("Y-m-d H:i:s.u");
$mydatetime_new = new DateTime($mydatetime);
$mydatetime_new->modify("-$utchour hour -$utcminutes minutes");
$my_startime= $mydatetime_new->format("Y-m-d H:i:s"); 
$newdates=date("Y-m-d H:i:s", strtotime($my_startime) - 60);
	   $stepsl="SELECT TOP 1 VariantValue FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(360),''".$newdates."'',''''')WHERE VariantValue !='' and VariantValue !='system idle' order by Timestamp desc"; 
		 $rstepsl=sqlsrv_query($conn, $stepsl);
		 $rowstplg = sqlsrv_fetch_array($rstepsl,SQLSRV_FETCH_ASSOC);
		  $current_value= $rowstplg['VariantValue'];

if($current_value > 0){ 
		$sqldatabase= "SELECT TOP 1 name, database_id, create_date FROM sys.databases where name LIKE '%IPC8210_SCADA#8210_ALG%' ORDER BY create_date";
		$resdatabase= sqlsrv_query($conn, $sqldatabase);			
		$rowdatabase = sqlsrv_fetch_array($resdatabase,SQLSRV_FETCH_ASSOC);
		$dbname= $rowdatabase["name"];
	 //urlencode($dbname)

       $sql = "SELECT TOP 1 MsgNr,DateTime,PText6,Text2,Ptext5,PText3 FROM [$dbname].[dbo].[AlgViewEx] WHERE  MsgNr = '$Online_msgno' AND State=1 ORDER BY DateTime DESC";  
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





	
		//$rows = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC);
		//$batchtype=$rows['PText6'];
		//$rows['DateTime']->modify("-$utchour hour -$utcminutes minutes"); 
		//$Online_srt_dt= $rows['DateTime']->format("Y-m-d H:i:s.u");
		//$Online_end_dt = date("Y-m-d H:i:s.u",strtotime("-$utchour hour -$utcminutes minutes"));
		 //echo $srt_dt= $rows['DateTime']->format($dateformat);
		 //$virtual_end=date("Y-m-d H:i:s");		
		//for online data	   
}

?>