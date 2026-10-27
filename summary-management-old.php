<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Summary Management    </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_dbdetails()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add New</button></p>
         </div>
      </div>
   </header>
   <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">		
            <table id="data-table" class="table">
               <thead>
                <tr>
				  <th>Sl.No</th>
                  <th width="10%">Summary For</th>
                  <th width="12%">Vessel</th>
                  <th >Sequence</th>
                  <th >Step NO</th>
				  <th>Sensor</th>
				 <!-- <th>F0 Result</th>
				  <th>F0 Limit</th>
                  <th>Temperature</th>
				  <th>Z</th>
				  <th>PHT</th>----> 
				  <th width="20%">Action</th>
                </tr>
               </thead>
                <tbody>
				<?php
                $selections	= array("f0_sel"=>"F0","con_sel"=>"Conductivtity","pht_sel"=>"PHT","age_sel"=>"Fermentation Age","avg_sel"=>"MIN/MAX/AVG",'nwf_sel'=>"Water Flux Rate",'diffsel'=>"Integrity Test",'dia_fil'=>"Diafiltration");		
				$sql= "SELECT Vessel_ID,Vessel_Name FROM VESSEL_DETAILS ORDER BY Vessel_Order ASC";
				$vess= sqlsrv_query($conn, $sql);
				$vessel_arr = array();
				while($row = sqlsrv_fetch_array($vess,SQLSRV_FETCH_ASSOC)){
					$vessel_arr[]=$row;
				}
				
				$x= 0;
				
				function getstepname($stepno,$seqid,$conn){
					$sqlstepname = "SELECT Step_Tag FROM [STEP_LOGS] WHERE Step_No='$stepno' AND Sequence_ID='$seqid'";
			     	$resstepname = sqlsrv_query($conn, $sqlstepname);
					$rowstepname = sqlsrv_fetch_array($resstepname,SQLSRV_FETCH_ASSOC);
					return $rowstepname['Step_Tag'];
				}
			
				$sql= "SELECT * FROM SUMMARY_DETAILS";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
					$Vessel_ID=$row['Vessel_ID'];
					$Sequence_ID=$row['Sequence_ID'];
					$Pht_Allowable=$row['Pht_Allowable'];
					$Step_No=$row['Step_No'];
					$Pht_Step1=$row['Pht_Step1'];
					$Pht_Step2=$row['Pht_Step2'];
				    
					$sql1= "SELECT Vessel_Name FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
				    $res1= sqlsrv_query($conn, $sql1);
					$row1=sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC);
					
					$sql2= "SELECT Sequence_Name FROM SEQUENCE_NUMBER_DETAIL WHERE Sequence_ID='$Sequence_ID'";
			     	$res2= sqlsrv_query($conn, $sql2);
					$row2 = sqlsrv_fetch_array($res2,SQLSRV_FETCH_ASSOC);
					
                    if($row["Selection"] == "pht_sel"){	
					if($dbtype == "SCADA"){						
						$sql3= "SELECT ValueID ,ValueName FROM Archive WHERE ValueID = '$Pht_Allowable'";
					}else{
					    $sql3= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE TagUID = '$Pht_Allowable'";
					}
			     	$res3= sqlsrv_query($conn, $sql3);
					$row3 = sqlsrv_fetch_array($res3,SQLSRV_FETCH_ASSOC);
					}
				 	
					$x++;
				?>
                <tr>
                  <td><?php echo $x; ?></td>
				  <td><?php echo $selections[$row['Selection']]; ?></td>
				  <td><?php echo $row1['Vessel_Name']; ?></td>
				  <td><?php echo $row2['Sequence_Name']; ?></td>			  
				  <td><?php echo getstepname($row['Step_No'],$Sequence_ID,$conn); ?></td>
				  <td><?php echo $row['Sensor']; ?></td>
				 				  
				  <td>
				  <div class="btn-group">
                      <button onclick="edit_summary(<?php echo $row['Summary_ID']; ?>,<?php echo $row['Vessel_ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </button>		
                      <button onclick="delete_summary(<?php echo $row['Summary_ID']; ?>)" type="button" class="btn btn-danger"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> </button>
                       <?php 	if($row['Selection'] == "con_sel"){	 ?>
					   <a href="conductivity-management.php?id=<?php echo $row['Summary_ID']; ?>" type="button" class="btn btn-success"><i class="zmdi zmdi-forward  zmdi-hc-fw"></i> </a>
					   <?php } ?>					   
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>           
                </tbody>            
            </table>
         </div>
      </div>
   </div>
   
   <?php 
                        $nwf_sensors = array();
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName NOT LIKE'%PARA%' AND ValueName NOT LIKE'%STEP%' AND ValueName NOT LIKE'%USER%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname NOT LIKE '%PARA%' AND Tagname NOT LIKE '%STEP%' AND Tagname NOT LIKE '%USER%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)):
							$nwf_sensors[] = $row;
						endwhile;
						?>
   
      <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Summary Management</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				<input name="dbid" type="hidden" value="" />
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Selection Type</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="selection" id='selection' onChange="change_selection();">
								<option value='f0_sel'>F0</option>
								<option value='pht_sel'>PHT</option>
								<option value='con_sel'>Conductivtity</option>
								<option value='avg_sel'>Min/Max/Average</option>
								<option value='age_sel'>Fermentation Age</option>
								<option value='nwf_sel'>Water Flux Rate</option>
								<option value='diffsel'>Integrity Test </option>
								<option value='dia_fil'>Diafiltration (Continuous) </option>
						</select>
                     </div>
                </div>
               </div>				
				<div class="col-sm-12">
                  <h3 class="card-body__title">Vessel</h3>
                  <div class="form-group">
                     <div class="select">
                       <select class="form-control" name="vessel" id='vessel' onChange="get_sequences();">
					            <option value="">Select</option>
							    <?php foreach($vessel_arr as $row) { ?>
								<option value='<?php echo $row['Vessel_ID']?>'><?php echo $row['Vessel_Name'];?></option>
								<?php } ?>
								</select>
                     </div>
                </div>
               </div>
                <div class="col-sm-12">
                  <h3 class="card-body__title">Sequence</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="sequence" id='sequence' onChange="get_step_nos(this.value);">
						        <option value="">Select</option>
						</select>
                     </div>
                </div>
               </div>				   
               <div class="col-sm-12" id="step_no_div">
                  <div class="form-group">
				     <label class="card-body__title">Step No</label>
                     <select class="form-control" name="step_no" id="step_no">
					 <option value="">Select</option>
					 </select>
                     <i class="form-group__bar"></i>
                  </div>
                </div>
				 <div class="col-sm-12" id="sensordiv">
                  <h3 class="card-body__title">Sensor</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="sensor" id='sensor'>
							    <option value="">Select</option>
								</select>
                     </div>
                </div>
               </div>
			   <div id="f0_div">
				        <div class="col-md-12 ">
						<div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="f0_result">
                                <span class="custom-control-indicator"></span> F0 Result
                           </label> 						   
						</div>		 
						</div>
						<div class="col-md-12 ">
						    <div class="form-group">
							<label class="card-body__title">Temperature</label>
							<input class="form-control" name="temperature" placeholder="Enter Temperature..." type="number" value="122">
							<i class="form-group__bar"></i>
							</div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
							<label class="card-body__title">Z</label>
								<input class="form-control" name="z" placeholder="Enter Z ..." type="number" value="10">
								<i class="form-group__bar"></i>
							</div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
							<label class="card-body__title">F0 Result Limit</label>
								<input class="form-control" name="f0_res_val" placeholder="Enter F0 Result Limit ..." type="number">
								<i class="form-group__bar"></i>
							</div>
						</div>
			   </div>
						<div  id="con_div" style="display:none;">
						<div class="col-md-12">
						<div class="form-group">
							<label class="card-body__title">Duration (In Seconds)</label>
								<input class="form-control" name="duration" placeholder="Enter duration ..." type="number" value="20">
								<i class="form-group__bar"></i>
							</div>
						</div>						
						</div>
                       <div id="pht_div" style="display:none;">														
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">Result Count</label>
							     <div class="select">
								<select class="form-control" name="result_type" id='result_type' onchange="change_result_count(this.value)">
						        <option value="1">One</option>
						        <option value="2">Two</option>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>			
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">PHT Step 1</label>
							     <div class="select">
								<select class="form-control" name="pht_step1" id='pht_step1'>
						        <option value="">Select</option>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>						
						<div class="col-md-12" id="res2div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">PHT Step 2</label>
							     <div class="select">
								<select class="form-control" name="pht_step2" id='pht_step2' >
						        <option value="">Select</option>
						     </select>
						     </div>
								<i class="form-group__bar"></i>
							</div>
						</div>						
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">Allowable Drop Param Tag</label>
								<select name="pht_alowable1" id="pht_alowable1" class="form-control">
						<?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%PARA%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>
						</select>
								<i class="form-group__bar"></i>
							</div>
						</div>                                            
						</div>
						
						
						<div id="nwf_div">
						
				 <div class="col-sm-12">
                  <h3 class="card-body__title">Qp (Permeate flow l/h) </h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="qpsensor" id='qpsensor'>
							    <option value="">Select</option>
							    <?php foreach($nwf_sensors as $row) { ?>
								<option value='<?php echo $row['ValueID']?>'><?php echo $row['ValueName'];?></option>
								<?php } ?>									
						 </select>
                     </div>
                </div>
               </div>				

			   <!--<div class="col-sm-12">
                  <h3 class="card-body__title">K (Temperature Corrected Flux) </h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="ksensor" id='ksensor'>
							    <option value="">Select</option>
							    <?php foreach($nwf_sensors as $row) { ?>
								<option value='<?php echo $row['ValueID']?>'><?php echo $row['ValueName'];?></option>
								<?php } ?>								
								</select>
                     </div>
                </div>
               </div>--->	
						<div class="col-md-12">
						<div class="form-group">
							<label class="card-body__title">A (Membrane area) </label>
								<input class="form-control" name="nwfa" id="nwfa" placeholder="Enter Area  ..." type="text">

						</div>	
						<i class="form-group__bar"></i>
							</div>
							
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">Recommended flow </label>
								<select name="Recommendedflow" id="Recommendedflow" class="form-control">
								<option value="">Select</option>
						<?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%PARA%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>
						</select>
								<i class="form-group__bar"></i>
							</div>
						</div>		
						<div class="col-md-12">
							<div class="form-group">
							<label class="card-body__title">No. of Cassettes
							</label>
							<select class="form-control" name="cassete" id='cassete' >
						        <option value="">Select</option>
								<?php 
								$sqlnwf= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%WATER%'";
								$resnwf = sqlsrv_query($conn,$sqlnwf);
								while($rownwf= sqlsrv_fetch_array($resnwf,SQLSRV_FETCH_ASSOC)){
								?>
								<option value="<?php echo $rownwf['ValueID']; ?>"><?php echo $rownwf['ValueName']; ?></option>								
								<?php } ?>
								</select>
							</div>
						</div>		
							
						
							
						
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">TMP Type</label>
							     <div class="select">
								<select class="form-control" name="tmp_type" id='tmp_type' onchange="change_tmp_sel(this.value)">
						        <option value="1">Direct</option>
						        <option value="2">Indirect</option>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>
						
                       <div class="col-sm-12" >
                  <h3 class="card-body__title" id="tmpsns1">Trans Membrane Pressure</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="tmpsensor1" id='tmpsensor1'>
							    <option value="">Select</option>
							    <?php foreach($nwf_sensors as $row) { ?>
								<option value='<?php echo $row['ValueID']?>'><?php echo $row['ValueName'];?></option>
								<?php } ?>								
								</select>
                     </div>
                </div>
               </div>
			   
			   <div id="indirect" style="display:none;">
			   <div class="col-sm-12" >
                  <h3 class="card-body__title">Retentate Pressure</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="tmpsensor2" id='tmpsensor2'>
							    <option value="">Select</option>
							    <?php foreach($nwf_sensors as $row) { ?>
								<option value='<?php echo $row['ValueID']?>'><?php echo $row['ValueName'];?></option>
								<?php } ?>								
								</select>
                     </div>
                </div>
               </div>			
			   <div class="col-sm-12" >
                  <h3 class="card-body__title">Permeate Pressure</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="tmpsensor3" id='tmpsensor3'>
							    <option value="">Select</option>
							    <?php foreach($nwf_sensors as $row) { ?>
								<option value='<?php echo $row['ValueID']?>'><?php echo $row['ValueName'];?></option>
								<?php } ?>								
								</select>
                     </div>
                </div>
               </div>
			   <div class="alertfixed alert-info">NOTE : TMP= [(Feed Pressure + Retentate Pressure)/2]- Permeate Pressure.</div>
               </div>
						</div>
						
						
					<div id="diffusion_div">
						
				<!----- <div class="col-sm-12">
                  <h3 class="card-body__title">Sensor for Initial Pressure </h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="diff_initial" id='diff_initial'>
							    <option value="">Select</option>
							    <?php //foreach($nwf_sensors as $row) { ?>
								<option value='<?php //echo$row['ValueID']?>'><?php //echo $row['ValueName'];?></option>
								<?php //} ?>									
						 </select>
                     </div>
                </div>
               </div>
				<div class="col-sm-12">
                  <h3 class="card-body__title">Sensor for Final Pressure </h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="diff_final" id='diff_final'>
							    <option value="">Select</option>
							    <?php //foreach($nwf_sensors as $row) { ?>
								<option value='<?php //echo $row['ValueID']?>'><?php //echo $row['ValueName'];?></option>
								<?php //} ?>									
						 </select>
                     </div>
                </div>
               </div>---->
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Inletvolume</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="diff_l02" id='diff_l02'>
						 <option value="">Select</option>
							    <?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%INTEGRITY%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>								
						 </select>
                     </div>
                </div>
               </div>
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Holding Timer</h3>
                  <div class="form-group">
                     <div class="select">
					
                         <select class="form-control" name="diff_p04" id='diff_p04'>
						 <option value="">Select</option>
							   <?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%INTEGRITY%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>									
						 </select>
                     </div>
                </div>
               </div>
			   <!---for acceptable --->
			   <div class="col-sm-12"><hr/>
			   <label ><i style="text-align:center;">For Acceptable Diffusion volume</i></label>
                  <h3 class="card-body__title">Diffusion volume per cassettes</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="diff_l03" id='diff_l03'>
						 <option value="">Select</option>
							    <?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%INTEGRITY%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>								
						 </select>
                     </div>
                </div>
               </div>
			   <div class="col-sm-12">
                  <h3 class="card-body__title">No of cassettes</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control" name="diff_p05" id='diff_p05'>
						 <option value="">Select</option>
							    <?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%INTEGRITY%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%PARA%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
						?>
						<option value="<?php echo $row['ValueID']; ?>"><?php echo $row['ValueName']; ?></option>
						<?php } ?>								
						 </select>
                     </div>
                </div>
               </div>
			   <!---for acceptable --->
			   
			   
               </div>					
						
						
						
						
						
						
						
						
                        <div class="alertfixed alert-info">NOTE : Please ADD Step numbers & Step Description in sequence Management before adding summary.</div>						
                        						
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>
   </div>
