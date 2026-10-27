<?php include('includes/header.php');
    if(isset($_REQUEST['vessel'])) { $Vessel_ID = $_REQUEST['vessel'];}
	
	$vessel_arr =array();
    $sql= "SELECT * FROM VESSEL_DETAILS where [online_comp]='Yes' ORDER BY Vessel_ID ASC";
    $res= sqlsrv_query($conn, $sql);
	while($row = sqlsrv_fetch_array($res)){ 
	   $vessel_arr[]=$row; 
	}
	
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID' AND where [online_comp]='Yes'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb);
	$vessel_name = $rowdb['Vessel_Name'];
	
	$refid = $rowdb['Refid'];
    $arr = array();
    if($refid)
    getparents($refid);
    krsort($arr);
 	
 ?>
<section class="content">
   <header class="content__title">
      <h1>Online Batch Comparison  <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $vessel_name; ?> </h1>
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
	  
			<form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			<div class="row">
			<div class="col-sm-12">
                  <h3 class="card-body__title">Vessel </h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name='vessel_1' id='vessel_1' onChange="check_vessel(this.value);">
                          <option value=''> Select Vessel</option>
						  <?php foreach($vessel_arr as $row)	{  ?>
						  <option value='<?php echo $row['Vessel_ID']; ?>'><?php echo $row['Vessel_Name']; ?></option>
						  <?php } ?>	
                        </select>
						
                     </div>
                  </div>
               </div>
			   <div class="col-sm-12">
			    
                  <h3 class="card-body__title">Active Sequence Start Datetime</h3>
				 
                  <div class="form-group">
				  <input type="text" name="current_dtime" id="current_dtime" class="form-control" />
                  </div>
               </div>
			   
			   <div class="col-sm-12">
			    
                 <div class="form-group">
				     <label class="card-body__title">Step No</label>
                     <select class="form-control" name="step_no" id="step_no">
					 <option value="">Select</option>
					 </select>
                     <i class="form-group__bar"></i>
                  </div>
               </div>
			   
			  
			   <div class="col-sm-12">
			    
                  <h3 class="card-body__title">Sensors</h3>
				  
                  <div class="form-group">
				 
				  <div class="row" id="hi"></div>
                  </div>
               </div>
			   <div class="col-sm-12"  <?php if($intervalselection != 1){ ?>style="display:none;"<?php } ?>>
                  <h3 class="card-body__title"> Select Time Interval</h3>
				  <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12">
						<!---<input type="radio" id="batchtimediff" name="batchtimediff" value="1" > <span class="radi" > 1 Second</span>-->
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="30" checked> <span class="radi" > 30 Second</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="60"> <span class="radi"> 1 Minute</span>
                       <input type="radio" id="batchtimediff" name="batchtimediff" value="120"> <span class="radi"> 2 Minutes</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="180"> <span class="radi"> 3 Minutes</span>
                        <input type="radio" id="batchtimediff" name="batchtimediff" value="300"> <span class="radi"> 5 Minutes</span></div>
						
                    </div>
					</div>
					
               </div>


			   
			   <div class="col-sm-12"> 
			   <div class="table-responsive">
			    
            <!----<table id="data-table" class="table table-bordered">--->
			<table id="data-table-batch" class="table table-bordered">
               <thead>
                <tr>
				  <th width="5%">No</th>
				  <th>Start Time</th>
				  <th>End Time</th>
				  <th>Batch Description</th>
				  <th>Batch type</th>	  
                </tr>
               </thead>
			    <tbody id="seq_1">
				
				<?php 
				
			/*	$k = 0;
				$sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$Vessel_ID'";
				$res= sqlsrv_query($conn, $sql);				
				while($row = sqlsrv_fetch_array($res)){
					$Step_Desc_Tag = $row['Step_Desc_Tag'];
					if($dbtype=="SCADA"){
						$sqlstepdesctag =  "SELECT ValueName AS Step_Desc_Tag FROM Archive WHERE ValueID='$Step_Desc_Tag'";
					}else{
						$sqlstepdesctag =  "SELECT Tagname AS Step_Desc_Tag FROM [IS].VTagBrowsing WHERE TagUID='$Step_Desc_Tag'";
					}
					$resstepdesctag= sqlsrv_query($conn, $sqlstepdesctag);
					$rowstepdesctag = sqlsrv_fetch_array($resstepdesctag);
				?>
                <tr>
                  <td><?php echo ++$k; ?></td>
                  <td><?php echo $row['Batch_Type']; ?></td>
				  <td><?php echo $row['Sequence_Name']; ?></td>
				  <td><?php echo $rowstepdesctag['Step_Desc_Tag']; ?></td>
				  <td><?php echo $row['Completion_Step']; ?></td>
				  <td>
				  <div class="btn-group">
				  	  <a class="btn btn-primary" onclick="edit_sequence(<?php echo $row['Sequence_ID']; ?>)" title="Edit Sequence"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-success"  href="parameter-management.php?sequence=<?php echo $row['Sequence_ID']; ?>&vessel=<?php echo $Vessel_ID; ?>" title="Parameter Management"><i class="zmdi zmdi-plus  zmdi-hc-fw"></i></a>
					  <a class="btn btn-primary" href="step-log-management.php?sequence=<?php echo $row['Sequence_ID']; ?>"  title="Step Log Management" ><i class="zmdi zmdi-eye  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger" onclick="delete_sequence(<?php echo $row['Sequence_ID']; ?>);"  title="Delete Sequence"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
				
                    </div>	
				  </td>
                </tr>
				<?php } */  ?>
					
				
                </tbody>                  
            </table>
         </div>
         </div>
		<input type="hidden" name="MsgNr" id="MsgNr" />
		<input type="hidden" name="DateTime" id="DateTime" class="form-control"/>
		<input type="hidden" name="PText6" id="PText6" />
		<input type="hidden" name="Text2" id="Text2" />
		<input type="hidden" name="Ptext5" id="Ptext5" />
 <div class="modal-footer">
			  
               <button type="button" id="showbatch1" class="btn btn-success" onclick="gobatchreport_1();">View Report</button>

			   


				 
			   
			   
			</form>
	  
	  
	  
	  
         
      </div>
	  
	<div class="col-sm-12">
	  <div id="projectIDSelectError"></div>
	  <div id="projectIDSelectError2"></div>
	  <div id="projectIDSelectError3"></div>
	  <div id="projectIDSelectError4"></div>
   </div>
   </div>
   
     
   
  <style>
  .modal-content{
	  background-color: #0e253a !important;
  }
  .error-msg{
	 color:red;
    font-size: 14px;
    font-weight: bold;
	  }
  </style>
