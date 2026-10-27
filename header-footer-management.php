<?php include('includes/header.php'); ?>
<section class="content">
   <header class="content__title">
      <h1> Header / Footer Management  </h1>
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
         <div class="table-responsive" style="overflow:auto">
			<?php 
				$sqlheader= "SELECT * FROM HEADER_DETAILS";
				$resheader= sqlsrv_query($conn, $sqlheader);
			    $rowheader = sqlsrv_fetch_array($resheader);			
				?>
		   
			<p>&nbsp;</p>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Header Data</h2></div> <div class="col-sm-6 pull-right" ><a class="btn btn-primary pull-right" onclick="preview('headerdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
				  <th>LOGO HEIGHT</th>
				  <th>LOGO WIDTH</th>
				  <th>CENTER TEXT</th>	
				  <th>RIGHT TEXT</th>	
				  <th>TREND TEXT</th>	
				  <th>ALARM TEXT</th>	
				  <th>PERFORMED ACTION TEXT</th>	
                  </tr>
               </thead>
			    <tbody>
                <tr>
				  <td><input type="number" rel="Logo_Height" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Logo_Height']; ?>"/></td>			  			  
				  <td><input type="number" rel="Logo_Width" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Logo_Width']; ?>"/></td>			  			  
				  <td><input type="text" rel="Center_Text" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Center_Text']; ?>"/></td>	
				  <td><input type="text" rel="Right_Text" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Right_Text']; ?>"/></td>	
				  <td><input type="text" rel="Trend_Text" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Trend_Text']; ?>"/></td>	
				  <td><input type="text" rel="Alarm_Text" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['Alarm_Text']; ?>"/></td>	
				  <td><input type="text" rel="PAction_Text" data-rel=""class="form-control changevalheader" value="<?php echo $rowheader['PAction_Text']; ?>"/></td>	
                </tr>
                </tbody>   
            </table>
			
		 		<?php 
				$sql= "SELECT * FROM [FOOTER_DETAILS]";
				$res= sqlsrv_query($conn, $sql);
			    $row = sqlsrv_fetch_array($res);			
				?>
		    
			<p>&nbsp;</p>
			<div class="row"><div class="col-sm-6">&nbsp;<h2>Footer Data</h2></div><div class="col-sm-6 pull-right" ><a class="btn btn-primary pull-right" onclick="preview('footerdiv');" ><i class="zmdi zmdi-eye"> Preview</i></a></div></div>
            <table id="" class="table table-bordered">
               <thead>
                  <tr>
                  <th rowspan="3" >FOOTER TYPE</th>
				  <?php if($row['Footer_Type'] =="firstpage"){ ?>
                  <th rowspan="3" >PADDING</th>
				  <?php } ?>
				  <th>LEFT</th>
				  <th>CENTER</th>
				  <th>RIGHT</th>	
                  </tr>
               </thead>
			    <tbody>
                <tr>
				  <td rowspan="3">
				  <select name="type" class="form-control changeval"  rel="Footer_Type">
				  <option value="allpages" <?php if($row['Footer_Type'] == "allpages"){echo "selected";} ?>>All Pages</option>
				  <option value="firstpage" <?php if($row['Footer_Type'] == "firstpage"){echo "selected";} ?>>First Page Only</option>
				  </select>
				  </td>
				  <?php if($row['Footer_Type'] =="firstpage"){ ?>
				  <td rowspan="3"><input type="number" rel="Padding" data-rel=""class="form-control changeval" value="<?php echo $row['Padding']; ?>"/></td>
				  <?php } ?>
				  <td><input type="text" rel="Column1" data-rel=""class="form-control changeval" value="<?php echo $row['Column1']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column2" data-rel=""class="form-control changeval" value="<?php echo $row['Column2']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column3" data-rel=""class="form-control changeval" value="<?php echo $row['Column3']; ?>"/></td>	
                </tr>
                 <tr>				
				  <td><input type="text" rel="Column4" data-rel=""class="form-control changeval" value="<?php echo $row['Column4']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column5" data-rel=""class="form-control changeval" value="<?php echo $row['Column5']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column6" data-rel=""class="form-control changeval" value="<?php echo $row['Column6']; ?>"/></td>
				</tr>
				<tr>
				  <td><input type="text" rel="Column7" data-rel=""class="form-control changeval" value="<?php echo $row['Column7']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column8" data-rel=""class="form-control changeval" value="<?php echo $row['Column8']; ?>"/></td>			  			  
				  <td><input type="text" rel="Column9" data-rel=""class="form-control changeval" value="<?php echo $row['Column9']; ?>"/></td>			  			  
                </tr>
                </tbody>   
            </table>	
         </div>
      </div>
   </div>  
   <div style="display:none;">
   <div id ="footerdiv">
   <p style="margin: 0 0 40em;">&nbsp;</p>
   			<table width="100%">
		    <tbody>
            <tr>
			<td><b><?php echo $row['Column1']; ?></b></td>
			<td align="center"><b><?php echo $row['Column2']; ?></b></td>
			<td align="center"><b><?php echo $row['Column3']; ?></b></td>
			</tr>         
			<tr>
			<td><b><?php echo $row['Column4']; ?></b></td>
			<td align="center"><b><?php echo $row['Column5']; ?></b></td>
			<td align="center"><b><?php echo $row['Column6']; ?></b></td>
			</tr>		
			<tr>
			<td><b><?php echo $row['Column7']; ?></b></td>
			<td align="center"><b><?php echo $row['Column8']; ?></b></td>
			<td align="center"><b><?php echo $row['Column9']; ?></b></td>
			</tr>			
		    </tbody>	
			</table>
   </div>
   <div id ="headerdiv">
      <table style="width: 100%;" class="previewtable">
		<thead>
			<tr>
			    <td align="center" width="30%"><img src="<?php echo $baseurl;?>uploads/logos/biozeen.jpg" class='imgsizing' style="height:<?php echo $rowheader['Logo_Height']; ?>% !important;width:<?php echo $rowheader['Logo_Width']; ?>%;">
				</td>
			    <td align="center" width="40%"><?php echo $rowheader['Center_Text']; ?> Vessel Name</td>
			    <td align="center" width="30%"><?php echo $rowheader['Right_Text']; ?>  </td>
			</tr>
		</thead>
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
<style>.pull-right{text-align:right;}.previewtable, .previewtable td, .previewtable th {border: 1px solid black;}</style>	
<script>
function preview(div){
divhtml = $("#"+div).html();
var popupWin = window.open('','_blank', 'location=no,left=200px');

popupWin.document.open();
popupWin.document.write('<link rel="stylesheet" href="<?php  echo $baseurl;  ?>dist/css/AdminLTE.min.css"><style>.pull-right{text-align:right;}.previewtable, .previewtable td, .previewtable th {border: 1px solid black;}</style><body onload="window.print()">');
popupWin.document.write(divhtml);  
popupWin.document.close(document.location.reload(true));	
	
}
$('.changeval').on('change', function(){
   var column = $(this).attr('rel');
   var value = $(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-footer-contents.php",
            data: {column: column,value: value},
			success:function(){
				if(column == "Footer_Type")
					location.reload();
			}
         });
        
});
	$('.changevalheader').on('change', function(){
   var column = $(this).attr('rel');
   var value = $(this).val();

   $.ajax ({
   			type:"post",
            url: "controllers/update-header-contents.php",
            data: {column: column,value: value},
         });
        
});	
</script>   
</section>
<?php include('includes/footer.php');?>