<?php include('includes/header.php');
    if(isset($_REQUEST['vessel'])) { $Vessel_ID = $_REQUEST['vessel'];}
	$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$Vessel_ID'";
	$resdb= sqlsrv_query($conn, $sqldb);
	$rowdb = sqlsrv_fetch_array($resdb);
	$vessel_name = $rowdb['Vessel_Name'];
	
	
 	
 ?>
<section class="content">
   <header class="content__title">
      <h1>Sequence Management  <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $vessel_name; ?> </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_sequence()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add Sequences  </button></p>
         </div>
      </div>
   </header>
   <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">
            <table id="data-table" class="table table-bordered">
               <thead>
                <tr>
				  <th width="5%">No</th>
				  <th>Sequence Name</th>
				  <th width="10%">Batch type</th>
                  <th align="center">Category</th>
				  <th align="center">Group Name</th>
				  <th width="20%">Action</th>
                </tr>
               </thead>
			    <tbody>
				<?php 
				$k = 0;
				$sql= "SELECT SEQUENCE_NUMBER_DETAIL.*, VESSEL_DETAILS.* FROM SEQUENCE_NUMBER_DETAIL JOIN VESSEL_DETAILS ON VESSEL_DETAILS.Vessel_ID = SEQUENCE_NUMBER_DETAIL.Vessel_ID WHERE  VESSEL_DETAILS.Vessel_ID = '$Vessel_ID'";
				$res= sqlsrv_query($conn, $sql);
				
				while($row = sqlsrv_fetch_array($res)){
					$k++;
				?>
                <tr>
                  
				  <td><?php echo $k; ?></td>
				  <td><?php echo $row['Seqname']; ?></td>
				  <td><?php echo $row['Batch_Type']; ?></td>  
				  <td align=""><?php if($row['seqtype'] == 0){ ?><span class="btn btn-success btn-xs">MAIN BATCH</span><?php } else { ?><span class="btn btn-primary btn-xs">	SUB BATCH</span><?php } ?></td>
				  <td align=""	><?php if($row['group_id'] == 'NA'){ ?><span class="btn btn-danger btn-xs"> NA </span><?php } else { ?><span class="btn btn-success btn-xs"><?php echo "GROUP ". $row['group_id']; ?></span><?php } ?></td>
				  <td>
					<div class="btn-group">
				  	  <a class="btn btn-primary" onclick="edit_sequence(<?php echo $row['ID']; ?>)" title="Edit Sequence"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </a>
					 
					  <a class="btn btn-danger" onclick="delete_sequence(<?php echo $row['ID']; ?>);"  title="Delete Sequence"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> </a>
				 <a class="btn btn-success"  href="parameter-management.php?sequence=<?php echo $row['ID']; ?>&vessel=<?php echo $Vessel_ID; ?>" title="Parameter Management"><i class="zmdi zmdi-plus  zmdi-hc-fw"></i> PARAMETERS</a>
					  <a class="btn btn-primary" href="step-log-management.php?sequence=<?php echo $row['ID']; ?>"  title="Step Log Management" ><i class="zmdi zmdi-eye  zmdi-hc-fw"></i> STEPS</a>
                    </div>	
				  </td>
                </tr>
				<?php }  ?>
				
			
                </tbody>                  
            </table>
         </div>
      </div>
   </div>
   
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Sequence Management</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
					<input name="Sequence_ID" type="hidden" value="" />
					<input name="Vessel_ID" type="hidden" value="<?php echo $Vessel_ID; ?>" />
			    <div class="col-sm-12">
                  <label class="card-body__title">Sequence Type</label>
                  <div class="form-group">
                     <div class="select">
                       	<select name="seqtype" id="seqtype" class="form-control">
							<option value="0">Main Sequence</option>
							<option value="1">Sub Sequence</option>
						</select>
                     </div>
                  </div>
               </div>
			    <div class="col-sm-12">
                  <div class="form-group">
				     <label class="card-body__title">Sequence Name</label>
                    <input class="form-control" name="seqname" placeholder="Enter Sequence Name..." type="text">
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
               <div class="col-sm-12">
                  <div class="form-group">
				     <label class="card-body__title">Batch Type</label>
                    <input class="form-control" name="batch" placeholder="Enter Batch Type..." type="text">
                     <i class="form-group__bar"></i>
                  </div>
                </div>
				<div class="col-sm-12">
                  <div class="form-group">
				     <label class="card-body__title">Group Code</label>
						<select name="group_id" id="group_id" class="form-control">
						<option value="NA">NA</option>
						<?php for($i=1; $i<=5; $i++){ ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php } ?>
						</select>
                     <i class="form-group__bar"></i>
                  </div>
                </div>
			     <div class="col-sm-12">
                  <div class="form-group">
				     <label class="card-body__title">Completion Step Tag</label>
                    <input class="form-control" name="compno" placeholder="Enter Step tag number..." type="text">
                     <i class="form-group__bar"></i>
                  </div>
                </div>		   
			 
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
  .modal-content{
	  background-color: #0e253a !important;
  }
  </style>
<script>
//for batch report and eventreport id

$('.batchreportid_add').on('change', function(){
   var editid = $(this).attr('rel');
   var Batch_report_id =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update_batchreport_id.php",
            data: {editid: editid,Batch_report_id: Batch_report_id},
         });
        
    });
	
	
$('.eventreportid_add').on('change', function(){
   var editid = $(this).attr('rel');
   var Event_report_id =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update_eventreport_id.php",
            data: {editid: editid,Event_report_id: Event_report_id},
         });
        
    });
//for batch report and eventreport id
	
function add_sequence()
    {
      save_method = 'add';
      $('#form')[0].reset(); 	  
      $('#modal_form').modal('show'); 

    }		
 function edit_sequence(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); 
 
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/edit-sequence.php?batch=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			
		for(i = 0; i < data.length; i++){
			$('[name="Sequence_ID"]').val(data[i].ID);
			$('[name="seqtype"]').val(data[i].seqtype);	
			$('[name="seqname"]').val(data[i].Seqname);
            $('[name="batch"]').val(data[i].Batch_Type);
            $('[name="group_id"]').val(data[i].group_id);
            $('[name="compno"]').val(data[i].compno);

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
         url = "<?php echo $baseurl; ?>controllers/add-sequence.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-sequence.php";
      }
 
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
	
function delete_sequence(userid)
	{
		if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":userid},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-sequence.php",
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