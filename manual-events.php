<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1>    Manual Events  </h1>
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
				if(isset($_REQUEST['starttime']))   
					{
					$vessel = $_REQUEST['vessel'];
					$endtime = $_REQUEST['endtime'];
					$starttime = $_REQUEST['starttime'];
					}
					//customer data
					$sqlcustomer = "SELECT * FROM CUSTOMER_DATA";
			        $rescustomer = sqlsrv_query($conn, $sqlcustomer);
			        $rowsequence = sqlsrv_fetch_array($rescustomer,SQLSRV_FETCH_ASSOC);
			        $logo = $rowsequence['Logo'];
					//HEADER DETAILS 
					$sqlheader = "SELECT * FROM HEADER_DETAILS";
					$resheader = sqlsrv_query($conn, $sqlheader);
					$rowheader = sqlsrv_fetch_array($resheader);
					
					$StartTimenew = new DateTime($starttime);
					$StartTimenew->modify("$timevalue$utchour hour $timevalue$utcminutes minutes");
					$time_zone_start = $StartTimenew->format("Y-m-d H:i:s");	
										
					$EndTimenew = new DateTime($endtime);
					$EndTimenew->modify("$timevalue$utchour hour $timevalue$utcminutes minutes");
					$time_zone_end = $EndTimenew->format("Y-m-d H:i:s");
					
					 
					if($vessel == "All")
					$sqldb= "SELECT * FROM VESSEL_DETAILS";
				    else
					$sqldb= "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID ='$vessel'"; 
				    $resdb= sqlsrv_query($conn, $sqldb);
					$rowdb = sqlsrv_fetch_array($resdb,SQLSRV_FETCH_ASSOC);
					$Alarm_Filter =$rowdb['Alarm_Filter'];
					$vessel_code = $rowdb['Vessel_Code'];
					$equipment_id = $rowdb["Equipment_Id"];
					$vcode= str_replace("O",0,$vessel_code);
					$userdatatable="SensorString_".$vcode;	
					//user details
					$tablesensor="SensorIndex_".$vcode;
					
					 $sqluserindex= "SELECT * FROM $tablesensor WHERE TagName LIKE '%system\User%'";
					 $reuserindex= sqlsrv_query($conn, $sqluserindex);
					 $rowuserindex = sqlsrv_fetch_array($reuserindex);
					 $userindex = $rowuserindex['TagIndex'];
					 $tablealarm="AllEvent";

					?>
		   <div class="card">
      <div class="card-body">
	  <div class="box-header"><p class="boxalignright" style="float:right"><button type="button" id="print" name="print" class="btn btn-primary" ><i class="fa fa-print"></i> Print</button></p></div>
        <div class="table-responsive" id="PrintMe">
		<table style="width: 100%;" class="">
		<thead>
			
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2">EVENT REPORT <?php if($vessel != "All"){echo "FOR ".$rowdb['Vessel_Name'];} ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
		</thead>
		<tbody>
		        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
				<td colspan="3">
		<div class="col-sm-12">
            <form class="form-horizontal">
				<div class="row">
                    <label class="col-sm-6"><span class="righttext">START DATE & TIME :</span></label>
                    <input type="text" class="col-sm-6 form-control specialcls" id="inputtext3" value="&nbsp;<?php echo date($dateformat,strtotime($starttime)); ?>" readonly />
                </div>
				<div class="row">
                    <label class="col-sm-6"><span class="righttext">END DATE & TIME :</span></label>
                    <input type="text" class="col-sm-6 form-control specialcls" id="inputtext3" value="&nbsp;<?php echo date($dateformat,strtotime($endtime)); ?>" readonly />
                </div>				
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
			<?php if($rowfooter['Footer_Type']!="allpages"){ ?><p style="margin:0 0 23em">&nbsp;</p><?php } ?>
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
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>	
<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
		    </tbody>	
			</table>
			</div>			
			</td>
        </tr>
        </tfoot>		
     </table>
    <div style="page-break-after:always;" id="batchrtrendbrk"></div>	 
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
			    <td align="center" class="headrow2">EVENTS REPORT <?php if($vessel != "All"){echo "FOR ".$rowdb['Vessel_Name'];} ?></td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text'];?> <?php echo $equipment_id; ?>  </td>
			</tr>
		</thead>
		<tbody>
				<tr><td colspan="3" style="margin:10px"><p>&nbsp;</p></td></tr>
				
		<tr>
		<td colspan="3">
		<table  style="width: 100%; margin: 0 auto;" class="table table-bordered mb-0" id="data-table">
	<thead>
  <tr>
    <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'event' ORDER BY Sort ASC";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout,SQLSRV_FETCH_ASSOC)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th style="text-align:left;"><?php echo $rowlayout['Name']; ?></th>					 
    <?php } ?>
  
  </tr>
  </thead>
		<tbody>
            <?php 
			if($vessel == "All"){
              // $sqlalarm = "Select * from [EVENT] where (Audience ='8' or Audience ='4') and Severity = '4' and Provider = 'FactoryTalk View SE Client' and TimeStmp BETWEEN '$time_zone_start' and '$time_zone_end' and MessageText !='download' and MessageText not like '%AppStart%' and MessageText not like '%RA-BAS%' and MessageText not like '%Executed%' order by TimeStmp asc";
               $sqlalarm = "Select * from [EVENT] where  Audience in(4,8) and Severity = '4' and Provider in ('FactoryTalk View SE Client','FactoryTalk Security') and TimeStmp BETWEEN '$time_zone_start' and '$time_zone_end' and MessageText !='download' and MessageText not like '%AppStart%' and MessageText not like '%RA-BAS%' and MessageText not like '%Executed%' order by TimeStmp asc";
			}else{
				// $sqlalarm = "SELECT SourceName,Message,AlarmClass,EventTimeStamp,Active FROM $tablealarm WHERE EventTimeStamp BETWEEN '$time_zone_start' and '$time_zone_end' AND GroupPath='$Alarm_Filter'";
				 $sqlalarm = "Select * from [EVENT] where  Audience in(4,8) and Severity = '4' and Provider in ('FactoryTalk View SE Client','FactoryTalk Security') and TimeStmp BETWEEN '$time_zone_start' and '$time_zone_end' and MessageText !='download' and MessageText not like '%AppStart%' and MessageText not like '%RA-BAS%' and MessageText not like '%Executed%' order by TimeStmp asc";
				
			}
			$resalarm = sqlsrv_query($conn, $sqlalarm);
			while ($rowalarm = sqlsrv_fetch_array($resalarm)) {
               ?>
            <tr>
            <?php// foreach($layoutarr as $rowlayout) { 
			//$field = $rowlayout['Field'];
			?>
			<td style="text-align:center; text-transform:uppercase !important" ><?php if($timevalue=='-'){$timesign='+';}else{$timesign='-';}
			$rowalarm['TimeStmp']->modify("$timesign$utchour hour $timesign$utcminutes minutes");
		    echo   $rowalarm['TimeStmp']->format($dateformat);?></td>
			<td><?php echo $rowalarm['MessageText']; ?></td>
			<td><?php echo $rowalarm['UserID']; ?></td>
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
			<td colspan="3"><p style="margin:0 0 2em;">&nbsp;</p></td>
        </tr>
        </tfoot>	
</table>
    </div>   
    </div>   
    </div>  
<style>	
thead{
	line-height: 5;
	border:1px solid;
}
.spectd{
	width:80%;
	line-height: 5;
	border:1px solid;
}
.spec-td{
	width:20%;
	line-height: 5;
	border:1px solid;
}
.zmdi-fullscreen{
	display:none !important;
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
.paramtitles{border: 1px solid white;width: 50%; padding: 10px;display: block;margin-left: 25%;MARGIN-TOP: 20PX;}
span.righttext {text-align: right;display: block;margin-top: 10px;}
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
<script type="text/javascript">

$('#print').click(function(){
printop = 'PrintMe';
document.getElementById('pagefooter').style.display="";
var toPrint = document.getElementById(printop);
var popupWin = window.open(' ','_blank', 'location=no,left=200px');

popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
        popupWin.document.write('<style>span.paramtitles {font-family: Nunito,sans-serif !important; border: 1px solid black;width: 50%; padding: 10px;display: block;margin-left: 25%;    MARGIN-TOP: 20PX;} body{font-family: Nunito,sans-serif !important;}.imgsizing{width:auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;}.white_border{border:1px solid black !important;padding:3px} tr.tr_bg th { font-size:16px !important;font-weight:bold;} .table th {padding: 0rem 1rem;}.table td {padding: 0rem 1rem;}.table{border-collapse: collapse;}span.righttext {text-align: right;display: block;margin-top: 10px;}</style>');
popupWin.document.write(toPrint.innerHTML);  
popupWin.document.close(document.location.reload(true));	
     
});

function closepopup()
{
var url = window.location.href;
window.location.replace(url);		
}
</script>
</section>
<?php include('includes/footer.php');?>