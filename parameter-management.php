<?php include('includes/header.php'); 
	    $Vessel_ID = isset($_REQUEST['vessel']) ? $_REQUEST['vessel']:0 ;
		$Sequence_ID = isset($_REQUEST['sequence']) ? $_REQUEST['sequence']:0 ;	
		
        $sqldb = "Select * From [VESSEL_DETAILS] WHERE [Vessel_ID]='$Vessel_ID'";
		$resdb= sqlsrv_query($conn, $sqldb);
		$rowdb = sqlsrv_fetch_array($resdb);
		$vessel_code = $rowdb['Vessel_Code'];
        $sqlseq = "SELECT * From SEQUENCE_NUMBER_DETAIL WHERE ID='$Sequence_ID'";
		$resseq= sqlsrv_query($conn, $sqlseq);
		$rowseq = sqlsrv_fetch_array($resseq);		
		$seqtype = $rowseq['seqtype'];

?>
<section class="content">
   <header class="content__title">
      <h1>Sequence Management <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?>  <?php echo $rowdb['Vessel_Name']; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $rowseq['Seqname']; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> Parameter Management  </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
         </div>
		 <p style="float:right"><!----<button onclick="add_parameters(<?php //echo $Sequence_ID; ?>)"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add Parameter  </button>--></p>
      </div>
   </header>
      <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
      <div class="card-body">
	  <!--<div class="alertfixed alert-info">NOTE : The WinCC TagLogging Vessel PV group name should contain 'PARAMETER' text</div>--->
	  <?php 


	  
		$sqlcheckedcount= "SELECT COUNT(Parameter_ID) as Count FROM PARAMETERS_DETAILS WHERE Sequence_ID='$Sequence_ID' AND checkparam = '0'";
		$rescheckedcount= sqlsrv_query($conn, $sqlcheckedcount);
		$rowcheckedcount = sqlsrv_fetch_array($rescheckedcount,SQLSRV_FETCH_ASSOC);
	  ?>	 
      <p>&nbsp;</p>	  
	  <label class="custom-control custom-checkbox" style="display:none;">
      <input type="checkbox" id="checkAll" class="custom-control-input" <?php if($rowcheckedcount['Count'] == 0) {echo "checked"; }?> />Check/Uncheck All
       <span class="custom-control-indicator"></span>
       </label>	  
         <div class="table-responsive">
		    
            <table id="data-table" class="table table-bordered">
               <thead>
                <tr>
                  <th>#</th>
				  
                  <th>Parameter Name</th>
                  <th>Parameter Tags</th>
                  <th>Unit</th>
				<!--  <th>Sequence Name</th>-->
				  <th width="10%">Decimal</th>
				  <th width="10%">Parameter Order</th>
				  <th width="10%">Action</th>	
                </tr>
               </thead>
                <tbody>
				<?php 
				//echo $seqtype;
				 $vcode= str_replace("O",0,$vessel_code);
				if($seqtype == 0)
				{
				  $sql = "SELECT * FROM ParaIndex_".$vcode;	
				}
				else
				{
					$sql = "SELECT * FROM SubParaIndex_".$vcode;
				}
				$res= sqlsrv_query($conn, $sql);
				while($rows = sqlsrv_fetch_array($res)){
				$tagname = $rows['TagName'];
				$TagIndex = $rows['TagIndex'];
				$Parmaters_Tagname=explode(']',$tagname);
				$parameter=$Parmaters_Tagname[1];
				$dupes="SELECT MAX(Parmaters_Tagname) as tag FROM  PARAMETERS_DETAILS WHERE Sequence_ID = '$Sequence_ID' AND Parmaters_Tagname = '$parameter' AND TagIndex='$TagIndex'";
				$dupesress= sqlsrv_query($conn, $dupes);
				$duprow = sqlsrv_fetch_array($dupesress);
				if(!$duprow['tag'])
				{

				$sqlss="INSERT INTO PARAMETERS_DETAILS (Sequence_ID,Parmaters_Tagname,Param_Name,TagIndex,Units) VALUES('$Sequence_ID','$parameter','$parameter','$TagIndex','Bar')";
				$ress= sqlsrv_query($conn, $sqlss);
				}
				}	

				 $sql2= "SELECT * FROM  [PARAMETERS_DETAILS] WHERE Sequence_ID = '$Sequence_ID'";
				$res2= sqlsrv_query($conn, $sql2);
				while($row = sqlsrv_fetch_array($res2)){		
				
				?>
                <tr>
					 <td>
				  <label class="custom-control custom-checkbox">
                 <input type="checkbox" onChange="change_status('<?php echo $row['Parameter_ID'];?>',this.checked)" name="sensorchecked<?php echo $row['Parameter_ID']; ?>" value="<?php echo $row['Parameter_ID']; ?>"  <?php if($row['checkparam'] ==1) {echo 'checked';}  ?>  class="custom-control-input sensor_selected" />
                  <span class="custom-control-indicator"></span>
                  </label></td>	
				<td><?php  echo $row['Param_Name']; ?></td>
				<td><?php echo $row['Parmaters_Tagname']; ?></td>
				<td><?php echo utf8_encode($row['Units']); ?></td>
				
				  <td><?php echo $row['Decimal']; ?></td>
			
			
<td><input type="text" rel="<?php echo $row['Parameter_ID']; ?>" data-rel=""  id="setparamorder"  class="setparamorder form-control" placeholder="Set Order..." value="<?php echo $row['Param_Order']; ?>"/></td>					  
				  <td>
					<div class="btn-group">                     
					<button onclick="edit_parameter(<?php echo $row['Parameter_ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </button>
                     <button onclick="delete_parameter(<?php echo $row['Parameter_ID']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> 	</button>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>		                    
            </table>
			
			<!---parameter--->
	<div id="modal_form_parameter" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			 <div class="modal-header">
               <h5 class="modal-title pull-left">Parameter Management</h5>
            </div>
			<div class="modal-body form">
				<form id="formparam" action="#" class="form-horizontal" >
					<input name="seq" type="hidden" value="" />
					<input name="hiddenid" type="HIDDEN" value="" />
					<div class="form-body">
					<div class="form-group">
							<label class="col-md-4 card-body__title">Parameter Name</label>	
							<label class="col-md-3 card-body__title">Tag </label>	
							<label class="col-md-2 card-body__title">Unit</label>	
							<label class="col-md-2 card-body__title">Decimal</label>	
						</div>
                        <?php for($i=0;$i<10;$i++){ ?>
						<div class="row">
								<div class="col-sm-4">
                                    <div class="form-group">
                                       <input class="form-control" name="paramname[]" placeholder="Enter Parameters Name..." type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-3">
                                    <div class="form-group">
                                       <input class="form-control" name="paramtag[]" placeholder="Parmaters Tag Name" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-2">
                                    <div class="form-group">
                                       <input class="form-control" name="unit[]" placeholder="Unit" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-2">
                                    <div class="form-group">
                                       <input class="form-control" name="decimal[]" placeholder="" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div> </div>
						<?php } ?>
					</div>
				</form>
			</div> 
			<div class="modal-footer">
				<button id="btnSave" class="btn btn-primary" onclick="save()" type="button">Save</button>
				<button class="btn btn-danger" data-dismiss="modal" type="button">Cancel</button></div></div> </div> </div>
	
			
	<div id="edit_form_parameter" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			 <div class="modal-header">
               <h5 class="modal-title pull-left">Update Parameter Management</h5>
            </div>
			<div class="modal-body form">
				<form id="formparams" action="#" class="form-horizontal" >
					<input name="seq" type="hidden" value="" />
					<input name="hiddenid" type="HIDDEN" value="" />
					<div class="form-body">
					<div class="form-group">
							<label class="col-md-4 card-body__title">Parameter Name</label>	
							<label class="col-md-3 card-body__title">Tag </label>	
							<label class="col-md-2 card-body__title">Unit</label>	
							<label class="col-md-2 card-body__title">Decimal</label>	
						</div>
						<div class="row">
								<div class="col-sm-4">
                                    <div class="form-group">
                                       <input class="form-control" name="hidebox" id="hidebox"  type="hidden">
                                       <input class="form-control" name="paramname" placeholder="Enter Parameters Name..." type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-3">
                                    <div class="form-group">
                                       <input class="form-control" name="paramtag" placeholder="Parmaters Tag Name" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-2">
                                    <div class="form-group">
                                       <input class="form-control" name="unit" placeholder="Unit" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
								<div class="col-sm-2">
                                    <div class="form-group">
                                       <input class="form-control" name="decimal" placeholder="" type="text">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div> </div>
						
			<div class="modal-footer">
				<button id="btnSave" class="btn btn-primary" onclick="saveparam()" type="button">Save</button>
				<button class="btn btn-danger" data-dismiss="modal" type="button">Cancel</button></div>
					</div>
				</form>
			</div> </div> </div> </div>
   
<script>
$('.setparamorder').on('change', function(){
   var editid = $(this).attr('rel');
   //var uid=$(this).attr('data-rel');
   var setparamorder =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-set-paramorder.php",
            data: {editid: editid,setparamorder: setparamorder},
            //success: function(data) 
            //{ showMessage('Low value updated successfully'); }
         });
        
     });
	
