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
      <h1> Sensor Management <i class="zmdi zmdi-forward" style="color:yellow;"></i><?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> Suit Details  </h1>
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
		$sql= "SELECT * FROM SUIT_DETAILS WHERE Refid =' $refid' ORDER BY Suit_Order ASC";
		$res= sqlsrv_query($conn, $sql);
		while($row = sqlsrv_fetch_array($res)){
            $count = getchildcount($row['Suit_ID']);  		
	?>
       <div class="col-sm-6 col-md-6">
	     <?php if($count){?>
         <a href="<?php echo $baseurl; ?>suits-sensor-mgt.php?refid=<?php echo $row['Suit_ID']; ?>">
		 <?php }else{ ?>
		 <a href="<?php echo $baseurl; ?>vessel-details-sensor-mgt.php?refid=<?php echo $row['Suit_ID']; ?>">
		 <?php } ?>
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2><?php echo $row['Suit_Name']; ?></h2>
                  <small></small>
               </div>
            </div>
         </a>
      </div>
   <?php } ?>
    </div>	   
</section>
<?php include('includes/footer.php');?>