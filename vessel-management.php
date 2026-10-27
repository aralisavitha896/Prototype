<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
   <?php 
   $refid = isset($_GET['refid']) ? $_GET['refid'] : 0; 
   $arr = array();
   if($refid)
   getparents($refid);
   krsort($arr);
   $arrcount = count($arr);
   $i=0;
   ?>   
      <h1> Vessel Management <?php if($arrcount){ ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i><?php } ?><?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;if(++$i != $arrcount){?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }}} ?>   </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_vessel()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i>Add Vessel </button></p>
         </div>
      </div>
   </header>
       <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive" style="overflow:hidden">
            <table id="data-table" class="table table-bordered">
                <thead>
                <tr>
				  
                  <th>Sl No</th>
                  <th>Vessel Name</th>
                  <th>Vessel Tag</th>
                  <th>Equipment ID</th>
                  <th>Alarm Filter</th>
				  <th width="10%">Action</th>
                </tr>
                </thead>
                <tbody>
				<?php
$x = 0;
$sql = "SELECT * FROM VESSEL_DETAILS order by Vessel_ID ASC";
$res = sqlsrv_query($conn, $sql);
while ($row = sqlsrv_fetch_array($res)) {
    $x++;
?>
                <tr>
                 
				  <td><?php echo $x; ?></td>
				  <td><?php echo $row['Vessel_Name']; ?></td>
				  <td><?php echo $row['Vessel_Code']; ?></td>
				  <td><?php echo $row['Equipment_Id']; ?></td>
				  <td><?php echo $row['Alarm_Filter']; ?></td>
				 
				  <td><div class="btn-group">
                      <button onclick="edit_vessel(<?php echo $row['Vessel_ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </button>		
                      <button onclick="showUIDDeleteModalWindow(<?php echo $row['Vessel_ID']; ?>);" type="button" class="btn btn-danger"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></button>  
	
					
					  
                    </div>
				  
				  </td>
                </tr>
				<?php
} ?>
        
                </tbody>
                
              </table>
         </div>
      </div>
   </div>
   
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Vessel Management</h5>
            </div>
            <div class="modal-body">
			<p><i>Note:Vessel Tag prfix should start alphabetical O </i></p>
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <input name="Vessel_ID" type="hidden" value="" />
			    <input name="Refid" type="hidden" value="<?php echo $refid; ?>" />
				<input name="hideimg" type="hidden" value="" />
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>Vessel Name</label>
                    <input type="text" class="form-control" placeholder="Enter Vessel Name..."  name="Vessel_Name"  id="Vessel_Name">
                     <i class="form-group__bar"></i>
                  </div>
                </div>
			   	
              <div class="row">			   
			   <div class="col-sm-4">
				     <label class="p15">Vessel Tag</label>
					 <div class="form-group p15">
                    <input class="form-control" id='Vessel_Code' name="Vessel_Code" placeholder="Enter Vessel Code..." type="text">
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
			   <div class="col-sm-4">
				    <label class="p15">Vessel Order</label>
					<div class="form-group p15">
                    <input class="form-control" id='Vessel_order' name="Vessel_order" placeholder="Enter Vessel Order..." type="number">
                     <i class="form-group__bar"></i>
                    </div>
               </div>			
			   <div class="col-sm-4">
				     <label class="p15">Equipment ID</label>
					 <div class="form-group p15">
                    <input class="form-control" id='Equipment_Id' name="Equipment_Id" placeholder="Enter Equipment ID..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
               </div>
			   <div class="row">	
			    <div class="col-sm-4">
				     <label class="p15">Alarm Filter</label>
					 <div class="form-group p15">
                    <input class="form-control" id='Alarm_Filter' name="Alarm_Filter" placeholder="Enter Alarm_Filter..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
               </div>
			  <H4>Main Tag Index details </h4> 
			   <div class="row">
			   
			    <div class="col-sm-4">
				     <label class="p15">Start Time</label>
					 <div class="form-group p15">
                    <input class="form-control" id='startime' name="startime" placeholder="Start Time Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   <div class="col-sm-4">
				     <label class="p15">End Time</label>
					 <div class="form-group p15">
                    <input class="form-control" id='endtime' name="endtime" placeholder="Enter Endtime Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			   <div class="col-sm-4">
				     <label class="p15">Batch Name</label>
					 <div class="form-group p15">
                    <input class="form-control" id='batchname' name="batchname" placeholder="Enter Batch Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
               </div>
			   <div class="row">	
			    <div class="col-sm-4">
				     <label class="p15">Batch Description</label>
					 <div class="form-group p15">
                    <input class="form-control" id='batchdesc' name="batchdesc" placeholder="Enter Batchdesc Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   <div class="col-sm-4">
				     <label class="p15">Start Time sort</label>
					 <div class="form-group p15">
                    <input class="form-control" id='starttimesort' name="starttimesort" placeholder="Enter Start timesort Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			   <div class="col-sm-4">
				     <label class="p15">End Time sort</label>
					 <div class="form-group p15">
                    <input class="form-control" id='endtimesort' name="endtimesort" placeholder="Enter Endtimesort Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			    <div class="col-sm-4">
				     <label class="p15">Start user</label>
					 <div class="form-group p15">
                    <input class="form-control" id='startuser' name="startuser" placeholder="Start user Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			    <div class="col-sm-4">
				     <label class="p15">End user</label>
					 <div class="form-group p15">
                    <input class="form-control" id='enduser' name="enduser" placeholder="End user Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
               </div>
			   
			   
			   <H4>Sub Tag Index details </h4> 
			   <div class="row">
			   
			    <div class="col-sm-4">
				     <label class="p15">Start Time</label>
					 <div class="form-group p15">
                    <input class="form-control" id='startime2' name="startime2" placeholder="Start Time Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   <div class="col-sm-4">
				     <label class="p15">End Time</label>
					 <div class="form-group p15">
                    <input class="form-control" id='endtime2' name="endtime2" placeholder="Enter Endtime Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			   <div class="col-sm-4">
				     <label class="p15">Batch Name</label>
					 <div class="form-group p15">
                    <input class="form-control" id='batchname2' name="batchname2" placeholder="Enter Batch Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
               </div>
			   <div class="row">	
			    <div class="col-sm-4">
				     <label class="p15">Batch Description</label>
					 <div class="form-group p15">
                    <input class="form-control" id='batchdesc2' name="batchdesc2" placeholder="Enter Batchdesc Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   <div class="col-sm-4">
				     <label class="p15">Start Time sort</label>
					 <div class="form-group p15">
                    <input class="form-control" id='starttimesort2' name="starttimesort2" placeholder="Enter Start timesort Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			   <div class="col-sm-4">
				     <label class="p15">End Time sort</label>
					 <div class="form-group p15">
                    <input class="form-control" id='endtimesort2' name="endtimesort2" placeholder="Enter Endtimesort Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			    <div class="col-sm-4">
				     <label class="p15">Start user</label>
					 <div class="form-group p15">
                    <input class="form-control" id='startuser_sub' name="startuser_sub" placeholder="Start user Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
			   
			    <div class="col-sm-4">
				     <label class="p15">End user</label>
					 <div class="form-group p15">
                    <input class="form-control" id='enduser_sub' name="enduser_sub" placeholder="End user Index..." type="text">
                     <i class="form-group__bar"></i>
					 </div>
               </div>
               </div>
			  
			  <!--- <div class="alertfixed alert-info">NOTE : Please Generate one alarms to get vessel name in Alarms & Events Filter.</div>--->
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div> 

  <style> 
  .p15{
	  padding-left:15px;
	  padding-right:15px;
  }
  </style> 
