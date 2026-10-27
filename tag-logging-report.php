	<?php include('includes/header.php'); ?>

      <?php
	  if (isset($_REQUEST['starttime'])) {
    $starttime = $_REQUEST['starttime'];
    $endtime = $_REQUEST['endtime'];
    $batch = $_REQUEST['batch'];
    $batchname = base64_decode($_REQUEST['batchname']);
     $batchdesc = base64_decode($_REQUEST['batchdesc']);
    $vessel = $_REQUEST['vessel'];
    $parameter = $_REQUEST['parameters'];
    $timeduration = $_REQUEST['duration'];
    $schools_array = explode(",", $parameter);
    $result = count($schools_array);
	$date_a = new DateTime($starttime);
    $date_b = new DateTime($endtime);
    $interval = date_diff($date_a,$date_b);
    $duration = $interval->format('%h:%i:%s');
}
//date time for alarms
	$StartTimenew = new DateTime($starttime);
	$StartTimenew->modify("$timevalue$utchour hour $timevalue$utcminutes minutes");
	$time_zone_start = $StartTimenew->format("Y-m-d H:i:s");	
						
	$EndTimenew = new DateTime($endtime);
	$EndTimenew->modify("$timevalue$utchour hour $timevalue$utcminutes minutes");
	$time_zone_end = $EndTimenew->format("Y-m-d H:i:s");
				//sequence 
			         $sqlsequence = "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Batch_Type = '$batch'";
			        $ressequence = sqlsrv_query($conn, $sqlsequence);
			        $rowsequence = sqlsrv_fetch_array($ressequence,SQLSRV_FETCH_ASSOC);
			        $seqtype = $rowsequence['seqtype'];
					$sequence_name = $rowsequence['Seqname'];
					$batch_type = $rowsequence['Batch_Type'];
					$sequence = $rowsequence['ID'];
					$compno = $rowsequence['compno'];
				//vessel data
					$sqlvessel = "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
					$resvessel= sqlsrv_query($conn, $sqlvessel);
					$rowvessel = sqlsrv_fetch_array($resvessel,SQLSRV_FETCH_ASSOC);
					$vessel_name = $rowvessel["Vessel_Name"];
					$equipment_id = $rowvessel["Equipment_Id"];
					$vessel_code = $rowvessel['Vessel_Code'];
					$Alarm_Filter = $rowvessel['Alarm_Filter'];
					
					
					
					
					
					
					
					
					
					$vcode= str_replace("O",0,$vessel_code);
					if ($seqtype == "1") 
					{
						$tableparamindex ="SubParaIndex_".$vcode; 
						$table ="SubParaFloat_".$vcode;
						$tablestring ="SubParaString_".$vcode;
						$tablesensor="SensorIndex_".$vcode;	
						$steptag=$vessel_code."_SUB_SEQ_STEP_RUN";
						$userdatatable="SensorString_".$vcode;
						$datatable="SensorFloat_".$vcode;
						$tablealarm="AllEvent";
						$startuser = $rowvessel['startuser_sub'];
						$enduser = $rowvessel['enduser_sub'];
						
					}
					else 
					{
						$tableparamindex ="ParaIndex_".$vcode; 
						$table ="ParaFloat_".$vcode;
						$tablesensor="SensorIndex_".$vcode;
						$steptag=$vessel_code."_MAIN_SEQ_STEP_RUN";
						$userdatatable="SensorString_".$vcode;
						$datatable="SensorFloat_".$vcode;
						$tablealarm="AllEvent";
						$tablestring ="ParaString_".$vcode;
						$startuser = $rowvessel['startuser'];
						$enduser = $rowvessel['enduser'];
					}
					
					//for start and end user
					 $sqlstart = "SELECT TOP 1 Val from $tablestring where TagIndex = '$startuser' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime ASC";
					$resstart = sqlsrv_query($conn, $sqlstart);
					$rowstart = sqlsrv_fetch_array($resstart);
					$batchstartuser=$rowstart ['Val'];
					
					$sqlend = "SELECT TOP 1 Val from $tablestring where TagIndex = '$enduser' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$resend= sqlsrv_query($conn, $sqlend);
					$rowend = sqlsrv_fetch_array($resend);
					$batchenduser=$rowend ['Val'];
					//for start and end user
					
					

					$parameter_arr  =array();
					$completion=false;
					//customer data
					$sqlcustomer = "SELECT * FROM CUSTOMER_DATA";
			        $rescustomer = sqlsrv_query($conn, $sqlcustomer);
			        $rowsequence = sqlsrv_fetch_array($rescustomer,SQLSRV_FETCH_ASSOC);
			        $logo = $rowsequence['Logo'];
					//HEADER DETAILS 
					$sqlheader = "SELECT * FROM HEADER_DETAILS";
					$resheader = sqlsrv_query($conn, $sqlheader);
					$rowheader = sqlsrv_fetch_array($resheader);
					//user details
					  $sqluserindex= "SELECT * FROM $tablesensor WHERE TagName LIKE '%system\User%'";
					 $reuserindex= sqlsrv_query($conn, $sqluserindex);
					 $rowuserindex = sqlsrv_fetch_array($reuserindex);
					 $userindex = $rowuserindex['TagIndex'];
								

					
                    $p= 0;
		             $sqlparameter= "SELECT * FROM PARAMETERS_DETAILS WHERE Sequence_ID ='$sequence' AND checkparam ='1' ORDER BY Param_Order ASC";
		            $resparameter= sqlsrv_query($conn, $sqlparameter);
		            while($rowparameter = sqlsrv_fetch_array($resparameter,SQLSRV_FETCH_ASSOC)){
		                  $tags = $rowparameter['Parmaters_Tagname']; 
		                  $Param_Name = $rowparameter['Param_Name']; 
						  $Decimal = $rowparameter['Decimal'];
						  //paramtagindex 
						  $sqltagindex= "SELECT * FROM $tableparamindex WHERE TagName LIKE '%$tags%'";
						  $restagindex= sqlsrv_query($conn, $sqltagindex);
						  $rowtagindex = sqlsrv_fetch_array($restagindex);
						  $TagIndex = $rowtagindex['TagIndex'];
						  			
						  $sqls = "SELECT TOP 1 Val from $table where TagIndex = '$TagIndex' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
						 $ress = sqlsrv_query($conn, $sqls);
						 $rows = sqlsrv_fetch_array($ress);
		                  		  
						  
		                  $parameter_arr[$p]["value"] = $rows["Val"];
		                  $parameter_arr[$p]["name"] = $rowparameter['Param_Name'];
		                  $parameter_arr[$p]["units"] = $rowparameter['Units'];
		                  $parameter_arr[$p++]["decimal"] = $rowparameter['Decimal'];
		   
		            }		

				//for step description
					$step_logs=array();
		            $sqlsteplogs = "Select * from STEP_LOGS WHERE Sequence_ID ='$sequence'";
		            $ressteplogs = sqlsrv_query($conn, $sqlsteplogs);
		            while($rowsteplogs =sqlsrv_fetch_array($ressteplogs,SQLSRV_FETCH_ASSOC)){
			            $step_no = $rowsteplogs['Step_No'];
                        $step_logs[$step_no]= $rowsteplogs['Step_Tag'];
                    }
					
					$sqlsteptag= "SELECT * FROM $tablesensor WHERE TagName LIKE '%$steptag%'";
					$ressteptag= sqlsrv_query($conn, $sqlsteptag);
					$rowsteptag = sqlsrv_fetch_array($ressteptag);
					$steptagindex = $rowsteptag['TagIndex'];
					 
					$sqlstepcommon = "SELECT DateAndTime,Val from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$starttime' and '$endtime') AND Val !='' order by DateAndTime ASC";
					$previous_log = "";
					$step_arr_main = array();
					$resstep_common = sqlsrv_query($conn, $sqlstepcommon);
					$rowstepnew = sqlsrv_fetch_array($resstep_common);
					$newstart=$rowstepnew['DateAndTime']->format('Y-m-d H:i:s');
					 
					 $sqlstep = "SELECT DateAndTime,Val from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$newstart' and '$endtime') AND (DATEDIFF(SECOND, DateAndTime,'$newstart')%$timeduration=0 )AND Val !=''  order by DateAndTime ASC";
					$previous_log = "";
					$step_arr = array();
					$resstep = sqlsrv_query($conn, $sqlstep);
					while ($rowstepviewalls = sqlsrv_fetch_array($resstep)) {
								$step_arr[] = $rowstepviewalls;
								if($rowstepviewalls['Val']==$compno){$completion=true;}	
					}
					
					
					if(!$completion)
					{
					 $sqlstepcomp = "SELECT top 1 DateAndTime,Val from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$newstart' and '$endtime') AND Val ='$compno' order by DateAndTime ASC";
					$resstepcomp = sqlsrv_query($conn, $sqlstepcomp);
					$rowstepcomp = sqlsrv_fetch_array($resstepcomp);
					$completiontimestamp=$rowstepcomp['DateAndTime']? $rowstepcomp['DateAndTime']->format($dateformat):null;
					$completiontimestamps=$rowstepcomp['DateAndTime']? $rowstepcomp['DateAndTime']->format('Y-m-d H:i:s'):null;
					} 
				//for step description
					
				//FOR SUMMARY DETAILS					$summarycount = 0;
				      $sqlsummary =  "SELECT * FROM  [SUMMARY_DETAILS] WHERE Sequence_ID = '$sequence' and [Vessel_ID]='$vessel' order by ID desc";
					$ressummary = sqlsrv_query($conn, $sqlsummary);
					while($rowsummary =  sqlsrv_fetch_array($ressummary,SQLSRV_FETCH_ASSOC)){
					++$summarycount;
					 $Summary_Type = $rowsummary['Summary_Type'];
					$Initial_1 = $rowsummary['Initial_1'];
					$Initial_2 = $rowsummary['Initial_2'];
					$Final_1 = $rowsummary['Final_1'];
					$Final_2 = $rowsummary['Final_2'];
					$Result_Count = $rowsummary['Result_Count'];
					$Allowable_Drop = $rowsummary['Allowable_Drop'];
					$con_tag = $rowsummary['con_tag'];
					
					
					
					if($Summary_Type == 'pht_sel')
					{
						
					$sqlparameter1= "SELECT * FROM PARAMETERS_DETAILS WHERE TagIndex ='$Allowable_Drop' AND Sequence_ID= '$sequence'";
		            $resparameter1= sqlsrv_query($conn, $sqlparameter1);
		            $rowparameter1 = sqlsrv_fetch_array($resparameter1,SQLSRV_FETCH_ASSOC);
					$allowblename=$rowparameter1['Param_Name'];
					$allowbledecimal=$rowparameter1['Decimal'];
					
					$sqlAllowable = "SELECT TOP 1 Val from $table where TagIndex = '$Allowable_Drop' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$resAllowable = sqlsrv_query($conn, $sqlAllowable);
					$rowsAllowable = sqlsrv_fetch_array($resAllowable);
					$allowable=$rowsAllowable['Val'];
					
					$sqlparameterin= "SELECT * FROM PARAMETERS_DETAILS WHERE TagIndex ='$Initial_1'  AND Sequence_ID= '$sequence'";
		            $resparameterin= sqlsrv_query($conn, $sqlparameterin);
		            $rowparameterin = sqlsrv_fetch_array($resparameterin,SQLSRV_FETCH_ASSOC);
					$intial1_name=$rowparameterin['Param_Name'];
					$initial1_decimal=$rowparameterin['Decimal'];
					
					 $sqlInitial_1 = "SELECT TOP 1 Val from $table where TagIndex = '$Initial_1' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$ressInitial_1 = sqlsrv_query($conn, $sqlInitial_1);
					$rowsInitial_1 = sqlsrv_fetch_array($ressInitial_1);
					$Initial_1_val=$rowsInitial_1['Val'];
					
					$sqlparameterfin= "SELECT * FROM PARAMETERS_DETAILS WHERE TagIndex ='$Final_1'  AND Sequence_ID= '$sequence'";
		            $resparameterfin= sqlsrv_query($conn, $sqlparameterfin);
		            $rowparameterfin = sqlsrv_fetch_array($resparameterfin,SQLSRV_FETCH_ASSOC);
					$final1_name=$rowparameterfin['Param_Name'];
					$final1_decimal=$rowparameterfin['Decimal'];	
					
					 $sqlFinal_1 = "SELECT TOP 1 Val from $table where TagIndex = '$Final_1' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$resFinal_1 = sqlsrv_query($conn, $sqlFinal_1);
					$rowsFinal_1 = sqlsrv_fetch_array($resFinal_1);
					$Final_1_val=$rowsFinal_1['Val'];

					if($Result_Count ==2){
					$sqlin2= "SELECT * FROM PARAMETERS_DETAILS WHERE TagIndex ='$Initial_2'  AND Sequence_ID= '$sequence'";
		            $resin2= sqlsrv_query($conn, $sqlin2);
		            $rowin2 = sqlsrv_fetch_array($resin2,SQLSRV_FETCH_ASSOC);
					$initial2_name=$rowin2['Param_Name'];
					$initial2_decimal=$rowin2['Decimal'];
					
					$sqlInitial_2 = "SELECT TOP 1 Val from $table where TagIndex = '$Initial_2' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$ressInitial_2 = sqlsrv_query($conn, $sqlInitial_2);
					$rowsInitial_2 = sqlsrv_fetch_array($ressInitial_2);
					$Initial_2_val=$rowsInitial_2['Val'];


					$sqlfin2= "SELECT * FROM PARAMETERS_DETAILS WHERE TagIndex ='$Final_2'  AND Sequence_ID= '$sequence'";
		            $resfin2= sqlsrv_query($conn, $sqlfin2);
		            $rowfin2 = sqlsrv_fetch_array($resfin2,SQLSRV_FETCH_ASSOC);
					$final2_name=$rowfin2['Param_Name'];
					$final2_decimal=$rowfin2['Decimal'];
					
					$sqlFinal_2 = "SELECT TOP 1 Val from $table where TagIndex = '$Final_2' and (DateAndTime between '$starttime' and '$endtime')  order by DateAndTime DESC";
					$resFinal_2 = sqlsrv_query($conn, $sqlFinal_2);
					$rowsFinal_2 = sqlsrv_fetch_array($resFinal_2);
					$Final_2_val=$rowsFinal_2['Val'];
					$result2 = ($Initial_2_val - $Final_2_val)<= $allowable ? "PASS" : "FAIL";					
					}

					$result1 = ($Initial_1_val - $Final_1_val)<= $allowable ? "PASS" : "FAIL";
					
					}
					if($Summary_Type == 'f0_sel')
					{
					$fo_val='';	
					$sum = 0;
					$Temperature = $rowsummary['Temperature'];
					$Step_no = $rowsummary['Step_no'];
					$z = $rowsummary['z'];
					 $Sensor01 = $rowsummary['Sensor1'];
					$Sensor2 = $rowsummary['Sensor2'];
					$Sensor3 = $rowsummary['Sensor3'];
					$Sensor4 = $rowsummary['Sensor4'];
					$timedur = $rowsummary['timedur'];
					
					//conversion tagname to tag index
					
					 $sqlsenindicator = "SELECT [TagIndex] from $tablesensor where TagName LIKE '%$Sensor01'";
					$resindicator = sqlsrv_query($conn, $sqlsenindicator);
					$rowindicator=sqlsrv_fetch_array($resindicator);
					$Sensor1 = $rowindicator['TagIndex'];
					$sqlstepf0 = "SELECT TOP 1 DateAndTime from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$starttime' and '$endtime') AND Val ='$Step_no' order by DateAndTime ASC"; 
					$resstepf0 = sqlsrv_query($conn, $sqlstepf0);
					$rowstepf0 = sqlsrv_fetch_array($resstepf0);
					if($rowstepf0['DateAndTime']){
					  $mintime=$rowstepf0['DateAndTime']->format("Y-m-d H:i:s"); 
					
					$sqlstepf02 = "SELECT TOP 1 DateAndTime from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$starttime' and '$endtime') AND Val ='$Step_no' order by DateAndTime DESC";
					$resstepf02 = sqlsrv_query($conn, $sqlstepf02);
					$rowstepf02 = sqlsrv_fetch_array($resstepf02);
					$maxtime=$rowstepf02['DateAndTime']->format("Y-m-d H:i:s"); 
					//for sensor 1
				     $sqlsen1 = "SELECT Val from $datatable WHERE TagIndex= '$Sensor1' AND ( DateAndTime between '$mintime' and '$maxtime')";
					$ressen1 = sqlsrv_query($conn, $sqlsen1);
					while($rowsen1 = sqlsrv_fetch_array($ressen1))
					{
					$sen1_val[]=$rowsen1['Val']; 
					}
					 $sensor1_avg=array_sum($sen1_val)/count($sen1_val);
					 $eqval = ($sensor1_avg - $Temperature) / $z ;
					$sum += pow(10,$eqval);
					 $fovalues_1 = $timedur * $sum;
					$f0_steps=$step_logs[$Step_no];
					}
					//for sensor 1
					}
					//for conductivity
					if($Summary_Type == 'con_sel')
					{
					 $Step_no = $rowsummary['Step_no'];
					 $sqlstepcon = "SELECT TOP 1 DateAndTime from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$starttime' and '$endtime') AND Val ='$Step_no' order by DateAndTime ASC"; 
					$resstepcon = sqlsrv_query($conn, $sqlstepcon);
					$rowstepcon = sqlsrv_fetch_array($resstepcon);
					if($rowstepcon['DateAndTime']){
					 $mintime=$rowstepcon['DateAndTime']->format("Y-m-d H:i:s"); 
					
					$sqlstepcon2 = "SELECT TOP 1 DateAndTime from $datatable WHERE TagIndex= '$steptagindex' AND ( DateAndTime between '$starttime' and '$endtime') AND Val ='$Step_no' order by DateAndTime DESC";
					$resstepcon = sqlsrv_query($conn, $sqlstepcon2);
					$rowstepcon = sqlsrv_fetch_array($resstepcon);
					$maxtime=$rowstepcon['DateAndTime']->format("Y-m-d H:i:s"); 
						
					 $sqlconductivity = "SELECT  TOP 1 Val from $datatable WHERE TagIndex= '$con_tag' AND ( DateAndTime between '$mintime' and '$maxtime') order by DateAndTime DESC";
					 $resconductivity = sqlsrv_query($conn, $sqlconductivity);
					 $rowcon = sqlsrv_fetch_array($resconductivity);
						 $conductivity_result=$rowcon['Val'];
					}
					}
					//for conductivity
					
					
					
					
					
					
					
					
					}
				//FOR SUMMARY DETAILS
					

				
	   ?>


