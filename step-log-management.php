<?php include('includes/header.php');
		if(isset($_REQUEST['sequence']))
				{
		$Sequence_ID= $_REQUEST['sequence'];	
		}	
        $sqlseq = "Select * From SEQUENCE_NUMBER_DETAIL WHERE ID='$Sequence_ID'";
		$resseq= sqlsrv_query($conn, $sqlseq);
		$rowseq = sqlsrv_fetch_array($resseq);
		$Vessel_ID =$rowseq['Vessel_ID'];
		        
        $sqldb = "Select * From [VESSEL_DETAILS] WHERE [Vessel_ID]='$Vessel_ID'";
		$resdb= sqlsrv_query($conn, $sqldb);
		$rowdb = sqlsrv_fetch_array($resdb);
		
	    $refid = $rowdb['Equipment_Id'];
		$vname = $rowdb['Vessel_Name'];
        		
		
	  ?>
<section class="content">
   <header class="content__title">
      <h1>Sequence Management <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $vname;  ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $rowseq['Seqname']; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> VIEW STEP DESCRIPTIONS</h1>
      <small>&nbsp;</small>
      <div class="actions">
        <p style="float:right">
	 <button onclick="upload_steps()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Upload Excel</button>
	<!---- <button id="export"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Export</button>--->
	 <button onclick="add_step_logs()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add Step Logs</button></p>
      </div>  
   </header>
      <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive">
            <table id="data-table" class="table table-bordered">
               <thead>
                <tr>
				
                  <th>Step No</th>
                  <th>Step Description</th>
				  <th width="20%">Action</th>
                </tr>
               </thead>
			    <tbody>
				<?php 
				$sql= "SELECT * FROM STEP_LOGS WHERE Sequence_ID = '$Sequence_ID'";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
				?>
                <tr>
				<!---<td><!---<input type="checkbox" id="checkItem" value="<?php //echo $row['Step_ID']; ?>"></td>--->
				  <td><input type="text" rel="<?php echo $row['Step_ID']; ?>" data-rel=""  id=""  class="stepno form-control" placeholder="" value="<?php echo $row['Step_No']; ?>"></td>
				  <td><input type="text" rel="<?php echo $row['Step_ID']; ?>" data-rel=""  id=""  class="stepdesc form-control" placeholder="" value="<?php echo $row['Step_Tag']; ?>"></td>	
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-danger" onclick="delete_steplog(<?php echo $row['Step_ID']; ?>)" title="Delete Step"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  </td>
                </tr>
				<?php } ?>	
                </tbody>                  
            </table>
         </div>
      </div>
   </div>
   
    <div class="modal fade" id="modal_form_step_logs" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">ADD STEP DESCRIPTION</h5>
            </div>
            <div class="modal-body">
			    <form id="formstep"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
					<input name="Sequence_ID" id='Sequence_ID' type="hidden" value="<?php echo $Sequence_ID; ?>" />
			 		<div class="form-body">
					<div class="form-group">
					<div class="row">
					<div class="col-md-4">
					    <b>No Of Step Logs</b>
						<input class="form-control" name="count_text" id='count_text' type="number" value="" placeholder="Enter Limit"/>
					</div>
					<div class="col-md-4">
						<button id="btnLimit" class="btn btn-primary" onclick="add_limits()" type="button" style="margin-top: 10px;">ADD</button>
					</div>
					</div>		
					</div>
					<div id="text_space" name ="text_space" class="col-md-12">
					</div>
					</div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="savestep()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div> 
   
   <div class="modal fade" id="modal_form_step" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">UPLOAD EXCEL</h5>
            </div>
            <div class="modal-body">
			    <form id="stepform"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				    <div class="alertfixed alert-info">NOTE : 
					<br>1. The first column should contain step no. 
					<br>2. The second column should contain step description.
					<br>3. The file should be .xlxs
					<br>4. The step details should  be in first sheet of excel.</div>
					
					<input name="Sequence_ID" id='Sequence_ID' type="hidden" value="<?php echo $Sequence_ID; ?>" />
			 		<div class="form-body">
					<div class="form-group">
					<div class="row">
					<div class="col-md-4">
					    <b>File</b>
						<input class="form-control" name="file" id='file' type="file" value="">
					</div>
					</div>		
					</div>
					</div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="saveexcel()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div> 
   
<script>
function upload_steps()
    {  
    save_method = 'saveexcel';
    $('#modal_form_step').modal('show');
    }	

$("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 function saveexcel()
    {

      var url;
      if(save_method == 'saveexcel')
      {
         url = "<?php echo $baseurl; ?>controllers/upload-excel.php";
      }
       var postData = new FormData($("#stepform")[0]);

		 $.ajax({
				 type:'POST',
				 url:url,
				 data : postData,
				 processData: false,
				 cache:false,
				 contentType: false,
				 success:function(data){
				   location.reload();	
				 }

			  });
		
		

    }	
//Step Log Management
    function add_step_logs()
    {
	 $('#modal_form_step_logs').modal('show'); 
	}
	function add_limits(){
		var limit_enter = $('#count_text').val()
		limit = parseInt(limit_enter);
		data= $('#text_space').html();
		if(limit){
	    for(var i=0;i<limit;i++){
	      data=data+ '<div class="row"><div class="col-md-3"><input class="form-control" name="step_no[]" placeholder="Enter Step NO..." type="number" value=""></div><div class="col-md-9"><input class="form-control" name="step_desc[]" placeholder="Enter Step Description" type="text"></div></div>'
	      }
	    $('#text_space').html(data);
		}
	}
	function savestep()
    {
         var url;
         url = "<?php echo $baseurl; ?>controllers/add-step-logs.php";
          $.ajax({
            url : url,
            type: "POST",
            data: $('#formstep').serialize(),
            dataType: "JSON",
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
$('.stepno').on('change', function(){
   var editid = $(this).attr('rel');
   var stepno =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-step-no.php",
            data: {editid: editid,stepno: stepno},
         });
        
 });
$('.stepdesc').on('change', function(){
   var editid = $(this).attr('rel');
   var stepdesc =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-step-desc.php",
            data: {editid: editid,stepdesc: stepdesc},

         });
        
         });	
function delete_steplog(userid)
	{
		 if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":userid},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-step-log.php",
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

/*$("#export").ready(function() {
    $("#data-table").table2excel({
        exclude: ".noExport",
        filename: "name-of-the-file",
    });
});*/

</script>   
</section>
<?php include('includes/footer.php');?>