<?php  include('../includes/config.php'); 
if(isset($_REQUEST['vessel_id']))
{  
    $html = '<option value="">Select</option>';
    $vessel_id = $_REQUEST['vessel_id'];
	$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE Vessel_ID = '$vessel_id' AND Sensor_Selected =1";
	$resparam= sqlsrv_query($conn, $sqlparam);
	
	while($rowparam = sqlsrv_fetch_array($resparam)){
	    $html .= "<option value='".$rowparam['Sensor_Tag']."'>".$rowparam['Sensor_Name']."</option>";
	}
    echo $html;  
}

?>