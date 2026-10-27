<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
   <?php 
   $refid = isset($_GET['refid']) ? $_GET['refid'] : 0; 
   $arr = array();
   if($refid)
   getparents($refid);
   krsort($arr); 
   ?>      
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> Vessel Details </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
         </div>
      </div>
   </header>
   <?php include('includes/notification.php'); ?>
   <div class="row quick-stats">
	<?php 
		$x= 0;
		if($suitstatus == "yes")
			$sql= "SELECT * FROM VESSEL_DETAILS WHERE Refid = '$refid' ORDER BY Vessel_Order ASC";
	    else
			$sql= "SELECT * FROM VESSEL_DETAILS ORDER BY Vessel_Order ASC";
		$res= sqlsrv_query($conn, $sql);
		while($row = sqlsrv_fetch_array($res)){
			$x++;		
	?>
       <div class="col-sm-6 col-md-6">
         <a href="<?php echo $baseurl; ?>sequences-type-report.php?vessel=<?php echo $row['Vessel_ID']; ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2><?php echo $row['Vessel_Name']; ?></h2>
                  <small></small>
               </div>
            </div>
         </a>
      </div>
   <?php } ?>
    </div>	   
</section>
<?php include('includes/footer.php');?>