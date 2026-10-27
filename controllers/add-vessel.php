<?php  include('../includes/config.php'); 
if(isset($_REQUEST['Vessel_Name'])){
	
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
      
      if(in_array($file_ext,$expensions)=== false){
			$errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 1097152){
         $errors[]='File size must be excately 1 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"../uploads/logos/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
    }
	
	$sql="INSERT INTO VESSEL_DETAILS(Vessel_Name,Equipment_Id,Vessel_Code,Vessel_order,Alarm_Filter,startime,endtime,batchname,batchdesc,starttimesort,endtimesort,startime2,endtime2,batchname2,batchdesc2,starttimesort2,endtimesort2,startuser,startuser_sub,enduser,enduser_sub) VALUES('$Vessel_Name','$Equipment_Id','$Vessel_Code','$Vessel_order','$Alarm_Filter','$startime','$endtime','$batchname','$batchdesc','$starttimesort','$endtimesort','$startime2','$endtime2','$batchname2','$batchdesc2','$starttimesort2','$endtimesort2','$startuser','$startuser_sub','$enduser','$enduser_sub')";
	$res= sqlsrv_query($conn, $sql);
	
	log_activity("Vessel($Vessel_Name) details  added");
    $message = $res ? "Vessel($Vessel_Name) details added successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
    set_session($message,$status);
  }
	

?>

