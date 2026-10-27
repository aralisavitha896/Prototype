 <?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Layout Management  </h1>
      <small>&nbsp;</small>
      <div class="actions">
         <p style="float:right"><button onclick="add()"  class="btn btn-primary"  type="button"><i class="zmdi zmdi-plus-circle-o-duplicate"></i> Add Column  </button>&nbsp;
		 <a href="header-footer-management.php"  class="btn btn-primary"  type="button"><i class="zmdi zmdi-assignment"></i> Header / Footer Management  </a>
		 <a href="setup-management.php"  class="btn btn-primary"  type="button"><i class="zmdi zmdi-settings"> Settings</i>  </a>
		 </p>
      </div>
   </header>
   <?php 
        include('includes/notification.php'); 
   
   		$sqlheader= "SELECT * FROM HEADER_DETAILS";
		$resheader= sqlsrv_query($conn, $sqlheader);
		$rowheader = sqlsrv_fetch_array($resheader);

		$sqlfooter= "SELECT * FROM FOOTER_DETAILS";
		$resfooter= sqlsrv_query($conn, $sqlfooter);
		$rowfooter = sqlsrv_fetch_array($resfooter);
   ?>
      <div class="card">
      <div class="card-body">
	   <!---<div class="col-sm-12 pull-right" ><a class="btn btn-primary" onclick="preview('commondiv');" ><i class="zmdi zmdi-eye"> Common Preview</i></a></div>--->
	  <div class="alertfixed alert-info" style="width:100%;">NOTE : Column <u><b>sql field name </b></u>should be same as sql database column name in your SCADA.</div>
         <div class="table-responsive" style="overflow:auto">
		 <div class="col-sm-12">&nbsp;</div>	
		<div class="row"> <div class="col-sm-6">&nbsp;<h2>Batch Details</h2></div> <div class="col-sm-6 pull-right" ><a class="btn btn-primary" href="img/layout/batch.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary" onclick="preview('batchdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
                  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$batch_arr = array();
				$x= 0;
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'batch' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
				$batch_arr[]= $row;	
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>
            <div class="col-sm-12">&nbsp;</div>	
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Report</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-primary" href="img/layout/report.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('reportdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a>	</div></div>	
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
                  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$report_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'report'  ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$report_arr[]= $row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>	
			<div class="col-sm-12">&nbsp;</div>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Alarm</h2></div> <div class="col-sm-6 pull-right" ><a class="btn btn-primary" href="img/layout/alarm.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('alarmdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
			<table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
				  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$alarm_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'alarm' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$alarm_arr[] = $row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>
            		
			<div class="col-sm-12">&nbsp;</div>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Event</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-primary" href="img/layout/event.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('eventdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a>	</div></div>	
			
			<table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
				  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$event_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'event' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$event_arr[] = $row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>	
			<div class="col-sm-12">&nbsp;</div>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Esign Event</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-<?php if($rowheader['Esign_Status'] == 1)echo "success";else echo"danger";?>" onclick="changestatus('Esign_Status',<?php echo $rowheader['Esign_Status']; ?>)" ><?php if($rowheader['Esign_Status'] == 1)echo "Enabled";else echo "Disabled";?></a> &nbsp;<a class="btn btn-primary" href="img/layout/e-sign.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('esigndiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
			
			<table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
				  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$esign_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'esign' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$esign_arr[] = $row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>	
			
			<div class="col-sm-12">&nbsp;</div>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>User Login Report</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-<?php if($rowheader['UserLogin_Status'] == 1)echo "success";else echo"danger";?>" onclick="changestatus('UserLogin_Status',<?php echo $rowheader['UserLogin_Status']; ?>)" ><?php if($rowheader['UserLogin_Status'] == 1)echo "Enabled";else echo "Disabled";?></a> &nbsp;<a class="btn btn-primary" href="img/layout/user.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('userlogin_div');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>	
			
			<table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
                  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$userlogin_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'userlogin' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$userlogin_arr[] =$row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>		
			<div class="col-sm-12">&nbsp;</div>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Perfomed Action Report</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-<?php if($rowheader['PAction_Status'] == 1)echo "success";else echo"danger";?>" onclick="changestatus('PAction_Status',<?php echo $rowheader['PAction_Status']; ?>)" ><?php if($rowheader['PAction_Status'] == 1)echo "Enabled";else echo "Disabled";?></a> &nbsp;<a class="btn btn-primary" href="img/layout/paction.jpg" ><i class="zmdi zmdi-eye"> view image</i></a> &nbsp;<a class="btn btn-primary pull-right" onclick="preview('performedaction_div');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
			
			<table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Sql Field Name</th>
                  <th>Order</th>
                  <th>Type</th>
				  <th width="10%">Action</th>
                  </tr>
               </thead>
			    <tbody>
				<?php 
				$x= 0;
				$performedaction_arr =array();
				$sql= "SELECT * FROM REPORT_LAYOUT WHERE Page = 'peroformedaction' ORDER BY Sort ASC";
				$res= sqlsrv_query($conn, $sql);
				while($row = sqlsrv_fetch_array($res)){
					$performedaction_arr[] =$row;
				?>
                <tr>
				  <td><?php echo ++$x; ?></td>
				  <td><?php echo $row['Name']; ?></td>
				  <td><?php echo $row['Field']; ?></td>
				  <td><?php echo $row['Sort']; ?></td>			  
				  <td><?php echo $row['Type']; ?></td>			  
				  <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" onclick="edit(<?php echo $row['Layout_ID']; ?>)"><i class="zmdi zmdi-edit  zmdi-hc-fw"></i></a>
					  <a class="btn btn-danger"  onclick="deleterow(<?php echo $row['Layout_ID']; ?>);" ><i class="zmdi zmdi-delete  zmdi-hc-fw"></i></a>
                    </div>
				  
				  </td>
                </tr>
				<?php } ?>
        
                </tbody>   
            </table>			
			
         </div>
      </div>
   </div>
   <div style="display:none;">

   <div class="col-sm-12" id="batchdiv">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($batch_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead> 
                <tbody>
                  <tr>	
				  <?php foreach($batch_arr as $row){ ?>
                  <td>&nbsp;</td>
				  <?php } ?>				  
                  </tr>	
                </tbody>			   
    </table> 	
   </div>
   <div id="commondiv">
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Center_Text']; ?> Vessel Name</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>	
	<tr>
	<td colspan="3">   
   <div class="col-sm-12" id="reportdiv">
   <table width="100%" >
				<tbody>
				 <?php foreach($report_arr as $row){ ?>
				<tr>
                <td colspan="3">&nbsp;</td>				
                </tr>				
				<tr>
				<td width="40%" style="text-align:right;"><?php echo $row['Name'];?> : </td>
				<td width="1%">&nbsp;</td>
				<td><div style="border:1px solid black;width:50%;" class="white_border">&nbsp;</div></td>
				</tr>
				<?php } ?>
				</tbody>
				</table> 	
   </div>   
   </td>
   </tr>
   </tbody>
		    <tfoot>
            <tr>
			<td colspan="3" >
			<div class="row" style="position:fixed;bottom:0;width:100%;" >
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
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2"><?php echo $rowheader['Alarm_Text']; ?> Vessel Name</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>  
   <tr>
   <td colspan="3">   
   <div class="col-sm-12" id="alarmdiv">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($alarm_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead>
                <tbody>
                  <tr>	
				  <?php foreach($alarm_arr as $row){ ?>
                  <td>&nbsp;</td>
				  <?php } ?>				  
                  </tr>	
                </tbody>				  
    </table>  
   </div> 
   </td>
   </tr>
    </tbody>
   <tfoot>
   <tr><td colspan="3">&nbsp;</td></tr>
   </tfoot>
   </table>  
  <div style="page-break-after:always;" id="batchrtrendbrk"></div>
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2">Event Report For Vessel Name</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>   
   <tr>
   <td colspan="3">      
   <div class="col-sm-12" id="eventdiv">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($event_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead>  
    </table>  
   </div>  
   </td>
   </tr>
   </tbody>
   <tfoot>
   <tr><td colspan="3">&nbsp;</td></tr>
   </tfoot>
   </table>   
   <div style="page-break-after:always;" id="batchrtrendbrk"></div>
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2">Electronic Signature Report For Vessel Name</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>  
   <tr>
   <td colspan="3">   
   <div class="col-sm-12" id="esigndiv">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($esign_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead>  
                <tbody>
                  <tr>	
				  <?php foreach($esign_arr as $row){ ?>
                  <td>&nbsp;</td>
				  <?php } ?>				  
                  </tr>	
                </tbody>			   
    </table>  
   </td>
   </tr>
   </tbody>
   <tfoot>
   <tr><td colspan="3">&nbsp;</td></tr>
   </tfoot>
   </table>   
  <div style="page-break-after:always;" id="batchrtrendbrk"></div>
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2">User Login Report</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>   
   <tr>
   <td colspan="3">      
   <div class="col-sm-12" id="userlogin_div">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($userlogin_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead> 
                <tbody>
                  <tr>	
				  <?php foreach($userlogin_arr as $row){ ?>
                  <td>&nbsp;</td>
				  <?php } ?>				  
                  </tr>	
                </tbody>			   
    </table>  
   </div> 
   </td>
   </tr>
   </tbody>
   <tfoot>
   <tr><td colspan="3">&nbsp;</td></tr>
   </tfoot>
   </table>   
  <div style="page-break-after:always;" id="batchrtrendbrk"></div>
  <table width="100%" >
   <thead>
			<tr>
			    <td align="center" class="headrow1"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" class="headrow2"><?php echo $rowheader['PAction_Text']; ?> Vessel Name</td>
			    <td align="center" class="headrow3"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
	</thead>
	<tbody>   
   <tr>
   <td colspan="3">      
   <div class="col-sm-12" id="performedaction_div">
   <table id="" class="table table-bordered" width="100%">
               <thead>
                  <tr>
				  <?php foreach($performedaction_arr as $row){ ?>
                  <th><?php echo $row['Name']; ?></th>
				  <?php } ?>
                  </tr>
               </thead> 
                <tbody>
                  <tr>	
				  <?php foreach($performedaction_arr as $row){ ?>
                  <td>&nbsp;</td>
				  <?php } ?>				  
                  </tr>	
                </tbody>			   
    </table>  
   </div> 
    </td>
	</tr>
    </tbody>
   <tfoot>
   <tr><td colspan="3">&nbsp;</td></tr>
   </tfoot>
   </table>
   </div>
   </div>
    <div class="modal fade" id="modal_preview" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">PREVIEW</h5>
            </div>
            <div class="modal-body" id="previewdiv">
			</div>
		 </div>
	  </div>
	</div>
    <div class="modal fade" id="modal_form" tabindex="-1">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title pull-left">Layout Management</h5>
            </div>
            <div class="modal-body">
			    <form id="form"  action="#" class="form-horizontal" enctype="multipart/form-data" method="POST" autoComplete='off'>
			    <input name="edit_id" type="hidden" value="" />
				
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Page</label>
                     <select class="form-control" name ="page" id ="page">
					 <option value="batch">BATCH DETAILS</option>
					 <option value="report">REPORT FIRST PAGE</option>
					 <option value="alarm">ALARMS</option>
					 <option value="event">EVENTS</option>
					 <option value="esign">ELECTRONIC SIGNATURE EVENTS</option>
					 <option value="userlogin">USER LOGIN REPORT</option>
					 <option value="peroformedaction">PERFORMED ACTION ALARM</option>
					 </select>
                </div>
               </div>			  
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Type</label>
                     <select class="form-control" name ="type" id ="type" onchange ="fieldrequired(this.value);">
					 <option value="none">none</option>
					 <option value="starttime">starttime</option>
					 <option value="endtime">endtime</option>
					 <option value="startuser">startuser</option>
					 <option value="enduser">enduser</option>
					 <option value="sequence duration">sequence duration</option>
					 <option value="alarm text">alarm text</option>
					 <option value="date time">date time</option>
					 <option value="alarm type">alarm type</option>
					 </select>
                </div>
               </div>			   
               <div class="col-sm-12">
                  <div class="form-group">
				     <label>Column Name</label>
                    <input type="text" class="form-control" placeholder="Enter Column Name..."  name="name"  id="name">
                     <i class="form-group__bar"></i>
                  </div>
                </div>			   			   
			   <div class="col-sm-12" id="divfield">
                  <div class="form-group">
				     <label>Sql Field Name</label>
                    <input class="form-control" id='field' name="field" placeholder="Enter Column Field..." type="text" >
                     <i class="form-group__bar"></i>
                </div>
               </div>			   
			   <div class="col-sm-12">
                  <div class="form-group">
				     <label>Order</label>
                    <input class="form-control" id='order' name="order" placeholder="Enter Vessel Order..." type="number">
                     <i class="form-group__bar"></i>
                </div>
               </div>				   
			
			   
			  </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" onclick="save()">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>   
<style>
.pull-right{
	text-align:right;
}

</style>
<script>
function preview(div){
divhtml = $("#"+div).html();
console.log(divhtml);
var popupWin = window.open(' ','_blank', 'location=no,left=200px');
popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><body onload="window.print()">');
popupWin.document.write(divhtml);  
popupWin.document.close(document.location.reload(true));	
   
	
}
/*save sequence*/	
function fieldrequired(value)
    {  
	if(value =="sequence duration" || value =="startuser" || value =="enduser"){
		document.getElementById('divfield').style.display= "none";
	}else{
		document.getElementById('divfield').style.display= "block";
	}
    }
function add()
    {  
      save_method = 'add';
	   $("#field").attr("readonly",false);
      $('#form')[0].reset(); 
    $('#modal_form').modal('show');
    }
 function edit(id)
    { 
       save_method = 'update';
     // $("#field").attr("readonly",true);
      $.ajax({
        url : "<?php echo $baseurl; ?>controllers/get-layout-report.php?id=" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
		for(i=0; i<data.length; i++){
			fieldrequired(data[i].Type);
            $('[name="name"]').val(data[i].Name);
            $('[name="field"]').val(data[i].Field);
            $('[name="order"]').val(data[i].Sort);
            $('[name="type"]').val(data[i].Type);
            $('[name="page"]').val(data[i].Page);
            $('[name="edit_id"]').val(data[i].Layout_ID);
		}	
            $('#modal_form').modal('show'); 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
	
	
    }

	

 function save()
    {

      var url;
      if(save_method == 'add')
      {
         url = "<?php echo $baseurl; ?>controllers/add-layout-report.php";
      }
      else
      {
        url = "<?php echo $baseurl; ?>controllers/update-layout-report.php";
      }
 
          var postData = new FormData($("#form")[0]);

		 $.ajax({
				 type:'POST',
				 url:url,
				 processData: false,
				 contentType: false,
				 data : postData,
				 success:function(data){
				 location.reload();	
				 }

			  });

    }

	
//delete 
function deleterow(hide_id)
	{
		  if (confirm("Warning! Are You Sure To Delete This?")) {	
		 $.ajax({
         data: {"hide_id":hide_id},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/delete-layout-report.php",
         success: function(data)
         	{
         	location.reload();		
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				location.reload();
            }
				
            });
            
            }
	}    

function changestatus(column,value){
	    var text = '';
	    if(value == 1)
			text = "Disable";
		else
			text = "Enable";
		
		if (confirm("Warning! Are You Sure To "+text+" This?")) {	
		 $.ajax({
         data: {"column":column,"value":value},
         type: "post",
         url: "<?php echo $baseurl; ?>controllers/update-layout-statuses.php",
         success: function(data)
         	{
         	location.reload();		
      		},
			error: function (jqXHR, textStatus, errorThrown)
            {
				location.reload();
            }
				
            });
            
            }
}
	
</script>   
</section>
<?php include('includes/footer.php');?>