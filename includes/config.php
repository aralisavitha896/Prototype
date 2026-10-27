<?php 
error_reporting(0);
session_start();
set_time_limit(0);
$link = mysqli_connect("localhost", "root", "","Config_Database_Details_Prototype");		
$dbconnection = "SELECT *,HOUR(utctime) as hour,MINUTE(utctime) as minutes FROM Config_details WHERE db_active = '1' ORDER BY id ASC ";
$resdbconnection = mysqli_query($link, $dbconnection);
$rowdbconnection = mysqli_fetch_array($resdbconnection);
$database = $rowdbconnection['db'];
$username = $rowdbconnection['un'];
$password = $rowdbconnection['psw'];
$dateformat = $rowdbconnection['format'];
$utctime = $rowdbconnection['utctime'];
$utchour = $rowdbconnection['hour'];
$utcminutes = $rowdbconnection['minutes'];
$timezone = $rowdbconnection['timezone'];
$timevalue = $rowdbconnection['timevalue'];
$dbtype = $rowdbconnection['dbtype'];	
$procode = $rowdbconnection['procode'];	
$rserver = base64_decode($rowdbconnection['rserver'])."\WINCC";;	
$rphserver = base64_decode($rowdbconnection['rphserver'])."\HISTORIAN";	
$rtype = $rowdbconnection['rtype'];	
$themeselection = $rowdbconnection['themeselection'] ? $rowdbconnection['themeselection']  : 1;
$suitstatus = $rowdbconnection['suitstatus'] == 1 ? "yes"  : "no";
$_SESSION['procode']= $procode;

$baseurl ="http://".gethostname().":8080/prototype/";
$server=gethostname();
$enserver=base64_encode($server);
$username_in='RA';
$password_in=gethostname();
//sql connection
$serverName = gethostname()."\\"."SQL2019BIOZEEN";
$ConnectionInfo = array( "Database"=>"$server", "UID"=>"RA", "PWD"=>"$server");
$conn = sqlsrv_connect( $serverName, $ConnectionInfo);

/*if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}*/
//connection
$create_status = true;
$sqlalldb = "SELECT name FROM master.sys.databases WHERE name ='$server'";	
$resalldb = sqlsrv_query($conn, $sqlalldb); 
$rowalldb = sqlsrv_fetch_array($resalldb,SQLSRV_FETCH_ASSOC);
$currentdb = $rowalldb['name'];

if(!$database && $rowdbconnection['id']!=1){
 $sqlins = "INSERT INTO Config_Details(dbtype, server, db,db_active,format,utctime,rtype,un,	psw) VALUES ('AB', '$enserver', '$currentdb' ,1,'Y/m/d H:i:s','05:30','Stand Alone Server','$username_in','$password_in')";
mysqli_query($link, $sqlins);	
}





if($currentdb != $password_in){
$mysqlupcrntdb = "UPDATE Config_details SET db='$currentdb',server='$server' WHERE db_active = '1' ORDER BY id ASC ";
$mysqlrescrntdb = mysqli_query($link, $mysqlupcrntdb);
$mysqlrowcrntdb = mysqli_fetch_array($mysqlrescrntdb);
}

$dbid='';


