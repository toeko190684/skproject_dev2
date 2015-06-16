<script>
$(document).ready(function(){	
	$('#cetak').click(function(){
		var tgl_awal = $('#tgl_awal').datebox('getValue');
		var tgl_akhir = $('#tgl_akhir').datebox('getValue');
		var status = $('#status').val();
		if(tgl_awal==''){
			$.messager.alert('SKProject','Tanggal awal tidak boleh kosong.!','info');
		}else if(tgl_akhir==''){
			$.messager.alert('SKProject','Tanggal akhir tidak boleh kosong..!','info');
		}else if(status==''){
			$.messager.alert('SKProject','Status Tidak boleh kosong..!','info');
		}else{
			/*$('#recoclosing').on('submit', function (e) {
				e.preventDefault();
			});*/
			$('#recoclosing').submit();
		}
	});
	
	$('#download').click(function(e){
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tampil').html()));
        e.preventDefault();
	});
	
	$('#cari').click(function(){
		$('#filter').toggle();
	});
	
	function hide(){
	    $('#menu').hide();
		$('#footer').hide();
		$('#cari').hide();
		$('#print').hide();	
		$('#download').hide();
	}
	
	function show(){
		$('#menu').show();
		$('#footer').show();
		$('#cari').show();
		$('#print').show();	
		$('#download').show();
	}
	
	$('#print').click(function(){
		hide();
		window.print();
		show();
	});

	$.fn.dataTable.TableTools.defaults.aButtons = [ "copy", "csv", "pdf", "print" ];
 
    $('#example').DataTable( {
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf"
        }
    });

	
});
</script>
<?php
$aksi="modul/mod_reco_closing_report/aksi_reco_closing_report.php";
ses_module();
switch($_GET[act]){
  // Tampil master_nasional
  default:
  $access = read_security();
  if($access=="allow"){
	   echo "<a class='btn btn-default' id='cari' ><i class='icon-search'></i></a>
		    <div id='filter'><form id='recoclosing' class='form-horizontal' method='post' action='?r=reco_closing_report'>
	          <fieldset><legend>Reco Closing Report</legend>
			  <div class='span6'>
					  <div class='control-group'>
							<label class='control-label' for='Periode'>Periode</label>
							<div class='controls'>
								<input type='text' id='tgl_awal' name='tgl_awal' class='easyui-datebox input-small'>&nbspS/D&nbsp
								<input type='text' id='tgl_akhir' name='tgl_akhir' class='easyui-datebox input-small'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='closing'>Status Closing</label>
							<div class='controls'>
								<select id='status' class='easyui-combobox' name='status' style='width:100px'>
									<option value='1'>Close</option>
									<option value='0'>Open</option>
								</select>
							</div>
					  </div>
					  <div class='control-group'>
							<div class='controls'>
								<input type='submit' id='cetak' class='btn btn-primary' value='Cetak'>
							</div>
					  </div>
			  </div>
			  </fieldset></form></div>";
			  
			echo "<div id='tampil'>";
				if(($_POST[tgl_awal]<>"")and($_POST[tgl_akhir]<>"")and($_POST[status]<>"")){				
					echo "<table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
							<thead>
								<tr class='success'>
									<th>Kode Promo</th><th>Departemen</th><th>Title</th><th>Reco</th><th>Claim</th><th>Selisih</th>
								</tr>
							</thead><tbody>";
							
					if(($_SESSION[grade_id]=="*")||($_SESSION[grade_id]=="**")||($_SESSION[grade_id]=="***")){
						$rs = mysql_query("SELECT a.kode_promo,c.department_name,a.title,cast(a.cost_of_promo as decimal(15,0))as cost_of_promo,
										   ifnull((select sum(claim_approved_ammount) from claim_request 
										   where kode_promo=a.kode_promo and upper(status)<>'rejected'),0) as claim, 
										   a.cost_of_promo-ifnull((select sum(claim_approved_ammount) 
										   from claim_request where kode_promo=a.kode_promo  and upper(status)<>'rejected'),0) as sisa 
										   FROM reco_request a ,detail_reco_item b,master_department c where a.kode_promo = b.kode_reco
											and b.departemen_id = c.department_id and a.close=$_POST[status] and str_to_date(a.tgl_promo,'%m/%d/%Y') between 
										   str_to_date('$_POST[tgl_awal]','%m/%d/%Y')  and str_to_date('$_POST[tgl_akhir]','%m/%d/%Y')");
					}else{
						$rs = mysql_query("SELECT a.kode_promo,c.department_name,a.title,cast(a.cost_of_promo as decimal(15,0))as cost_of_promo,
										   ifnull((select sum(claim_approved_ammount) from claim_request 
										   where kode_promo=a.kode_promo and upper(status)<>'rejected'),0) as claim, 
										   a.cost_of_promo-ifnull((select sum(claim_approved_ammount) 
										   from claim_request where kode_promo=a.kode_promo  and upper(status)<>'rejected'),0) as sisa 
										   FROM reco_request a, detail_reco_item b,master_department c  where a.kode_promo=b.kode_reco and b.divisi_id='$_SESSION[divisi_id]'
										   and b.departemen_id='$_SESSION[department_id]' and  b.departemen_id=c.department_id and a.close=0 and str_to_date(a.tgl_promo,'%m/%d/%Y') between 
										   str_to_date('$_POST[tgl_awal]','%m/%d/%Y')  and str_to_date('$_POST[tgl_akhir]','%m/%d/%Y')");
					}					
					
					$reco = 0;
					$claim = 0;
					$sisa = 0;
					while($r = mysql_fetch_array($rs)){
						echo "<tr>
									<td>$r[kode_promo]</td>
									<td>$r[department_name]</td>
									<td>$r[title]</td>
									<td>".number_format($r[cost_of_promo],0,'.',',')."</td>
									<td>".number_format($r[claim],0,'.',',')."</td>
									<td>".number_format($r[sisa],0,'.',',')."</td>
							  </tr>";
							  
							  $reco = $reco + $r[cost_of_promo];
							  $claim = $claim + $r[claim];
							  $sisa = $sisa + $r[sisa];
					}
					echo "</tbody>
						<tfoot>
							<tr>
								<td colspan='3'><b>TOTAL ALL</a></td>
								<td><b>".number_format($reco,0,'.',',')."</b></td>
								<td><b>".number_format($claim,0,'.',',')."</b></td>
								<td><b>".number_format($sisa,0,'.',',')."</b></td>
							</tr>
						</tfoot>
					</table>";
				}	
			echo "<div>";
	}else{
		msg_security();
	}
    break;

}
?>
