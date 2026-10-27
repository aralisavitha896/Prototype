<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">

        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">
    </head>

<?php

session_start();
//if(isset($_SESSION['username'])) {
   //header('location:dashboard.php');
//}
$link = mysqli_connect("localhost", "root", "");

$login_status =  0;
if(isset($_REQUEST["status"])){
$login_status = $_REQUEST["status"] == "yes" ? 1 : 0;
}
if(!$login_status){
	$_SESSION['username']= "user";	
	$_SESSION['user_type']= "user";	
}	

$create_status =true;
if($link && $create_status){
$sql = "CREATE DATABASE Config_Database_Details_Prototype";
if(mysqli_query($link, $sql)){echo "";}
$sqluser = "CREATE USER IF NOT EXISTS 'biozeen'@'%' IDENTIFIED BY 'Password1'";
mysqli_query($link, $sqluser);
$sqlprivillage = "GRANT ALL PRIVILEGES ON *.* TO 'biozeen'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;";
mysqli_query($link, $sqlprivillage);


$sqltbl = "CREATE TABLE Config_Database_Details_Prototype.Config_Details (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,dbtype VARCHAR(100) NOT NULL,server TEXT NOT NULL,db VARCHAR(500),un VARCHAR(500),psw VARCHAR(500),db_active INT(11) NOT NULL, add_date TIMESTAMP,format VARCHAR(100),utctime TIME,themeselection INT(11) NOT NULL,rserver VARCHAR(500),rtype VARCHAR(100),rphserver VARCHAR(100),procode VARCHAR(500),suitstatus INT(11) NOT NULL DEFAULT '0',sqlserver VARCHAR(500),timezone VARCHAR(500),timevalue VARCHAR(500))";
mysqli_query($link, $sqltbl);
	
$sqltbl = "CREATE TABLE Config_Database_Details_Prototype.Users (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,username VARCHAR(100) NOT NULL,password VARCHAR(100) NOT NULL,usertype VARCHAR(10) NOT NULL,add_date TIMESTAMP,menu TEXT NULL,lmenu TEXT NULL,sensorselection INT NOT NULL,intervalselection INT NOT NULL)";
mysqli_query($link, $sqltbl);

$sqltbl = "CREATE TABLE Config_Database_Details_Prototype.Activity_History (id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,username VARCHAR(100) NOT NULL,activity TEXT,ip VARCHAR(500) NOT NULL,actiontime TIMESTAMP)";
mysqli_query($link, $sqltbl);	

$sqlcount = "SELECT id FROM Config_Database_Details_Prototype.Users WHERE username = 'Biozeen Service'";
$rescount = mysqli_query($link, $sqlcount);
$rowcount=mysqli_num_rows($rescount);
if($rowcount <= 0){
$sqlins = "INSERT INTO Config_Database_Details_Prototype.Users (username,password,usertype,menu,lmenu,sensorselection,intervalselection) VALUES ('Biozeen Service', '".base64_encode("Password1")."','admin','1,2,3,4,5,6,7,8,9,10','1,2,3,4',1,1),('user', '".base64_encode("Password1")."','user','9,10','2,4',0,1)";
mysqli_query($link, $sqlins);	
}
}

if(isset($_REQUEST['login']))
{ 
	$username= $_REQUEST['username'];
	$password= $_REQUEST['password'];
	
	 
    $query = "SELECT count(*) as count,usertype,menu,lmenu FROM Config_Database_Details_Prototype.Users WHERE username = '$username' and password ='".base64_encode($password)."'";  
    $result = mysqli_query($link, $query);  
    $row = mysqli_fetch_array($result);
	$count = $row['count'];
	if($count){
    $usertype = $row['usertype'];
	$_SESSION['username']= $username;	
	$_SESSION['user_type']= $usertype;
	$ip = $_SERVER['REMOTE_ADDR'];
	$sqlins = "INSERT INTO Config_Database_Details_Prototype.Activity_History (username,ip,activity) VALUES('$username','User logined','$ip')";
	mysqli_query($link, $sqlins);
    header('location:dashboard.php');		
	}else{	
	$error= "Sorry! Username or Password Incorrect";
}
}
$dbconnection = "SELECT themeselection FROM Config_Database_Details_Prototype.Config_details WHERE db_active = '1' ORDER BY id ASC ";
$resdbconnection = mysqli_query($link, $dbconnection);
$rowdbconnection = mysqli_fetch_array($resdbconnection);	
$themeselection = $rowdbconnection['themeselection'] ? $rowdbconnection['themeselection']  : 1;	
 ?> 
        <body data-sa-theme="<?php echo $themeselection; ?>"> <!--style="background-image:url('uploads/login.jpg')" --> 
		<div>
		<p>&nbsp;</p>
 		 <p style="text-align:right;padding-right: 1em;"><a href="<?php if($login_status){ ?>?status=no<?php }else{ ?>?status=yes<?php } ?>" class="btn btn-primary"> <?php if($login_status)echo "Default User Login" ;else echo "Login With Password";?> </a></p>
		  </div>
         <div class="login" >
            <!-- Login -->
            <div class="login__block active" id="l-login" <?php if(!$login_status){ ?>style="display:none;"<?php } ?>>
			 <form action="#" method="post">
                <div class="login__block__header">
                    <i class="zmdi zmdi-account-circle"></i>
            

                    <div class="actions actions--inverse login__block__actions">
						
                    </div>
					<?php if(@$error){ ?><p class="login-box-msg errormsg"><?php echo $error; ?></p><?php } ?>
                </div>

                <div class="login__block__body" >
                    <div class="form-group">
                        <input type="text" class="form-control text-center" placeholder="Username" name="username" id="username">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control text-center" placeholder="Password" name="password" id="password">
                    </div>

                    <button type="submit" class="btn btn--icon login__block__btn" name="login"><i class="zmdi zmdi-long-arrow-right"></i></button>
                </div>
            </div>
			
			</form>
        </div>
        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
		<script>
		$(".login").click(function(){
			var status = "<?php echo $login_status; ?>";
			if(status == 0){
				location.href = "dashboard.php";
			}
		});
		</script>
    </body>
</html>