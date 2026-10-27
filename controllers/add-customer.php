<?php 
/*include('../includes/config.php'); 
$cust = $_REQUEST['cust'];
			$target_dir = "logo/";
			$target_file = $target_dir . basename($_FILES["file"]["name"]);
			$target = $_FILES["file"]["name"];
			$uploadOk = 1;
			$fileFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			  $check = getfilesize($_FILES["file"]["tmp_name"]);
			
			if($check == true) 
			{  
				if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
				{
					echo  $sqlins = "INSERT INTO CUSTOMER_DATA (Customer_Name,Logo)values('$cust','$target')";
					if(sqlsrv_query($conn, $sqlins)){ echo "Data INSERTED";}else{echo "No insertion";}
				}
			}	
			else
			{
				echo "error in uploading";
			}
*/
?>
<?php  
include('../includes/config.php'); 

	if(isset($_FILES['file'])){
							  $cust = $_REQUEST['cust'];
							  $errors= array();
							  $file_name = $_FILES['file']['name'];
							  $file_size =$_FILES['file']['size'];
							  $file_tmp =$_FILES['file']['tmp_name'];
							  $file_type=$_FILES['file']['type'];
							  $file_ext=strtolower(end(explode('.',$_FILES['file']['name'])));
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
	
	$sqlins = "INSERT INTO CUSTOMER_DATA (Customer_Name,Logo)values('$cust','$file_name')";
					
	$res= sqlsrv_query($conn, $sqlins);
 log_activity("Vessel($Vessel_Name) details  added");
    $message = $res ? "Vessel($Vessel_Name) details added successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
    set_session($message,$status);
?>

