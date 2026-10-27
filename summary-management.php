<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Summary Management </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_dbdetails()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add Summary</button></p>
         </div>
      </div>
   </header>
   <?php include('includes/notification.php'); ?>
    
      <div class="card-body">
         <div class="table-responsive">		
            <table id="data-table" class="table table-bordered">
               <thead>
                <tr>
				  <th>Sl_no</th>
                  <th width="">Vessel_ID</th>
                  <th width="">Summary_Type</th>
                  <th width="">Sequence_ID</th>
				  <th>Result_Count</th>
				  <th width="">Action</th>
                </tr>
               </thead>
                <tbody>
              
				
				<?php
					 $selections	= array("f0_sel"=>"F0","pht_sel"=>"PHT","con_sel"=>"Conductivity");		
						$sql= "SELECT Vessel_ID,Vessel_Name FROM VESSEL_DETAILS ORDER BY Vessel_Order ASC";
						$vess= sqlsrv_query($conn, $sql);
						$vessel_arr = array();
						while($row = sqlsrv_fetch_array($vess,SQLSRV_FETCH_ASSOC)){
							$vessel_arr[]=$row;
						}
						$x= 0;
				
				$sql= "SELECT * FROM SUMMARY_DETAILS";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
					$Vessel_ID=$row['Vessel_ID'];
					$Sequence_ID=$row['Sequence_ID'];
					$Pht_Allowable=$row['Allowable_Drop'];
					$Result_Count=$row['Result_Count'];
					$Final_2=$row['Final_2'];
					$Final_1=$row['Final_1'];
					$Initial_2=$row['Initial_2'];
					$Initial_1=$row['Initial_1'];
					$Summary_Type=$row['Summary_Type'];
					$id=$row['ID'];
				    
					$sql1= "SELECT Vessel_Name FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
				    $res1= sqlsrv_query($conn, $sql1);
					$row1=sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC);
					
					$sql2= "SELECT Seqname FROM SEQUENCE_NUMBER_DETAIL WHERE ID='$Sequence_ID'";
			     	$res2= sqlsrv_query($conn, $sql2);
					$row2 = sqlsrv_fetch_array($res2,SQLSRV_FETCH_ASSOC);
					
                    if($row["Summary_Type"] == "pht_sel")
					{	
						$pht = "PHT";
					}
					else if($row["Summary_Type"] == "f0_sel")
					{
						$pht = "F0";
					}
					else{$pht = "Conductivity";}
				 	
				$x++;
				?><tr>
				  <td><?php echo $x; ?></td>
				  <td><?php echo $row1['Vessel_Name']; ?></td>
				  <td><?php echo $pht; ?></td>
				  <td><?php echo $row2['Seqname']; ?></td><td><?php echo $row['Result_Count']; ?></td>
				 				  
				  <td>
				  <div class="btn-group">
                      <button onclick="edit_summary(<?php echo $row['ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </button>		
                      <button onclick="delete_summary(<?php echo $row['ID']; ?>)" type="button" class="btn btn-danger"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> </button>
                       <button onclick="view_summary(<?php echo $row['ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-eye  zmdi-hc-fw"></i> </button>
                    </div>
				  
				  </td>
                </tr><?php } ?>
        
                </tbody>           
                           
            </table>
         </div>
      </div>
   </div>
      <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Summary Management</h5>
            </div>
            <div class="modal-body">
			<input type='hidden' name='hideid' id='hideid'>
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				<input name="dbid" type="hidden" value="" />
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Selection Type</h3>
                  <div class="form-group">
                     <div class="select">
                         <select class="form-control"  name="selection" id='selection' onChange="change_selection();">
								<option>Select</option>
								<option value='f0_sel'>F0</option>
								<option value='pht_sel'>PHT</option>
								<option value='con_sel'>Conductivity</option>
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
							    <?php 
				$sql= "SELECT * FROM VESSEL_DETAILS";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){?>
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
                       <select class="form-control" name="sequence" id='sequence' >
					            <option value="">Select</option>
							    <?php 
				$sql= "SELECT * FROM SEQUENCE_NUMBER_DETAIL";
				$res= sqlsrv_query($conn, $sql);
				
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){?>
								<option value='<?php echo $row['ID']?>'><?php echo $row['Seqname'];?></option>
								<?php } ?>l
								</select>
                     </div>
                </div>
               </div>
			   
                <div class="col-sm-12" id='allow' style='display:none;'>
                  <h3 class="card-body__title">Allowable Drop</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="pht_alowable1" id='pht_alowable1'>
						        <option value="">Select</option>
								<?php
								 
												$sql= "SELECT * FROM PARAMETERS_DETAILS WHERE Parmaters_Tagname LIKE '%PHT%'";
												$res= sqlsrv_query($conn, $sql);
												while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
												$Param_Name = $row['Param_Name'];
												$TagIndex = $row['TagIndex'];
												$Parmaters_Tagname = $row['Parmaters_Tagname'];
												echo "<option value='$TagIndex'>".$Parmaters_Tagname."</option>";
												}
								?>
						</select>
                     </div>
                     </div>
                     </div><div class="col-sm-12" id='step' style='display:none;'>
                  <h3 class="card-body__title">Step_no</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="stepno" id='stepno'>
						        <option value="">Select</option>
								
						</select>
                     </div>
                     </div>
                     </div>
					 
					   <div id="f0_div" style='display:none;' >														
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">Result Count</label>
							     <div class="select">
								<select class="form-control" name="result_type" id='result_type' onchange="change_f0_result_count(this.value)">
						        <option value=''>Select</option>
								<option value="1">One</option>
						        <option value="2">Two</option>
						        <option value="3">Three</option>
						        <option value="4">Four</op
								tion>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>	
							
							<div class="col-md-12" id="res-for" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Result For</label>
							     <div class="">
								<input type='checkbox' name='res' id='min' value='1'> min/max 
						        </div>
							</div>
							</div>	<br>
								<div class="col-md-12" id="temp2" style="display:none;">
							<label class="card-body__title">Time Duration</label>
							     <div class="select">
								 <input type="text" name="timedur" id="timedur" class='form-control' >
									
						        </div>
								
								<i class="form-group__bar"></i>
							</div><div class="col-md-12" id="temp" style="display:none;">
							<label class="card-body__title">Temprature</label>
							     <div class="select">
								 <input type="text" name='tem' id='tem' class='form-control' >
									
						        </div>
								
								<i class="form-group__bar"></i>
							</div>
							<div class="col-md-12" id="z" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Z</label>
							     <div class="select">
								 <input type="text" name='z' id='z' class='form-control' >
						        </div>
							</div>
							</div>	
							
							<div class="col-md-12" id="f01div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Sensor 1</label>
							     <div class="select">
								<select class="form-control" name="Sensor1" id='Sensor1'>
						        <option value="">Select</option>
											<?php		 
												$sql1= "SELECT * FROM SENSORS_HIGHLIMITS";
												$res1= sqlsrv_query($conn, $sql1);
												while($row1 = sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC)){
												$Sensor_Name = $row1['Sensor_Name'];
												$Sensor_Tag = $row1['Sensor_Tag'];
												$TagIndex = $row1['TagIndex'];
												echo "<option value='$Sensor_Tag'>".$Sensor_Name."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							</div>		
		
						<div class="col-md-12" id="f02div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Sensor 2</label>
							     <div class="select">
								<select class="form-control" name="Sensor2" id='Sensor2'>
						        <option value="">Select</option>
											<?php		 
												$sql1= "SELECT * FROM SENSORS_HIGHLIMITS";
												$res1= sqlsrv_query($conn, $sql1);
												while($row1 = sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC)){
												$Sensor_Name = $row1['Sensor_Name'];
												$Sensor_Tag = $row1['Sensor_Tag'];
												$TagIndex = $row1['TagIndex'];
												echo "<option value='$Sensor_Tag'>".$Sensor_Name."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							</div>	
						<div class="col-md-12" id="f03div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Sensor 3</label>
							     <div class="select">
								<select class="form-control" name="Sensor3" id='Sensor3'>
						        <option value="">Select</option>
											<?php		 
												$sql1= "SELECT * FROM SENSORS_HIGHLIMITS";
												$res1= sqlsrv_query($conn, $sql1);
												while($row1 = sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC)){
												$Sensor_Name = $row1['Sensor_Name'];
												$Sensor_Tag = $row1['Sensor_Tag'];
												$TagIndex = $row1['TagIndex'];
												echo "<option value='$TagIndex'>".$Sensor_Name."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							</div>	
						<div class="col-md-12" id="f04div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Sensor 4</label>
							     <div class="select">
								<select class="form-control" name="Sensor4" id='Sensor4'>
						        <option value="">Select</option>
											<?php		 
												$sql1= "SELECT * FROM SENSORS_HIGHLIMITS";
												$res1= sqlsrv_query($conn, $sql1);
												while($row1 = sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC)){
												$Sensor_Name = $row1['Sensor_Name'];
												$Sensor_Tag = $row1['Sensor_Tag'];
												$TagIndex = $row1['TagIndex'];
												echo "<option value='$TagIndex'>".$Sensor_Name."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							</div>	
						<div class="col-md-12" >
                           <div class="form-group" > 
								<i class="form-group__bar"></i>
							</div>
							
						</div>  
						                                           
						</div>
					   <div id="pht_div" style='display:none;' >														
						<div class="col-md-12" >
                           <div class="form-group" > 
							<label class="card-body__title">Result Count</label>
							     <div class="select">
								<select class="form-control" name="result_type" id='result_type' onchange="change_result_count(this.value)">
						        <option value=''>Select</option>
								<option value="1">One</option>
						        <option value="2">Two</option>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>			
						<div class="col-md-12" id="res1div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Initial Presure 1</label>
							     <div class="select">
								<select class="form-control" name="initial1" id='initial1'>
						        <option value="">Select</option>
											<?php		 
												$sql= "SELECT * FROM PARAMETERS_DETAILS  WHERE Parmaters_Tagname LIKE '%PHT%'";
												$res= sqlsrv_query($conn, $sql);
												while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
												$Param_Name = $row['Param_Name'];
												$TagIndex = $row['TagIndex'];
												$Parmaters_Tagname = $row['Parmaters_Tagname'];
												echo "<option value='$TagIndex'>".$Parmaters_Tagname."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							<div class="form-group" > 
							<label class="card-body__title">Final Presure 1</label>
							     <div class="select">
								<select class="form-control" name="final1" id='final1'>
						        <option value="">Select</option>
								<?php
								 
												$sql= "SELECT * FROM PARAMETERS_DETAILS  WHERE Parmaters_Tagname LIKE '%PHT%'";
												$res= sqlsrv_query($conn, $sql);
												while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
												$Param_Name = $row['Param_Name'];
												$TagIndex = $row['TagIndex'];
												$Parmaters_Tagname = $row['Parmaters_Tagname'];
												echo "<option value='$TagIndex'>".$Parmaters_Tagname." </option>";
												}
								?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
						</div>						
						<div class="col-md-12" id="res2div" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Initial Presure 2</label>
							     <div class="select">
								<select class="form-control" name="initial2" id='initial2' >
						        <option value="">Select</option>
								<?php
								 
												$sql= "SELECT * FROM PARAMETERS_DETAILS WHERE Parmaters_Tagname LIKE '%PHT%'";
												$res= sqlsrv_query($conn, $sql);
												while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
												$Param_Name = $row['Param_Name'];
												$TagIndex = $row['TagIndex'];
												$Parmaters_Tagname = $row['Parmaters_Tagname'];
												echo "<option value='$TagIndex'>".$Parmaters_Tagname."</option>";
												}
								?>
						     </select>
						     </div>
								<i class="form-group__bar"></i>
							</div>
							<div class="form-group" > 
							<label class="card-body__title">Final Presure 2</label>
							     <div class="select">
								<select class="form-control" name="final2" id='final2' >
						        <option value="">Select</option>
								<?php
								 
												$sql= "SELECT * FROM PARAMETERS_DETAILS WHERE Parmaters_Tagname LIKE '%PHT%'";
												$res= sqlsrv_query($conn, $sql);
												while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
												$Param_Name = $row['Param_Name'];
												$TagIndex = $row['TagIndex'];
												$Parmaters_Tagname = $row['Parmaters_Tagname'];
												echo "<option value='$TagIndex'>".$Parmaters_Tagname."</option>";
												}
								?>
						     </select>
						     </div>
								<i class="form-group__bar"></i>
							</div>
						</div>						
						<div class="col-md-12" >
                           <div class="form-group" > 
								<i class="form-group__bar"></i>
							</div>
						</div>  
						                                           
						</div>
						
						<!----conductivity --->
						<div class="col-md-12" id="condiv" style="display:none;">
                           <div class="form-group" > 
							<label class="card-body__title">Conductivity Tag</label>
							     <div class="select">
								<select class="form-control" name="con_tag" id='con_tag'>
						        <option value="">Select</option>
											<?php		 

												$sql1= "SELECT * FROM SENSORS_HIGHLIMITS where Sensor_Tag Like '%_COND_CHECK%'";
												$res1= sqlsrv_query($conn, $sql1);
												while($row1 = sqlsrv_fetch_array($res1,SQLSRV_FETCH_ASSOC)){
												$Sensor_Name = $row1['Sensor_Name'];
												$Sensor_Tag = $row1['TagIndex'];
												$TagIndex = $row1['TagIndex'];
												echo "<option value='$Sensor_Tag'>".$Sensor_Name."</option>";
											}
											?>
						        </select>
						        </div>
								<i class="form-group__bar"></i>
							</div>
							</div>
						<!----conductivity --->
						
						
						
						
						
					 </div>						
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
      </div>
	  </form>
	
	 <div class="modal fade" id="view" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">View Details</h5>
            </div>
            <div class="modal-body">
				<div class="col-sm-12">
                  <h3 class="card-body__title"></h3>
                  <div class="form-group">
				<h3>  <?php
				  
