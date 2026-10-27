  <?php include('includes/header.php');
	if(isset($_REQUEST['vessel'])){$vessel = $_REQUEST['vessel']; }
	 
	$sqlv= "SELECT * FROM VESSEL_DETAILS WHERE  Vessel_ID ='$vessel'";
	$resv= sqlsrv_query($conn, $sqlv);
	$rowv = sqlsrv_fetch_array($resv);
	
	$refid = $rowv['Refid'];
    $arr = array();
    if($refid)
        getparents($refid);
    krsort($arr);

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
		 $sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID ='$vessel'";
		$res= sqlsrv_query($conn, $sql);
		while($row = sqlsrv_fetch_array($res)){
		$btype = $row['Batch_Type'];	
	?>
    <div class="col-sm-6 col-md-6">
	     <span class=""><i class="fa fa-database"></i></span>
         <a onclick="showmodal(<?php echo $row['ID'].",'".$row['Sensor_Selection']."'"; ?>);"  >
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
     <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left"></h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
					<input id="vessel" type="hidden" value="<?php echo $vessel; ?>" />
					<input id="sequence" type="hidden"  />
			    <div class="form-group">
                <label>Start Date Time</label>
                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd/mm/yyyy  HH:ii p" data-link-field="starttime">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"><i class="zmdi zmdi-close"></i></span></span>
					<span class="input-group-addon">
					<input class="form-control" size="100" type="text" value="" readonly>
					<span class="glyphicon glyphicon-th"><i class="zmdi zmdi-calendar"></i></span></span>
                </div>
				<input type="hidden" name="starttime" id="starttime" value="" /><br/>
               </div>		  
			   <div class="form-group">
                <label>End Date Time</label>
                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd/mm/yyyy  HH:ii p" data-link-field="endtime">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"><i class="zmdi zmdi-close"></i></span></span>
					<span class="input-group-addon">
					<input class="form-control" size="100" type="text" value="" readonly>
					<span class="glyphicon glyphicon-th"><i class="zmdi zmdi-calendar"></i></span></span>
                </div>
				<input type="hidden" name="endtime" id="endtime" value="" /><br/>
               </div>	   
                <div class="col-sm-12">
				<span class="radittl" ><p class="card-body__title"><i class="fa fa-check-circle"></i> Select Sensors</p></span>
						<?php 
						
						$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE  Vessel_ID = '$vessel' AND Sensor_Selected = 1 ";
						$resparam= sqlsrv_query($conn, $sqlparam);
						while($rowparam = sqlsrv_fetch_array($resparam,SQLSRV_FETCH_ASSOC)){
							?>
						    <label class="custom-control custom-checkbox col-sm-3">
								<input id="<?php echo $rowparam['Sensor_Tag']; ?>" type="checkbox" class="custom-control-input"  name="parameter[]" value="<?php echo $rowparam['Sensor_Tag']; ?>">
                                <span class="custom-control-indicator"></span> <?php echo $rowparam['Sensor_Name']; ?>
                           </label>
						
						<?php } ?>	
                </div>
				<p>&nbsp;</p>
				 
				 <div class="col-sm-12" <?php if($intervalselection != 1){ ?> style="display:none;"<?php } ?>>
                  <h3 class="card-body__title"> Select Time Interval</h3>
                    <div class="form-group">
                        <div class="col-sm-12"><input type="radio" id="timediff" name="timediff" value="1" > <span class="radi" > 1 Second</span>
                        <input type="radio" id="timediff" name="timediff" value="30" checked> <span class="radi" > 30 Second</span>
                        <input type="radio" id="timediff" name="timediff" value="60"> <span class="radi"> 1 Minute</span>
                       <input type="radio" id="timediff" name="timediff" value="120"> <span class="radi">2 Minutes</span>
                        <input type="radio" id="timediff" name="timediff" value="180"> <span class="radi"> 3 Minutes</span>
                        <input type="radio" id="timediff" name="timediff" value="300"> <span class="radi">5 Minutes</span></div>
                    </div>
               </div>	
               			   
            <div class="modal-footer">
				<button id="btnSave" class="btn btn-primary" onclick="goreport()" type="button"><i class="fa fa-arrow-circle-right"></i>
				Next</button>
				<button class="btn btn-danger" data-dismiss="modal" type="button"><i class="fa fa-times-circle"></i>
				Cancel</button></div>
            </div>
         </div>
      </div>
   </div>
<link href="./datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">			
<script type="text/javascript" src="./datetimepicker/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="./datetimepicker/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./datetimepicker/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>    
<script>
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1,
		minuteStep:2,
    });
function showmodal(value,sensor_selection){
    $('input[name="parameter[]"]').each(function() {
	    document.getElementById(this.id).checked = false;
	});
	if(sensor_selection){
	var str = sensor_selection.split(",");
	str.forEach(function(value,index){
		//alert(value);
		document.getElementById(value).checked = true;
	});
	}
	$("#sequence").val(value);
	$('#modal_form').modal('show'); 	
}
function goreport()
    {  
	var vessel = $('#vessel').val();
	var sequence = $('#sequence').val();
	var starttime = $('#starttime').val();
	var endtime = $('#endtime').val();
	
	var selectedLanguage = new Array();
	$('input[name="parameter[]"]:checked').each(function() {
	    selectedLanguage.push(this.value);
	});
	
	if($('[name="timediff"]:checked').length > 0) {  var timediff = $('input:radio[name=timediff]:checked').val(); }
	else {var timediff = '30';}
		
	$('#modal_form').modal('hide');
	
	window.location.replace("<?php echo $baseurl; ?>manual-report.php?parameters=" + selectedLanguage + "&sequence=" + sequence +"&vessel=" +vessel  + "&time=" + timediff+ "&starttime=" + starttime+ "&endtime=" + endtime);
    }
</script>
<?php include('includes/footer.php');?>