<?php  include('../includes/config.php'); 
if(isset($_REQUEST['theme']))
	{
		$theme= $_REQUEST['theme'];	
		$sql="UPDATE Config_Details SET themeselection = '$theme'";
		mysqli_query($link, $sql);			
    }
?>