<script>
function add_vessel()
    {  
    save_method = 'add';
    $('#form')[0].reset(); 
    $('#modal_form').modal('show');
    }
 function edit_vessel(id)
    { 
      save_method = 'update';

      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/view-vessel.php?Vessel_ID=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		for(i=0; i<data.length; i++){
            $('[name="Vessel_Name"]').val(data[i].Vessel_Name);
            $('[name="Vessel_ID"]').val(data[i].Vessel_ID);
            $('[name="Equipment_Id"]').val(data[i].Equipment_Id);
            $('[name="Vessel_Code"]').val(data[i].Vessel_Code);
            $('[name="Vessel_order"]').val(data[i].Vessel_order);
            $('[name="Alarm_Filter"]').val(data[i].Alarm_Filter);
            $('[name="startime"]').val(data[i].startime);
            $('[name="endtime"]').val(data[i].endtime);
            $('[name="batchname"]').val(data[i].batchname);
            $('[name="batchdesc"]').val(data[i].batchdesc);
            $('[name="starttimesort"]').val(data[i].starttimesort);
            $('[name="endtimesort"]').val(data[i].endtimesort);
			$('[name="startime2"]').val(data[i].startime2);
            $('[name="endtime2"]').val(data[i].endtime2);
            $('[name="batchname2"]').val(data[i].batchname2);
            $('[name="batchdesc2"]').val(data[i].batchdesc2);
            $('[name="starttimesort2"]').val(data[i].starttimesort2);
            $('[name="endtimesort2"]').val(data[i].endtimesort2);
            $('[name="startuser"]').val(data[i].startuser);
            $('[name="enduser"]').val(data[i].enduser);
            $('[name="startuser_sub"]').val(data[i].startuser_sub);
            $('[name="enduser_sub"]').val(data[i].enduser_sub);
		
		}	
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
         url = "<?php echo $baseurl; ?>controllers/add-vessel.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-vessel.php";
      }
 
          var postData = new FormData($("#form")[0]);

		 $.ajax({
				 type:'POST',
				 url:url,
				 processData: false,
				 contentType: false,
				 data : postData,
				 success:function(data){
				 location.reload();	
				 }

			  });

    }
function delete_vessel(userid)
	{
		if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":userid},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-vessel.php",
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