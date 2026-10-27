<?php include('includes/header.php'); 
     
				 if(isset($_REQUEST['value_id']))   
					{
					$value_id = $_REQUEST['value_id'];
					$Vessel_ID =$vessel = $_REQUEST['vessel'];
					$sequence =$_REQUEST['sequence'];
					$database =$_REQUEST['database'];
					}
                    $starttimefield= '';
                    $endtimefield= '';
					
					
				    $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'report' ORDER BY Sort ASC";
				    $reslayout= sqlsrv_query($conn, $sqllayout);
				    while($rowlayout = sqlsrv_fetch_array($reslayout,SQLSRV_FETCH_ASSOC)){
					    $layoutarr[]=  $rowlayout;
						if($rowlayout['Type'] == "starttime")
							$starttimefield = $rowlayout['Field'];					
						if($rowlayout['Type'] == "endtime")
							$endtimefield = $rowlayout['Field'];
				    }					 
					$sqltable= "SELECT * FROM [$database].[dbo].[AlgViewEx] WHERE DateTime = '$value_id' AND Class = 11 AND State = 1 ORDER BY DateTime DESC";
                    $restable= sqlsrv_query($conn, $sqltable);			
                    $rowtable = sqlsrv_fetch_array($restable,SQLSRV_FETCH_ASSOC);
					$starttime =  $rowtable[$starttimefield];
					$endtime =  $rowtable[$endtimefield];
											  
	                $srt_dts = new DateTime($starttime);
	                $srt_dts->modify("-$utchour hour -$utcminutes minutes");
                    $srt_dt= $srt_dts->format("Y-m-d H:i:s"); 

	                $end_dts = new DateTime($endtime);
	                $end_dts->modify("-$utchour hour -$utcminutes minutes");
                    $end_dt= $end_dts->format("Y-m-d H:i:s"); 
					
					$date1 = date_create($starttime);
					$date2 = date_create($endtime);

					$interval = date_diff($date1,$date2);
					$dur_yr = $interval->format('%y');
					$dur_mt = $interval->format('%m');
					$dur_dy = $interval->format('%D');
					$dur_hms = $interval->format('%H Hours %I Minutes %S Seconds');
					
					$total_dur = '';
					if($dur_yr)
					$total_dur = $dur_yr." Year ";
				    if($dur_mt)
					$total_dur .= $dur_mt." Month ";
				
					$total_dur .= $dur_dy." Days ";
					$total_dur .= $dur_hms ;
  
  
  			        //sequence 
			        $sqlsequence = "SELECT * FROM SEQUENCE_NUMBER_DETAIL WHERE Vessel_ID = '$vessel' AND Sequence_ID = '$sequence'";
			        $ressequence = sqlsrv_query($conn, $sqlsequence);
			        $rowsequence = sqlsrv_fetch_array($ressequence,SQLSRV_FETCH_ASSOC);
			        $sequence_type = $rowsequence['Sequence_Type'];
			        $step_desc_tag = $rowsequence['Step_Desc_Tag']; 
			        $completion_step_no = $rowsequence['Completion_Step']; 
					$sequence_name = $rowsequence['Sequence_Name'];
					$batch_type = $rowsequence['Batch_Type'];
					$Event_report_id = $rowsequence['Event_report_id'];
					//vessel
					$sqlvessel = "SELECT * FROM VESSEL_DETAILS WHERE Vessel_ID = '$vessel'";
					$resvessel= sqlsrv_query($conn, $sqlvessel);
					$rowvessel = sqlsrv_fetch_array($resvessel,SQLSRV_FETCH_ASSOC);
					$username_tag = $rowvessel["Username_Tag"];
					$vessel_name = $rowvessel["Vessel_Name"];
					$logo = $rowvessel["Logo"];
					$equipment_id = $rowvessel["Equipment_Id"];
					$vessel_filter = $rowvessel["Alarm_Filter"];
					$refid = $rowvessel["Refid"];
					
		            //start and end user
	                $sqluser = "SELECT TOP 1 Timestamp,VariantValue FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$username_tag."),''".$srt_dt."'',''".$end_dt."''')  WHERE [Timestamp] >='".$srt_dt."' and [Timestamp] <= '".$end_dt."'";
		            $resuser = sqlsrv_query($conn, $sqluser);
		            $rowuser = sqlsrv_fetch_array($resuser,SQLSRV_FETCH_ASSOC);
		            $start_user = $rowuser["VariantValue"];
					
					$sqluser = "SELECT TOP 1 Timestamp,VariantValue FROM  OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$username_tag."),''".$srt_dt."'',''".$end_dt."''')  WHERE [Timestamp] >='".$srt_dt."' and [Timestamp] <= '".$end_dt."' ORDER BY Timestamp DESC";
		            $resuser = sqlsrv_query($conn, $sqluser);
		            $rowuser = sqlsrv_fetch_array($resuser,SQLSRV_FETCH_ASSOC);
		            $end_user = $rowuser["VariantValue"];		

	    $sqlheader = "SELECT * FROM HEADER_DETAILS";
		$resheader = sqlsrv_query($conn, $sqlheader);
        $rowheader = sqlsrv_fetch_array($resheader);					
					
					
		$arr = array();
        if($refid)
        getparents($refid);
        krsort($arr);
