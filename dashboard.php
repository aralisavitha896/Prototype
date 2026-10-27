<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1>Dashboard</h1>
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
    <?php 
    $com = new COM('Scripting.FileSystemObject');
	$memory_info= array();
	$base_gb = pow(1024,3);
	foreach($com->Drives as $drive){
		 $drive_letter = $drive->DriveLetter;
		 $free = disk_free_space("$drive_letter:")/$base_gb;
         $total = disk_total_space("$drive_letter:")/$base_gb;
		 $percent= $free / $total * 100;
		 $memory_info[$drive_letter]['free'] = $free;
		 $memory_info[$drive_letter]['total'] = $total;
		 $memory_info[$drive_letter]['percent'] = $percent;
	}
	
    $vessel_arr =array();
    $sql= "SELECT * FROM VESSEL_DETAILS ORDER BY Vessel_ID ASC";
    $res= sqlsrv_query($conn, $sql);
	while($row = sqlsrv_fetch_array($res)){ 
	   $vessel_arr[]=$row; 
	}
  ?>
   <div class="row quick-stats">
     <?php if(in_array(1,$menus)){?>
      <div class="col-sm-6 col-md-3">
         <a href="connection-management.php">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-settings zmdi-hc-fw"></i></p> 
               <div class="quick-stats__info">
                  <h2>Database</h2>
                  <small>Management</small>
               </div> 
            			   
            </div>
         </a>
      </div> 
	 <?php }if(in_array(2,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a href="user-management.php">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i></p> 
               <div class="quick-stats__info">
                  <h2>User</h2>
                  <small>Management</small>
               </div> 
            			   
            </div>
         </a>
		 
      </div>
	 <?php }if(in_array(3,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a href="layout-management.php">
            <div class="quick-stats__item">   
			<p class="fifty"><i class="zmdi zmdi-shape zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Layout</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>  
	 <?php } if(in_array("4",$menus) && $suitstatus =="yes"){?>	  
	  <div class="col-sm-6 col-md-3">
         <a href="suit-management.php">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Suit</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>	 
	 <?php } if(in_array("5",$menus)){?>	  
	  <div class="col-sm-6 col-md-3">
         <a href="<?php if($suitstatus == "yes") { ?>suits-vessel-mgt.php<?php }else{ ?> vessel-management.php<?php } ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-balance-wallet zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Vessel</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } if(in_array(6,$menus)){?>
      <div class="col-sm-6 col-md-3">
         <a href="<?php if($suitstatus == "yes") { ?>suits-sequence-mgt.php<?php }else{ ?>vessel-details-sequence-mgt.php <?php } ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-layers zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Sequence</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } if(in_array(7,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a href="<?php if($suitstatus == "yes") { ?>suits-sensor-mgt.php<?php }else{ ?>vessel-details-sensor-mgt.php<?php } ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-rss zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Sensor</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } if(in_array(8,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a href="summary-management.php">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-file-text zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Summary</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } if(in_array(9,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a href="<?php if($suitstatus == "yes") { ?>suits-report.php<?php }else{ ?>vessel-details-report.php<?php } ?>">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-collection-text zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Reports</h2>
                  <small>View & Print</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } if(in_array(10,$menus)){?>	  
      <div class="col-sm-6 col-md-3">
         <a onclick="viewmodal();" href="#">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-alarm zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Manual Alarms /<br> Events</h2>
                  <small>View & Print</small>
               </div>
			    
            </div>
         </a>
      </div>	
	 <?php } if(in_array(11,$menus)){ ?>	

	<div class="col-sm-6 col-md-3">
         <a href="online-batch-comparision.php" href="#">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-chart zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Online Batch <br> Comparison</h2>
                  <small>View & Print</small>
               </div>
			    
            </div>
         </a>
      </div>	
	 <?php } if(in_array(12,$menus)){   ?>
	 <div class="col-sm-6 col-md-3">
         <a href="customer_details.php" href="#">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Customer Details</h2>
                  <small>Management</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php }if(in_array(13,$menus)){ ?>
	 <div class="col-sm-6 col-md-3">
         <a onclick="deletedata();" href="#">
            <div class="quick-stats__item">
			<p class="fifty"><i class="zmdi zmdi-delete	 zmdi-hc-fw"></i></p>
               <div class="quick-stats__info">
                  <h2>Clear Database</h2>
                  <small>Specific Data Delete</small>
               </div>
			    
            </div>
         </a>
      </div>
	 <?php } ?>
	 
   </div>
                       <div class="card widget-pie">
					    <?php foreach($memory_info as $drive_name => $row ) { ?>
					   
					   
                        <div class="col-6 col-sm-4 col-md-6 col-lg-4 widget-pie__item">

                            <div class="easy-pie-chart" data-percent="<?php echo $row['percent'];?>" data-size="100" data-track-color="rgba(0,0,0,0.5)" data-bar-color="#fff">
                                <span class="easy-pie-chart__value"><?php echo round($row['percent'],2);?></span>
                                
                            </div>
                            <div class="widget-pie__title" style="font-size: large;"><?php echo $drive_name;?> </div>
                            <div class="widget-pie__title"><?php echo round($row['free'],2);?> GB AVAILABLE/ <?php echo round($row['total'],2);?> GB</div>
                        </div>
						
                        <?php } ?>

                    </div>
   
      <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Manual Alarms / Events</h5>
            </div>
            <div class="modal-body">
			    <form id="formeqn" action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <div class="col-sm-12">
                  <h3 class="card-body__title">Selection</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name='selection' id='selection' onChange="myevents(this.value)">
                          <option value='alarms'>Alarms</option>	
                          <option value='events'>Events</option>	
                        </select>
                     </div>
                  </div>
               </div>				   

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
                  <h3 class="card-body__title">Vessel Name</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name='vessel' id='vessel'>
                          <option value='All'>All</option>
						  <?php foreach($vessel_arr as $row)	{ ?>
						  <option class="eid" value='<?php echo $row['Vessel_ID']; ?>'><?php echo $row['Vessel_Name']; ?></option>
						  <?php } ?>	
                        </select>
                     </div>
                  </div>
               </div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="viewreport()">Next</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>
   
   
   <!----delete data---->
   
   <div class="modal fade" id="deletedata" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Clear Database</h5>
            </div>
            <div class="modal-body">
			    <form id="formdelete" action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			   				   

		        <div class="form-group">
                <label>Start Date Time</label>
                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd/mm/yyyy  HH:ii p" data-link-field="starttimeclear">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"><i class="zmdi zmdi-close"></i></span></span>
					<span class="input-group-addon">
					<input class="form-control" size="100" type="text" value="" readonly>
					<span class="glyphicon glyphicon-th"><i class="zmdi zmdi-calendar"></i></span></span>
                </div>
				<input type="hidden" name="starttimeclear" id="starttimeclear" value="" /><br/>
               </div>		  
			   <div class="form-group">
                <label>End Date Time</label>
                <div class="input-group date form_datetime col-md-12" data-date="" data-date-format="dd/mm/yyyy  HH:ii p" data-link-field="endtimeclear">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"><i class="zmdi zmdi-close"></i></span></span>
					<span class="input-group-addon">
					<input class="form-control" size="100" type="text" value="" readonly>
					<span class="glyphicon glyphicon-th"><i class="zmdi zmdi-calendar"></i></span></span>
                </div>
				<input type="hidden" name="endtimeclear" id="endtimeclear" value="" /><br/>
               </div>
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Vessel Name</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name='vesselclear' id='vesselclear'>
                          <option value=''>Select Vessel</option>
						  <?php foreach($vessel_arr as $row)	{ ?>
						  <option value='<?php echo $row['Vessel_ID']; ?>'><?php echo $row['Vessel_Name']; ?></option>
						  <?php } ?>	
                        </select>
                     </div>
                  </div>
               </div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="cleardata()">Clear Data</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>
   <!----delete data---->
   
   
   
 <style>
 .table-condensed{
	width: 300px;
    height: 220px;
	background-color: white;
    color: black;
 }
 </style>
 <link href="./datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">			
<script type="text/javascript" src="./datetimepicker/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="./datetimepicker/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="./datetimepicker/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>  
 <script type="text/javascript">
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
</script>	
   <style>
.flatpickr-time input {
    background: #3f7088;
}
.flatpickr-time .flatpickr-am-pm:hover {
	background-color: #3f7088;
}
.flatpickr-time .flatpickr-am-pm {
	background-color: #3f7088;
    margin-left: 6px;
}
.datetime-local{
	width:100%;height:2.4em;background-color:#0b2627;color:white;
}
   </style>
<script type="text/javascript">
    function viewmodal()
    { 
       $('#modal_form').modal('show'); 
    }    
	function deletedata()
    { 
       $('#deletedata').modal('show'); 
    }  
	function myevents(id)
	{
		if(id=='events')
		{$('.eid').hide();}else{$('.eid').show();}
		
	}
	function viewreport()
    {  
	var selection = $('#selection').val();
	var starttime = $('#starttime').val();
	var endtime = $('#endtime').val();
	var vessel = $('#vessel').val();
	var dbtype ="<?php echo $dbtype; ?>";
	if(dbtype == "AB"){
	if(selection == "alarms")
	window.location.replace("<?php echo $baseurl; ?>manual-alarms.php?starttime=" + starttime + "&endtime=" + endtime + "&vessel=" + vessel);
    else
	window.location.replace("<?php echo $baseurl; ?>manual-events.php?starttime=" + starttime + "&endtime=" + endtime + "&vessel=" + vessel);
	}else{
	if(selection == "alarms")
	window.location.replace("<?php echo $baseurl; ?>manual-alarms.php?starttime=" + starttime + "&endtime=" + endtime + "&vessel=" + vessel);
    else
	window.location.replace("<?php echo $baseurl; ?>manual-events.php?starttime=" + starttime + "&endtime=" + endtime + "&vessel=" + vessel);	
	} 
	}
	
	function view_user_login_modal()
    { 
    $('#modal_user_login').modal('show'); 
    }	
	
	function viewuserloginreport()
    {
	 var starttime = $('input[name=starttimeU]').val();
	 var endtime = $('input[name=endtimeU]').val();
	 window.location.replace("<?php echo $baseurl; ?>user-login-report.php?starttime=" + starttime + "&endtime=" + endtime);
    }
	
	
	function cleardata()
    {
		//var starttimeclear = $('input[name=starttimeclear]').val();
		//var endtimeclear = $('input[name=endtimeclear]').val();
		//var vesselclear = $('input[name=vesselclear]').val();
		$.ajax({
			url : '<?php echo $baseurl; ?>controllers/delete_database.php',
			type: "POST",
			data: $('#formdelete').serialize(),
			dataType: "JSON",
			success: function(data)
			{ 
			  $('#deletedata').modal('hide');
				//location.reload();
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				$('#deletedata').modal('hide');
				//location.reload();
			}
		});
    }
	
	
</script>   
</section>
<?php include('includes/footer.php');?>