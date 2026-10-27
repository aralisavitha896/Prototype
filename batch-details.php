<?php include('includes/header.php'); 

	if(isset($_REQUEST['vessel']))
	{ $batch = $_REQUEST['batch'];  $vessel = $_REQUEST['vessel'];  }

	$sqlsequence = "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Batch_Type = '$batch'";
	$ressequence = sqlsrv_query($conn, $sqlsequence);
	$rowsequence = sqlsrv_fetch_array($ressequence,SQLSRV_FETCH_ASSOC);
	$sequence = $rowsequence['ID'];
	$seqtype = $rowsequence['seqtype'];
	$sequence_name = $rowsequence['Seqname'];
			
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb,SQLSRV_FETCH_ASSOC);
	$vessel_name = $rowdb['Vessel_Name'];
	$Vessel_Code = $rowdb['Vessel_Code'];
	
	
	
?>
<section class="content">
   <header class="content__title">
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $vessel_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $sequence_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> BATCH DETAILS</h1>
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
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">		
            <table id="data-table-batch" class="table">
               <thead>
                <tr>
				  <th>SL NO</th>
				  <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'batch'";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout,SQLSRV_FETCH_ASSOC)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th><?php echo $rowlayout['Name']; ?></th>					 
                  <?php } ?>	
                 <th>ACTION</th>				  
                 				  
                </tr>
               </thead>
                <tbody>
	<?php
$arr = array();
$i = 1;
$j = 0;
$valcheckarry = array();
$valcheckarr = array();
$result = array();
$a=0; $b=0;
if ($seqtype == "1") {
	$vcode= str_replace("O",0,$Vessel_Code);
	$table = "SubRptFloat_".$vcode;
	$tablemain2 = "SubRptString_".$vcode;
	$tableindex = "SubRptIndex_".$vcode;	
}
else {
	
	$vcode= str_replace("O",0,$Vessel_Code);
	$table = "MainRptFloat_".$vcode;
	$tablemain2 = "MainRptString_".$vcode;
	$tableindex = "MainRptIndex_".$vcode;
} 	
//for data filter
$valcheckarry = array();
$valcheckarr = array();
$result = array();
$a=0; $b=0;
       $sqlmain = "SELECT * FROM $table where Val = '$batch' AND Marker='S'	order by DateAndTime DESC";
	  $resmain = sqlsrv_query($conn, $sqlmain);
	  while($rows = sqlsrv_fetch_array($resmain)) { 	
	  $ref_date= $rows['DateAndTime']->format("Y-m-d H:i:s");
	  $Millitm[]=$rows['Millitm'];
	//AND Marker='S'	
	    $sql_string = "SELECT * FROM $tablemain2 where DateAndTime  = '$ref_date'  AND Val != '' order by DateAndTime DESC ";	
	   $res_string= sqlsrv_query($conn, $sql_string);
	   while($rows_string = sqlsrv_fetch_array($res_string))
		 {	
				$valcheckarr[$a][$b] = $rows_string['Val'];
				$valcheckarry[$a][$rows_string['TagIndex']] = $rows_string['Val'];
		 	$b++;
		 }
	 
	 
	 
	 $a++;
		
 }
//for data filter

