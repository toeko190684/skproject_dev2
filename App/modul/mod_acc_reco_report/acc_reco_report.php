<script>
$(document).ready(function(){

	
	$.fn.dataTable.TableTools.defaults.aButtons = [ "copy", "csv", "pdf", "print" ];
 
    $('#example').DataTable( {
        "scrollX" : true,
		dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>

<?php
ses_module();
switch($_GET[act]){
  default:
  $access = read_security();
  if($access=="allow"){
	    echo "<a class='btn btn-default' id='download' ><i class='icon-download-alt'></i></a>
			<form class='form-horizontal' method='post' action='?r=acc_reco_report'>
				<fieldset><legend>Acc reco Report</legend>
					<label for='tgl'>Masukan Bulan & Tahun :</label>
						<select name='bulan' id='bulan' class='input-medium'>";
						$bln = mysql_query("select * from month");
						while($rbln = mysql_fetch_array($bln)){
							echo "<option value='$rbln[month_name]'>$rbln[month_name]</option>";
						}
		echo"			</select>&nbsp&nbsp-&nbsp&nbsp<select name='tahun' id='tahun' class='input-small'>";
						for($i=0;$i<5;$i++){
							$tahun = date('Y')-$i;
							echo "<option value='$tahun'>$tahun</option>";
						}
		echo "			</select>
						<input type=submit class='btn btn-primary' value='Cari' >
				</fieldset>
			  </form>";
		
		echo "<table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
							<thead>
								<tr class='success'>
									<td>No</td><td>Periode Budget</td><td>Kode Budget</td>
									<tD>Departemen</td><tD>Kode Reco</td><td>Tgl Reco</td><tD>Area ID</td>
									<td>Distributor ID</td><tD>Group Promo ID</td><tD>Promo type ID</td><td>Class ID</td>
									<td>Account ID</td><td>Title</td><td>Tgl Awal</td>
									<tD>Tgl Akhir</td><td>Total Sales Target</td><td>Background</td><td>Promo Mechanisme</td>
									<td>Claim Mechanisme</td><td>Claim Trade Off</td><td>Cost Of Promo</td><td>Type Of Cost</td>
									<td>Cost Rasio</td><td>Status</td><td>complete</td><td>tgl_complete</td>
									<td>Jenis biaya</td><td>Created By</td><td>Last Update</td><td>Approval1</td>
									<td>Tgl Approval1</td><tD>Approval2</td><td>Tgl Approval2</td>
								</tr>
							</thead><tbody>";
				
				if(($_POST[bulan]=="")||($_POST[tahun]=="")){
					$sql = "SELECT a.bulan,a.tahun,a.kode_budget,a.department_id,c.kode_promo, c.tgl_promo,
							c.area_id,c.distributor_id,c.grouppromo_id,c.promotype_id,c.class_id,c.account_id,c.total_sales_target,
							c.background,c.promo_mechanisme,c.claim_mechanisme,c.claimtradeoff,c.claimtradeoff,
							c.cost_of_promo,c.typeofcost,c.cost_rasio,  c.status, c.complete,c.tgl_complete,c.jenis_biaya,
							c.created_by,c.last_update,c.approval1,c.tgl_approval1,c.approval2,c.tgl_approval2 ,c.title,c.tgl_awal,
							c.tgl_akhir FROM master_budget a,detail_reco_item b, 
							reco_request c where a.kode_budget=b.kode_budget and b.kode_reco=c.kode_promo 
							order by a.kode_budget,c.kode_promo,c.tgl_promo";
					$tampil=mysql_query($sql);
				}else{
					$sql = "SELECT a.bulan,a.tahun,a.kode_budget,a.department_id,c.kode_promo, c.tgl_promo,
							c.area_id,c.distributor_id,c.grouppromo_id,c.promotype_id,c.class_id,c.account_id,c.total_sales_target,
							c.background,c.promo_mechanisme,c.claim_mechanisme,c.claimtradeoff,c.claimtradeoff,
							c.cost_of_promo,c.typeofcost,c.cost_rasio,  c.status, c.complete,c.tgl_complete,c.jenis_biaya,
							c.created_by,c.last_update,c.approval1,c.tgl_approval1,c.approval2,c.tgl_approval2 ,c.title,c.tgl_awal,
							c.tgl_akhir FROM master_budget a,detail_reco_item b, 
							reco_request c where a.kode_budget=b.kode_budget and b.kode_reco=c.kode_promo 
							and a.bulan='$_POST[bulan]'  and tahun='$_POST[tahun]'
							order by a.kode_budget,c.kode_promo,c.tgl_promo";
					$tampil=mysql_query($sql);
				}

				$no =1;
				$total = 0;
				while($r = mysql_fetch_array($tampil)){
						echo "<tr>
								<td>$no</td><td>$r[bulan] $r[tahun]</td>
								<td>$r[kode_budget]</td><tD>$r[department_id]</td>
								<tD>$r[kode_promo]</td><td>$r[tgl_promo]</td>
								<tD>$r[area_id]</td><td>$r[distributor_id]</td>
								<tD>$r[grouppromo_id]</td><tD>$r[promotype_id]</td>
								<td>$r[class_id]</td><td>$r[account_id]</td>
								<td>$r[title]</td><td>$r[tgl_awal]</td>
								<tD>$r[tgl_akhir]</td><td>$r[total_sales_target]</td>
								<td>$r[background]</td><td>$r[promo_mechanisme]</td>
								<td>$r[claim_mechanisme]</td><td>$r[claimtradeoff]</td>
								<td>$r[cost_of_promo]</td><td>$r[typeofcost]</td>
								<td>$r[cost_rasio]</td><td>$r[status]</td>
								<td>$r[complete]</td><td>$r[tgl_complete]</td>
								<td>$r[jenis_biaya]</td><td>$r[created_by]</td>
								<td>$r[last_update]</td><td>$r[approval1]</td>
								<td>$r[tgl_approval1]</td><tD>$r[approval2]</td>
								<td>$r[tgl_approval2]</td>
							  </tr>";
						$total = $total + $r[cost_of_promo];
						$no++;
				}
				
		echo "</tbody>
						<tfoot>
							<tr>
								<td>No</td><td>Periode Budget</td><td>Kode Budget</td>
								<tD>Departemen</td><tD>Kode Reco</td><td>Tgl Reco</td><tD>Area ID</td>
								<td>Distributor ID</td><tD>Group Promo ID</td><tD>Promo type ID</td><td>Class ID</td>
								<td>Account ID</td><td>Title</td><td>Tgl Awal</td>
								<tD>Tgl Akhir</td><td>Total Sales Target</td><td>Background</td><td>Promo Mechanisme</td>
								<td>Claim Mechanisme</td><td>Claim Trade Off</td><td>Cost Of Promo</td><td>Type Of Cost</td>
								<td>Cost Rasio</td><td>Status</td><td>complete</td><td>tgl_complete</td>
								<td>Jenis biaya</td><td>Created By</td><td>Last Update</td><td>Approval1</td>
								<td>Tgl Approval1</td><tD>Approval2</td><td>Tgl Approval2</td>
							</tr>
						</tfoot>
					</table>";
	}else{
		msg_security();
	}
    break; 
}
?>
