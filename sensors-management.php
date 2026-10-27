<?php include('includes/header.php'); 
    $Vessel_ID= $_REQUEST['vessel'];	 
	$sqlv= "SELECT * FROM VESSEL_DETAILS WHERE  Vessel_ID ='$Vessel_ID'";
	$resv= sqlsrv_query($conn, $sqlv);
	$rowv = sqlsrv_fetch_array($resv);				
	$Vessel_Name= $rowv['Vessel_Name'];						
	$vessel_code = $rowv['Vessel_Code'];
	
	
	$refid = $rowv['Equipment_Id'];
    $arr = array();
    if($refid)
        getparents($refid);
        krsort($arr);	
?>
<section class="content">
   <header class="content__title">
      <h1>SENSOR MANAGEMENT <i class="zmdi zmdi-forward" style="color:yellow;"></i><?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $Vessel_Name; ?>   </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <div class="dropdown actions__item">
            <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
            <div class="dropdown-menu dropdown-menu-right">
               <a href="" class="dropdown-item">Refresh</a>
            </div>
         </div>
      </div>
   </header>
   <?php include('includes/notification.php'); ?>
      <div class="card">
      <div class="card-body">

	 
		
<?php $vcode= str_replace("O",0,$vessel_code);
				$sql = "SELECT TagName,TagIndex FROM SensorIndex_".$vcode." WHERE TagName NOT LIKE '%MAIN_SEQ_STEP_RUN%' AND TagName NOT LIKE '%system\User%'";	
				$res= sqlsrv_query($conn, $sql);
				while($rows = sqlsrv_fetch_array($res)){
				$tagname = $rows['TagName'];
				$TagIndex = $rows['TagIndex'];
				$sensor_Tagname=explode(']',$tagname);
				$sensors=$sensor_Tagname[1];
				$sensor_name=str_replace('_', '-', $sensors);
				$dupes="SELECT MAX(Sensor_Tag) as tag FROM  SENSORS_HIGHLIMITS WHERE Vessel_ID='$Vessel_ID' AND  Sensor_Tag = '$sensors'";
				$dupesress= sqlsrv_query($conn, $dupes);
				$duprow = sqlsrv_fetch_array($dupesress);
				if(!$duprow['tag'])
				{
				$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
                 $Color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
				$sqlss="INSERT INTO SENSORS_HIGHLIMITS (Sensor_Tag,Sensor_Name,Vessel_ID,colors,TagIndex) VALUES('$sensors','$sensor_name','$Vessel_ID','$Color','$TagIndex')";
			    $ress= sqlsrv_query($conn, $sqlss);
				}
				}	
		
