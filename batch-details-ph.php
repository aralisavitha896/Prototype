<?php include('includes/header.php'); 

	if(isset($_REQUEST['vessel']))
	{ $batch = $_REQUEST['batch'];  $vessel = $_REQUEST['vessel'];  }

	$sqlsequence = "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Batch_Type = '$batch'";
	$ressequence = sqlsrv_query($conn, $sqlsequence);
	$rowsequence = sqlsrv_fetch_array($ressequence);
	$sequence = $rowsequence['Sequence_ID'];
	$sequence_name = $rowsequence['Sequence_Name'];
			
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb);
	$vessel_name = $rowdb['Vessel_Name'];  
?>
<section class="content">
   <header class="content__title">
      <h1><?php echo $vessel_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $sequence_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> BATCH DETAILS</h1>
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
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">		
            <table id="data-table" class="table">
               <thead>
                <tr>
				  <th>SL NO</th>
				  <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'batch'";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th><?php echo $rowlayout['Name']; ?></th>					 
                  <?php } ?>	
                 <th>ACTION</th>				  
                 <th>BATCH COMPARISSON</th>
                </tr>
               </thead>
                <tbody>
				<?php
				$param =array();
				$i =0;
				$j =0;
				
				$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
				$resdb= sqlsrv_query($conn, $sqldb);
				$rowdb = sqlsrv_fetch_array($resdb);
				
				
			    $sqlcheck= "SELECT * FROM SEQUENCE_NUMBER_DETAIL  WHERE Batch_Type = '$batch' AND Vessel_ID = '$Vessel_ID'";
				$rescheck= sqlsrv_query($conn, $sqlcheck);
				$rowcheck = sqlsrv_fetch_array($rescheck);
				$Sensor_Selection = $rowcheck['Sensor_Selection'];
				$Vessel_Code = $rowdb['Vessel_Tag'];
		
                $sql ="SELECT * FROM ALG.VRuntimeMessage  WHERE (Message_State = 1) AND (PText6 = '$batch') ORDER BY [START TIME SORT] DESC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){ 			
	?>
                <tr>
				<td><?php echo $a; ?></td>
				<?php foreach($layoutarr as $rowlayout){ 
				$field = $rowlayout['Field'];
				if($rowlayout['Type']== "starttime")
					$starttime = $row[$field]; 				
				if($rowlayout['Type']== "endtime")
					$endtime = $row[$field]; 
				?>
				<td><?php if($rowlayout['Type']== "starttime" || $rowlayout['Type']=="endtime")echo date($dateformat,strtotime($row[$field])); else echo $row[$field]; ?></td>
				<?php } ?>
				  <td align="center"> 
				  <button class="btn btn-primary " onclick="go_details(<?php echo $Vessel_ID.",".$i++; ?>)" <i class="fa fa-external-link-square"></i> View Report</button>
				  <a class="btn btn-success " href="<?php echo $baseurl; ?>manual-events.php?starttime=<?php echo $starttime;?>&endtime=<?php echo $endtime;?>&system=<?php echo $vessel;?>" <i class=""></i> View Events</a>
				  </td>
				  <td align="center"> 
                  <div class="form-group">
                  <div class="col-sm-12">
				    <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="batches[]" id="batches<?php echo  $j; ?>" value="<?php echo $j; ?>" onchange="checkbatch('<?php echo  $j; ?>')">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description"></span>
                   </label>				    			
				   </div> 
				   </div> 
				   </td>
                </tr>
<?php $j++;} ?>        
                </tbody>          
            </table>
         </div>
      </div>
   </div>
     <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Sensor</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				<input name="Vessel_ID" id="Vessel_ID" type="hidden" value="<?php echo $Vessel_ID; ?>" />
				<input name="Batch_Type" id="Batch_Type" type="hidden" value="<?php echo $batch; ?>" />
				<input class="form-control" name="Index"  type="hidden">					   
                <div class="col-sm-12">
				<span class="radittl" ><p class="card-body__title"><i class="fa fa-check-circle"></i> Select Sensors</p></span>
						<?php 
						$sqlsen= "SELECT Sensor_Selection FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$sequence'";
						$ressen= sqlsrv_query($conn, $sqlsen);
						$rowsen = sqlsrv_fetch_array($ressen);
						$Sensor_Selection = $rowsen['Sensor_Selection'];
						
						$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE  Vessel_ID = '$Vessel_ID' AND Sensor_Selected = 1 ";
						$resparam= sqlsrv_query($conn, $sqlparam);
						while($rowparam = sqlsrv_fetch_array($resparam)){
							?>
						    <label class="custom-control custom-checkbox col-sm-2">
								<input id="parameter" type="checkbox" class="custom-control-input"  name="parameter[]" value="<?php echo $rowparam['Sensor_Tag']; ?>" <?php $sen = explode(',', $Sensor_Selection); foreach($sen as $selsen){ if($selsen == $rowparam['Sensor_Tag']) { echo 'checked'; } } ?>>
                                <span class="custom-control-indicator"></span> <?php echo $rowparam['Sensor_Name']; ?>
                           </label>
						
						<?php } ?>	
                </div>
				<p>&nbsp;</p>
				 <div class="col-sm-12">
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
			    <?php if($sensorselection == 1){ ?>
                <button id="btnSave" class="btn btn-success" onclick="savesensors()" type="button"><i class="fa fa-floppy-o"></i>
				Save</button>
				<?php } ?>
				<button id="btnSave" class="btn btn-primary" onclick="showbatchlogs()" type="button"><i class="fa fa-arrow-circle-right"></i>
				Next</button>
				<button class="btn btn-danger" data-dismiss="modal" type="button"><i class="fa fa-times-circle"></i>
				Cancel</button></div>
            </div>
         </div>
      </div>
   </div>
   