<script>
function change_result_count(value){
	if(value == 2)
		$("#res2div").css("display", "block");	
	else
		$("#res2div").css("display", "none");
}
function change_tmp_sel(value){
	if(value == 2){
		$("#indirect").css("display", "block");	
		$("#tmpsns1").html("Feed Pressure");	
	}else{
		$("#indirect").css("display", "none");
	    $("#tmpsns1").html("Trans Membrane Pressure");
	}		
}	
function add_dbdetails()
    {	
    $("#con_div").css("display", "none");	
    $("#pht_div").css("display", "none");	
    $("#f0_div").css("display", "block");	
    $("#step_no").css("display", "block");	
    $("#sensordiv").css("display", "block");	
    $("#nwf_div").css("display", "none");	
    $("#diffusion_div").css("display", "none");	
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals	  
    $('#modal_form').modal('show'); 

    }	
function edit_summary(id,vessel)
    {	
      save_method = 'update';
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-summary.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {   
		
		var selection = data.Selection;
			if(selection == "f0_sel"){	
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "block");	
                $("#step_no_div").css("display", "block");
				$("#sensordiv").css("display", "block");
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
				
			}			
			else if(selection == "con_sel"){
                $("#con_div").css("display", "block");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");
                $("#sensordiv").css("display", "block");
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
			}				
			else if(selection == "pht_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "block");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "none");
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
			}			
			else if(selection == "age_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");				
                $("#sensordiv").css("display", "none");	
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
			}
			else if(selection == "nwf_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");				
				$("#step_no_div").css("display", "block");
				$("#sensordiv").css("display", "none");
				$("#nwf_div").css("display", "block");
				$("#diffusion_div").css("display", "none");
			}
			//diffusion_sel
			else if(selection == "diffsel"){
                 $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");				
                $("#sensordiv").css("display", "block");	
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "block");
				
			}
			
			else {
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");
                $("#sensordiv").css("display", "block");
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
			}
			
            $('[name="vessel"]').val(data.Vessel_ID);
			$('[name="step_no"]').val(data.Step_No);
            $('[name="selection"]').val(data.Selection);
		    if(data.f0_result != 0)
            $('[name="f0_result"]').prop("checked",data.F0_Result);
            $('[name="temperature"]').val(data.Temperature);
            $('[name="z"]').val(data.Z);
            $('[name="f0_res_val"]').val(data.F0_Result_Val);
            $('[name="dbid"]').val(data.Summary_ID);
			$('[name="sequence"]').val(data.Sequence_ID);
			$('[name="sensor"]').val(data.Sensor);
			$('[name="duration"]').val(data.Duration);
			$('[name="result_type"]').val(data.Result_Type);
			get_sequences_edit(data.Vessel_ID,data.Sequence_ID,data.Sensor);
			get_step_nos(data.Sequence_ID,data.Step_No,data.Pht_Step1,data.Pht_Step2);
			change_result_count(data.Result_Type);
			//pht
			$('[name="pht_step1"]').val(data.Pht_Step1);
			$('[name="pht_step2"]').val(data.Pht_Step2);
			$('[name="pht_alowable1"]').val(data.Pht_Allowable);
			
			//nwf
			
			$('[name="qpsensor"]').val(data.Qpsensor);
			$('[name="ksensor"]').val(data.Ksensor);
			$('[name="nwfa"]').val(data.Nwfa);
			$('[name="cassete"]').val(data.cassete);
			$('[name="tmp_type"]').val(data.Tmp_Type);
			$('[name="tmpsensor1"]').val(data.Tmpsensor1);
			$('[name="tmpsensor2"]').val(data.Tmpsensor2);
			$('[name="tmpsensor3"]').val(data.Tmpsensor3);
			
			//$('[name="diff_initial"]').val(data.diff_initial);
			//$('[name="diff_final"]').val(data.diff_final);
			$('[name="diff_l03"]').val(data.cassette_vol);
			$('[name="diff_p05"]').val(data.cassette_number);
			$('[name="diff_l02"]').val(data.inletvolume);
			$('[name="diff_p04"]').val(data.holdingtimer);
			$('[name="Recommendedflow"]').val(data.Recommendedflow);
			

			change_tmp_sel(data.Tmp_Type);
			
            $('#modal_form').modal('show'); 
	
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }
			
