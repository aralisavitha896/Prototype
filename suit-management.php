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
      <h1> Suit Management  <?php if($arrcount ){ ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php } ?> <?php foreach($arr as $row){ echo $row; if(++$i != $arrcount){?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?>  </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
			<p style="float:right"><button onclick="add_suit()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i>Add Suit</button></p>
         </div>
      </div>
   </header>
       <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">
         <div class="table-responsive" style="overflow:auto">
            <table id="data-table" class="table table-bordered">
               <thead>
                  <tr>
                  <th>Suit Name</th>
                  <th>Order</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
		        $sql= "SELECT * FROM SUIT_DETAILS WHERE Refid = '$refid' ORDER BY Suit_Order ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
				?>
                <tr>
				  <td><?php echo $row['Suit_Name']; ?></td>
				  <td><?php echo $row['Suit_Order']; ?></td>				  				  				  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-success" href="suit-management.php?refid=<?php echo $row['Suit_ID']; ?>" title="create subsuite"><i class="zmdi zmdi-plus  zmdi-hc-fw"></i></a>
					  <a class="btn btn-primary" onclick="edit_suit(<?php echo $row['Suit_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="delete_suit(<?php echo $row['Suit_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
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
               <h5 class="modal-title pull-left">Suit Management</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <input name="Suit_ID" type="hidden" value="" />
			    <input name="refid" type="hidden" value="<?php echo $refid; ?>" />
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>Suit Name</label>
                    <input type="text" class="form-control" placeholder="Enter Suit Name..."  name="Suit_Name"  id="Suit_Name">
                     <i class="form-group__bar"></i>
                  </div>
                </div>			   
			   <div class="col-sm-12">
				    <label>Suit Order</label>
					 <input class="form-control" id='Suit_Order' name="Suit_Order" placeholder="Enter Suit Order..." type="number">
                     <i class="form-group__bar"></i>
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
  .p15{
	  padding-left:15px;
	  padding-right:15px;
  }
  </style> 
<script>
function add_suit()
    {  
    save_method = 'add';
    $('#form')[0].reset(); 
    $('#modal_form').modal('show');
    }
 function edit_suit(id)
    { 
      save_method = 'update';

      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-suit.php?Suit_ID=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		for(i=0; i<data.length; i++){
            $('[name="Suit_Name"]').val(data[i].Suit_Name);
            $('[name="Suit_Order"]').val(data[i].Suit_Order);
            $('[name="Suit_ID"]').val(data[i].Suit_ID);		
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
         url = "<?php echo $baseurl; ?>controllers/add-suit.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-suit.php";
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
function delete_suit(userid)
	{
		if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":userid},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-suit.php",
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