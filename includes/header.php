<!DOCTYPE html>
<?php include("config.php");
$usercheck = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$procode = $_SESSION['procode'];
$query = "SELECT menu,lmenu,sensorselection,intervalselection FROM Users WHERE username = '$usercheck'";  
$result = mysqli_query($link, $query);  
$row = mysqli_fetch_array($result); 
$menus=  $row['menu'] ? explode(",",$row['menu']) : array();
$lmenus= $row['lmenu']? explode(",",$row['lmenu']): array();
$sensorselection= $row['sensorselection'];
$intervalselection= $row['intervalselection'];
$REQUEST_URI = explode("/",$_SERVER['REQUEST_URI']);
$REQUEST_URI = explode("?",$REQUEST_URI[2]);
$uri = $REQUEST_URI[0];
$excludearr = array("vessel-details-report.php","sequences-type-report.php","batch-details.php","tag-logging-report.php","batch-comparisson-report.php","manual-events.php","manual-alarms.php","change-password.php","user-management.php","sequence-details-report.php","suits-report.php","batch-events.php");

?>	 
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Automation</title>
      <!-- Vendor styles -->
      <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
      <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">
      <link rel="stylesheet" href="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
      <link rel="stylesheet" href="vendors/bower_components/fullcalendar/dist/fullcalendar.min.css">
      <link rel="stylesheet" href="vendors/bower_components/flatpickr/dist/flatpickr.min.css" />
      <link rel="stylesheet" href="css/app.min.css">
	  <script src="bower_components/jquery/dist/jquery.min.js"></script>
   </head>
   <body data-sa-theme="<?php echo $themeselection; ?>">
      <main class="main">
      <div class="page-loader">
         <div class="page-loader__spinner">
            <svg viewBox="25 25 50 50">
               <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
         </div>
      </div>
      <header class="header">
         <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
            <i class="zmdi zmdi-menu"></i>
         </div>
         <div class="logo hidden-sm-down">
            <h1><a href="dashboard.php"><img src="uploads/logos/biozeen.jpg"></a></h1>
         </div>         
		 <div class="logo hidden-sm-down">
            <h1><a onclick="opensoftwareinf();"><i class="zmdi zmdi-info"></i></a></h1>
         </div>
         <ul class="top-nav">
            <li class="dropdown hidden-xs-down">
            </li>
			<?php if($user_type == "admin" && ($rserver || $rphserver) && $rtype !="Stand Alone Server" && !in_array($uri,$excludearr)){ ?>
			  <a href= "#" onclick="updaterserver();" class="btn btn-primary">Update Redundant Server</a>
			<?php } ?>
			<?php if($user_type == "admin"){ ?>
			<li class="hidden-xs-down">
                        <a href="" class="top-nav__themes" data-sa-action="aside-open" data-sa-target=".themes"><i class="zmdi zmdi-palette"></i></a>
             </li>
			<?php } ?>
         </ul>		 
         <div class="clock hidden-md-down">
            <div class="time">
               <span class="time__hours"></span>
               <span class="time__min"></span>
               <span class="time__sec"></span>
            </div>
         </div>
		 &nbsp;
		 <a class="btn btn-primary" href="<?php  echo $baseurl;?>signout.php" >Logout</a>		 
      </header>
      <aside class="sidebar">
         <div class="scrollbar-inner">
            <div class="user">
               <div class="user__info" data-toggle="dropdown">
                  <img class="user__img" src="img/user.jpg" alt="">
                  <div>
                     <div class="user__name"><?php echo $_SESSION['username'];?></div>
                     <div class="user__email"></div>
                  </div>
               </div>
               <div class="dropdown-menu">
                  <a class="dropdown-item" href="signout.php">Logout</a>
               </div>
            </div>
            <ul class="navigation">
               <li class="navigation"><a href="dashboard.php"><i class="zmdi zmdi-home"></i>Home</a></li>
			    <?php if(in_array(1,$lmenus)){?>
               <li class="navigation"><a href="connection-management.php"><i class="zmdi zmdi-settings"></i>Database Management</a></li>
			    <?php }if(in_array(2,$lmenus)){?>
               <li class="navigation"><a href="<?php if($suitstatus == "yes") { ?>suits-report.php<?php }else{ ?>vessel-details-report.php<?php } ?>"><i class="zmdi zmdi-collection-text"></i>Reports</a></li>
			   <?php }if(in_array(3,$lmenus)){?>
			   <li class="navigation__sub">
                  <a href=""><i class="zmdi zmdi-collection-text"></i>Project Management</a>
                  <ul>
                     <li class="@@formelementactive"><a href="<?php if($suitstatus == "yes") { ?>suits-vessel-mgt.php<?php }else{ ?> vessel-management.php<?php } ?>">Vessel Management</a></li>
                     <li class="@@formcomponentactive"><a href="<?php if($suitstatus == "yes") { ?>suits-sequence-mgt.php<?php }else{ ?>vessel-details-sequence-mgt.php <?php } ?>">Sequence Management </a></li>
                     <li class="@@formcomponentactive"><a href="<?php if($suitstatus == "yes") { ?>suits-sensor-mgt.php<?php }else{ ?>vessel-details-sensor-mgt.php<?php } ?>">Sensor Management</a></li>
                  </ul>
               </li>
                <?php }if(in_array(4,$lmenus)){?>
               <li class="navigation"><a href="change-password.php"><i class="zmdi zmdi-accounts"></i>Change Password</a></li>
			   <?php }?>
            </ul>
         </div>
      </aside>
	  <div class="themes">
    <div class="scrollbar-inner">
        <a href="" class="themes__item <?php if($themeselection == 1){ ?> active <?php } ?>" data-sa-value="1" rel= "1"><img src="img/bg/1.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 2){ ?> active <?php } ?>" data-sa-value="2" rel= "2"><img src="img/bg/2.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 3){ ?> active <?php } ?>" data-sa-value="3" rel= "3"><img src="img/bg/3.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 4){ ?> active <?php } ?>" data-sa-value="4" rel= "4"><img src="img/bg/4.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 5){ ?> active <?php } ?>" data-sa-value="5" rel= "5"><img src="img/bg/5.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 6){ ?> active <?php } ?>" data-sa-value="6" rel= "6"><img src="img/bg/6.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 7){ ?> active <?php } ?>" data-sa-value="7" rel= "7"><img src="img/bg/7.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 8){ ?> active <?php } ?>" data-sa-value="8" rel= "8"><img src="img/bg/8.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 9){ ?> active <?php } ?>" data-sa-value="9" rel= "9"><img src="img/bg/9.jpg" alt=""></a>
        <a href="" class="themes__item <?php if($themeselection == 10){?> active <?php } ?>" data-sa-value="10"rel= "10"><img src="img/bg/10.jpg" alt=""></a>
    </div>
</div>
    <div class="modal fade" id="modal_inf" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left"><u>Software Informations</u></h5>
            </div>
            <div class="modal-body" style="background-color: white;color: black;">
			<p>BIOZEEN REPORTING TOOL VERSION 2.0</p>
			<p>Last Updated - 21 August 2020</p>
			<p>Developed by BIOZEEN</p>
			</div>
		 </div>
	  </div>
	</div>
	  <style>
	  .zmdi-print{display:none;}
	  .zmdi-download{display:none  !important;}
	  </style>
	  <script>
	  $('.themes__item').on('click', function(){
        var theme = $(this).attr('rel');

        $.ajax ({
   			type:"post",
            url: "<?php echo $baseurl ; ?>controllers/update-theme-selection.php",
            data: {theme: theme},
        });
        
       });
	   function opensoftwareinf(){
		   $('#modal_inf').modal('show');
	   }
	   function updaterserver(){
		   if(confirm("Warning: other Systems BIOZEEN reporting tool sequences & settings will be updated as per this system BIOZEEN reporting tool.")){
			   location.href = "controllers/update-redundant-server.php";
		   }
	   }
	  </script>
 