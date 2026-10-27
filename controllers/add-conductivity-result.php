<?php  include('../includes/config.php'); 
$res =true;
if(isset($_REQUEST['refid'])){
$tag = $_REQUEST['tag'];
$text = $_REQUEST['text'];
$refid = $_REQUEST['refid'];
$paramcount = count($tag);
for($x = 0; $x < $paramcount; $x++){
	    if($tag[$x] || $text[$x]){
        $sql="INSERT INTO [CONDUCTIVITY_DETAILS](Ref_Id,Tag,Text) VALUES('$refid','$tag[$x]','$text[$x]')";
		$res= sqlsrv_query($conn, $sql);
  }
		}
}
echo json_encode($res);

?>

