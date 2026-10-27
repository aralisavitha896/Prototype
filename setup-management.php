 <?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> REPORT SETTINGS </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <p style="float:right"><button onclick="add()"  class="btn btn-primary"  type="button"><i class="zmdi zmdi-plus-circle-o-duplicate"></i> Add Details  </button> </a>
		 </p>
      </div>
   </header>
   <?php 
        include('includes/notification.php'); 
   
   		$sqlheader= "SELECT * FROM HEADER_DETAILS";
		$resheader= sqlsrv_query($conn, $sqlheader);
		$rowheader = sqlsrv_fetch_array($resheader);

		$sqlfooter= "SELECT * FROM FOOTER_DETAILS";
		$resfooter= sqlsrv_query($conn, $sqlfooter);
		$rowfooter = sqlsrv_fetch_array($resfooter);
   ?>
      <div class="card">
      <div class="card-body">
	   <!---<div class="col-sm-12 pull-right" ><a class="btn btn-primary" onclick="preview('commondiv');" ><i class="zmdi zmdi-eye"> Common Preview</i></a></div>--->
	 <!--- <div class="alertfixed alert-info" style="width:100%;">NOTE : Column <u><b>sql field name </b></u>should be same as sql database column name in your SCADA.</div>-->
         <div class="table-responsive" style="overflow:auto">
		 <div class="col-sm-12">&nbsp;</div>	
		<div class="row"> <div class="col-sm-6">&nbsp;<h2>Batch Details</h2></div> <div class="col-sm-6 pull-right" ><a class="btn btn-primary" href="img/layout/batch.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary" onclick="preview('batchdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$batch_arr = array();
				$x= 0;
				$sql= "SELECT * FROM REPORT_SETTINGS ORDER BY SET_ID ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
				$batch_arr[]= $row;	
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Page_name']; ?></td>
				  <td><?php echo $row['Page_data']; ?></td>
		  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['SET_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['SET_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>
            
         </div>
      </div>
   </div>
   <div style="display:none;">

   
   
    
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Report Settings</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <input name="edit_id" type="hidden" value="" />
				
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Page</label>
                     <select class="form-control" name ="page" id ="page">
					 <option value="batch">BATCH DETAILS</option>
					 <option value="report">REPORT FIRST PAGE</option>
					 <option value="alarm">ALARMS</option>
					 <option value="event">EVENTS</option>
					 <option value="esign">ELECTRONIC SIGNATURE EVENTS</option>
					 <option value="userlogin">USER LOGIN REPORT</option>
					 <option value="peroformedaction">PERFORMED ACTION ALARM</option>
					 </select>
                </div>
               </div>			  
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Type</label>
                     <select class="form-control" name ="type" id ="type" onchange ="fieldrequired(this.value);">
					 <option value="none">none</option>
					 <option value="starttime">starttime</option>
					 <option value="endtime">endtime</option>
					 <option value="startuser">startuser</option>
					 <option value="enduser">enduser</option>
					 <option value="sequence duration">sequence duration</option>
					 <option value="alarm text">alarm text</option>
					 <option value="date time">date time</option>
					 <option value="alarm type">alarm type</option>
					 </select>
                </div>
               </div>			   
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>Column Name</label>
                    <input type="text" class="form-control" placeholder="Enter Column Name..."  name="name"  id="name">
                     <i class="form-group__bar"></i>
                  </div>
                </div>			   			   
			   <div class="col-sm-12" id="divfield">
                  <div class="form-group">
				     <label>Sql Field Name</label>
                    <input class="form-control" id='field' name="field" placeholder="Enter Column Field..." type="text" >
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Order</label>
                    <input class="form-control" id='order' name="order" placeholder="Enter Vessel Order..." type="number">
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
   </div>  
<style>
.pull-right{
	text-align:right;
}

</style>
<script>
function preview(div){
divhtml = $("#"+div).html();
console.log(divhtml);
var popupWin = window.open(' ','_blank', 'location=no,left=200px');
popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
popupWin.document.write(divhtml);  
popupWin.document.close(document.location.reload(true));	
   
	
}
/*save sequence*/	
function fieldrequired(value)
    {  
	if(value =="sequence duration" || value =="startuser" || value =="enduser"){
		document.getElementById('divfield').style.display= "none";
	}else{
		document.getElementById('divfield').style.display= "block";
	}
    }
function add()
    {  
      save_method = 'add';
	   $("#field").attr("readonly",false);
      $('#form')[0].reset(); 
    $('#modal_form').modal('show');
    }
 function edit(id)
    { 
       save_method = 'update';
      $("#field").attr("readonly",true);
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-layout-report.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		for(i=0; i<data.length; i++){
			fieldrequired(data[i].Type);
            $('[name="name"]').val(data[i].Name);
            $('[name="field"]').val(data[i].Field);
            $('[name="order"]').val(data[i].Sort);
            $('[name="type"]').val(data[i].Type);
            $('[name="page"]').val(data[i].Page);
            $('[name="edit_id"]').val(data[i].Layout_ID);
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
         url = "<?php echo $baseurl; ?>controllers/add-layout-report.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-layout-report.php";
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

	
//delete 
function deleterow(hide_id)
	{
		  if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-layout-report.php",
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

function changestatus(column,value){
	    var text = '';
	    if(value == 1)
			text = "Disable";
		else
			text = "Enable";
		
		if (confirm("Warning! Are You Sure To "+text+" This?")) {	
		 $.ajax({
         data: {"column":column,"value":value},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/update-layout-statuses.php",
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