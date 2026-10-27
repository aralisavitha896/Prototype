<?php /*include ('../includes/config.php');
if (isset($_REQUEST['hideid'])) {
    $hideid = $_REQUEST['hideid'];
    $oldimg = $_REQUEST['oldimg'];
    $cust = $_REQUEST['cust'];
	
	
 if (isset($_FILES['file'])) {
        $errors = array();
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['file']['name'])));
        $expensions = array("jpeg", "jpg", "png");
		
        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 1 MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "../uploads/logos/" . $file_name);
            echo "Success";
			
        } else {
            print_r($errors);
        }
 }
    if ($file_name == '')
	{
        $file_name = $oldimg;
    }
    $sql = "UPDATE CUSTOMER_DATA SET Customer_Name='$cust',Logo='$file_name' WHERE Id='$hideid'";
    $res = sqlsrv_query($conn, $sql);
    echo json_encode($res);
}*/


include('../includes/config.php'); 
if (isset($_REQUEST['hideid'])) {
							  $oldimg = $_REQUEST['oldimg'];
							  $hideid = $_REQUEST['hideid'];
							  $cust = $_REQUEST['cust'];

	if(isset($_FILES['file'])){
							  $errors= array();
							  $file_name = $_FILES['file']['name'];
							  $file_size =$_FILES['file']['size'];
							  $file_tmp =$_FILES['file']['tmp_name'];
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
	if(!$file_name)
	{
		$file_name = $oldimg;
	}


	 $sqlins = "UPDATE CUSTOMER_DATA SET Customer_Name='$cust',Logo='$file_name' WHERE Id='$hideid'"; 
					
	$res= sqlsrv_query($conn, $sqlins);
 log_activity("Customer details  added");
    $message = $res ? "Customer details added successfully":"Something went wrong..!";
	$status = $res ? TRUE:FALSE;
    set_session($message,$status);
}

?>
