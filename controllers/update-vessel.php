<?php  include('../includes/config.php'); 
if(isset($_REQUEST['Vessel_ID']))
{
	$Vessel_ID = $_REQUEST['Vessel_ID'];
	$Vessel_Name = $_REQUEST['Vessel_Name'];
	$Equipment_Id = $_REQUEST['Equipment_Id'];
    $Vessel_Code = $_REQUEST['Vessel_Code'];
    $Vessel_order = $_REQUEST['Vessel_order'];
    $Alarm_Filter = $_REQUEST['Alarm_Filter'];
    $startime = $_REQUEST['startime'];
    $endtime = $_REQUEST['endtime'];
    $batchname = $_REQUEST['batchname'];
    $batchdesc = $_REQUEST['batchdesc'];
    $starttimesort = $_REQUEST['starttimesort'];
    $endtimesort = $_REQUEST['endtimesort'];
	$startime2 = $_REQUEST['startime2'];
    $endtime2 = $_REQUEST['endtime2'];
    $batchname2 = $_REQUEST['batchname2'];
    $batchdesc2 = $_REQUEST['batchdesc2'];
    $starttimesort2 = $_REQUEST['starttimesort2'];
    $endtimesort2 = $_REQUEST['endtimesort2'];
    $startuser = $_REQUEST['startuser'];
    $startuser_sub = $_REQUEST['startuser_sub'];
    $enduser = $_REQUEST['enduser'];
    $enduser_sub = $_REQUEST['enduser_sub'];
	
	
	if(isset($_FILES['image'])){
		
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $file_type=$_FILES['image']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
    $expensions= array("jpeg","jpg","png");
	
    if($file_size > 2097152){
        $errors[]='File size must be excately 1 MB';
    }
      
    if(empty($errors)==true){
        move_uploaded_file($file_tmp,"../uploads/logos/".$file_name);
         echo "Success";
    }else{
         print_r($errors);
      }
    } 

	
	
	if($file_name == ''){$file_name =$hideimg;}
	$sql="UPDATE VESSEL_DETAILS SET Vessel_Name = '$Vessel_Name', Equipment_Id ='$Equipment_Id',Vessel_Code='$Vessel_Code',Vessel_order = '$Vessel_order',Alarm_Filter='$Alarm_Filter',startime='$startime',endtime='$endtime',batchname='$batchname',batchdesc='$batchdesc',starttimesort='$starttimesort',endtimesort='$endtimesort',startime2='$startime2',endtime2='$endtime2',batchname2='$batchname2',batchdesc2='$batchdesc2',starttimesort2='$starttimesort2',endtimesort2='$endtimesort2',startuser='$startuser',startuser_sub='$startuser_sub',enduser='$enduser',enduser_sub='$enduser_sub' WHERE Vessel_ID = '$Vessel_ID'";
	$res= sqlsrv_query($conn, $sql);
	
	log_activity("Vessel($Vessel_Name) details  updated");
	$message = $res ? "Vessel($Vessel_Name) details updated successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
    set_session($message,$status);
 
}

?>