//include('../includes/config.php'); 
				  $select= "SELECT * FROM SUMMARY_DETAILS where ID='$id'";
				$val= sqlsrv_query($conn, $select);
				while($col = sqlsrv_fetch_array($val,SQLSRV_FETCH_ASSOC)){
					
						if($col['Summary_Type'] == 'pht_sel')
						{
				  ?>
                     <div class="" style='float:left;margin-right:7%;'>
						Summary_Type = <?php echo $col['Summary_Type']; ?>
                     </div>
					 <div class="">
						Result_Count = <?php echo $col['Result_Count']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:7%;'>
						Initial_1 = <?php echo $col['Initial_1']; ?>
                     </div>
					 <div class="">
						Initial_2 = <?php echo $col['Initial_2']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:7%;'>
						Final_1 = <?php echo $col['Final_1']; ?>
                     </div>
					 <div class="">
						Final_2 = <?php echo $col['Final_2']; 
						}
						else if($col['Summary_Type'] == 'f0_sel')
						{
							?>
                     </div><br></h3><h3>
					 <div class="" style='float:left;margin-right:8.5%;'>
						Summary_Type = <?php echo $col['Summary_Type']; ?>
                     </div>
					 <div class="">
						Result_Count = <?php echo $col['Result_Count']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:27%;'>
						Step_no = <?php echo $col['Step_no']; ?>
                     </div>
					 <div class="">
						Result_for = <?php echo $col['Result_for']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:18%;'>
						Temperature = <?php echo $col['Temperature']; ?>
                     </div>
					 <div class="">
						z = <?php echo $col['z']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:3%;'>
						Sensor1 = <?php echo $col['Sensor1']; ?>
                     </div>
					 <div class="">
						Sensor2 = <?php echo $col['Sensor2']; ?>
                     </div><br>
					 <div class="" style='float:left;margin-right:3%;'>
						Sensor3 = <?php echo $col['Sensor3']; ?>
                     </div>
					 <div class="">
						Sensor4 = <?php echo $col['Sensor4']; }}?>
                     </div><br>
                </div></h3>
                </div>	
										
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
      </div>
      </div>
	