function add_parameters(id)
    {
		save_method = 'add';
      $('#formparam')[0].reset(); 
      $('[name="seq"]').val(id);
      $('#modal_form_parameter').modal('show'); 
    }
		function saveparam()
    {
      var url = "<?php echo $baseurl; ?>controllers/update-parameters.php";

          $.ajax({
            url : url,
            type: "POST",
            data: $('#formparams').serialize(),
            dataType: "JSON",
            success: function(data)
            {
             $('#modal_form').modal('hide');
             location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                //alert('Error adding / update data');
				location.reload();
            }
        });
    }
	
function edit_parameter(id)
    {
			save_method = 'update';
			 $('#edit_form_parameter').modal('show'); 
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/edit-parameters.php?sequence=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		for(i=0; i<data.length; i++)
		{
			//alert(data[i].Units);
            $('#formparams').modal('show'); 
			
            $('[name="seq"]').val(data[i].Sequence_ID);
            $('[name="paramname"]').val(data[i].Param_Name);
            $('[name="hidebox"]').val(data[i].checkparam);
			$('[name="paramtag"]').val(data[i].Parmaters_Tagname);
			$('[name="hiddenid"]').val(data[i].Parameter_ID);
			//$('[name="unit"]').val(data[i].Units);
			$('[name="unit"').val(data[i].Units);
			$('[name="decimal"]').val(data[i].Decimal);
			
		}	
		
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
         url = "<?php echo $baseurl; ?>controllers/add-parameters.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-parameters.php";
      }
          $.ajax({
            url : url,
            type: "POST",
            data: $('#formparam').serialize(),
            dataType: "JSON",
            success: function(data)
            {
				//alert(data);
              $('#modal_form').modal('hide');
              location.reload();
            },
        error: function (jqXHR, textStatus, errorThrown)
        {
           // alert('Error in Inserting / Updating data');
		   location.reload();
        }
        });
    }
	