?>





<section class="content">
   <header class="content__title">
      <h1>Reports <i class="zmdi zmdi-forward" style="color:yellow;"></i>  <?php if($suitstatus == "yes"){foreach($arr as $row){ echo $row;?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php }} ?> <?php echo $vessel_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i> <?php echo $sequence_name; ?> <i class="zmdi zmdi-forward" style="color:yellow;"></i>    Batch Events  </h1>
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
		   <div class="card">
      <div class="card-body">
	  <div class="box-header"><p class="boxalignright" style="float:right"><button type="button" id="print" onclick="" class="btn btn-primary" ><i class="fa fa-print"></i> Print</button></p></div>
	
		
        <div class="table-responsive" id="first">
		<table style="width: 100%;" class="" >
		<thead>
			<tr>
			    <td align="center" class="spec-td">
				<img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;">
				</td>
				<td align="center" class="spectd">EVENT REPORT FOR  <?php echo $rowvessel['Vessel_Name']; ?></td>

			</tr>
            <tr>
			<td colspan="2"><p  class="center"><span class="paramtitles">Batch Details For <?php echo $sequence_name; ?> </span></p></td>
            </tr>			
		</thead>
		<tbody>
		        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
				<td colspan="2">
		<div class="col-sm-12">
            <form class="form-horizontal">
				<form class="form-horizontal">
				
				<table width="100%" >
				<tbody>
				<?php foreach($layoutarr as $rowlayout){ 
				$field = $rowlayout['Field'];
				switch($rowlayout['Type']){
					case 'starttime':$printtext = date($dateformat,strtotime($rowtable[$field]));
					break;
					case 'endtime':$printtext = date($dateformat,strtotime($rowtable[$field]));
					break;
					case 'startuser':$printtext = $start_user;
					break;
					case 'enduser':$printtext = $end_user;
					break;
					case 'sequence duration':$printtext = $total_dur;
					break;
					default: $printtext = $rowtable[$field];
				}
				?>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;"><?php echo $rowlayout['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $printtext;?></div></td>
				</tr>
				<?php } ?>
				
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				<tr>
				<td width="40%" style="text-align:right;">BATCH EVENT ID : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid white;width:50%;" class="white_border">&nbsp;<?php echo $Event_report_id;?></div></td>
				</tr>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>
				</tbody>
				</table>	
			</form>				
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
			date_default_timezone_set("Asia/Kolkata");
			$mytime = date($dateformat);
			
			  
			$printime = date("Y-m-d H:i",strtotime("-$utchour hour -$utcminutes minutes"));
			$sqlprint = "SELECT TOP 1  VariantValue FROM OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG_EX:R,(".$username_tag."),''".$printime."'',''''')";
            $resprint = sqlsrv_query($conn, $sqlprint);
            $rowprint = sqlsrv_fetch_array($resprint);
            $currentuser = $rowprint['VariantValue'];
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
			<td><b><?php echo $rowfooter['Column7']; ?></b> <?php echo $currentuser; ?> / <?php echo  $mytime; ?></td>
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
     </div>
 
	<div class="table-responsive" id="second">
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
				<td align="center" class="spec-td">
				<img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;">
				</td>
				<td align="center" class="spectd">EVENT REPORT  FOR <?php echo $rowvessel['Vessel_Name']; ?></</td>
			</tr>
            <tr>
			<td colspan="2"><p  class="center"><span class="paramtitles">EVENTS DETAILS FOR <?php echo $sequence_name; ?></span></p></td>
            </tr>			
		</thead>
		<tbody>
				
				
		<tr>
		<td colspan="2">
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
	$arr = array();
	$sql = "SELECT name FROM master.sys.databases WHERE name LIKE '%ALG%'";
	$res = sqlsrv_query($conn, $sql); 
	while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){
	$newalarmtbl = $row ['name'];
    //$sql_alaram="SELECT * FROM [".$newalarmtbl."].[dbo].[AlgViewEx] WHERE  DateTime BETWEEN '".$srt_dt.".000' AND  '".$end_dt.".000' AND Class = 10 AND PText1 = '$vessel_filter'"; 
    $sql_alaram="SELECT * FROM [".$newalarmtbl."].[dbo].[AlgViewEx] WHERE  DateTime BETWEEN '".$srt_dt.".000' AND  '".$end_dt.".000' AND Class = 10 AND PText1 = '$vessel_filter'"; 
	$res_alarm = sqlsrv_query($conn, $sql_alaram); 
	while($row_alarm = sqlsrv_fetch_array($res_alarm,SQLSRV_FETCH_ASSOC)){ 
    ?>
        <tr>
            <?php foreach($layoutarr as $rowlayout) { 
			$field = $rowlayout['Field'];
			?>
			<td style="text-align:left;"><?php if($rowlayout['Type']=="date time"){$row_alarm['DateTime']->modify("+$utchour hour +$utcminutes minutes"); echo $row_alarm['DateTime']->format($dateformat);}else{echo $row_alarm[$field];} ?>  </td>
            <?php } ?>			
            </tr>
	<?php }} ?>
	</tbody>	
  </table>	
  </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="2"><p style="margin:0 0 2em;">&nbsp;</p></td>
        </tr>
        </tfoot>