<section class="content">
   <header class="content__title">
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $vessel_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $sequence_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i>  Batch Reports  </h1>
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
	  <div class="box-header">
	  <p class="boxalignright" style="float:right"><button type="button" id="print" name="print" class="btn btn-primary" onclick="printing()"><i class="fa fa-print"></i> Print</button></p>
	  </div>
      <div class="table-responsive" id="PrintMe">	  
	  <div id="firstpart">
	  <table style="width: 100%;" class="">
		<thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Center_Text'];?>  <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
            <tr>
			<td colspan="3"><p  class="center"><span class="paramtitles">BATCH DETAILS FOR <?php echo $sequence_name; ?>  </span></p></td>
            </tr>
		</thead>
		<tbody>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
        <td style="vertical-align: top;" colspan="3">
		<div class="col-sm-12">
		
		
            <form class="form-horizontal">
				
				<table width="100%" >
				<tbody>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH NAME<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $batchname; ?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH DESCRIPTION<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $batchdesc; ?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH START DATE TIME<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo date($dateformat,strtotime($starttime));?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH END DATE TIME : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo date($dateformat,strtotime($endtime));?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH START USER<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $batchstartuser; ?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">BATCH END USER<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $batchenduser;?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;">DURATION<?php //echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $duration;?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>


				</tbody>
				</table>	
			</form>
        </div>
        </td>		
		</tr>				
		</tbody>
		<tfoot>
		<tr>
		<?php 
		$sqlfooter = "SELECT * FROM FOOTER_DETAILS";
		$resfooter = sqlsrv_query($conn, $sqlfooter);
        $rowfooter = sqlsrv_fetch_array($resfooter);
		?>
			<td colspan="3" id="pagefooter" style="display:none;" >
			<div class="row" <?php if($rowfooter['Footer_Type']=="allpages"){ ?>style="position:fixed;bottom:0;width:98%; border-bottom:1px solid black !important" <?php } ?>>
			<?php 
			date_default_timezone_set($timezone);
			$mytime = date($dateformat);
			
			  
			//$printime = date("Y-m-d H:i:s");
			 $printime = date('Y-m-d H:i:s', strtotime('-15 seconds'));
			$sqlprint = "SELECT TOP 1 Val from $userdatatable where TagIndex = '$userindex' and (DateAndTime = '$printime')  order by DateAndTime desc";
            $resprint = sqlsrv_query($conn, $sqlprint);
            $rowprint = sqlsrv_fetch_array($resprint);
            $currentuser = $rowprint['Val'];
			
	
			
			?>
			<?php if($rowfooter['Footer_Type']!="allpages"){ ?><p style="margin:0 0 <?php echo $rowfooter['Padding']; ?>em">&nbsp;</p><?php } ?>
			<table width="100%">
		    <tbody>
            <tr>
			<td width="45%"><b><?php echo $rowfooter['Column1']; ?></b> </td>
			<td align=""><b><?php echo $rowfooter['Column2']; ?></b> </td>
			<td align=""><b><?php echo $rowfooter['Column3']; ?></b> </td>
			</tr>         
			<tr>
			<td><b><?php echo $rowfooter['Column4']; ?></b> </td>
			<td align="" width="40%"><b><?php echo $rowfooter['Column5']; ?></b> </td>
			<td align=""><b><?php echo $rowfooter['Column6']; ?></b> </td>
			</tr>		
			<tr>
			<td><b><?php echo $rowfooter['Column7']; ?></b> <?php echo str_replace("PSS9147\\","",$currentuser).'/'.$mytime; ?></td>
			<td align=""><b><?php echo $rowfooter['Column8']; ?></b> </td>
			<td align="left"><b><?php echo $rowfooter['Column9']; ?></b> </td>
			</tr>			
		    </tbody>	
			</table>
			</div>			
			</td>
        </tr>
        </tfoot>
    </table>
	<div style="page-break-after:always;" id="batchrtrendbrk"></div>	
    <?php   if($parameter_arr){ ?>
    <table style="width: 100%;" class="">
		<thead>
			<tr>
			     <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2">BATCH REPORT FOR <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"> <?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
			<td colspan="3"><p  class="center"><span class="paramtitles">PROCESS PARAMETER FOR <?php echo $sequence_name; ?></span></p><p></p></td>
            </tr>
		</thead>
		<tbody>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
        <td style="vertical-align: top;" colspan="3">
        <div class="col-sm-12">
                                 <form class="form-horizontal">
								       
										<table width="100%" >
				                        <tbody>	
                                          <?php $ab=0;
							              foreach($parameter_arr as $rowparam){	$ab++;							  
								          ?>		
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;"><?php echo $rowparam["name"];?> :  </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format( $rowparam["value"],$rowparam["decimal"], '.', '');  echo " ".$rowparam["units"]; ?></div></td>
				</tr>
				<?php if($ab %20== 0){ ?>
				<tr class="nopre" style="display:none; padding:25px !important">
                <td colspan="3">&nbsp;</td>				
                </tr>
				<?php } ?>
				
                                          <?php } ?> 
				</tbody>									  
				</table>										  
                                 </form>
                </div>
        </td>		
		</tr>				
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 4em;">&nbsp;</p></td>
        </tr>
        </tfoot>
    </table>	
	<div style="page-break-after:always;" id="batchrtrendbrk"></div>
	<?php } ?>
	</div>
    <div id="batchreport">
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			   <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2">BATCH REPORT FOR <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?>  <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
		    <td colspan="3"><p  class="center"><span class="paramtitles">STEP LOGS FOR <?php echo $sequence_name; ?> </span></p><P>&nbsp;</p></td>
			</tr>
		</thead>
		<tbody>
	    <tr><td colspan="3">&nbsp;</td></tr>
		<tr>
		     <td colspan='3'>
                              <table width="100%" class="table table-bordered mb-0" id="">
                                 <thead>
                                    <tr class="tr_bg">
                                       <th style="text-align:center;">DATE & TIME</th>	    
                                       <th style="text-align:left;">STEP DESCRIPTION</th>	    
                                    </tr>
                                 </thead>
								 <tbody>
                                 <?php 
		                        while ($rowstepviewall = sqlsrv_fetch_array($resstep_common)) {
								$step_arr_main[] = $rowstepviewall;
								if ($previous_log != $rowstepviewall["Val"]) {
									$previous_log = $rowstepviewall["Val"];
								?>
                                 <tr>
                                    <td style="text-align:center;"><?php 
									//$rowstepviewall['DateAndTime']->modify("+$utchour hour +$utcminutes minutes");
		                             echo   $rowstepviewall['DateAndTime']->format($dateformat);
									 ?></td>
                                    <td style="text-align:left !important; text-transform:uppercase"><?php 	
									   $val =$rowstepviewall['Val'];
                                     echo strtoupper(@$step_logs[$val]);?></td>
                                 </tr>
								<?php }} ?>
								 </tbody>
                              </table>
                        </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>
    </table>
	<div style="page-break-after:always;" id="batchrtrendbrk"></div>
	<?php if($rowheader['PAction_Status']== 1){ ?>
		<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			     <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['PAction_Text'];?> <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
			<td colspan="3"><p  class="center"><span class="paramtitles">SEQUENCE OPERATION REPORT FOR <?php echo $sequence_name; ?></span> <P>&nbsp;</p></p></td>
            </tr>
		</thead>
		<tbody>
		
		<tr>
		<td colspan='3'>
		
            <table  width="100%" class="table table-bordered mb-0">
            <thead>
            <tr class="tr_bg">
            <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'peroformedaction' ORDER BY Sort ASC";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th style="text-align:center"><?php echo $rowlayout['Name']; ?></th>					 
            <?php } ?>

            </tr>
            </thead>
			<tbody>
            <?php
	        $sql_alaram="SELECT * FROM [$database].[dbo].[AlgViewEx] WHERE  DateTime BETWEEN '".$srt_dt.".000' AND '".$end_dt.".000' AND Class = 12  AND Text2  LIKE '$vessel_filter%'";  
	        $res_alarm = sqlsrv_query($conn, $sql_alaram); 
	        while($row_alarm = sqlsrv_fetch_array($res_alarm)){ 
               ?>
            <tr>
            <?php foreach($layoutarr as $rowlayout) { 
			$field = $rowlayout['Field'];
			?>
			<td style="text-align:center"><?php if($rowlayout['Type']=="date time"){$row_alarm['DateTime']->modify("+$utchour hour +$utcminutes minutes"); echo $row_alarm['DateTime']->format($dateformat);}else{echo $row_alarm[$field];} ?> </td>
            <?php } ?>			
            </tr>
	        <?php } ?>
			</tbody>
            </table>
            </td> 
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>
    </table>
	<div style="page-break-after:always;" id="batchrtrendbrk"></div>
	<?php } ?>
    <table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Center_Text'];?> <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
				<td colspan="3"><p  class="center"><span class="paramtitles">BATCH REPORT FOR <?php echo $sequence_name; ?> </span></span></p><P>&nbsp;</p></td>
            </tr>
		</thead>
		<tbody>
		
		<tr>
		     <td colspan='3'>
                              <table width="100%" class="table table-bordered mb-0" id="data-table">
                                 <thead>
                                    <tr class="tr_bg" >
                                       <th style="text-align:center;">DATE & TIME</th>	    
                                       <th style="text-align:left;">STEP DESCRIPTION</th>	    
                                       <?php 
									   $step_in = 0;
									   $batch_arr = array();
									   foreach ($schools_array as $selectedsensors) { ?>
                                       <th style="text-align:center;"><?php 
                                          $sqlsenname = "SELECT TagIndex,Sensor_Name,Sensor_Unit FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '".$selectedsensors."' AND Vessel_ID = '$vessel'";
                                          $ressenname= sqlsrv_query($conn, $sqlsenname);
                                          $rowsenname = sqlsrv_fetch_array($ressenname);
										  $TagindexSensor=$rowsenname['TagIndex'];
								  
										   $sqlbatch = "Select Val from $datatable where (DateAndTime between '$newstart' and '$endtime')  AND (DATEDIFF(SECOND, DateAndTime,'$newstart')%$timeduration=0 ) AND TagIndex ='$TagindexSensor' order by DateAndTime asc";
										 $resbatch = sqlsrv_query($conn, $sqlbatch);
										 while ($rowbatch = sqlsrv_fetch_array($resbatch)) {
											$batch_arr[$selectedsensors][] = $rowbatch["Val"];
										 }
                                 										  
                                          if($rowsenname['Sensor_Name']){ echo $rowsenname['Sensor_Name']; if($rowsenname['Sensor_Unit']) {echo " ("; echo $rowsenname['Sensor_Unit'];echo ")";}  }?></th>
                                       <?php } ?> 
                                    </tr>
                                 </thead>
        <tbody>
		<?php $i=0; $des=0;
		foreach ($step_arr as $rowstep) { 
		
		?>
		<tr>
		<td style="text-align:center;">
		<?php   echo   $rowstep['DateAndTime']->format($dateformat); ?></td>
		<td style="text-align:left; text-transform:uppercase"><?php echo @$step_logs[$rowstep["Val"]]; ?></td>
		<?php foreach ($schools_array as $selectedsensors) {  ?>
		<td style="text-align:center;"><?php 
			$tagdecimalsql = "SELECT TagIndex,Sensor_Name,Sensor_Unit,Tag_Decimal FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '" . $selectedsensors . "' AND Vessel_ID='$vessel'";
			$tagresdes = sqlsrv_query($conn, $tagdecimalsql);
			$tagrowdes = sqlsrv_fetch_array($tagresdes);
		    $decimal=$tagrowdes['Tag_Decimal'];
		    $sname=$tagrowdes['Sensor_Name'];

			echo number_format($batch_arr[$selectedsensors][$i], $tagrowdes['Tag_Decimal'], '.', ''); 		
			?></td>
		<?php  $des++; }?>
		</tr>
		
		<?php $i++;} if(!$completion && $completiontimestamp){ ?>
		<tr>
		<td style="text-align:center;">
		<?php   echo   $completiontimestamp; ?></td>
		<td style="text-align:left; text-transform:uppercase"><?php echo @$step_logs[$rowstepcomp["Val"]]; ?></td>
		
		<?php foreach ($schools_array as $selectedsensors) { 

		   $sqlsenname = "SELECT TagIndex,Sensor_Name,Sensor_Unit FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '".$selectedsensors."' AND Vessel_ID = '$vessel'";
		  $ressenname= sqlsrv_query($conn, $sqlsenname);
		  $rowsenname = sqlsrv_fetch_array($ressenname);
		  $TagindexSensor=$rowsenname['TagIndex'];
		  $sqlbatchs = "Select Val from $datatable where (DateAndTime between '$starttime' and '$endtime')  AND TagIndex ='$TagindexSensor' AND DateAndTime = '$completiontimestamps'   order by DateAndTime asc";
		$resbatchs = sqlsrv_query($conn, $sqlbatchs);
		$rowbatchs = sqlsrv_fetch_array($resbatchs);
		$batch_arr_comp[$selectedsensors] = $rowbatchs["Val"];
	
		?><td style="text-align:center;">
		<?php 
			$tagdecimalsql = "SELECT TagIndex,Sensor_Name,Sensor_Unit,Tag_Decimal FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '" . $selectedsensors . "' AND Vessel_ID='$vessel'";
			$tagresdes = sqlsrv_query($conn, $tagdecimalsql);
			$tagrowdes = sqlsrv_fetch_array($tagresdes);
		    $decimal=$tagrowdes['Tag_Decimal'];
		    $sname=$tagrowdes['Sensor_Name'];
			echo number_format($batch_arr_comp[$selectedsensors], $tagrowdes['Tag_Decimal'], '.', ''); 		
			?></td>
		<?php  $des++; }?>
		</tr>
		<?php }?>
		</tbody>
        </table>
         </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>
    </table>
    <div style="page-break-after:always;" id="batchrtrendbrk"></div>
    </div>
    <div id="batchsummary">	
	<?php  if(@$result1 || @$fovalues_1 || ($Summary_Type == 'con_sel' && $conductivity_result==0 || $conductivity_result==1)){  ?>
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			     <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Center_Text'];?> <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
				<td colspan="3"><p  class="center"><span class="paramtitles">SUMMARY REPORT FOR <?php echo $sequence_name; ?> </span></p></td>
            </tr>
		</thead>
		<tbody>
				
		<tr>
		<td colspan='3'>
						 <div class="col-sm-12">
                              <div class="box-body">
                                <form class="form-horizontal">
								         
										  	<table width="100%" >
											<tbody>
											
                                     		
										  
				
				
				<?php if(@$result1){   ?>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
								<tr>
				<td width="40%" style="text-align:right;"><?php   echo $allowblename; ?> :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format($allowable, 2, '.', ''); ?> bar</div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;"><?php   echo $intial1_name; ?> :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format($Initial_1_val, 2, '.', ''); ?> bar</div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;"><?php   echo $final1_name; ?> :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format($Final_1_val, 2, '.', ''); ?> bar</div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;">  RESULT :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo $result1; ?> </div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;"><?php   echo $initial2_name; ?> :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format($Initial_2_val, 2, '.', ''); ?> bar</div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;"><?php   echo $final2_name; ?> :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo number_format($Final_2_val, 2, '.', ''); ?> bar</div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;">  RESULT :</td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php  echo $result2; ?> </div></td>
				</tr>	
									    <?php } if(@$fovalues_1){ ?>									
										<tr>
										<td colspan="3">&nbsp;</td>				
										</tr>
										<tr>
										<td width="40%" style="text-align:right;">  F0 (<?php echo $f0_steps ?>):</td>
										<td width="1%">&nbsp;</td>
										<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php   echo number_format($fovalues_1, 0, '.', ''); ?> min</div></td>
										</tr>
										
										<?php } if($Summary_Type == 'con_sel' && $conductivity_result==0 || $conductivity_result==1){ ?>
										<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;">  CONDUCTIVITY RESULT :</td>	
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php    if($conductivity_result==0){$conrsult="FAIL";}else{$conrsult="PASS";} echo $conrsult; //$conductivity_result==0 ?> </div></td>
				</tr>
										<?php } ?>
									 		</tbody>
									 		</table>
                                 </form>
                              </div>
                           </div>
                        
			</td> 
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>		
    </table>
		<div style="page-break-after:always;" id="batchrtrendbrk"></div>
	<?php } ?>
	</div>

	<div id="batchrtrend">
	<table style="width:100%" class="">
	    <thead>
			<tr>
			     <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Trend_Text'];?> <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
			<td colspan="3"><p  class="center"><span class="paramtitles">TREND DETAILS FOR <?php echo $sequence_name; ?> </span></p></td>
            </tr>
		</thead>
		<tbody>
			
		<tr>
		      <td colspan='3'>
                           <div id="container" style="margin:5px !important; display:block; width:auto; height:600px"></div>
						   <p>&nbsp;</p>
                        </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>		
    </table>
	<div style="page-break-after:always;" id="batchrtrendbrk"></div>
	</div>	
	<div id="batchalarm">
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Alarm_Text'];?> <?php echo $vessel_name; ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
			<tr>
			<td colspan="3"><p  class="center"><span class="paramtitles">ALARM DETAILS FOR <?php echo $sequence_name; ?> </span> </p> <P>&nbsp;</p></td>
            </tr>
		</thead>
		<tbody>
	
		<tr>
		<td colspan='3'>
		
            <table  width="100%" class="table table-bordered mb-0" id="data-table2">
            <thead>
            <tr class="tr_bg">
            <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'alarm' ORDER BY Sort ASC";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th style="text-align:center;"><?php echo $rowlayout['Name']; ?></th>					 
            <?php } ?>

            </tr>
            </thead>
			<tbody>
            <?php 
            $sqlalarm = "SELECT SourceName,Message,AlarmClass,EventTimeStamp,Active FROM $tablealarm WHERE EventTimeStamp BETWEEN '$time_zone_start' and '$time_zone_end' AND GroupPath='$Alarm_Filter'";
			$resalarm = sqlsrv_query($conn, $sqlalarm);
			while ($rowalarm = sqlsrv_fetch_array($resalarm)) {
               ?>
            <tr>
            <?php// foreach($layoutarr as $rowlayout) { 
			//$field = $rowlayout['Field'];
			?>
			<td style="text-align:center; text-transform:uppercase !important" ><?php if($timevalue=='-'){$timesign='+';}else{$timesign='-';}
			$rowalarm['EventTimeStamp']->modify("$timesign$utchour hour $timesign$utcminutes minutes");
		    echo   $rowalarm['EventTimeStamp']->format($dateformat);?></td>
			<td><?php echo $rowalarm['Message']; ?></td>
			<td><?php echo $rowalarm['AlarmClass']; ?></td>
            <?php //} ?>			
            </tr>
	        <?php } ?>
			</tbody>
            </table>
            </td> 
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="3"><p style="margin:0 0 3em;">&nbsp;</p></td>
        </tr>
        </tfoot>
    </table>
	</div>
	 </div>  
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Print Report</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
				<span class="radittl" >
                     <p><i class="fa fa-check-circle"></i> Select Print Reports</p>
                </span>
               <div class="col-sm-12">
                  <div class="form-group">
				  <p>
				    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel2" id="checkAll" value="PrintMe">
                        <span class="custom-control-indicator"></span> Check All
                    </label>
				  </p>
					<label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="batchdetailsid" value="batchreport">
                        <span class="custom-control-indicator"></span> Batch Details Report
                    </label>	
                    <?php if($summarycount){ ?>					
					<label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="batchsummaryid" value="batchsummary">
                        <span class="custom-control-indicator"></span> Batch Summary Report
                    </label> 
					<?php } ?>
				    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="batchrtrendid" value="batchrtrend">
                        <span class="custom-control-indicator"></span> Batch Trend Report
                    </label>
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="batchalarmid" value="batchalarm">
                        <span class="custom-control-indicator"></span> Batch Alarm Report
                    </label>
                  </div>
                </div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" id="printMe">Print</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closepopup();">Cancel</button>
            </div>
         </div>
      </div>
   </div>
    </div>   
    </div>  
