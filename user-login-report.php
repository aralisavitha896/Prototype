<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> User Login Report  </h1>
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
					$endtime = $_REQUEST['endtime'];
					$starttime = $_REQUEST['starttime'];
					}
					
					$stimename = new DateTime($starttime);
	                $xname =$stimename->modify("-$utchour hour -$utcminutes minutes");
	                $startevent=$xname->format("Y-m-d H:i:s");
	
	                $endtimenew = new DateTime($endtime);
	                $xendtime =$endtimenew->modify("-$utchour hour -$utcminutes minutes");
	                $endevent =$xendtime->format("Y-m-d H:i:s");
					
					$sqldb= "SELECT * FROM VESSEL_DETAILS";
				    $resdb= sqlsrv_query($conn, $sqldb);
					$rowdb = sqlsrv_fetch_array($resdb);
					$Logo= $rowdb['Logo'];
					$username_tag= $rowdb['Username_Tag'];
					?>
		   <div class="card">
      <div class="card-body">
	  <div class="box-header"><p class="boxalignright" style="float:right"><button type="button" id="print" name="print" class="btn btn-primary" ><i class="fa fa-print"></i> Print</button></p></div>
        <div class="table-responsive" id="PrintMe">
		<table style="width: 100%;" class="">
		<thead>
			<tr>
			    <td align="center" class="spec-td"><img src="<?php echo $baseurl;?>uploads/logos/<?php  echo $Logo; ?>" style="height:<?php echo $rowheader['Logo_Height'];?>% !important;width:<?php echo $rowheader['Logo_Width'];?>% !important; "/>
				</td>
				<td align="center" class="spectd">USER LOGIN REPORT</td>
			</tr>
		</thead>
		<tbody>
		        <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		<tr>
				<td colspan="2">
		<div class="col-sm-12">
            <form class="form-horizontal">
				<div class="row">
                    <label class="col-sm-6">Start date / Start time :</label>
                    <input type="text" class="col-sm-6 form-control specialcls" id="inputtext3" value="&nbsp;<?php echo date($dateformat,strtotime($starttime)); ?>" readonly />
                </div>
				<div class="row">
                    <label class="col-sm-6">End date / End time :</label>
                    <input type="text" class="col-sm-6 form-control specialcls" id="inputtext3" value="&nbsp;<?php echo date($dateformat,strtotime($endtime)); ?>" readonly />
                </div>				
			</form>
        </div>
		</td>
		</tr>
		</tbody>	
	    <tfoot>
		<?php 
		$sqlfooter = "SELECT * FROM FOOTER_DETAILS";
		$resfooter = sqlsrv_query($conn, $sqlfooter);
        $rowfooter = sqlsrv_fetch_array($resfooter);
		?>		
		<tr>
			<td colspan="2" id="pagefooter" style="display:none;" >
			<div class="row" <?php if($rowfooter['Footer_Type']=="allpages"){ ?>style="position:fixed;bottom:0;width:100%;" <?php } ?>>
			<?php 
			date_default_timezone_set("Asia/Kolkata");
			$printime = date("Y-m-d H:i",strtotime("-$utchour hour -$utcminutes minutes"));
			$sqlprint = "SELECT TOP 1  VariantValue FROM OPENQUERY(LNKRTDB_WINCCOLEDB, 'TAG:R,(".$username_tag."),''".$printime."'',''''')";
            $resprint = sqlsrv_query($conn, $sqlprint);
            $rowprint = sqlsrv_fetch_array($resprint);
            $currentuser = $rowprint['VariantValue'];
			?>
			<?php if($rowfooter['Footer_Type']!="allpages"){ ?><p style="margin:0 0 9em;">&nbsp;</p><?php } ?>
			<table width="100%">
		    <tbody>
            <tr>
			<td><b><?php echo $rowfooter['Column1']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column2']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column3']; ?></b></td>
			</tr>         
			<tr>
			<td><b><?php echo $rowfooter['Column4']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column5']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column6']; ?></b></td>
			</tr>		
			<tr>
			<td><b><?php echo $rowfooter['Column7']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column8']; ?></b></td>
			<td align="center"><b><?php echo $rowfooter['Column9']; ?></b></td>
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
			    <td align="center" class="spec-td"><img src="<?php echo $baseurl;?>uploads/logos/<?php  echo $Logo; ?>" style="height:<?php echo $rowheader['Logo_Height'];?>% !important;width:<?php echo $rowheader['Logo_Width'];?>% !important; "/></td>
				<td align="center" class="spectd">USER LOGIN REPORT</td>
			</tr>
		</thead>
		<tbody>
				<tr><td colspan="2">&nbsp;</td></tr>
				
		<tr>
		<td colspan="2">
		<table  style="width: 100%; margin: 0 auto;" class="table table-bordered mb-0" id="data-table">
	<thead>
  <tr>
    <?php
				  $layoutarr = array();
				  $sqllayout= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'userlogin' ORDER BY Sort ASC";
				  $reslayout= sqlsrv_query($conn, $sqllayout);
				  while($rowlayout = sqlsrv_fetch_array($reslayout)){
					 $layoutarr[]=  $rowlayout; ?>
                     <th><?php echo $rowlayout['Name']; ?></th>					 
    <?php } ?>
  </tr>
  </thead>
	<tbody>
    <?php
	
	$sql = "SELECT name FROM master.sys.databases WHERE name LIKE '%ALG%'";
	$res = sqlsrv_query($conn, $sql); 
	while($row = sqlsrv_fetch_array($res)){
	$newalarmtbl = $row ['name'];
	$sql="SELECT * FROM [".$newalarmtbl."].[dbo].[AlgViewEx] WHERE  DateTime BETWEEN '".$startevent.".000' AND  '".$endevent.".000' AND  MsgNr IN (1008001,1008002,1008003,1008004,1008005)";	
	$res = sqlsrv_query($conn, $sql); 
	while($row = sqlsrv_fetch_array($res)){ 
    ?>
            <tr>
            <?php foreach($layoutarr as $rowlayout) { 
			$field = $rowlayout['Field'];
			?>
			<td><?php if($rowlayout['Type']=="date time"){$row['DateTime']->modify("+$utchour hour +$utcminutes minutes"); echo $row['DateTime']->format($dateformat);}else{echo str_replace("USERT:@100%s@:","",$row[$field]);} ?>  </td>
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
			<td colspan="2"><p style="margin:0 0 3em;">&nbsp;</p></td>
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
	width:50%;
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
var popupWin = window.open('','_blank', 'location=no,left=200px');

popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
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