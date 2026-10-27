<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Connection Management  </h1>
      <small>&nbsp;</small>
      <div class="actions">
		 <div>
		 <br>
		 	<p>
			<!--<button onclick="view_modal()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Create Connection </button>-->		
			</p>
		 </div>
      </div>
   </header>
      <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
				  <th>Sl.No</th>
                  <th>Database For</th>
                  <th>DataBase Name</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Date Format</th>
                  <th>UTC Time</th>
                  <th>Status</th>             
				  <th width="20%">Action</th>
                  </tr>
               </thead>
               <tbody>
			    <?php 
				$connectionInfo = array(  "UID"=>"sa", "PWD"=>"Password1");
				$rdbname= '';
				$rphdbname= '';

	
		
				$x= 0;
				$sql= "SELECT * FROM Config_Details";
				$res= mysqli_query($link, $sql);
				while($row = mysqli_fetch_array($res)){
					$x++;
				?>
				<tr>
                  <td><?php echo $x; ?></td>
				  <td><?php echo $row['dbtype']; ?></td>
				  <td><?php echo"<br>";echo $row['db']; ?></td>
				
				  <td><?php echo $row['un']; ?></td>
				  <td><?php echo $row['psw']; ?></td>
				    <td><?php if($row['format']=="Y/m/d H:i:s"){echo "YYYY/MM/DD";}elseif($row['format']=="d/m/Y H:i:s"){echo "DD/MM/YYYY"; }else {echo"MM/DD/YYYY";}  ?></td>
				  <td><?php echo $row['utctime']; ?></td>
				  <td>
				  <?php if($row['db_active'] == 0) { ?>
				  
                <span class="btn btn-danger" onclick="change_status(<?php echo $row['id']; ?>)">Deactive</span>
				  <?php } else { ?>
                <span class="btn btn-success" onclick="change_status(<?php echo $row['id']; ?>)">Active</span>
				  <?php } ?>
				  
				  </td>

				  <td>
				      <a class="btn btn-primary" onclick="get_db_data(<?php echo $row['id']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="delete_database(<?php echo $row['id']; ?>)" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
				  </td>
                </tr>
				  <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Database Management</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <input name="dbid" type="hidden" value="" />
			    <div class="col-sm-12" >
                  <h3 class="card-body__title">Select Database For</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="dbtype" id='dbtype' onchange="checkdbfor(this.value);">
                         <option value="AB">Allen Bradley</option>
                         <option value="PROCESS HISTORIAN">PROCESS HISTORIAN</option>	
                        </select>
                     </div>
                  </div>
               </div>
               <div id="scadadiv">			   
			   <div class="col-sm-12" >
                  <h3 class="card-body__title">Scada Type</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="rtype" id='rtype' onchange="changeredundant(this.value);">
                         <option value="">Select</option>
                         <option value="Stand Alone Server">Stand Alone Server</option>
                         <option value="Server With Redundant & Clients">Server With Redundant & Clients</option>	
                         <option value="Server With Redundant & PH">Server With Redundant & Clients & PH Server </option>	
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>Server Name</label>
                    <input type="text" class="form-control" placeholder="Enter Server Name..."  name="server" >
                     <i class="form-group__bar"></i>
                  </div>
                </div> 			   
			   <div id="rdiv" style="display:none;">
				<div class="col-sm-12">
                  <div class="form-group">
				     <label>Redundant Server Name</label>
                    <input type="text" class="form-control" placeholder="Enter Redundant Server Name..."  name="rserver" >
                     <i class="form-group__bar"></i>
                  </div>
                </div>			   
               </div>			 
			   <div id="rphdiv" style="display:none;">
				<div class="col-sm-12">
                  <div class="form-group">
				     <label>Process Historian Server Name</label>
                    <input type="text" class="form-control" placeholder="Enter Redundant Server Name PH..."  name="rphserver" >
                     <i class="form-group__bar"></i>
                  </div>
                </div>			   
               </div>
			   </div>        
			    <div class="col-sm-12">
                  <div class="form-group">
				     <label>Database Name</label>
                    <input type="text" class="form-control" name="db" placeholder="Enter Database Name...">
                     <i class="form-group__bar"></i>
                </div>
               </div>
			    <div class="col-sm-12">
                  <h3 class="card-body__title">Date Format</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="format" id='format'>
                         <option value="Y/m/d H:i:s">YYYY/MM/DD</option>
					     <option value="d/m/Y H:i:s">DD/MM/YYYY</option>	
					     <option value="m/d/Y H:i:s">MM/DD/YYYY</option>	
						 
						 <option value="Y-m-d H:i:s">YYYY-MM-DD</option>
					     <option value="d-m-Y H:i:s">DD-MM-YYYY</option>	
					     <option value="m-d-Y H:i:s">MM-DD-YYYY</option>
						 
                        </select>
                     </div>
                  </div>
               </div>		   
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>UTC Time</label>
                    <input type="time" class="form-control" name="utctime" placeholder="">
                     <i class="form-group__bar"></i>
                </div>
               </div>
			   
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Time Zone</label>
                    <input type="text" class="form-control" name="timezone" placeholder="Asia/Kolkata">
                     <i class="form-group__bar"></i>
                </div>
               </div>
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Time Value</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="timevalue" id='timevalue'>
                         <option value="-">+</option>
					     <option value="+">-</option>		
                        </select>
                     </div>
                  </div>
               </div>

				<div class="col-sm-12">
                  <div class="form-group">
				     <label>Project Code</label>
                    <input type="text" class="form-control" name="procode" placeholder="">
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
			    <div class="col-sm-12">
                  <h3 class="card-body__title">Suit Status</h3>
                  <div class="form-group">
                     <div class="select">
                        <select class="form-control" name="suitstatus" id='suitstatus'>
                         <option value="0">No</option>
					     <option value="1">Yes</option>		
                        </select>
                     </div>
                  </div>
               </div>			   
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" id="save" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>   
   
<script>
/*save sequence*/	
function view_modal()
    {
      $('#form')[0].reset(); // reset form on modals	  
      $('#modal_form').modal('show'); 

    }	

function get_db_data(id)
    {
	  
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/edit-dbconnnection.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			checkdbfor(data.dbtype);
			changeredundant(data.rtype);
           $('[name="dbtype"]').val(data.dbtype);
          $('[name="server"]').val(data.server);
			$('[name="db"]').val(data.db);
			$('[name="dbid"]').val(data.id);
			$('[name="format"]').val(data.format);
			$('[name="utctime"]').val(data.utctime);
			$('[name="utctime"]').val(data.utctime);
			$('[name="rtype"]').val(data.rtype);
			$('[name="rserver"]').val(data.rserver);
			$('[name="suitstatus"]').val(data.suitstatus);
			$('[name="procode"]').val(data.procode);
			$('[name="timevalue"]').val(data.timevalue);
			$('[name="timezone"]').val(data.timezone);
			
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
         var url = "<?php echo $baseurl; ?>controllers/add-dbconnnection.php";
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

	
function change_status(id)
    {

        $.ajax({
        url : "<?php echo $baseurl; ?>controllers/change_status.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			location.reload();
        }
    });
    }	

//delete Sequence
function delete_database(hide_id)
	{
		  if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-database.php",
         success: function(data)
         	{
         	location.reload();		
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
                //alert('Error adding / update data');
				location.reload();
            }
				
            });
            
            }
	} 


function changeredundant(type){
	if(type == "Server With Redundant & Clients"){
		$("#save").attr("disabled",false);
		document.getElementById('rdiv').style.display = "block";
		document.getElementById('rphdiv').style.display = "none";
	}else if(type == "Server With Redundant & PH"){
		$("#save").attr("disabled",true);
		document.getElementById('rdiv').style.display = "block";
		document.getElementById('rphdiv').style.display = "block";
	}else{
		$("#save").attr("disabled",false);
		document.getElementById('rdiv').style.display = "none";
		document.getElementById('rphdiv').style.display = "none";
	}
}
function checkdbfor(type){
	if(type != "SCADA"){
		document.getElementById('scadadiv').style.display = "none";
	}else{
		document.getElementById('scadadiv').style.display = "block";
	}
}
	
</script>   
</section>
<?php include('includes/footer.php');?>