</table>
    <div style="page-break-after:always;" id="batchrtrendbrk"></div>
</div>	
<?php if($rowheader['UserLogin_Status']== 1){ ?>
	<div class="table-responsive" id="fourth">
	<table style="width: 100%; margin: 0 auto;" class="">
	    <thead>
			<tr>
			    <td align="center" class="spec-td"><img src="<?php echo $baseurl;?>uploads/logos/<?php echo $logo; ?>" class='imgsizing' style="width:<?php //echo $rowheader['Logo_Width'];?>auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;"></td>
				<td align="center" class="spectd">USER LOGIN REPORT FOR <?php echo $rowvessel['Vessel_Name']; ?></td>
			</tr>
			
			<tr>
			<td colspan="2"><p  class="center"><span class="paramtitles">USER LOGIN DETAILS FOR <?php echo $sequence_name; ?></span></p></td>
            </tr>
		</thead>
		<tbody>		
		<tr>
		<td colspan="2">
		<table  style="width: 100%; margin: 0 auto;" class="table table-bordered mb-0" id="data-table2">
	<thead>
  <tr>
    <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'userlogin' ORDER BY Sort ASC";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout,SQLSRV_FETCH_ASSOC)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th style="text-align:left;"><?php echo $rowlayout['Name']; ?></th>					 
    <?php } ?>
  </tr>
  </thead>
	<tbody>
    <?php
	
	$sqls = "SELECT name FROM master.sys.databases WHERE name LIKE '%ALG%'";
	$ress = sqlsrv_query($conn, $sqls); 
	while($rows = sqlsrv_fetch_array($ress,SQLSRV_FETCH_ASSOC)){
	$newalarmtbl = $rows ['name'];
	$sql="SELECT * FROM [".$newalarmtbl."].[dbo].[AlgViewEx] WHERE  DateTime BETWEEN '".$srt_dt.".000' AND  '".$end_dt.".000' AND  MsgNr IN (1008001,1008002,1008003,1008004,1008005,1008007)";	
	$res = sqlsrv_query($conn, $sql); 
	while($row = sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC)){ 
    ?>
            <tr>
            <?php foreach($layoutarr as $rowlayout) { 
			$field = $rowlayout['Field'];
			?>
			<td style="text-align:left;"><?php if($rowlayout['Type']=="date time"){$row['DateTime']->modify("+$utchour hour +$utcminutes minutes"); echo $row['DateTime']->format($dateformat);}else{echo str_replace("USERT:@100%s@:","",$row[$field]);} ?>  </td>
            <?php } ?>			
            </tr>
	<?php }}?>
			</tbody>
	
  </table>	
  </td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="2"><p style="margin:0 0 2em;">&nbsp;</p></td>
        </tr>
        </tfoot>	
</table>	
	</div>
<?php } ?>


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
                        <input type="checkbox" class="custom-control-input" name="printsel" id="" value="second">
                        <span class="custom-control-indicator"></span> Event Report
                    </label>					
					<label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="" value="third">
                        <span class="custom-control-indicator"></span> Esign Report
                    </label> 
				    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="printsel" id="" value="fourth">
                        <span class="custom-control-indicator"></span> User Login Report
                    </label>

                  </div>
                </div>
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" id="">Print</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="closepopup();">Cancel</button>
            </div>
         </div>
      </div>
   </div>
   
	
<style>	
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
.center{
	text-align: center;
}

.paramtitles{border: 1px solid white;width: 50%; padding: 10px;display: block;margin-left: 25%;MARGIN-TOP: 20PX; text-transform:uppercase}
</style>	
<style type="text/css" media="print">
        @page
        {
           size:auto;
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
      $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      });	
      
      $("#checkAll").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
      });
function printing()
{  
    $('#form')[0].reset(); 	
    $('#modal_form').modal('show');	 
}	
$('#print').click(function(){
document.getElementById('pagefooter').style.display="";
var html = document.getElementById('first').innerHTML;
html = html + document.getElementById('second').innerHTML;
html = html + document.getElementById('fourth').innerHTML;
/*$('input[name="printsel"]:checked').each(function() {
	html = html + document.getElementById(this.value).innerHTML;	
});*/
var popupWin = window.open('','_blank', 'location=no,left=200px');

popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
popupWin.document.write('<style>span.paramtitles {font-family: Nunito,sans-serif !important; border: 1px solid black;width: 50%; padding: 10px;display: block;margin-left: 25%;    MARGIN-TOP: 20PX;} body{font-family: Nunito,sans-serif !important;}.imgsizing{width:auto !important; height:<?php echo $rowheader['Logo_Height'];?>px !important; padding:3px;}.white_border{border:1px solid black !important;padding:3px} tr.tr_bg th { font-size:16px !important;font-weight:bold;}.table td, .table th {padding: 0rem 1rem;}.table{border-collapse: collapse;}</style>');
popupWin.document.write(html);  
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