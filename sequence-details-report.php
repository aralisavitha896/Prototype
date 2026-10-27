<?php include('includes/header.php');
	if(isset($_REQUEST['vessel'])){$vessel = $_REQUEST['vessel'];$type = $_REQUEST['type']; }
	if(isset($_REQUEST['seqtype'])) { $seqtype = $_REQUEST['seqtype'];}
	 
	$sqlv= "SELECT * FROM VESSEL_DETAILS WHERE  Vessel_ID ='$vessel'";
	$resv= sqlsrv_query($conn, $sqlv);
	$rowv = sqlsrv_fetch_array($resv);
	
	

 ?>
<section class="content">
   <header class="content__title">
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $rowv['Vessel_Name']; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> SEQUENCE DETAILS</h1>
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
	 
	    if($seqtype =="0")
		    $sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID ='$vessel' and [seqtype] !=1";
	    else
		    $sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID ='$vessel' and [seqtype] =1";
	
		$res= sqlsrv_query($conn, $sql);
		while($row = sqlsrv_fetch_array($res)){
		$btype = $row['Batch_Type'];	
	?>
    <div class="col-sm-6 col-md-6">
	     <span class=""><i class="fa fa-database"></i></span>
         <a href="<?php  if($dbtype!="PROCESS HISTORIAN"){ ?>batch-details.php<?php }else{ ?>batch-details.php<?php } ?>?vessel=<?php echo $vessel; ?>&batch=<?php echo $btype; ?>"  >
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-layers zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2><?php echo $row['Seqname']; ?></h2>
                  <small></small>
               </div>
            </div>
         </a>
    </div>
   <?php } ?>
    </div> 	   
</section>
<?php include('includes/footer.php');?>