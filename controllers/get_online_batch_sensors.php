<?php  include('../includes/config.php'); 
date_default_timezone_set("Asia/Kolkata");
if(isset($_REQUEST['vessel_id'])){
	$vessel_id = $_REQUEST['vessel_id'];

	$sqlparam= "SELECT * FROM SENSORS_HIGHLIMITS WHERE Vessel_ID = '$vessel_id' AND Sensor_Selected =1";
	$resparam= sqlsrv_query($conn, $sqlparam);
	$i=0;
	while($rowparam = sqlsrv_fetch_array($resparam)){
	    //$html .= "<option value='".$rowparam['Sensor_Tag']."'>".$rowparam['Sensor_Name']."</option>"; <input type="checkbox" name="sensor_1[]" id="sensor"/>
	    $html .= "<div class='col-sm-2'><input type='checkbox' value='".$rowparam['Sensor_Tag']."' name='sensor_1[]' id='sensor'  /> ".$rowparam['Sensor_Name']."</div>";
	}
	
	
	
    echo $html;  

}

?>