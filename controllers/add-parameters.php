<?php  include('../includes/config.php'); 
if(isset($_REQUEST['seq'])){
	
$paramname = array_filter($_REQUEST['paramname']);
$paramtag = array_filter($_REQUEST['paramtag']);
$Units = array_filter($_REQUEST['unit']);
$decimal = array_filter($_REQUEST['decimal']);
$batch = $_REQUEST['seq'];
$paramcount = count($paramname);

  for($x = 0; $x < $paramcount; $x++){
    //$str[] = "('{$paramname[$x]}','{$paramtag[$x]}')";
  
		   $sql="INSERT INTO PARAMETERS_DETAILS(Sequence_ID,Param_Name,Parmaters_Tagname,Units,Decimal,checkparam) VALUES('$batch','$paramname[$x]','$paramtag[$x]', '$Units[$x]','$decimal[$x]',NULL)";
		  $res= sqlsrv_query($conn, $sql);
		  echo json_encode($res);
		  /* if($conn->query($sql))
		   {
			   echo "Inserted";
		   }
		   else
		   {
			   echo $conn->error;
		   }*/
  }
}

?>