<style>	
select{
  width: 150px;
  height: 30px;
  padding: 5px;
  color: green;
}
.headrow1{
	width:30%;
	border:1px solid;
}
.headrow2{
	width:40%;
	border:1px solid;
}
.headrow3{
	width:30%;
	border:1px solid;
}
.spectd{
	width:80%;
	border:1px solid;
}
.spec-td{
	width:20%;
	border:1px solid;
}
.center{
	text-align: center;
}
.zmdi-fullscreen{
	display:none;
}
#data-table_filter{
	display:none;
}
/*.imgsizing{height:<?php echo $rowheader['Logo_Height']; ?>px; !important; max-height: <?php echo $rowheader['Logo_Height']; ?>; max-width: 100px;}*/
.blkwht{
	background-color: black !important;
    color: white;
}
.paramtitles{border: 1px solid white;width: 50%; padding: 10px;display: block;margin-left: 25%;MARGIN-TOP: 20PX;}
</style>	
<style type="text/css" media="print">

        @page
        {
           size: auto;
           margin: 0mm 0mm 0mm 0mm; 
        }
        thead
        {
            display: table-header-group;
        }
        tfoot
        {
            display: table-footer-group;
        }

</style>
<script src="dist/ghraph/highcharts.js"></script>
<script type="text/javascript">
      function printing()
       {  
          $('#form')[0].reset(); 	
      	  $('#modal_form').modal('show');	 
      }	
      
      $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      });	
      
      $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      });	
      
      
      
      function closepopup()
      {
      var url = window.location.href;
      window.location.replace(url);		
      }
      
      		
     //---graphp---//
	 var chart =Highcharts.chart('container', {
    chart: {
        zoomType: 'xy',
		marginBottom:130,
		marginTop:50,
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: [{
        categories: [<?php $i = 0;
foreach ($step_arr as $row) { ?>'<?php echo $row['DateAndTime']->format($dateformat);  ?>',<?php
} ?>],
        crosshair: true,
		
		labels: {
      
            overflow: 'allow',
			x:15,
			y:27,
			 
			 
        },
        
       
		 
    },

        
	
	
	],
    yAxis: [<?php
	$yaxislist = array();
foreach ($schools_array as $param) {
	$yaxis= explode('_',$param);
	if(!in_array($yaxis[1],$yaxislist)){
	$dsname = $yaxislist[]=$yaxis[1];
     $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$param' AND Vessel_ID='$vessel' ";
    $ressenname = sqlsrv_query($conn, $sqlsenname);
    $rowsenname = sqlsrv_fetch_array($ressenname);
    $Sensor_highvalue = $rowsenname['Sensor_highvalue'];
    $Sensor_lowvalue = $rowsenname['Sensor_lowvalue'];
	$unit = $rowsenname['Sensor_Unit']; 
	switch($rowsenname['Sensor_Unit']){
	    case '&#8451' : $unit = '℃';
		break;
		case '&#181;S/cm' : $unit = 'µS/cm';
		break;
		default : $unit = $rowsenname['Sensor_Unit'];
		break;
	}
   $display_name =  $dsname." ($unit)";
	
	
?>
	
	
	
	
	{ // Primary yAxis
	
					
        labels: {
            //format: '{value}°C',
            style: {
                color: Highcharts.getOptions().colors[1<?php //echo $bk;
     ?>]
            }
        },
        title: {
            text: '<?php echo $display_name ;?>',
            style: {
                color: Highcharts.getOptions().colors[1<?php //echo $bk;
     ?>]
            },
			
        },
		tickPositions:[<?php   $tp = $Sensor_highvalue - $Sensor_lowvalue;
    if ($tp <= 10 && $tp > 5) {
        $intv = 1;
    } else if ($tp > 10 && $tp <= 50) {
        $intv = 5;
    } else if ($tp > 50 && $tp <= 200) {
        $intv = 10;
    } else if ($tp > 200 && $tp <= 2000) {
        $intv = 25;
    } else if ($tp > 2000) {
        $intv = 1000;
    } else if ($tp <= 5) {
        $intv = 0.5;
    } else {
        $intv = 1;
    }
    $km = $Sensor_lowvalue;
    while ($km <= $Sensor_highvalue) { ?> <?php echo number_format($km, 2, '.', '');  ?>, <?php $km = $km + $intv;
    } ?>],	
		
        gridLineWidth: 0,
    }, 		
	<?php }} ?>
	],
    tooltip: {
        shared: false
    },

	legend: {
		layout: 'horizontal',
        align: 'left',
		x: 80,
        verticalAlign: 'bottom',
        y:12,
	    itemStyle:{font:'8pt sans-serif', fontSize:'8px',},
		padding:0,
		itemMarginTop:0,	
        floating: false,
        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'	
	},
    series: [<?php
$result = count($schools_array);
$newy =array();
$count = 0;
for ($i = 0;$i <= $result - 1;$i++) {
    $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$schools_array[$i]' AND Vessel_ID='$vessel'";
    $ressenname = sqlsrv_query($conn, $sqlsenname);
    $rowsenname = sqlsrv_fetch_array($ressenname);
	
				$newrange= explode('_',$rowsenname['Sensor_Tag']);
				$flag =false;
				if($newy){
					foreach($newy as $n){
						if($n['sensor'] == $newrange[1]){
							$newy[$i]['sensor']=$n['sensor'];
							$newy[$i]['count']=$n['count'];
							$flag = true;
						}
						$count =$n['count'];
					}
				}
				if(!$flag){
				$newy[$i]['sensor']=$newrange[1];
				$newy[$i]['count']= $i ==0 ? 0 : ++$count;
				}
				$_SESSION['newy']= $newy;	
?>
	
	{
        name: '<?php echo $rowsenname['Sensor_Name']; ?>',
        type: 'spline',
        yAxis: <?php echo $newy[$i]['count'] ;
		?>,
				<?php //}}
     ?>
	
        data: [<?php foreach ($batch_arr[$rowsenname['Sensor_Tag']] as $row) { ?><?php echo number_format($row, $rowsenname['Tag_Decimal'], '.', '');  ?>,<?php } ?>],
		marker: {
            enabled: false
        },
       
        

    }, 
				<?php
} //$vk++;  }
 ?>	
	
	
	
	]
});
	 
	 
     //---graphp---//
 
	  

      $('#printMe').click(function(){
		document.getElementById('container').style.width = "90%";
		document.getElementById('container').style.height = "500px";
		chart.xAxis[0].setExtremes(null,null);
		chart.reflow();
		chart.chartBackground.attr({
		  fill:"#fff"
	    });
		document.getElementById('pagefooter').style.display="";
       // document.getElementById('nonprint').style.display = 'none';
      	document.getElementById('modal_form').style.display = 'none';		
	
        var toPrint = '';	
      	var printop = new Array();
		toPrint = toPrint +document.getElementById('firstpart').innerHTML;
      	$('input[name="printsel"]:checked').each(function() {
			toPrint = toPrint + document.getElementById(this.value).innerHTML;	
      	});
        console.log(toPrint); 		
        var popupWin = window.open(' ','_blank', 'location=no,left=200px');
      
        popupWin.document.open();
        popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
        popupWin.document.write('<style>span.paramtitles {font-family: Nunito,sans-serif !important; border: 1px solid black;width: 50%; padding: 10px;display: block;margin-left: 25%;    MARGIN-TOP: 20PX;} body{font-family: Nunito,sans-serif !important;}.imgsizing{width:auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;}.white_border{border:1px solid black !important;padding:3px} tr.tr_bg th { font-size:16px !important;font-weight:bold;}.table td, .table th {padding: 0rem 1rem;}.table{border-collapse: collapse;}.nopre{display:block !important; Padding:25px !important}</style>');
      
      
        popupWin.document.write(toPrint);  
      	popupWin.document.close(document.location.reload(true));
      	});
		
   
      
      
      
 </script>
 

 
 
 
</section>
<?php include('includes/footer.php');?>