<script>
function view_summary(hide_id)
	{
		$('#view').modal('show');
	}

function change_f0_result_count(value){
	if(value == "1"){
		$("#f01div").css("display", "block");
		$("#f02div").css("display", "none");
		$("#f03div").css("display", "none");
		$("#f04div").css("display", "none");
	}
	else if(value == "2"){
		$("#f01div").css("display", "block");
		$("#f02div").css("display", "block");	
		$("#f03div").css("display", "none");
		$("#f04div").css("display", "none");	
		}
	else if(value == "3"){
		$("#f01div").css("display", "block");
		$("#f02div").css("display", "block");
		$("#f03div").css("display", "block");
		$("#f04div").css("display", "none");
		}
	else if(value == "4"){
		$("#f01div").css("display", "block");
		$("#f02div").css("display", "block");
		$("#f03div").css("display", "block");
		$("#f04div").css("display", "block");
		}
	
}
function change_result_count(value){
	if(value == "1"){
		$("#res1div").css("display", "block");
		$("#res2div").css("display", "none");}
	else{
		$("#res2div").css("display", "block");
		$("#res1div").css("display", "block");	}
	
}
	function add_dbdetails()
    {		
    $("#pht_div").css("display", "none");	
    $("#condiv").css("display", "none");	
    $("#f0_div").css("display", "block");	
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals	  
    $('#modal_form').modal('show'); 

    }	
