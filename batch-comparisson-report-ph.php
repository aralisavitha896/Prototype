<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1>   Batch Comparison Report </h1>
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
          <?php
				if(isset($_REQUEST['batcharr']))   
					{
					$batcharr = $_REQUEST['batcharr'];
					$senarr = $_REQUEST['senarr'];
					$timeduration = $_REQUEST['time'];
                    $batchtype = $_REQUEST['batchtype'];  
                    $vessel = $_REQUEST['vessel']; 					
					$schools_array = explode(",", $senarr);
					$batcharr = explode(",", $batcharr);
					}
	                $sqlsequenceas= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Batch_Type = '$batchtype'";
	                $ressequenceas= sqlsrv_query($conn, $sqlsequenceas);
	                $rowsequenceas = sqlsrv_fetch_array($ressequenceas);
					$Step_Desc = $rowsequenceas['Step_Desc_Tag']; 
					$Sequence_Name = $rowsequenceas['Sequence_Name']; 
			
	                $sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
	                $resdb= sqlsrv_query($conn, $sqldb);
	                $rowdb = sqlsrv_fetch_array($resdb);
                    $batchvalarr =array();
                    $batchnames =array();
					$ind  = 0;
					$sql ="SELECT PText1 AS [BATCH NAME], PText2 AS [BATCH DESC], PText3 AS [START TIME], PText4 AS [END TIME],PText5 AS [USER], PText6 AS [BATCH TYPE], PText7 AS [START TIME SORT], PText8 AS [END TIME SORT] FROM ALG.VRuntimeMessage  WHERE (Message_State = 1) AND (NOT (PText3 IS NULL)) AND (NOT (PText4 IS NULL)) AND (PText6 = '$batchtype') ORDER BY [START TIME SORT] DESC";
					$res= sqlsrv_query($conn, $sql);
         
                    while($rowpar=sqlsrv_fetch_array($res)){
                         $param[]= $rowpar;
                    }
					foreach($batcharr as $index){
                    $Params= $param[$index];
                    $Batch_Name = $Params['BATCH NAME'];
                    $Start_Time_Sort = $Params['START TIME SORT'];
                    $End_Time_Sort =$Params['END TIME SORT'];
		 
		            $srt_dts = new DateTime($Start_Time_Sort);
	                $srt_dts->modify("-$utchour hour -$utcminutes minutes");
                    $srt_dt= $srt_dts->format("Y-m-d H:i:s"); 

	                $end_dts = new DateTime($End_Time_Sort);
	                $end_dts->modify("-$utchour hour -$utcminutes minutes");
                    $end_dt= $end_dts->format("Y-m-d H:i:s"); 
					
					$batchnames[$ind]= $Batch_Name;
						
					 
					 $stepsl="SELECT  TimeStamp  FROM TLG.VTagValue WHERE (TagUID = '".$Step_Desc."') AND TimeStamp BETWEEN '".$srt_dt."' AND '".$end_dt."' ORDER BY TimeStamp  ASC"; 
		             $rstepsl=sqlsrv_query($conn, $stepsl);
		             $rowstplg = sqlsrv_fetch_array($rstepsl);
		             if($rowstplg){
			            $new_start_time = $rowstplg['TimeStamp'] ? $rowstplg['TimeStamp']->format('Y-m-d H:i:s.u') :null;
		             }
		             if($new_start_time){
					    $new_start_time = $new_start_time." +5:30";
		
		             foreach($schools_array as $selectedsensors){
		               $sqlopenview = "SELECT Sensor.ValueFloat AS ValueFloat,TimeSensor.TimeStamp AS TimeStamp  FROM TLG.VTagValue AS Sensor INNER JOIN  TLG.VTagValue AS TimeSensor ON Sensor.TimeStamp = TimeSensor.TimeStamp WHERE (Sensor.TagUID = '".$selectedsensors."') AND (TimeSensor.TagUID = '".$Step_Desc."') AND TimeSensor.TimeStamp BETWEEN '".$new_start_time."' AND '".$end_dt."' AND DATEDIFF(SECOND, TimeSensor.Timestamp,'$new_start_time')% $timeduration=0 ORDER BY TimeSensor.TimeStamp ASC";
		               $resview = sqlsrv_query($conn, $sqlopenview);
		               $i = 0;
		               while($rowview = sqlsrv_fetch_array($resview)){ 
				          $batchvalarr[$ind][$i]['Timestamp']=$rowview['TimeStamp']; 
				          $batchvalarr[$ind][$i++][$selectedsensors]=$rowview['ValueFloat']; 
		               }
	                }	 
					}
					$ind++;
					}
	   ?>
	   <div class="card">
      <div class="card-body">
	  <div class="box-header"><p class="boxalignright" style="float:right"><button type="button" id="print" name="print" class="btn btn-primary" onclick="printing()"><i class="fa fa-print"></i> Print</button></p></div>
      <div class="table-responsive" id="PrintMe">	  
	 <div id="batchrtrend">
	 <table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="spectd">TREND REPORT FOR <?php echo $rowdb['Vessel_Name']; ?></td>
				<td align="center" class="spec-td"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $rowdb['Logo']; ?>"/ width="90%">
				
			</tr>
			<tr>
			<td colspan="2"><p>&nbsp;</p><p  class="center"><span class="paramtitles"><u><?php echo $Sequence_Name; ?> </span></u> </span></p></td>
            </tr>
		</thead>
		<tbody>
			<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		      <td colspan='2'>
                           <form  id="livegraph"  method="post">
						      
                           </form>
						   <p>&nbsp;</p>
                           <div id="container" style="width:95%;margin:5px !important;"></div>
						   <p>&nbsp;</p>
                        </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan='2'><div class="row">&nbsp;</div></td>
        </tr>
        </tfoot>		
    </table>
	</div>	
	
    </div>   
    </div>   
    </div>  