$ab=0; $nb=0;
foreach($valcheckarry as $data)
{
	
	$ab++;
	if ($seqtype == "1") { 
	$starttime =  $data[$rowdb['startime2']];
	$endtime =  $data[$rowdb['endtime2']];
	$batchname =  $data[$rowdb['batchname2']];
	$batchdesc =  $data[$rowdb['batchdesc2']];
	$starttimesort =  $data[$rowdb['starttimesort2']];
	$endtimesort =  $data[$rowdb['endtimesort2']];
	}else{
	$starttime =  $data[$rowdb['startime']];
	$endtime =  $data[$rowdb['endtime']];
	$batchname =  $data[$rowdb['batchname']];
	$batchdesc =  $data[$rowdb['batchdesc']];
	$starttimesort =  $data[$rowdb['starttimesort']];
	$endtimesort =  $data[$rowdb['endtimesort']];		
		
	}
	

?>  
<tr>
<td><?php  echo $ab;  ?> </td>
<td><?php echo date($dateformat,strtotime($starttime));   ?></td>
<td><?php echo date($dateformat,strtotime($endtime)); ?></td>
<td><?php echo $batchname; ?></td>
<td><?php echo $batchdesc; ?></td>

<td>
<button class="btn btn-primary  btn-xs"  onclick="go_details(<?php echo "'" . $starttimesort . "','" . $endtimesort . "'," . $vessel . "," . $batch . ",'" . base64_encode($batchname) . "','" . base64_encode($batchdesc). "'"; ?>)"><i class="fa fa-external-link-square"></i> View Report</button>
<?php if($user_type =='Superadmin'){?>
<button onclick="delete_report(<?php echo $Millitm[$nb]; ?>,<?php echo $vessel; ?>,<?php echo $batch; ?>,'<?php echo $starttimesort; ?>','<?php echo $endtimesort; ?>')" type="button" class="btn btn-danger"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> </button><?php } ?>
</td>
</tr>
<?php $nb++; } ?>
                      
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
					<input name="book_id" type="hidden" value="" />
					<input name="datatbl" type="hidden" value="" />
					<input class="form-control" name="starttime"  type="hidden">
					<input class="form-control" name="endtime"  type="hidden" >
					<input class="form-control" name="vessel"  type="hidden" >
					<input class="form-control" name="batch"  type="hidden" >									
					<input class="form-control" name="batchname"  type="hidden" >									
					<input class="form-control" name="batchdesc"  type="hidden" >					   
					<input class="form-control" name="sequence"  type="hidden" value="<?php echo $sequence; ?>">					   
                <div class="col-sm-12">
				<span class="radittl" ><p class="card-body__title"><i class="fa fa-check-circle"></i> Select Sensors</p></span>
						<?php 
						$sqlsen= "SELECT Sensor_Selection FROM SEQUENCE_NUMBER_DETAIL WHERE ID = '$sequence'";
						$ressen= sqlsrv_query($conn, $sqlsen);
						$rowsen = sqlsrv_fetch_array($ressen,SQLSRV_FETCH_ASSOC);
						$Sensor_Selection = $rowsen['Sensor_Selection'];
						
						$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE  Vessel_ID = '$vessel' AND Sensor_Selected = 1 ";
						$resparam= sqlsrv_query($conn, $sqlparam);
						while($rowparam = sqlsrv_fetch_array($resparam,SQLSRV_FETCH_ASSOC)){
							?>
						    <label class="custom-control custom-checkbox col-sm-3">
								<input id="parameter" type="checkbox" class="custom-control-input"  name="parameter[]" value="<?php echo $rowparam['Sensor_Tag']; ?>" <?php $sen = explode(',', $Sensor_Selection); foreach($sen as $selsen){ if($selsen == $rowparam['Sensor_Tag']) { echo 'checked'; } } ?>  <?php if($sensorselection != 1){ ?>disabled<?php } ?>>
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
					<input name="vessel" type="hidden" value="<?php echo $vessel; ?>" />
	

								   
                <div class="col-sm-12">
				<span class="radittl" ><p class="card-body__title"><i class="fa fa-check-circle"></i> Select Sensors</p></span>
						<?php 
						$i=0;
						$sqlsen= "SELECT Sensor_Selection FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID = '$sequence'";
						$ressen= sqlsrv_query($conn, $sqlsen);
						$rowsen = sqlsrv_fetch_array($ressen,SQLSRV_FETCH_ASSOC);
						$Sensor_Selection = $rowsen['Sensor_Selection'];
						$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE  Vessel_ID = '$vessel' AND Sensor_Selected = 1 ";
						$resparam= sqlsrv_query($conn, $sqlparam);
						while($rowparam = sqlsrv_fetch_array($resparam,SQLSRV_FETCH_ASSOC)){
							?>
						    <label class="custom-control custom-checkbox col-sm-2">
								<input id="bacthparameter<?php echo $i; ?>" type="checkbox" class="custom-control-input bacthparameter"  name="bacthparameter[]" value="<?php echo $rowparam['Sensor_Tag']; ?>" onchange="checksensors(<?php echo $i++; ?>)"<?php if($sensorselection != 1){ ?>disabled<?php } ?>>
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
function go_details(starttime,endtime,vessel,batch,batchname,batchdesc)
    {
		
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
 
      $('[name="starttime"]').val(starttime);
      $('[name="endtime"]').val(endtime);
	  $('[name="vessel"]').val(vessel);
	  $('[name="batch"]').val(batch);
	  $('[name="batchname"]').val(batchname);
	  $('[name="batchdesc"]').val(batchdesc);
	  $('#modal_form').modal('show'); 
		
    }	

//checkbox count checking
	$(document).ready(function() {
	$('.parameter').change(function(){
		var total=$('input[name="parameter[]"]:checked').length;
		if(total > 15)
		{
			alert("Warning! Maximum Limit is 15");
			this.checked = false;
		}
		
	});
});
//checkbox count checking
	
//for check all 
$("#checkAll").click(function(){
$('input:checkbox').not(this).prop('checked', this.checked);
});
//for check all 

	
function showbatchlogs()
    {
      var url;
      if(save_method == 'add')
      {
         url = "<?php echo $baseurl; ?>batch-logs-search.php";
      }  
	 var starttime = $('input[name=starttime]').val();
	 var endtime = $('input[name=endtime]').val();
	 var vessel = $('input[name=vessel]').val();
	 var batch = $('input[name=batch]').val();
	 var batchname = $('input[name=batchname]').val();
	 var batchdesc = $('input[name=batchdesc]').val();
	
	 var selectedLanguage = new Array();
		$('input[id="parameter"]:checked').each(function() {
	    selectedLanguage.push(this.value);
		});
	
	if($('[name="timediff"]:checked').length > 0) {  var timediff = $('input:radio[name=timediff]:checked').val(); }
	else {var timediff = '30';}

	
	if($('input[name="parameter[]"]:checked').length > 0){	
	$('#modal_form').modal('hide');
	window.location.replace("<?php echo $baseurl; ?>tag-logging-report.php?starttime=" + starttime + "&endtime=" + endtime + "&parameters=" + selectedLanguage + "&vessel=" + vessel  + "&batch=" + batch + "&batchname="+batchname +"&batchdesc="+batchdesc+ "&duration="+timediff);
	}else{
		alert("You must select any one of the sensor");
	}
      
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

// delete sequence
function delete_subdetails(id,table)
    {
	if(confirm('Warning! Are You Sure TO Delete This?')){
      $.ajax({
        url : "<?php echo $baseurl; ?>delete_table_data.php",
        type: "POST",
		data:{id:id,table:table},
        success: function(data)
        {
			location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error occured');
        }
    });
	}
    }
	$('.bacthparameter').change(function(){
		var total=$('input[name="bacthparameter[]"]:checked').length;
		if(total > 5)
		{
			alert("Warning! Maximum Limit is 5");
			this.checked = false;
		}
		
	});	
function showbatch(){
	$('#modal_batch').modal('show'); 	
}
function checksensors(id){
	 var total=$('input[name="bacthparameter[]"]:checked').length;	
	 if(total > 4){
		$( "#showbatch" ).prop( "disabled", true );
		document.getElementById('bacthparameter'+id).checked=false;
		alert("Maximum 4 Sensors ..!");
	}
}	
function checkbatch(id){
	var arr = new Array();
    var total=$('input[name="batches[]"]:checked').length;	
	var checked = document.getElementById('batches'+id).checked;
	if(checked)
	document.getElementById('datetimes'+id).checked=true;
    else
	document.getElementById('datetimes'+id).checked=false;
    if(total < 2)
        $( "#showbatch" ).prop( "disabled", true );
	if(checked && total > 1){
	if(total < 5){
		$( "#showbatch" ).prop( "disabled", false );
	}else{
		$( "#showbatch" ).prop( "disabled", true );
		document.getElementById('batches'+id).checked=false;
		document.getElementById('datetimes'+id).checked=false;
		alert("Maximum 4 Batches ..!");
	}	
	}
}
 function gobatchreport()
    {  
	var vessel = $('input[name=vessel]').val();
	var sequence = $('input[name=sequence_id]').val();
	var batcharr = new Array();
	var senarr = new Array();
	var datetimesarr = new Array();
	$('input[name="batches[]"]:checked').each(function() {
	    batcharr.push(this.value);
	});		
	$('input[name="datetimes[]"]:checked').each(function() {
	    datetimesarr.push(this.value);
	});	
	$('input[name="bacthparameter[]"]:checked').each(function() {
	    senarr.push(this.value);
	});
	
	if($('[name="batchtimediff"]:checked').length > 0) {  var timediff = $('input:radio[name=timediff]:checked').val(); }
	else {var timediff = '30';}
		
	$('#modal_batch').modal('hide');
	window.location.replace("<?php echo $baseurl; ?>batch-comparisson-report.php?&batcharr=" + encodeURI(batcharr) + "&senarr=" + encodeURI(senarr)  + "&vessel=" + vessel +"&sequence=" + sequence + "&time=" + timediff+"&datetimesarr="+encodeURI(datetimesarr));
      
    }	
	
	
	function delete_report(id,vessel,batch,start,end)
	{ 



	if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":id,"vessel":vessel,"batch":batch,"start":start,"end":end},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-report.php",
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
	} 
</script>   
</section>
<?php include('includes/footer.php');?>