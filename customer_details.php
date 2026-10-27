<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Customer Details </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_dbdetails()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Create</button></p>
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
				  <th>Sl_no</th>
                  <th width="">Customer_Name</th>
                  <th width="">Logo</th>
				  <th width="">Action</th>
                </tr>
               </thead>
                <tbody>
                <tr>
				
				<?php				 	
			$x=1;
				
				$sql= "SELECT * FROM [CUSTOMER_DATA]";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
				?><tr>
				  <td><?php echo $x; ?></td>
				  <td><?php echo $row['Customer_Name']; ?></td>
				  <td><img src="uploads/logos/<?php echo $row['Logo'];  ?>" height="55" width='160'></td>
				 				  
				  <td>
				  <div class="btn-group">
                      <button onclick="edit_summary(<?php echo $row['Id']; ?>)" type="button" class="btn btn-primary"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i> </button>		
                   <button onclick="delete_summary(<?php echo $row['Id']; ?>)" type="button" class="btn btn-danger"><i class="zmdi zmdi-delete  zmdi-hc-fw"></i> </button>
				   
                      
                    </div>
				<?php $x++; }?>
				  </td>
				</tr>
        
                </tbody>           
                </tbody>            
            </table>
         </div>
      </div>
   </div>
      <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Customer Details</h5>
            </div>
            <div class="modal-body">
		
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				<input name="dbid" type="hidden" value="" />
			   <div class="col-sm-12">
                  <h3 class="card-body__title">Customer name</h3>
                  <div class="form-group">
                     <div class="">
                         <input type='text'  name="cust" id='cust' class='form-control' />
                     </div>
                </div>
                </div>
					<div class="col-sm-12">
                  <h3 class="card-body__title">Logo</h3>
                  <div class="form-group">
                     <div class="">
						 <input type='file' class='form-control' name='file' id='file'/>
                     </div>
                </div>
                </div>	
										
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="save()">Create</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
      </div>
      </div>
	  	<input type='hidden' name='oldimg' id='oldimg'>
			<input type='hidden' name='hideid' id='hideid'>
	  </form>
	
<script>

function delete_summary(hide_id)
	{
		 if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-cust.php",
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
	
function add_dbdetails()
{
	$('#modal_form').modal('show');	
    save_method = 'add';
    $('#form')[0].reset();
}
function save()
{
	var url;
      if(save_method == 'add')
      {
         url = "<?php echo $baseurl; ?>controllers/add-customer.php";
      }
      else
	  {
		url = "<?php echo $baseurl; ?>controllers/update-customer.php";  
		  
	  }
	//  alert(url);
	  var postData = new FormData($("#form")[0]);

		 $.ajax({
				 type:'POST',
				 url:url,
				 cache:false,
				 processData: false,
				 contentType: false,
				 data : postData,
				 success:function(html){
				 	
				// alert(html);
				location.reload();
				 }

			  });

}
function edit_summary(id)
    {	
       $('#modal_form').modal('show');
	  // $('#hideid').val(id);
	  save_method = 'update';
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-cust.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {   
			$('[name="hideid"]').val(data.Id);
            $('[name="cust"]').val(data.Customer_Name);
            $('[name="oldimg"]').val(data.Logo);
	
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