<style>	
.spectd{
	width:80%;
	border:1px solid;
	line-height: 5;
}
.spec-td{
	width:20%;
	border:1px solid;
	line-height: 5;
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
      		
      //---graphp---//
      var chart =Highcharts.chart('container', {
          chart: {
            zoomType: 'xy',
			backgroundColor:'#0c2927',
			height:'800px'
          },
          title: {
              text: ''
          },
          subtitle: {
              text: ''
          },
          xAxis: [<?php 
		  
		 foreach($batchvalarr as $key => $x){ ?>{
              categories: [<?php $i=0;foreach($x as $row){ ?>'<?php echo $row["Timestamp"]->format($dateformat); ?>',<?php } ?>],
              crosshair: true,
              lineWidth:1,
			  lineColor:'grey',
			  tickColor:'#1aafaf'
          },
		  <?php } ?>
		  ],
          yAxis: [<?php 
         foreach($schools_array as $param) {
			 
            $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$param' AND Vessel_ID = '$vessel'";
         	$ressenname= sqlsrv_query($conn, $sqlsenname);
         	$rowsenname = sqlsrv_fetch_array($ressenname);
         	$Sensor_highvalue = $rowsenname['Sensor_Highvalue']; 
         	$Sensor_lowvalue = $rowsenname['Sensor_Lowvalue']; 
         	$Sensor_Name = $rowsenname['Sensor_Name']; 
         ?>
      	{ // Primary yAxis
      	
      					
              labels: {
                  style: {
                      color: '#124b88'
                  }
              },
              title: {
                  text: '<?php echo $Sensor_Name; ?> ( <?php   if($rowsenname['Sensor_Unit']=='&deg;C'){echo '°C';} else if($rowsenname['Sensor_Unit']=='&mu;S/cm'){echo 'μS/cm';} else {echo $rowsenname['Sensor_Unit'];}?> )',
                  style: {
                      color: '#124b88'
                  },
      			
              },
      		tickPositions:[<?php $tp=$Sensor_highvalue - $Sensor_lowvalue;  if($tp<=10){  $intv=1 ;}else if($tp >10 && $tp<=50){ $intv=5;}else if($tp>50 && $tp<=200){$intv=10;}else if($tp > 200 && $tp<=1000){$intv=25;}else if($tp > 1000){$intv=100;} else if($tp<=5){ $intv= 0.5;} else{$intv=1;}    $km=$Sensor_lowvalue; while($km<=$Sensor_highvalue){ ?> <?php echo number_format($km, 2, '.', ''); ?>, <?php $km=$km+$intv; } ?>],	
      		
              gridLineWidth: 0,
			  lineWidth:1,
			  lineColor:'grey'
          }, 		
		 <?php }  ?>
      	],
          tooltip: {
              shared: false
          },
      
      	legend: {
			layout: 'horizontal',
            align: 'left',
      		x: 700,
            verticalAlign: 'top',
            y:0,
      	    itemStyle:{font:'8pt sans-serif', fontSize:'8px',color:'#124b88'},
			itemHiddenStyle:{color: 'red'},
      		padding:0,
      		itemMarginTop:0,	
            floating: false

      	},
         series: [<?php
		 $ind = 0;
         foreach($batchvalarr as $key => $x){		 
         $result = count($schools_array);
         for($i=0; $i<$result; $i++){ 
         $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$schools_array[$i]' AND Vessel_ID = '$vessel'";
         $ressenname= sqlsrv_query($conn, $sqlsenname);
         $rowsenname = sqlsrv_fetch_array($ressenname);
         $Sensor_Tag = $rowsenname['Sensor_Tag']; 
         $Sensor_Name = $rowsenname['Sensor_Name']; 
		 $explode =explode("_",$Sensor_Tag); 
		 $sens = $explode[1];		 
         ?>
      	
      	{
              name: '<?php  echo str_replace("'"," ",$batchnames[$ind]); echo "-"; echo $Sensor_Name;  ?>',
              type: 'line',
              yAxis: <?php echo $i;?>,
              xAxis: <?php echo $ind;?>,
              data: [<?php foreach($x as $row){?><?php  print_r($row[$schools_array[$i]]); ?>,<?php } ?>],
      		  marker: {
                  enabled: false
              },
             
              
      
          }, 
      				<?php  }
					$ind++;
					} ?>	
      	
      	
      	
      	]
      });
	  
      function printing(){
		chart.chartBackground.attr({
		  fill:"#fff"
	    });
        toPrint = document.getElementById('batchrtrend').innerHTML;	
        var popupWin = window.open('','_blank', 'location=no,left=200px');
      
        popupWin.document.open();
        popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
      
      
        popupWin.document.write(toPrint);  
      	popupWin.document.close(document.location.reload(true));			
	  }
      $('#changegraph').on('click',function(e){
      	e.preventDefault();
      	var index = $('#sensor_list').val();
      	var min = $('#min').val();
      	var max = $('#max').val();
      	
      $.ajax({
      	method:"POST",
      	data:{'min':min,'max':max},
      	url:'ajax_map.php',
      	success:function(data){	
      	var result =JSON.parse(data);
      	if(result){
      		chart.yAxis[index].update({
      		tickPositions:result,
      	});
      	}
      }
      });
      });
      
      
      
 </script>
</section>
<?php include('includes/footer.php');?>