?>   	  
	   <p>&nbsp;</p>	  
	  <label class="custom-control custom-checkbox" style="display:none;">
      <input type="checkbox" id="checkAll" class="custom-control-input" <?php if($rowcheckedcount['Count'] == 0) {echo "checked"; }?> />Check/Uncheck All
       <span class="custom-control-indicator"></span>
       </label>	
         <div class="table-responsive">
		    <?php 
			?>
            <table id="data-table" class="table">
               <thead>
                <tr>
				  <th>#</th>
                  <th>Sensor Tag</th>
                  <th>Sensor Name</th>
				  <th>Analog Low Limit</th>
                  <th>Analog High Limit</th>
                  <th>Sensor Unit</th>
                  <th>Decimal</th>
              
                </tr>
               </thead>
                <tbody>
				<?php 
				$sql= "SELECT * FROM SENSORS_HIGHLIMITS WHERE Vessel_ID= '$Vessel_ID' ORDER BY  Sensor_Tag ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
				?>
                <tr>
                  <td>
				  <label class="custom-control custom-checkbox">
                 <input type="checkbox" onChange="change_status('<?php echo $row['ID'];?>',this.checked)" name="sensorchecked<?php echo $row['ID']; ?>" value="<?php echo $row['ID']; ?>"  <?php if($row['Sensor_Selected'] ==1) {echo 'checked';}  ?>  class="custom-control-input sensor_selected" />
                  <span class="custom-control-indicator"></span>
                  </label></td>	
				  <td><?php echo $row['Sensor_Tag']; ?></td>
				  <td><input type="text" rel="<?php echo $row['ID']; ?>" data-rel=""  id="sensor_name"  class="sensor_name form-control" placeholder="Enter Sensor Name here..." value="<?php echo $row['Sensor_Name']; ?>"/> </td>
				   <td>
	<input type="number" rel="<?php echo $row['ID']; ?>" data-rel=""  id="sensor_low"  class="sensor_low form-control" placeholder="Enter Sensor Low Limit here..." value="<?php echo $row['Sensor_lowvalue']; ?>"/>		  
				  </td>
				  <td>
		<input type="number" rel="<?php echo $row['ID']; ?>" data-rel=""  id="sensor_high"  class="sensor_high form-control" placeholder="Enter Sensor High Limit here..." value="<?php echo $row['Sensor_highvalue']; ?>"/>
				  </td>
				  <td>
		<input type="text" rel="<?php echo $row['ID']; ?>" data-rel=""  id="sensor_unit"  class="sensor_unit form-control" placeholder="Enter Sensor Unit here..." value="<?php echo $row['Sensor_Unit']; ?>"/>
				  </td>
				   <td>
				   
		<input type="text" rel="<?php echo $row['ID']; ?>" data-rel=""  id="tagdecimal"  class="tagdecimal form-control" 	 value="<?php echo $row['Tag_Decimal']; ?>"  />
				  </td>	
				  			  
                </tr>
				<?php } ?>
        
                </tbody>			                    
            </table>
         </div>
      </div>
   </div>   
<script>

$("#checkAll").click(function(){
if($(this).prop("checked") == true)	
$("input:checkbox").attr("checked",true);	
else
$("input:checkbox").attr("checked",false);		
$.post('controllers/update-all-sensor-status.php', { 'checked':$(this).prop("checked")}, function(data){
});
});
function change_status(value,checked){ 
    var checked = true;

    $("input:checkbox.sensor_selected").each(function (){
		if($(this).prop("checked")== false){
			checked = false;
		}
	}); 
	if(checked == true)
	$("#checkAll").attr("checked","checked");
    else
	$("#checkAll").attr("checked",false);
	$.post('controllers/update-sensor-status.php', { value:value }, function(data){
});
}

$('.sensor_name').on('change', function(){
   var editid = $(this).attr('rel');
   var sensorname =$(this).val();
   $.ajax ({
   			type:"post",
            url: "controllers/update-sensor-details.php",
            data: {editid: editid,sensorname: sensorname},
    });
        
});
		 
$('.sensor_high').on('change', function(){
   var editid = $(this).attr('rel');
   var sensor_high =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-sensor-high.php",
            data: {editid: editid,sensor_high: sensor_high},
  });
        
});
	
$('.sensor_low').on('change', function(){
   var editid = $(this).attr('rel');
   var sensor_low =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-sensor-low.php",
            data: {editid: editid,sensor_low: sensor_low},
         });
        
});
	
	
	
$('.sensor_unit').on('change', function(){
   var editid = $(this).attr('rel');
   var sensor_unit =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-sensor-units.php",
            data: {editid: editid,sensor_unit: sensor_unit},
         });
        
});
	
	
	
$('.tagdecimal').on('change', function(){
   var editid = $(this).attr('rel');
   var tagdecimal =$(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-tag-decimal.php",
            data: {editid: editid,tagdecimal: tagdecimal},
         });
        
});
$('.legendcolor').on('change', function(){
   var editid = $(this).attr('rel');
   var legendcolor =$(this).val();
   var exist = 0;
   var vessel = "<?php echo $Vessel_ID; ?>";
   $.ajax ({
   			type:"post",
            url: "controllers/legend-color-exist.php",
            data: {legendcolor: legendcolor,vessel:vessel},
			success: function(data)
            {
				if(data==0){
					$.ajax ({
   			           type:"post",
                       url: "controllers/update-legend-color.php",
                       data: {editid: editid,legendcolor: legendcolor},
                    });
				}else{
					alert("Color Already Exist");
				}
			}
            });
			
        
});	
</script>  
</section>
<?php include('includes/footer.php');?>