function change_status(value,checked){ 
    var checked = true;

    $("input:checkbox.sensor_selected").each(function (){
		if($(this).prop("checked")== false){
			checked = false;
		}
	}); 
	if(checked == true)
	$("#checkAll").attr("checked","checked");
    else
	$("#checkAll").attr("checked",false);
	$.post('controllers/update-parameter-status.php', { value:value }, function(data){
});
}

$('.param_name').on('change', function(){
   var editid = $(this).attr('rel');
   var param_name =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-parameter-name.php",
            data: {editid: editid,param_name: param_name},
         });
        
    });
$('.param_tag').on('change', function(){
   var editid = $(this).attr('rel');
   var param_tag =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-parameter-tag.php",
            data: {editid: editid,param_tag: param_tag},
         });
        
 });
$('.param_unit').on('change', function(){
   var editid = $(this).attr('rel');
   var param_unit =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-parameter-unit.php",
            data: {editid: editid,param_unit: param_unit},

         });
        
         });		 
		 
$('.param_decimal').on('change', function(){
   var editid = $(this).attr('rel');
   var param_decimal =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-parameter-decimal.php",
            data: {editid: editid,param_decimal: param_decimal},
        });
        
 });
 $('.param_order').on('change', function(){
   var editid = $(this).attr('rel');
   var param_order =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-parameter-order.php",
            data: {editid: editid,param_order: param_order},
        });
        
 });

function delete_parameter(userid)
	{
		if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":userid},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-parameter.php",
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