<script>



function check_vessel(ves)
{	var tmp;
	var ves1=$('#vessel_1').val();		 
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-online-sequences.php?vessel_id=" + ves1,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			
		for(i=0; i<data.length; i++){
			
			var online_date = moment(new Date((data[i].DateTime['date']))).format('Y-MM-D H:m:ss');
            $('[name="MsgNr"]').val(data[i].MsgNr);
            $('[name="DateTime"]').val(online_date);
            $('[name="PText6"]').val(data[i].PText6);
            $('[name="Text2"]').val(data[i].Text2);
            $('[name="Ptext5"]').val(data[i].Ptext5);
            $('[name="current_dtime"]').val(data[i].PText3);
			
			}
			 tmp=data[0].PText6; 

				if(tmp != '') {
					
		var data ={"batch_id":tmp,"vessel_id":ves1};
	
		$.ajax({
			data: data,
			type: "post",
			url: "<?php echo $baseurl; ?>controllers/get-online-batch-details.php",
			success: function(data)
			{				
				$('#seq_1').html(data);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
				
		});	
		
		
		//step descripition
		var data ={"batch_id":tmp,"vessel_id":ves1};
	
		$.ajax({
			data: data,
			type: "post",
			url: "<?php echo $baseurl; ?>controllers/get-online-batch-steps.php",
			success: function(data)
			{				
				$('#step_no').html(data);
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Error get data from ajax');
			}
				
		});	
	} 		 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('There is no runnng Sequence!');
        }
    });
	
	var data2 ={"vessel_id":ves1}; 	
		 $.ajax({
         data: data2,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get_online_batch_sensors.php",
         success: function(data)
         	{	
			$("#hi").append(data);
			
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });	
		
	//checkbox count checking
var limit = 3;
$('input.mybacthes').on('change', function(evt) {
   if($(this).siblings(':checked').length >= limit) {
       this.checked = false;
   }
});
	
}





function gobatchreport_1()
   {  
	var step_no = $('#step_no').val();
	var vessel1 = $('#vessel_1').val();
	var Batch_id = $('#PText6').val();
	var MsgNr = $('#MsgNr').val();
	var User = $('#Ptext5').val();
	var DateTime = $('#DateTime').val();
	var batcharr1 = new Array();
	var senarr = new Array();
	var datetimesarr1 = new Array();
	var timediff = '';
	$('input[name="batches[]"]:checked').each(function() {
	    batcharr1.push(this.value);
		datetimesarr1.push($(this).attr('rel'));
	});	 

/*	$('input[name="datetimes[]"]:checked').each(function() {
	    datetimesarr.push(this.value);
	});	*/
	
	$('input[name="sensor_1[]"]:checked').each(function() {
	    senarr.push(this.value);
	});
	var selected_sensor = $('input[name="sensor_1[]"]:checked').length;
	var selected_batch = $('input[name="batches[]"]:checked').length;

	if($('[name="batchtimediff"]:checked').length > 0) {  var timediff = $('input:radio[name=batchtimediff]:checked').val(); }
	else {var timediff = '30';}
	if ($("#vessel_1").val() === ""){$("#projectIDSelectError").html("* Please select a vessel").addClass("alertfixed alert-danger");}
	else if(selected_sensor <= 0){$("#projectIDSelectError2").html("* Please select a sensor").addClass("alertfixed alert-danger");}
	else if(selected_batch <= 0){$("#projectIDSelectError3").html("* Please select a batch details").addClass("alertfixed alert-danger");}
	else if(selected_batch > 5){$("#projectIDSelectError4").html("* Maximum limit of Batch selection is Five!").addClass("alertfixed alert-danger");}
	else{
	window.location.replace("<?php echo $baseurl; ?>online-batch-comparison-report.php?&batcharr=" + encodeURI(batcharr1) + "&senarr=" + encodeURI(senarr)  + "&vessel=" + encodeURI(vessel1) +"&MsgNr=" + encodeURI(MsgNr) +"&Batch_id=" + Batch_id  +"&OnlineDateTime=" + encodeURI(DateTime) + "&time=" + timediff+"&datetimesarr="+encodeURI(datetimesarr1)+"&step="+step_no);
	} 
    }

	
	

</script>   
</section>
<?php include('includes/footer.php');?>