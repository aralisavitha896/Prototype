 <?php
		include('../includes/config.php'); 	
		$connectionInfo = array(  "UID"=>"sa", "PWD"=>"Password1");
		$exp = explode("\\",$rserver);
		
		$rserverconn =sqlsrv_connect($rserver,$connectionInfo );
		$errors = sqlsrv_errors();
		$location = $_SERVER['HTTP_REFERER'];
		$localIP = getHostByName($exp[0]);
        $linkexternal = mysqli_connect($localIP, "biozeen", "Password1");	
		if($errors[0]['SQLSTATE'] == 28000){
			set_session($errors[0]['message'],FALSE);
			header("Location: $location");
			die;
		}

        $sqlfinddb = "SELECT name FROM master.sys.databases WHERE name LIKE '%R' AND name LIKE'CC_%'";
        $resfinddb = sqlsrv_query($rserverconn, $sqlfinddb); 
        $rowfinddb = sqlsrv_fetch_array($resfinddb,SQLSRV_FETCH_ASSOC);
		$rdbname= $rowfinddb['name'];
		
        if($rdbname ==''){
			set_session("Redundant database is not connecting..!",FALSE);
			header("Location: $location");
			die;
		}
		$connectionInfo = array( "Database"=>$rdbname, "UID"=>"sa", "PWD"=>"Password1");
        $conn1 =sqlsrv_connect($rserver,$connectionInfo);
        $tables = array("VESSEL_DETAILS","SEQUENCE_NUMBER_DETAIL","PARAMETERS_DETAILS","SENSORS_HIGHLIMITS","STEP_LOGS","SUMMARY_DETAILS","REPORT_LAYOUT","FOOTER_DETAILS","HEADER_DETAILS","CONDUCTIVITY_DETAILS","SUIT_DETAILS");
        foreach($tables as $table){
			$sqlexist = "IF OBJECT_ID (N'".$table."', N'U') IS NOT NULL SELECT 1 AS res ELSE SELECT 0 AS res;";
			$resexist= sqlsrv_query($conn1, $sqlexist);
			$rowexist = sqlsrv_fetch_array($resexist,SQLSRV_FETCH_ASSOC);
			if($rowexist['res'] == 1){
			$sqltruncate = "TRUNCATE TABLE $table";
			sqlsrv_query($conn1, $sqltruncate);
			}else{
			sqlsrv_query($conn1,$tablesquery[$table]);				
			}		
            $sqlidentityon= "SET IDENTITY_INSERT $table ON";
			sqlsrv_query($conn1, $sqlidentityon);
			
            $sqlget = "SELECT * FROM $table";
            $resget = sqlsrv_query($conn, $sqlget);
            while($rowget = sqlsrv_fetch_array($resget,SQLSRV_FETCH_ASSOC)){
				$sqlinsert = "INSERT INTO $table (".implode(", ",array_keys($rowget)).") VALUES ('".implode("', '",array_values($rowget))."')";
				$resinsert = sqlsrv_query($conn1, $sqlinsert);
			}
            $sqlidentityon= "SET IDENTITY_INSERT $table OFF";
			sqlsrv_query($conn1, $sqlidentityon);			
		}
		$suitst = $rowdbconnection['suitstatus'];
		$mysqlconf = "UPDATE Config_Database_Details_Prototype.Config_Details SET themeselection ='$themeselection',suitstatus='$suitst',utctime='$utctime',dbtype='$dbtype',format='$dateformat' WHERE db_active = '1'";
		$mysqlres = mysqli_query($linkexternal,$mysqlconf);
		
		if($rphserver && $rtype =="Server With Redundant & PH"){
            $connectionInfo = array("UID"=>"sa", "PWD"=>"Password1");
		    $rserverconn =sqlsrv_connect($rphserver,$connectionInfo );
			$errors = sqlsrv_errors();
		    if($errors[0]['SQLSTATE'] == 28000){
			set_session($errors[0]['message'],FALSE);
			header("Location: $location");
			die;
		    }  
			
			$sqlfinddb = "SELECT name FROM master.sys.databases WHERE name LIKE '%HistorianStorage'";
			$resfinddb = sqlsrv_query($rserverconn, $sqlfinddb); 
            $rowfinddb = sqlsrv_fetch_array($resfinddb,SQLSRV_FETCH_ASSOC);
            $rdbname = $rowfinddb['name'];
            if(!$rdbname){
				set_session("PH database is not connecting..!",FALSE);
			    header("Location: $location");
				die;
		    }			
			$connectionInfo = array( "Database"=>$rdbname, "UID"=>"sa", "PWD"=>"Password1");
			$conn2 = sqlsrv_connect($rphserver,$connectionInfo);
			foreach($tables as $table){
			$sqlexist = "IF OBJECT_ID (N'".$table."', N'U') IS NOT NULL SELECT 1 AS res ELSE SELECT 0 AS res;";
			$resexist= sqlsrv_query($conn2, $sqlexist);
			$rowexist = sqlsrv_fetch_array($resexist,SQLSRV_FETCH_ASSOC);
			if($rowexist['res'] == 1){
			$sqltruncate = "TRUNCATE TABLE $table";
			sqlsrv_query($conn2, $sqltruncate);
			}else{
			sqlsrv_query($conn2,$tablequery[$table]);				
			}

            $sqlidentityon= "SET IDENTITY_INSERT $table ON";
			sqlsrv_query($conn1, $sqlidentityon); 
			
            $sqlget = "SELECT * FROM $table";
            $resget = sqlsrv_query($conn, $sqlget);
            while($rowget = sqlsrv_fetch_array($resget,SQLSRV_FETCH_ASSOC)){
				$sqlinsert = "INSERT INTO $table (".implode(", ",array_keys($rowget)).") VALUES ('".implode("', '",array_values($rowget))."')";
				$resinsert = sqlsrv_query($conn2, $sqlinsert);
			}
            $sqlidentityon= "SET IDENTITY_INSERT $table OFF";
			sqlsrv_query($conn1, $sqlidentityon);			
		    }
	    $exp = explode("\\",$rphserver);
		$localIP = getHostByName($exp[0]);
        $linkexternalph = mysqli_connect($localIP, "biozeen", "Password1");
		
		$mysqlconf = "UPDATE Config_Database_Details_Prototype.Config_Details SET themeselection ='$themeselection',suitstatus='$suitst',utctime='$utctime',dbtype='$dbtype',format='$dateformat'  WHERE db_active = '1'";
		$mysqlres = mysqli_query($linkexternalph,$mysqlconf);			
		}
		log_activity("Redundant database synchronisation");
		set_session("Redundant database synchronisation completed successfully .. !",TRUE);
		$location = $_SERVER['HTTP_REFERER'];
		header("Location: $location");
		
?>