function edit_summary(id)
    {
	  $('#form')[0].reset(); 
       $('#modal_form').modal('show');
	  save_method = 'update';
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-summary.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {   

		if(data.Summary_Type == 'pht_sel')
		{
				$("#pht_div").css("display", "block");
				$("#allow").css("display", "block");
				$("#f0_div").css("display", "none");
				$("#condiv").css("display", "none");
                $("#res-for").css("display", "none");	
                $("#temp").css("display", "none");	
                $("#temp2").css("display", "none");	
                $("#z").css("display", "none");
		}
		else if(data.Summary_Type == 'con_sel')
		{
				$("#pht_div").css("display", "none");
				$("#allow").css("display", "none");
				$("#f0_div").css("display", "none");
				$("#condiv").css("display", "block");
                $("#res-for").css("display", "none");	
                $("#temp").css("display", "none");	
                $("#temp2").css("display", "none");	
                $("#z").css("display", "none");	
				 $("#step").css("display", "block");
			
		}else
		{		
				$("#f0_div").css("display", "block");
                $("#res-for").css("display", "block");
				$("#allow").css("display", "none");	
                $("#temp").css("display", "block");	
                $("#temp2").css("display", "block");	
                $("#z").css("display", "block");	
                $("#step").css("display", "block");	
				$("#pht_div").css("display", "none");
				$("#condiv").css("display", "none");				
		}
				if(data.Result_for == "1")
				{
					$('[name="res"]').prop('checked','checked');
				}
				
				
			
			
			$('[name="stepno"]').val(data.Step_no);
            $('[name="vessel"]').val(data.Vessel_ID);
            $('[name="selection"]').val(data.Summary_Type);
			$('[name="sequence"]').val(data.Sequence_ID);
			$('[name="result_type"]').val(data.Result_Count);
			$('[name="pht_alowable1"]').val(data.Allowable_Drop);
			$('[name="initial1"]').val(data.Initial_1);
			$('[name="initial2"]').val(data.Initial_2);
			$('[name="final1"]').val(data.Final_1);
			$('[name="final2"]').val(data.Final_2); 
			$('[name="dbid"]').val(data.ID); 
			$('[name="z"]').val(data.z); 
			$('[name="tem"]').val(data.Temperature); 
			$('[name="Sensor1"]').val(data.Sensor1); 
			$('[name="Sensor2"]').val(data.Sensor2); 
			$('[name="Sensor3"]').val(data.Sensor3); 
			$('[name="Sensor4"]').val(data.Sensor4); 
			$('[name="f0result_type"]').val(data.Result_Count); 
			$('[name="timedur"]').val(data.timedur); 
			$('[name="con_tag"]').val(data.con_tag); 
			 
	get_step_nos(data.Sequence_ID,data.Step_no);
	change_result_count(data.Result_Count);
			change_f0_result_count(data.Result_Count);
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
 //alert(url);
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
	
	function change_selection(){
		var sel = $('#selection').val(); 
            if(sel == "f0_sel"){	
                $("#pht_div").css("display", "none");	
                $("#res-for").css("display", "block");	
                $("#temp").css("display", "block");	
                $("#step").css("display", "block");	
                $("#z").css("display", "block");	
                $("#f0_div").css("display", "block");	
                $("#allow").css("display", "none");	
                $("#f01div").css("display", "none");	
                $("#f02div").css("display", "none");	
                $("#f03div").css("display", "none");	
                $("#f04div").css("display", "none");	
				
			}					
			else if(sel == "pht_sel"){
                $("#pht_div").css("display", "block");	
                $("#step").css("display", "block");	
                $("#f0_div").css("display", "none");	
                $("#allow").css("display", "block");	
                $("#z").css("display", "none");	
				$("#res-for").css("display", "none");
				$("#temp").css("display", "none");
			}
			else{
				$("#condiv").css("display", "block");	
				$("#pht_div").css("display", "none");	
                $("#step").css("display", "block");	
                $("#f0_div").css("display", "none");	
                $("#allow").css("display", "none");	
                $("#z").css("display", "none");	
				$("#res-for").css("display", "none");
				}			
	}


$(document).ready(function(){
	$('#sequence').change(function(){
		var sequence_id = $('#sequence').val();
		$.ajax({
			url:"<?php echo $baseurl; ?>controllers/get-step-nos.php",
			type:'POST',
			data:{'sequence':sequence_id},
			cache:false,
			success:function(data)
			{
				$('#stepno').html(data);
			}
		});
	});
});

	function get_step_nos(sequence,stepno=0){
	 var data ={"sequence":sequence}; 	
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/get-step-nos.php",
         success: function(data)
         	{				
			 $('#stepno').html(data);
			if(stepno!=0)
				$('#stepno').val(stepno);

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
	} 
	
</script>   
</section>
<?php include('includes/footer.php');?>