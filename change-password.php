<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Change Password <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $usercheck; ?>  </h1>
      <small>&nbsp;</small>
      <div class="actions">
		 <div>
		 <br>
		 	<p>&nbsp;</p>
		 </div>
      </div>
   </header>
 	    <?php 
		$error = false;
		if(isset($_POST["submit"])){
			$password = $_POST["password"];
			$cpassword = $_POST["cpassword"];
			$npassword = $_POST["npassword"];
			if($cpassword != $npassword){
			$error =true;
            set_session("New Password and confirm password should be same",FALSE);		
			}		
			if($password == $npassword){
			$error =true;
            set_session("You sholud enter diffrent password than older one..!",FALSE);		
			}			
			if(strlen($npassword)< 6){
			$error =true;
            set_session("Minimum password length should be 6 characters..!",FALSE);				
			}			
			if(strlen($npassword)> 10){
			$error =true;
		    set_session("Maximum password length should be 10 characters..!",FALSE);		
			}
			if(!$error){
			$user = $_SESSION['username'];
            $query = "SELECT count(*) as count,usertype,menu,lmenu FROM Config_database_details_prototype.Users WHERE username = '$user' and password ='".base64_encode($password)."'";  
            $result = mysqli_query($link, $query);  
            $row = mysqli_fetch_array($result);
	        $count = $row['count'];
	        if($count && $npassword){
            $sqlins = "UPDATE Users SET password='".base64_encode($npassword)."' WHERE username='$user'";			
	        $res = mysqli_query($link, $sqlins);
			
			log_activity("Password Updated");
            $message = $res ? "Password Updated Successfully":"Something went wrong..!";
            $status = $res ? TRUE:FALSE;
            set_session($message,$status);
			
			}else{
		    set_session("Invalid Current Password..!",FALSE);	
			}
			}			
		}?>
		
   <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
		 <p>&nbsp;</p>
	   <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST">
			    <div class="col-sm-6">
                  <div class="form-group">
				     <label>Current Password</label>
                    <input type="password" class="form-control" name="password"id="password"  required>
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
			   <div class="col-sm-6">
                  <div class="form-group">
				     <label>New Password</label>
                    <input type="password" class="form-control" name="npassword"  id="npassword"  required>
                     <i class="form-group__bar"></i>
                </div>
               </div>			 
			   <div class="col-sm-6">
                  <div class="form-group">
				     <label>Confirm Password</label>
                    <input type="password" class="form-control" name="cpassword"id="cpassword"  required>
                     <i class="form-group__bar"></i>
                </div>
               </div>
			   <div class="col-sm-6" style="text-align: center;">
			   <button type="submit" class="btn btn-primary" name="submit" value="submit" style="width: 25%;">Change</button>
			    </div>
		</form>
      </div>
   </div> 
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.min.js"></script>   
 <script>  
  $("#form").validate({
        rules: {
         password: {
           required: true,
         },        
		 npassword: {
           required: true,
         },
		 cpassword: {
           required: true,
		   equalTo:"#npassword"
         }
       },
   	messages: {
       password: {
         required: 'Please enter current password'
       },
       npassword: {
         required: 'Please enter new password',
       }, 
	   cpassword: {
         required: 'Please enter confirm password',
		 equalTo:"New Password and confirm password should be same"
       }, 
   	},	
});

 </script>  
</section>
<?php include('includes/footer.php');?>