if ($conn && $create_status) {
    //parameters
    $sql2 = "CREATE TABLE PARAMETERS_DETAILS (Parameter_ID INT NOT NULL IDENTITY PRIMARY KEY, Sequence_ID INT  NOT NULL, Param_Name VARCHAR(MAX)  NOT NULL, Parmaters_Tagname VARCHAR(MAX)  NOT NULL, Units VARCHAR(MAX)  NOT NULL, Decimal INT NULL, Param_Order INT NULL, checkparam int NULL,TagIndex INT)";
    $res2 = sqlsrv_query($conn, $sql2);
    //vessels
    $sql3 = "CREATE TABLE VESSEL_DETAILS (Vessel_ID INT NOT NULL IDENTITY PRIMARY KEY, Vessel_Name VARCHAR(MAX)  NOT NULL,Equipment_Id VARCHAR(MAX)  NULL,Vessel_order INT  NULL, Vessel_Code VARCHAR(100)  NULL,Vessel_Type VARCHAR(100)  NULL,Alarm_Filter VARCHAR(MAX))";
    $res3 = sqlsrv_query($conn, $sql3);
    //SENSORS_HIGHLIMITS
    $sql6 = "CREATE TABLE SENSORS_HIGHLIMITS (ID INT NOT NULL IDENTITY PRIMARY KEY, Sensor_Tag VARCHAR(MAX)  NOT NULL, Sensor_Name VARCHAR(MAX)  NULL, Vessel_ID INT  NULL, Sensor_highvalue real NULL, Sensor_Selected INT NULL, Sensor_lowvalue real NULL, Table_title VARCHAR(MAX) NULL, Sensor_Unit VARCHAR(MAX) NULL, Tag_Decimal nchar(10) NULL,combine INT,TagIndex INT,colors VARCHAR(MAX))";
    $res6 = sqlsrv_query($conn, $sql6);
    //sequence number
    $sql7 = "CREATE TABLE SEQUENCE_NUMBER_DETAIL (ID INT NOT NULL IDENTITY PRIMARY KEY, Batch_Type VARCHAR(MAX)  NOT NULL, Seqname VARCHAR(MAX)  NOT NULL, Seqsort VARCHAR(MAX)   NULL, Vessel_ID INT NOT NULL, Sensor_Selection VARCHAR(MAX)  NULL, maxvalue float NULL, maxvaluesensors varchar(MAX) NULL, maxvaluestepvalue INT NULL, minvalue float NULL, minvaluesensors varchar(MAX) NULL, minvaluestepvalue INT  NULL, fovalue float NULL, fosensors varchar(MAX) NULL, fostepvalue float  NULL, Conductvalue float NULL, conductsensors varchar(MAX) NULL, conductstepvalue INT  NULL, view_status INT NULL, seqtype INT  NULL,group_id varchar(MAX) NULL,compno varchar(MAX))";
    $res7 = sqlsrv_query($conn, $sql7);
	
	//F0 
    $sql8 ="CREATE TABLE Fnot_details (id INT NOT NULL IDENTITY PRIMARY KEY, vessel INT NOT NULL, sequence INT NOT NULL,step_no VARCHAR(MAX)  NOT NULL,temperature INT NOT NULL ,max_min VARCHAR(10) NOT NULL,f0 VARCHAR(10) NOT NULL,z INT NOT NULL,sensor VARCHAR(MAX),sensor1 VARCHAR(MAX),sensorsel VARCHAR(MAX))";
    $res8 = sqlsrv_query($conn, $sql8);
	// timezone mgt
		   
    $sql9 ="CREATE TABLE Timezone_Details (id INT NOT NULL IDENTITY PRIMARY KEY, hours INT NOT NULL, minutes INT NOT NULL,active VARCHAR(10)  NOT NULL DEFAULT 'no',timezone VARCHAR(MAX))";
    $res9 = sqlsrv_query($conn, $sql9);

    $sql5="CREATE TABLE STEP_LOGS (Step_ID INT NOT NULL IDENTITY PRIMARY KEY, Sequence_ID INT  NOT NULL, Step_No FLOAT  NOT NULL, Step_Tag VARCHAR(MAX)  NOT NULL)";
    sqlsrv_query($conn, $sql5);	
	
	$sql6="CREATE TABLE CUSTOMER_DATA (Id INT NOT NULL IDENTITY PRIMARY KEY, Customer_Name VARCHAR(MAX)  NOT NULL, Logo VARCHAR(MAX)  NOT NULL)";
    sqlsrv_query($conn, $sql6);
	
	$sql71 ="CREATE TABLE REPORT_LAYOUT (Layout_ID INT NOT NULL IDENTITY PRIMARY KEY , Name VARCHAR(500) NULL , Field VARCHAR(500) NOT NULL,Sort INT ,Type VARCHAR(20),Page VARCHAR(20))";
	sqlsrv_query($conn, $sql71);
	
	$sql78 ="CREATE TABLE FOOTER_DETAILS (FOOTER_ID INT NOT NULL IDENTITY PRIMARY KEY, Footer_Type VARCHAR(100),Column1 VARCHAR(100),Column2 VARCHAR(100),Column3 VARCHAR(100),Column4 VARCHAR(100),Column5 VARCHAR(100),Column6 VARCHAR(100),Column7 VARCHAR(100),Column8 VARCHAR(100),Column9 VARCHAR(100),Padding REAL)";
	sqlsrv_query($conn, $sql78);
	
	$sql81 ="CREATE TABLE HEADER_DETAILS (HEADER_ID INT NOT NULL IDENTITY PRIMARY KEY, Logo_Height REAL,Logo_Width REAL,Center_Text VARCHAR(MAX),Right_Text VARCHAR(MAX),Trend_Text VARCHAR(MAX),Alarm_Text VARCHAR(MAX),PAction_Text VARCHAR(MAX),PAction_Status INT,UserLogin_Status INT,Esign_Status INT)";
	sqlsrv_query($conn, $sql81);
	
	$sqlsum="CREATE TABLE SUMMARY_DETAILS (ID INT NOT NULL IDENTITY PRIMARY KEY, Summary_Type varchar(MAX) NOT NULL, Vessel_ID INT NOT NULL, Sequence_ID int NOT NULL,Initial_1 VARCHAR(MAX), Initial_2 VARCHAR(MAX),Final_1 VARCHAR(MAX),Final_2 VARCHAR(MAX),Allowable_Drop VARCHAR(MAX),Result_Count int,Step_no int,Sensor1 varchar(MAX),Sensor2 varchar(MAX),Sensor3 varchar(MAX),Sensor4 varchar(MAX),Result_for varchar(MAX),Temperature varchar(MAX),z varchar(MAX),con_tag varchar(MAX))";
	$res9 = sqlsrv_query($conn, $sqlsum);
}



function set_session($msg,$status){
	$_SESSION['redirect']['message']= $msg;
	$_SESSION['redirect']['status']=  $status;
}  
function log_activity($activity){
	$ip = $_SERVER['REMOTE_ADDR'];
	$username = $_SESSION['username'];
	
	$sqlins = "INSERT INTO Config_Database_Details_Prototype.Activity_History (username,activity,ip) VALUES('$username','$activity','$ip')";
	mysqli_query($GLOBALS['link'], $sqlins);
}
function getparents($refid){
/*	$sql  = "SELECT Suit_Name,Refid FROM SUIT_DETAILS WHERE Suit_ID = '$refid'";
	$res = sqlsrv_query($GLOBALS['conn'],$sql);
	$row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC);
	$GLOBALS['arr'][] = $row['Suit_Name'];
	if($row['Refid'] !=0)
		getparents($row['Refid']);*/
}
function getchildcount($id){
	$sql = "SELECT COUNT(*) AS COUNT FROM SUIT_DETAILS WHERE Refid ='$id'";
	$res = sqlsrv_query($GLOBALS['conn'],$sql);
	$row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC);
	return $row['COUNT'];
}
?>