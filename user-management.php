<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> User Management  </h1>
      <small>&nbsp;</small>
      <div class="actions">
		 <div>
		 <br>
		 	<p>
			<button onclick="add_user()"  class="btn btn-primary"  type="button"><i class="fa fa-database"></i> Add User </button>	
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
                  <th>Username</th>
                  <th>Password</th>
                  <th>User Type</th>
                  <th>Created Date</th>     
				  <th width="20%">Action</th>
                  </tr>
               </thead>
               <tbody>
			    <?php 
				$x= 0;
				$sql= "SELECT * FROM Users";
				$res= mysqli_query($link, $sql);
				while($row = mysqli_fetch_array($res)){
					$x++;
				?>
				<tr>
                  <td><?php echo $x; ?></td>
				  <td><?php echo $row['username']; ?></td>
				  <td>******</td>
				  <td><?php echo $row['usertype']; ?></td>				  
				  <td><?php echo $row['add_date']; ?></td>
				  <td>
				      <a class="btn btn-primary" onclick="edit_user(<?php echo $row['id']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="delete_user(<?php echo $row['id']; ?>)" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
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
		 <form id="form"  action="<?php echo $baseurl; ?>controllers/add-users.php" class="form-horizontal" enctype="multipart/form-data" method="POST">
            <div class="modal-header">
               <h5 class="modal-title pull-left">User Management</h5>
            </div>
            <div class="modal-body">
			   <input name="editid" type="hidden" value="" />
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>User Type</label>
                    <select name="usertype" id="usertype" class="form-control">
					<option value="user">User</option>
					<option value="admin">Administrator</option>
					<option value="Superadmin">Superadmin</option>
					</select>
                     <i class="form-group__bar"></i>
                </div>
               </div>				
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>User Name</label>
                    <input type="text" class="form-control"  name="username" id="username" required>
                     <i class="form-group__bar"></i>
                  </div>
                </div>
			    <div class="col-sm-12">
                  <div class="form-group">
				     <label>Password</label>
                    <input type="password" class="form-control" name="password"  id="password"  required>
                     <i class="form-group__bar"></i>
                </div>
               </div>				   
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Confirm Password</label>
                    <input type="password" class="form-control" name="cpassword" id="cpassword" required>
                    <span class="error" id="error"></span>
                     <i class="form-group__bar"></i>
                </div>
               </div>			    

			   <div class="col-sm-12">
			   	<label>Dashboard Menu</label>
			   </div>
			   	<div class="col-sm-12">
                  <div class="form-group">
                      <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" id="check1" value="1" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Database Management</span>
                      </label>	        
					 <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="2" id="check2" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">User Management</span>
                      </label>		
					   <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="3" id="check3">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Layout Management</span>
                       </label>						  
					  <label class="custom-control custom-checkbox" <?php if($suitstatus !="yes") { ?>style="display:none;" <?php } ?>>
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="4" id="check4">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Suit Management</span>
                      </label>				
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="5" id="check5">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Vessel Management</span>
                      </label>				
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="6" id="check6" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Sequence Management</span>
                      </label>	
                       <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="7" id="check7">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Sensor Management</span>
                       </label>            
					   <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="8" id="check8">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Summary Management</span>
                       </label>						  
					   <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="9" id="check9">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Reports</span>
                       </label>					   
					   <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="10" id="check10">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Manual Alarms / Events</span>
                       </label>	
					   <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="11" id="check11">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Online Batch Comparison</span>
                       </label>	
						<label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="12" id="check12">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Customer Details</span>
                       </label>	
						<label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="menu[]" value="13" id="check13">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Clear Database</span>
                       </label>						   
                </div>
                </div>
			   <div class="col-sm-12">
			   	<label>Left Menu</label>
			   </div>
			   	<div class="col-sm-12">
                  <div class="form-group">
                      <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="lmenu[]" id="left1" value="1" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Database Management</span>
                      </label>	        
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="lmenu[]" value="2" id="left2" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Reports</span>
                      </label>					
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="lmenu[]" value="3" id="left3">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Project Management</span>
                      </label>					  
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="lmenu[]" value="4" id="left4" checked>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Change Password</span>
                      </label>							  
                     <i class="form-group__bar"></i>
                </div>				
               </div>	
                <div class="col-sm-12">
			   	<label>Batch Report</label>
			   </div>			   
			   	<div class="col-sm-12">
                  <div class="form-group">
                      <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="sensorselection" id="sensorselection" value="1" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Sensor Selection</span>
                      </label>                      
					  <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="interval" id="interval" value="1" >
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Interval Selection</span>
                      </label>	        							  
                     <i class="form-group__bar"></i>
                </div>				
               </div>		   

            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
			</form>
         </div>
      </div>
   </div>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.min.js"></script>     
<script>	
 $("#form").validate({
        rules: {
         username : {
         required: true,
		 //username_exist:true
         },
         password: {
           required: true,
         },
		 cpassword: {
           required: true,
		   equalTo:"#password"
         }
       },
   	messages: {
       username: {
         required: 'Please enter username'
       },
       password: {
         required: 'Please enter password',
       }, 
	   cpassword: {
         required: 'Please enter cpassword',
		 equalTo:"Password and confirm password should be same"
       }, 
   	},	
});
jQuery.validator.addMethod("username_exist", function(value, element) {
   	var isSuccess = false;
      $.ajax({ url: "<?php echo $baseurl; ?>controllers/username-exist.php", 
               data: {"username":value}, 
   			method:"post",
               async: false, 
               success: 
                   function(msg) { isSuccess = msg == 0 ? false : true }
             });
       return isSuccess;					
   			
   }, 'Username Already Exist');
function add_user()
    {
      save_method = 'add';
	  document.getElementById('form').action = "<?php echo $baseurl; ?>controllers/add-users.php";
      $('#form')[0].reset(); // reset form on modals	  
      $('#modal_form').modal('show'); 

    }	

function edit_user(id)
    {
      save_method = 'update';
      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-users.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			for(var i=1;i<=10;i++){
			document.getElementById('check'+i).checked = false;	
			}			
			for(var i=1;i<4;i++){
			document.getElementById('left'+i).checked = false;	
			}
            $('[name="editid"]').val(data.id);
            $('[name="username"]').val(data.username);
            $('[name="password"]').val(data.password);
            $('[name="cpassword"]').val(data.password);
            $('[name="usertype"]').val(data.usertype);
			if(data.sensorselection !=0)
            document.getElementById('sensorselection').checked= true;			
		    if(data.intervalselection !=0)
            document.getElementById('interval').checked= true;
			var str = data.menu;
			var strl = data.lmenu;
			if(str){
			var strarr = str.split(',');
			$.each(strarr,function(index,value){
				document.getElementById('check'+value).checked = true;
			});
			}			
			if(strl){
			var strlarr = strl.split(',');
			$.each(strlarr,function(index,value){
				document.getElementById('left'+value).checked = true;
			});
			}
			document.getElementById('form').action = "<?php echo $baseurl; ?>controllers/update-users.php";
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
         url = "<?php echo $baseurl; ?>controllers/add-users.php";
      }
      else
	  {
		url = "<?php echo $baseurl; ?>controllers/update-users.php";  
		  
	  }
 
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
              $('#modal_form').modal('hide');
                //location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				$('#modal_form').modal('hide');
				//location.reload();
            }
        });
    }		

function delete_user(userid)
	{
		 var data ={"hide_id":userid}; 
		 var result = confirm("Warning! Are You Sure To Delete This?");
		 if (result) {	
		 $.ajax({
         data: data,
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-users.php",
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