function save()
    {
      var url;
      if(save_method == 'add')
      {
         url = "<?php echo $baseurl; ?>controllers/add-summary.php";
      }
      else
	  {
		url = "<?php echo $baseurl; ?>controllers/update-summary.php";  
		  
	  }
 
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
              $('#modal_form').modal('hide');
             location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
			   $('#modal_form').modal('hide');
			  location.reload();
            }
			
        });
    }

//delete Sequence
function delete_summary(hide_id)
	{
		 if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-summary.php",
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
	function get_sequences_edit(vessel,sequence,sensor)
	{
		
		 var data ={"vessel_id":vessel}; 
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-sequences.php",
         success: function(data)
         	{				
			 $('#sequence').html(data);
			 $('#sequence').val(sequence);
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });
			
		 var data ={"vessel_id":vessel}; 	
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-sensors.php",
         success: function(data)
         	{				
			 $('#sensor').html(data);
			 $('#sensor').val(sensor);
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });			
	} 
	function get_sequences()
	{
	     var id = $('#vessel').val(); 
		 var data ={"vessel_id":id}; 
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-sequences.php",
         success: function(data)
         	{				
			 $('#sequence').html(data);
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });
			
		 var data ={"vessel_id":$('#vessel').val()}; 	
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-sensors.php",
         success: function(data)
         	{				
			 $('#sensor').html(data);
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });		
						
	} 
	function get_step_nos(sequence,stepno=0,pht_step1=0,pht_step2=0){
	 var data ={"sequence":sequence}; 	
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-step-nos.php",
         success: function(data)
         	{				
			 $('#step_no').html(data);
			 $('#pht_step1').html(data);
			 $('#pht_step2').html(data);
			if(stepno!=0)
				$('#step_no').val(stepno);
			if(pht_step1!=0)
				$('#pht_step1').val(pht_step1); 
			if(pht_step2!=0)
				$('#pht_step2').val(pht_step2); 
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				alert('Error get data from ajax');
            }
				
            });	
	}
	function change_selection(){
		var sel = $('#selection').val(); 
            if(sel == "f0_sel"){	
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "block");	
                $("#step_no_div").css("display", "block");
				$("#sensordiv").css("display", "block");
				$("#diffusion_div").css("display", "none");
				
			}			
			else if(sel == "con_sel"){
                $("#con_div").css("display", "block");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");
                $("#sensordiv").css("display", "block");
				$("#diffusion_div").css("display", "none");				
			}				
			else if(sel == "pht_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "block");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "none");
				$("#diffusion_div").css("display", "none");
			}			
			else if(sel == "age_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");				
                $("#sensordiv").css("display", "none");	
				$("#diffusion_div").css("display", "none");				
			}
			else if(sel == "nwf_sel"){
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");				
				$("#step_no_div").css("display", "block");
				$("#sensordiv").css("display", "none");	
				$("#nwf_div").css("display", "block");
				$("#diffusion_div").css("display", "none");
			}
			//diffusion_sel
			else if(sel == "diffsel"){
                 $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");				
                $("#sensordiv").css("display", "block");	
				$("#nwf_div").css("display", "none");
				$("#diffusion_div").css("display", "block");
			}
			
			
			else {
                $("#con_div").css("display", "none");	
                $("#pht_div").css("display", "none");	
                $("#f0_div").css("display", "none");	
                $("#step_no_div").css("display", "block");
                $("#sensordiv").css("display", "block");
				$("#diffusion_div").css("display", "none");				
			}
	}



	
</script>   
</section>
<?php include('includes/footer.php');?>