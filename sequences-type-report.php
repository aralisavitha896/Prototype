<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
       <?php
	   if(isset($_REQUEST['vessel'])) { $Vessel_ID = $_REQUEST['vessel']; }
		$sql="SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
		$res = sqlsrv_query($conn, $sql); 
		$rowvessel = sqlsrv_fetch_array($res);
		

	 ?>
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $rowvessel['Vessel_Name']; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> Dashboard  </h1>
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
		$sql= "SELECT COUNT(*) as Count FROM [SEQUENCE_NUMBER_DETAIL] WHERE Vessel_ID = '$Vessel_ID' and [seqtype] =1 ";
		$res= sqlsrv_query($conn, $sql);
		$row = sqlsrv_fetch_array($res);
		$count = $row['Count'];	
	?>
       <div class="col-sm-6 col-md-6">
	     <span class=""><i class="fa fa-database"></i></span>
         <a href="sequence-details-report.php?vessel=<?php echo $Vessel_ID; ?>&type=batch-report&&seqtype=0">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-collection-text zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Main Batch Reports</h2>
                  <small></small>
               </div>
            </div>
         </a>
      </div>     

	  <?php if($count){ ?>
	  <div class="col-sm-6 col-md-6">
	     <span class=""><i class="fa fa-database"></i></span>
         <a href="sequence-details-report.php?vessel=<?php echo $Vessel_ID; ?>&type=batch-report&&seqtype=1">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-collection-text zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Sub Batch Reports</h2>
                  <small></small>
               </div>
            </div>
         </a>
      </div>
	  <?php } ?>
	  <div class="col-sm-6 col-md-6">
	     <span class=""><i class="fa fa-database"></i></span>
         <a href="manual-report-sequences.php?vessel=<?php echo $Vessel_ID; ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-collection-text zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Manual Reports</h2>
                  <small></small>
               </div>
            </div>
         </a>
      </div>
    </div>
<style>
.quick-stats__item{
	/*background-color:#00c0ef ;*/
	height:125px;
}
</style>	   
</section>
<?php include('includes/footer.php');?>