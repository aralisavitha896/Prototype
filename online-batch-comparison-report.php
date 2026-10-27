<?php include('includes/header.php'); ?>

          <?php
     date_default_timezone_set("Asia/Kolkata");
				if(isset($_REQUEST['batcharr']))   
					{
					$batcharr = urldecode($_REQUEST['batcharr']);
					$datetimesarr =urldecode( $_REQUEST['datetimesarr']);
					$senarr = $_REQUEST['senarr'];
					$step_no = $_REQUEST['step'];
					$timeduration = $_REQUEST['time'];
                    $sequence = $_REQUEST['sequence'];
                    $vessel = urldecode($_REQUEST['vessel']); 
					$schools_array = explode(",", $senarr);
					$batcharr = explode(",", $batcharr); 
					
					$datetimesarr = explode(",", $datetimesarr);
					$OnlineDateTime =urldecode( $_REQUEST['OnlineDateTime']);
					$datetimesarr[]=$OnlineDateTime;
				
					$Batch_id =$_REQUEST['Batch_id'];
					$MsgNr =urldecode( $_REQUEST['MsgNr']);
					}
                    $starttimefield= '';
                    $endtimefield= '';

				    $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'report'";
				    $reslayout= sqlsrv_query($conn, $sqllayout);
				    while($rowlayout = sqlsrv_fetch_array($reslayout,SQLSRV_FETCH_ASSOC)){
					    $layoutarr[]=  $rowlayout;
						if($rowlayout['Type'] == "starttime")
							$starttimefield = $rowlayout['Field'];					
						if($rowlayout['Type'] == "endtime")
							$endtimefield = $rowlayout['Field'];
				    }						
	                $sqlsequenceas= "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Batch_Type = '$Batch_id'";
	                $ressequenceas= sqlsrv_query($conn, $sqlsequenceas);
	                $rowsequenceas = sqlsrv_fetch_array($ressequenceas,SQLSRV_FETCH_ASSOC);
					$Step_Desc_Tag = $rowsequenceas['Step_Desc_Tag']; 
					$Sequence_Name = $rowsequenceas['Sequence_Name']; 
			
	                $sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID ='$vessel'";
	                $resdb= sqlsrv_query($conn, $sqldb);
	                $rowdb = sqlsrv_fetch_array($resdb,SQLSRV_FETCH_ASSOC);
					$Online_msgno = $rowdb['Online_msgno'];
					$Vessel_Name = $rowdb['Vessel_Name'];
					$Logo = $rowdb["Logo"];
					//$sqldatabase= "SELECT TOP 1 name, database_id, create_date FROM sys.databases where name LIKE '%IPC8210_SCADA#8210_ALG%' AND snapshot_isolation_state=0";
					$sqldatabase= "SELECT TOP 1 name, database_id, create_date FROM sys.databases where name LIKE '%IPC8210_SCADA#8210_ALG%' ORDER BY create_date";
					$resdatabase= sqlsrv_query($conn, $sqldatabase);			
					$rowdatabase = sqlsrv_fetch_array($resdatabase,SQLSRV_FETCH_ASSOC);
					$dbname= $rowdatabase["name"];
					
					
					$batcharr[] =$dbname;

                    $batchvalarr =array();
                    $batchnames =array();
					$ind  = 0;
					$inc  = 0;
					foreach($batcharr as $key => $database){
						
					$value_id = $datetimesarr[$key]; 
				    $sqltable= "SELECT TOP 1 * FROM [$database].[dbo].[AlgViewEx] WHERE DateTime = '$value_id' AND Class = 11 AND State = 1 ORDER BY DateTime DESC";
                    $restable= sqlsrv_query($conn, $sqltable);			
                    $rowtable = sqlsrv_fetch_array($restable,SQLSRV_FETCH_ASSOC);
					 $starttime[]=$rowtable[$starttimefield];
					 $endtime[]=$rowtable[$endtimefield];
					 $batchname[] =  $rowtable["PText1"];
					$ind++;
					}
					$loopcount=count($starttime);
					for($x=0; $x<$loopcount; $x++)	
						{
							$srt_dts = new DateTime($starttime[$x]);
							$srt_dts->modify("-$utchour hour -$utcminutes minutes");
							$srt_dt= $srt_dts->format("Y-m-d H:i:s.u"); 

							$end_dts = new DateTime($endtime[$x]);
							$end_dts->modify("-$utchour hour -$utcminutes minutes");
							$end_dt= $end_dts->format("Y-m-d H:i:s.u"); 
					
					 $stepsl="SELECT MIN(Timestamp) AS Timestamp FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$Step_Desc_Tag."),''".$srt_dt."'',''".$end_dt."''')WHERE VariantValue !='' and VariantValue !='system idle' AND VariantValue !='0' AND VariantValue='$step_no'"; 
		             $rstepsl=sqlsrv_query($conn, $stepsl);
		             $rowstplg = sqlsrv_fetch_array($rstepsl,SQLSRV_FETCH_ASSOC);
		             if($rowstplg){ $newdatetimes=$rowstplg['Timestamp']?$rowstplg['Timestamp']->format('Y-m-d H:i:s.u') : null; }
					 else{ echo '<span class="alertfixed alert-danger">WARNING! Selected Step is not logged yet.</span>';}
					 
					$stepsl2="SELECT MAX(Timestamp) AS Timestamp FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$Step_Desc_Tag."),''".$srt_dt."'',''".$end_dt."''')WHERE VariantValue !='' and VariantValue !='system idle' AND VariantValue !='0' AND VariantValue='$step_no'"; 
		             $rstepsl2=sqlsrv_query($conn, $stepsl2);
		             $rowstplg2 = sqlsrv_fetch_array($rstepsl2,SQLSRV_FETCH_ASSOC);
		             if($rowstplg2){ $newdatetimesend=$rowstplg2['Timestamp']?$rowstplg2['Timestamp']->format('Y-m-d H:i:s.u') : null; }
					 
					 
		             if($newdatetimes){
		             foreach($schools_array as $selectedsensors){
		               $sqlsen= "SELECT ValueID FROM Archive WHERE ValueName LIKE '%$selectedsensors%' ORDER BY ValueID ASC";
		               $ressen= sqlsrv_query($conn, $sqlsen);  
		               $rowsen = sqlsrv_fetch_array($ressen,SQLSRV_FETCH_ASSOC);
		                $sqlopenview = "SELECT Timestamp,VariantValue FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$rowsen['ValueID']."),''".$newdatetimes."'',''".$newdatetimesend."''')  WHERE (DATEDIFF(SECOND, Timestamp,'".$newdatetimes."')%$timeduration=0 and [Timestamp] >='".$newdatetimes."' and [Timestamp] <= '".$newdatetimesend."')";
		               $resview = sqlsrv_query($conn, $sqlopenview);
		               $i = 0;
		               while($rowview = sqlsrv_fetch_array($resview)){ 
				          $batchvalarr[$inc][$i]['Timestamp']=$rowview['Timestamp']; 
				          $batchvalarr[$inc][$i++][$selectedsensors]=$rowview['VariantValue']; 
		               }
	                }	 
					}
					$inc++;
						}  
		$refid = $rowdb['Refid'];			
        $arr = array();
        if($refid)
        getparents($refid);
        krsort($arr);
		 $sqlheader = "SELECT * FROM HEADER_DETAILS";
		$resheader = sqlsrv_query($conn, $sqlheader);
        $rowheader = sqlsrv_fetch_array($resheader);
	   ?>
<section class="content">
   <header class="content__title">
      <h1> Reports  <i class="zmdi zmdi-forward" style="color:yellow;"></i><?php foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php } ?> <?php echo $Sequence_Name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> Online Batch Comparison Report </h1>
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
	  <div class="box-header"><p class="boxalignright" style="float:right"><button type="button" id="print" name="print" class="btn btn-primary" onclick="printing()"><i class="fa fa-print"></i> Print</button></p></div>
      <div class="table-responsive" id="PrintMe">	  
	 <div id="batchrtrend">
	 <table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="spec-td"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $Logo; ?>" class='imgsizing' style="width:<?php echo $rowheader['Logo_Width'];?>% !important; ">
			    <td align="center" class="spectd">BATCH COMPARISION REPORT FOR <?php echo $Vessel_Name; ?></td>
			</tr>
			<tr>
			<td colspan="2"><p  class="center"><span class="paramtitles"><u><?php echo $Sequence_Name; ?></u> </span></p></td>
            </tr>
		</thead>
		<tbody>
			
		<tr>
		      <td colspan='2'>
                           <form  id="livegraph"  method="post">
                           </form>
						   <p>&nbsp;</p>
                           <div id="container" style="width:95%;"></div>
						   <p>&nbsp;</p>
                        </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="2"><div class="row">&nbsp;</div></td>
        </tr>
        </tfoot>		
    </table>
	</div>	
	
    </div> 

<div class="alertfixed alert-info">NOTE: Please select PORTRAIT mode while taking print, if you are comparing morethan THREE batches.</div>	
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
			<?php  if(count($schools_array)== 1){ ?>
			marginLeft:120,
			<?php } ?>
			marginTop:150,
			//backgroundColor:'#0c2927',
			
			<?php if(count($datetimesarr)>2){ ?>
			height:'1000px',
			<?php }else{ ?>
			height:'500px',
			<?php } ?>
          },
          title: {
              text: ''
          },
          subtitle: {
              text: ''
          },
          xAxis: [<?php $j=0;
		 foreach($batchvalarr as $key => $x){ ?>{
              categories: [<?php $i=0;foreach($x as $row){ $row['Timestamp']->modify("+$utchour hour +$utcminutes minutes");  ?>'<?php  echo $row["Timestamp"]->format($dateformat); ?>',<?php } ?>],
              crosshair: true,
              lineWidth:1,
			  lineColor:'black',
			  tickColor:'#1aafaf',
			  labels: {
            style: {
                color: 'black',fontWeight: 'bold'
            }
        }
			 
          },
		  <?php $j++; } ?>
		  ],
          yAxis: [<?php 
         foreach($schools_array as $param) {
			 
            $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$param' AND Vessel_ID IN ('$vessel','$vessel2')";
         	$ressenname= sqlsrv_query($conn, $sqlsenname);
         	$rowsenname = sqlsrv_fetch_array($ressenname);
         	$Sensor_highvalue = $rowsenname['Sensor_Highvalue']; 
         	$Sensor_lowvalue = $rowsenname['Sensor_Lowvalue']; 
         	$Sensor_Name = $rowsenname['Sensor_Name']; 
         ?>
      	{ // Primary yAxis
      	
      					
              labels: {
                  style: {
                      color: '#000000',fontWeight: 'bold'
                  }
              },
              title: {
                  text: '<?php echo $Sensor_Name; ?> ( <?php   if($rowsenname['Sensor_Unit']=='&deg;C'){echo '°C';} else if($rowsenname['Sensor_Unit']=='&mu;S/cm'){echo 'μS/cm';} else {echo $rowsenname['Sensor_Unit'];}?> )',
                  style: {
                      color: '#000000',fontWeight: 'bold'
                  },
      			
              },
      		tickPositions:[<?php $tp=$Sensor_highvalue - $Sensor_lowvalue;  if($tp<=10){  $intv=1 ;}else if($tp >10 && $tp<=50){ $intv=5;}else if($tp>50 && $tp<=200){$intv=10;}else if($tp > 200 && $tp<=1000){$intv=25;}else if($tp > 1000){$intv=100;} else if($tp<=5){ $intv= 0.5;} else{$intv=1;}    $km=$Sensor_lowvalue; while($km<=$Sensor_highvalue){ ?> <?php echo number_format($km, 2, '.', ''); ?>, <?php $km=$km+$intv; } ?>],	
      		
              gridLineWidth: 0,
			  lineWidth:1,
			  lineColor:'black'
          }, 		
		 <?php }  ?>
      	],
          tooltip: {
              shared: false
          },
      
      	legend: {
			layout: 'horizontal',
            align: 'left',
      		x: 10,
            y:0,
      	    itemStyle:{font:'9pt sans-serif', fontSize:'9px', color: '#000000',fontWeight: 'bold'},
			itemHiddenStyle:{color: '#cfd1d3'},
      		 padding: 5,
      		itemMarginTop:0,	
            floating: false,
			borderColor: '#000202',
			verticalAlign: 'top',
			width: 1050,
			borderWidth: 1,
			symbolWidth:50,
			itemWidth: 335,

      	},
         series: [<?php
		 $ind = 0;
         foreach($batchvalarr as $key => $x){		 
         $result = count($schools_array);
         for($i=0; $i<$result; $i++){ 
         $sqlsenname = "SELECT * FROM SENSORS_HIGHLIMITS WHERE Sensor_Tag = '$schools_array[$i]' AND Vessel_ID IN ('$vessel','$vessel2')";
         $ressenname= sqlsrv_query($conn, $sqlsenname);
         $rowsenname = sqlsrv_fetch_array($ressenname);
         $Sensor_Tag = $rowsenname['Sensor_Tag']; 
         $Sensor_Name = $rowsenname['Sensor_Name']; 
		 $explode =explode("_",$Sensor_Tag); 
		 $sens = $explode[1];

		if($batchname[$ind]){$mybatch=$batchname[$ind];}else{$mybatch='Online';}
         ?>
      	
      	{
              name: '<?php echo $Sensor_Name. ' : '; echo $mybatch; ?>',
              type: 'line',
              yAxis: <?php echo $i;?>,
              xAxis: <?php echo $ind;?>,
              data: [<?php foreach($x as $row){?><?php  echo number_format( $row[$schools_array[$i]],$rowsenname["Tag_Decimal"], '.', '')  //print_r($row[$schools_array[$i]]); ?>,<?php } ?>],
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