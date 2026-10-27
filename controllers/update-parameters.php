<?php  include('../includes/config.php'); 
if(isset($_REQUEST['hiddenid'])){
$ID = $_REQUEST['hiddenid'];	
$paramname = $_REQUEST['paramname'];
$paramtag =  $_REQUEST['paramtag'];
$batch = $_REQUEST['seq'];
$unit = $_REQUEST['unit'];
$decimal = $_REQUEST['decimal'];
$hide = $_REQUEST['hidebox'];


    $sql="UPDATE PARAMETERS_DETAILS set Sequence_ID = '$batch',checkparam='$hide',  Param_Name = '$paramname', Parmaters_Tagname= '$paramtag', Units= '$unit', Decimal= '$decimal' WHERE Parameter_ID = '$ID'";
   $res= sqlsrv_query($conn, $sql);
   echo json_encode($res);
 
}

?>