<!-- batch comparission -->

     <div class="modal fade" id="modal_batch" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Sensors</h5>
            </div>
            <div class="modal-body">
			    <form id="formbatch"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
					<input name="sequence_id" type="hidden" value="<?php echo $sequence; ?>" />
					<input name="vessel" type="hidden" value="<?php echo $Vessel_ID; ?>" />
	

								   
                <div class="col-sm-12">
				<span class="radittl" ><p class="card-body__title"><i class="fa fa-check-circle"></i> Select Sensors</p></span>
						<?php 
						$sqlsen= "SELECT Sensor_Selection FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$sequence'";
						$ressen= sqlsrv_query($conn, $sqlsen);
						$rowsen = sqlsrv_fetch_array($ressen);
						$Sensor_Selection = $rowsen['Sensor_Selection'];
						$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE  Vessel_ID = '$Vessel_ID' AND Sensor_Selected = 1 ";
						$resparam= sqlsrv_query($conn, $sqlparam);
						while($rowparam = sqlsrv_fetch_array($resparam)){
							?>
						    <label class="custom-control custom-checkbox col-sm-2">
								<input id="bacthparameter" type="checkbox" class="custom-control-input bacthparameter"  name="bacthparameter[]" value="<?php echo $rowparam['Sensor_Tag']; ?>">
                                <span class="custom-control-indicator"></span> <?php echo $rowparam['Sensor_Name']; ?>
                           </label>
						
						<?php } ?>	
                </div>
				<p>&nbsp;</p>
				 <div class="col-sm-12"  <?php if($intervalselection != 1){ ?>style="display:none;"<?php } ?>>
                  <h3 class="card-body__title"> Select Time Interval</h3>
                    <div class="form-group">
                        <div class="col-sm-12"><input type="radio" id="batchtimediff" name="batchtimediff" value="1" > <span class="radi" > 1 Second</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="30" checked> <span class="radi" > 30 Second</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="60"> <span class="radi"> 1 Minute</span>
                       <input type="radio" id="batchtimediff" name="batchtimediff" value="120"> <span class="radi">2 Minutes</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="180"> <span class="radi"> 3 Minutes</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="300"> <span class="radi">5 Minutes</span></div>
                    </div>
               </div>					
            <div class="modal-footer">
				<button id="batchview" class="btn btn-primary" onclick="gobatchreport();" type="button"><i class="fa fa-arrow-circle-right"></i>
				View Report</button>
				<button class="btn btn-danger" data-dismiss="modal" type="button"><i class="fa fa-times-circle"></i>
				Cancel</button></div>
            </div>
         </div>
      </div>
   </div>

<!--  end batch -->   
<script>
function go_details(Vessel_ID,Index)
    {
	  $('#modal_form').modal('show'); 
	  $('[name="Index"]').val(Index);
 }
  function showbatchlogs()
    {	
	 var Vessel_ID = $('input[name=Vessel_ID]').val();
	 var Index = $('input[name=Index]').val();
	 var Batch_Type = $('input[name=Batch_Type]').val();
	 
	 var selectedLanguage = new Array();
		$('input[id="parameter"]:checked').each(function() {
	    selectedLanguage.push(this.value);
		});
	
	if($('[name="timediff"]:checked').length > 0) {  var timediff = $('input:radio[name=timediff]:checked').val(); }
	else {var timediff = '1';}
		
	$('#modal_form').modal('hide');
	window.location.replace("<?php echo $baseurl; ?>process_historian_report.php?parameters=" + selectedLanguage  +"&Vessel_ID=" + Vessel_ID + "&time=" + timediff + "&Index=" +Index + "&Batch_Type="+Batch_Type);
      
    }
	function savesensors()
    {
		var url = "<?php echo $baseurl; ?>controllers/save-sensors.php";
    
        $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
             location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
			location.reload();
            }
        });
    }

function checkbatch(id){
	var arr = new Array();
    var total=$('input[name="batches[]"]:checked').length;	

	if(total <= 4){
		$('#modal_batch').modal('show'); 	
	}else{
		document.getElementById('batches'+id).checked=false;
		alert("Maximum 4 Batches ..!");
	}	
}
 function gobatchreport()
    {  
	var vessel = $('input[name=Vessel_ID]').val();
	var batchtype = $('input[name=Batch_Type]').val();
	var batcharr = new Array();
	var senarr = new Array();
	var datetimesarr = new Array();
	$('input[name="batches[]"]:checked').each(function() {
	    batcharr.push(this.value);
	});		
	$('input[name="bacthparameter[]"]:checked').each(function() {
	    senarr.push(this.value);
	});
	
	if($('[name="batchtimediff"]:checked').length > 0) {  var timediff = $('input:radio[name=timediff]:checked').val(); }
	else {var timediff = '30';}
		
	$('#modal_batch').modal('hide');
	window.location.replace("<?php echo $baseurl; ?>batch-comparisson-report.php?&batcharr=" + batcharr + "&senarr=" + senarr  + "&vessel=" + vessel +"&batchtype=" + batchtype + "&time=" + timediff);
      
    }	
</script>   
</section>
<?php include('includes/footer.php');?>