   <?php if(isset($_SESSION['redirect'])){
	   $sess = $_SESSION['redirect'];
	   unset($_SESSION['redirect']);

	?>
	<div class="alert <?php if($sess['status']== true){?>alert-success<?php }else{ ?>alert-danger<?php } ?> alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                               <?php echo $sess['message']; ?> 
     </div>
   <?php } ?>  