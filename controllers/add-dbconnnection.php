<?php  include('../includes/config.php'); 
if(isset($_REQUEST['dbtype']))
	{
		$dbtype= $_REQUEST['dbtype'];	
		$server= base64_encode($_REQUEST['server']);	
		$db= $_REQUEST['db'];	
		$dbid= $_REQUEST['dbid'];	
		$format= $_REQUEST['format'];
		$utctime= $_REQUEST['utctime'];
		$rserver= base64_encode($_REQUEST['rserver']);
		$rphserver= base64_encode($_REQUEST['rphserver']);
		$rtype= $_REQUEST['rtype'];
		$suitstatus= $_REQUEST['suitstatus'];
		$procode= $_REQUEST['procode'];
		$timezone= $_REQUEST['timezone'];
		$timevalue= $_REQUEST['timevalue'];
		
		$sqlcount = "SELECT dbtype FROM Config_Details WHERE dbtype = '$dbtype'";
		$rescount = mysqli_query($link, $sqlcount);
		$rowcount= mysqli_num_rows($rescount);
		
		if($rowcount <= 0){
            $sqlins = "INSERT INTO Config_Details(dbtype, server, db,db_active,format,utctime,rserver,rtype,rphserver,suitstatus,procode,timevalue,timezone) VALUES ('$dbtype', '$server', '$db' ,1,'$format','$utctime','$rserver','$rtype','$rphserver','$suitstatus','$procode','$timevalue','$timezone')";
	        $res = mysqli_query($link, $sqlins);
		}
	    else
		{
		    $sqlu="UPDATE Config_Details SET dbtype = '$dbtype',  server = '$server', db= '$db',format='$format',utctime='$utctime',rserver='$rserver',rtype='$rtype',rphserver='$rphserver',suitstatus='$suitstatus',procode='$procode',timezone='$timezone',timevalue='$timevalue'  WHERE id = '$dbid'";
			$res = mysqli_query($link, $sqlu);
		}
		log_activity("Db connection  updated");
		$message = $res ? "DB connection updated successfully":"Something went wrong..!";
		$status = $res ? TRUE:FALSE;
		set_session($message,$status);
}
?>