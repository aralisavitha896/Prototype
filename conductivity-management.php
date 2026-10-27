<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Conductivity Result Management    </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
             <p style="float:right"><button onclick="add()"  class="btn btn-primary"  type="button"><i class="zmdi zmdi-plus  zmdi-hc-fw"></i> Add Result</button></p>		 
         </div>
      </div>  
   </header>
      <div class="card">
      <div class="card-body">
	  <?php 
		if(isset($_REQUEST['id']))
				{
		$ID= $_REQUEST['id'];	
		}	
	  ?>
         <div class="table-responsive">
            <table id="data-table" class="table">
               <thead>
                <tr>
				  
                  <th>Tag</th>
                  <th>Text</th>
				  <th width="20%">Action</th>
                </tr>
               </thead>
			    <tbody>
				<?php 
				$sql= "SELECT * FROM CONDUCTIVITY_DETAILS WHERE  Ref_Id= '$ID'";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
				?>
                <tr>
				  
				  <td>
				  <select name="tag" id="tag" class="tag form-control" rel="<?php echo $row['ID']; ?>">
						<?php 
						if($dbtype == "SCADA"){						
						$sqlar= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%Q%' AND ValueName NOT LIKE'%STEP%' AND ValueName NOT LIKE'%.PV%' AND ValueName NOT LIKE'%.SP%'";
						}else{
					    $sqlar= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%COND%'";
						}
						$resar = sqlsrv_query($conn,$sqlar);
						while($rowar= sqlsrv_fetch_array($resar)){
						?>
						<option value="<?php echo $rowar["ValueID"]; ?>" <?php if($rowar["ValueID"] == $row["Tag"] )echo "selected"; ?>><?php echo $rowar["ValueName"]; ?></option>
						<?php } ?>
				  </td>
				  <td><input type="text" rel="<?php echo $row['ID']; ?>" data-rel=""  id=""  class="text form-control" placeholder="" value="<?php echo $row['Text']; ?>"></td>	
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-danger" onclick="deleterow(<?php echo $row['ID']; ?>)" title="Delete"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
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
               <h5 class="modal-title pull-left">ADD RESULT</h5>
            </div>
            <div class="modal-body">
			    <form id="formstep"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
					<input name="refid" id='refid' type="hidden" value="<?php echo $ID; ?>" />
			 		<div class="form-body">
					<div class="alertfixed alert-info">NOTE : Conductivity SP parameter Tagname should contain 'COND' text</div>
					<div class="form-group">

					<div class="row">
					<div class="col-md-4">
					    <b>No Of Results</b>
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
               <button type="button" class="btn btn-success" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div> 
   
<script>

    function add()
    {
	 $('#modal_form').modal('show'); 
	}
	function add_limits(){
		var limit_enter = $('#count_text').val()
		limit = parseInt(limit_enter);
		data= $('#text_space').html();
		if(limit){
	    for(var i=0;i<limit;i++){
	      data=data+ '<div class="row"><div class="col-md-6"><select name="tag[]" id="tag" class="form-control">';
						<?php 
						if($dbtype == "SCADA"){						
						$sql= "SELECT ValueID ,ValueName FROM Archive WHERE ValueName LIKE'%Q%' AND ValueName NOT LIKE'%STEP%' AND ValueName NOT LIKE'%.PV%' AND ValueName NOT LIKE'%.SP%'";
						}else{
					    $sql= "SELECT TagUID AS ValueID,Tagname AS ValueName FROM [IS].VTagBrowsing WHERE Tagname LIKE '%COND%'";
						}
						$res = sqlsrv_query($conn,$sql);
						while($row= sqlsrv_fetch_array($res)){
						?>
						data=data+'<option value="<?php echo $row["ValueID"]; ?>"><?php echo $row["ValueName"]; ?></option>';
						<?php } ?>
						data=data+'</select></div><div class="col-md-6"><input class="form-control" name="text[]" placeholder="Text" type="text"></div></div>';
	      }
	    $('#text_space').html(data);
		}
	}
	function save()
    {
         var url;
         url = "<?php echo $baseurl; ?>controllers/add-conductivity-result.php";
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
				location.reload();
            }
        });
    }
$('.tag').on('change', function(){
   var editid = $(this).attr('rel');
   var tag =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-conductivity-tag.php",
            data: {editid: editid,tag: tag},
         });
        
 });
$('.text').on('change', function(){
   var editid = $(this).attr('rel');
   var text =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-conductivity-text.php",
            data: {editid: editid,text: text},

         });
        
         });	
function deleterow(hide_id)
	{
		 var result = confirm("Warning! Are You Sure To Delete This?");
		 if (result) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-conductivity-text.php",
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

</script>   
</section>
<?php include('includes/footer.php');?>