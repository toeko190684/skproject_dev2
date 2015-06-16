<?php
require_once("../../../configuration/connection_inc.php");
require_once("../../../function/email.php");
require_once("get_sql.php");


$lebar = "style='width:100px;cellpadding:2px;color: #ffffff;'";

$header = "<h2>Lokal</h2><table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
             <tR style='background-color:#000F9F;border: solid 1px #ccc'>
		     <td align='center' $lebar>DIVISI</td>
			 <td align='center' $lebar>BSC.TARGET</td>
			 <td align='center'  $lebar>UPD.TARGET</td>
			 <td align='center'  $lebar>LAST YEAR</td>
			 <td align='center'  $lebar>SO ( * )</td>
			 <td align='center' $lebar>DO</td>
			 <td align='center' $lebar>RETUR</td>
			 <td align='center' $lebar>NET SALES</td>
			 <td align='center' $lebar>NET SALES vs SO %</td>
			 <td align='center' $lebar>NET SALES vs BSC.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs UPD.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs LAST YEAR %</td></tR>";
		
		$tgl_awal = substr(date('Y-m-d',strtotime(date('m/d/Y'))),0,8).'01';
		$tgl_akhir = date('Y-m-d',strtotime(date('m/d/Y')));
		
		$tgl_awal_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_awal)));
		$tgl_akhir_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_akhir)));

	    $so = 0;
		$do = 0;
		$netsales =0;
		$pembagi = 1000000;
		$sql = "select distinct category from product order by category";
		$qx = odbc_exec($conn2,$sql);
		while($rx = odbc_fetch_array($qx)){
			@$sodo = (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/so_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])*100;
			@$dob = (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/basic_target($tgl_akhir,$rx[category])*100;
			@$dot = (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/target($tgl_akhir,$rx[category])*100;
			@$dol = (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/lastyear($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])*100;
			
			@$basic_target = $basic_target + basic_target($tgl_akhir,$rx[category])/$pembagi;
			@$target = $target + target($tgl_akhir,$rx[category])/$pembagi;
			@$lastyear = $lastyear + lastyear($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])/$pembagi;
			@$so = $so + so_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])/$pembagi;
			@$do = $do + do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])/$pembagi;
			$retur = $retur + so_divisi_min($tgl_awal,$tgl_akhir,$rx[category])/$pembagi;
			$netsales = $netsales + (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/$pembagi;

			$isi = $isi."<tR>
	    			<td>$rx[category]</td>
					<td align='right'>".number_format(basic_target($tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(target($tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(lastyear($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])/$pembagi,2,',','.')."</td>
     			    <td align='right'>".number_format(so_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(so_divisi_min($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'  bgcolor='#FFD700'>".number_format((do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($sodo,2,',','.')." %</td>
					<td align='right'>".number_format($dob,2,',','.')." %</td>
					<td align='right'>".number_format($dot,2,',','.')." %</td>
					<td align='right'>".number_format($dol,2,',','.')." %</td>
				  </tr><tr><td colspan=8></td></tr>"; 
		}
		@$total_sodo = $netsales / $so * 100;
		@$total_dob = $netsales / $basic_target * 100;
		@$total_dot = $netsales / $target * 100;
		@$total_dol = $netsales / $lastyear * 100;
		$footer = "<tr style='font-weight:bold'>
				<td>TOTAL</td>
				<td align='right'>".number_format($basic_target,2,',','.')."</td>
				<td align='right'>".number_format($target,2,',','.')."</td>
				<td align='right'>".number_format($lastyear,2,',','.')."</td>
				<td align='right'>".number_format($so,2,',','.')."</td>
				<td align='right'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($retur,2,',','.')."</td>
				<td align='right' bgcolor='#FFD700'>".number_format($netsales,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
				<td align='right'>".number_format($total_dob,2,',','.')." %</td>
				<td align='right'>".number_format($total_dot,2,',','.')." %</td>
				<td align='right'>".number_format($total_dol,2,',','.')." %</td>
			 </tr></table>";
		$akhir = "<br><i>Note : </i>
			  <br><i>(*) SO Column including discontinued and non targeted product. SO column show the real order from distributor</i>
			  <br><i>NET SALES = DO - Retur </i>
			  <br><i>Report in million</i>
			  <br><i>Net Value = Quantity * Price list * 0.875 * 0.99 / 1.1 </i>
			  <br><i>Exclude Export Transaction</i><br>";
		$message = $header.$isi.$footer.$akhir;
		
		
// --------------------------------------------exportnya nih---------------------------------------------------------------------------

$so = 0;
$do = 0;
$netsales =0;
$pembagi = 1000000;
$sodo = 0;
@$dob = 0;
@$dot = 0;
@$dol = 0;
@$basic_target = 0;
@$target = 0;
@$lastyear = 0;
@$so = 0;
@$do = 0;
$retur = 0;
$netsales = 0;
$total_sodo = 0;
@$total_dob = 0;
@$total_dot = 0;
@$total_dol = 0;
$header = "";
$isi = "";
$footer = "";
$akhir = "";	

$header = "<h2>Export</h2><table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
             <tR style='background-color:#000F9F;border: solid 1px #ccc'>
		     <td align='center' $lebar>DIVISI</td>
			 <td align='center' $lebar>BSC.TARGET</td>
			 <td align='center'  $lebar>UPD.TARGET</td>
			 <td align='center'  $lebar>LAST YEAR</td>
			 <td align='center'  $lebar>SO ( * )</td>
			 <td align='center' $lebar>DO</td>
			 <td align='center' $lebar>RETUR</td>
			 <td align='center' $lebar>NET SALES</td>
			 <td align='center' $lebar>NET SALES vs SO %</td>
			 <td align='center' $lebar>NET SALES vs BSC.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs UPD.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs LAST YEAR %</td></tR>";
		
		$tgl_awal = substr(date('Y-m-d',strtotime(date('m/d/Y'))),0,8).'01';
		$tgl_akhir = date('Y-m-d',strtotime(date('m/d/Y')));
		
		$tgl_awal_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_awal)));
		$tgl_akhir_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_akhir)));

	    $so = 0;
		$do = 0;
		$netsales =0;
		$pembagi = 1000000;
		$sql2 = "select distinct category, case 
											when category='candy' then '91'
											when category='snack' then '92'
											when category='beverage' then '93'
											end as category_id 
				 from product order by category";
		$q = odbc_exec($conn2,$sql2);
		while($r = odbc_fetch_array($q)){
			@$sodo = (do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/so_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])*100;
			@$dob = (do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/basic_target_export($tgl_akhir,$r[category_id])*100;
			@$dot = (do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/target_export($tgl_akhir,$r[category_id])*100;
			@$dol = (do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/lastyear_export($tgl_awal_lastyear,$tgl_akhir_lastyear,$r[category])*100;
			
			@$basic_target = $basic_target + basic_target_export($tgl_akhir,$r[category_id])/$pembagi;
			@$target = $target + target_export($tgl_akhir,$r[category_id])/$pembagi;
			@$lastyear = $lastyear + lastyear_export($tgl_awal_lastyear,$tgl_akhir_lastyear,$r[category])/$pembagi;
			@$so = $so + so_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi;
			@$do = $do + do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi;
			$retur = $retur + so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi;
			$netsales = $netsales + (do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/$pembagi;

			$isi = $isi."<tR>
	    			<td>$r[category]</td>
					<td align='right'>".number_format(basic_target_export($tgl_akhir,$r[category_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(target_export($tgl_akhir,$r[category_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(lastyear_export($tgl_awal_lastyear,$tgl_akhir_lastyear,$r[category])/$pembagi,2,',','.')."</td>
     			    <td align='right'>".number_format(so_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category])/$pembagi,2,',','.')."</td>
					<td align='right'  bgcolor='#FFD700'>".number_format((do_divisi_plus_export($tgl_awal,$tgl_akhir,$r[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$r[category]))/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($sodo,2,',','.')." %</td>
					<td align='right'>".number_format($dob,2,',','.')." %</td>
					<td align='right'>".number_format($dot,2,',','.')." %</td>
					<td align='right'>".number_format($dol,2,',','.')." %</td>
				  </tr><tr><td colspan=8></td></tr>"; 
		}
		@$total_sodo = $netsales / $so * 100;
		@$total_dob = $netsales / $basic_target * 100;
		@$total_dot = $netsales / $target * 100;
		@$total_dol = $netsales / $lastyear * 100;
		$footer = "<tr style='font-weight:bold'>
				<td>TOTAL</td>
				<td align='right'>".number_format($basic_target,2,',','.')."</td>
				<td align='right'>".number_format($target,2,',','.')."</td>
				<td align='right'>".number_format($lastyear,2,',','.')."</td>
				<td align='right'>".number_format($so,2,',','.')."</td>
				<td align='right'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($retur,2,',','.')."</td>
				<td align='right' bgcolor='#FFD700'>".number_format($netsales,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
				<td align='right'>".number_format($total_dob,2,',','.')." %</td>
				<td align='right'>".number_format($total_dot,2,',','.')." %</td>
				<td align='right'>".number_format($total_dol,2,',','.')." %</td>
			 </tr></table>";
		$akhir = "<br><i>Note : </i>
		          <br><i>All transaction convert to IDR</i>
				  <br><i>(*) SO Column including discontinued and non targeted product. SO column show the real order from buyer</i>
				  <br><i>NET SALES = DO - Retur </i>
				  <br><i>Report in million</i>
				  <br><i>Net Value = Quantity * Sale Price</i><br>";
		$message = $message.$header.$isi.$footer.$akhir;
		
		
		
		
//==================================================TOTAL ALL========================================================================

	
$so = 0;
$do = 0;
$netsales =0;
$pembagi = 1000000;
$sodo = 0;
@$dob = 0;
@$dot = 0;
@$dol = 0;
@$basic_target = 0;
@$target = 0;
@$lastyear_detail = 0;
@$lastyear = 0;
@$so_detail = 0;
@$so = 0;
@$do_detail = 0;
@$do = 0;
@$retur_detail = 0;
$retur = 0;
$netsales = 0;
$total_sodo = 0;
@$total_dob = 0;
@$total_dot = 0;
@$total_dol = 0;
$header = "";
$isi = "";
$footer = "";
$akhir = "";

$header = "<h2>Lokal + Export</h2><table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
             <tR style='background-color:#000F9F;border: solid 1px #ccc'>
		     <td align='center' $lebar>DIVISI</td>
			 <td align='center' $lebar>BSC.TARGET</td>
			 <td align='center'  $lebar>UPD.TARGET</td>
			 <td align='center'  $lebar>LAST YEAR</td>
			 <td align='center'  $lebar>SO ( * )</td>
			 <td align='center' $lebar>DO</td>
			 <td align='center' $lebar>RETUR</td>
			 <td align='center' $lebar>NET SALES</td>
			 <td align='center' $lebar>NET SALES vs SO %</td>
			 <td align='center' $lebar>NET SALES vs BSC.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs UPD.TARGET %</td>
			 <td align='center' $lebar>NET SALES vs LAST YEAR %</td></tR>";
		
		$tgl_awal = substr(date('Y-m-d',strtotime(date('m/d/Y'))),0,8).'01';
		$tgl_akhir = date('Y-m-d',strtotime(date('m/d/Y')));
		
		$tgl_awal_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_awal)));
		$tgl_akhir_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_akhir)));


		$sql = "select distinct category, case 
											when category='candy' then '1'
											when category='snack' then '2'
											when category='beverage' then '3'
											end as category_id 
				 from product order by category";
		$qx = odbc_exec($conn2,$sql);
		while($rx = odbc_fetch_array($qx)){
			@$sodo = (do_divisi_plus_all($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min_all($tgl_awal,$tgl_akhir,$rx[category]))/so_divisi_plus_all($tgl_awal,$tgl_akhir,$rx[category])*100;
			@$dob = (do_divisi_plus_all($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min_all($tgl_awal,$tgl_akhir,$rx[category]))/basic_target_all($tgl_akhir,$rx[category_id])*100;
			@$dot = (do_divisi_plus_all($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min_all($tgl_awal,$tgl_akhir,$rx[category]))/target_all($tgl_akhir,$rx[category_id])*100;
			@$dol = (do_divisi_plus_all($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min_all($tgl_awal,$tgl_akhir,$rx[category]))/lastyear_all($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])*100;
			
			@$basic_target = $basic_target + basic_target_all($tgl_akhir,$rx[category_id])/$pembagi;
			@$target = $target + target_all($tgl_akhir,$rx[category_id])/$pembagi;
			@$lastyear_detail = lastyear($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])+lastyear_export($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category]);
			@$lastyear = $lastyear + $lastyear_detail/$pembagi;
			$so_detail = (so_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_plus_export($tgl_awal,$tgl_akhir,$rx[category]));
			@$so = $so + $so_detail/$pembagi;
			$do_detail = (do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+do_divisi_plus_export($tgl_awal,$tgl_akhir,$rx[category]));
			@$do = $do + $do_detail /$pembagi;
			$retur_detail = (so_divisi_min($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min_export($tgl_awal,$tgl_akhir,$rx[category]));
			$retur = $retur + $retur_detail /$pembagi;
			$netsales = $netsales + ($do_detail + $retur_detail )/$pembagi;

			$isi = $isi."<tR>
	    			<td>$rx[category]</td>
					<td align='right'>".number_format(basic_target_all($tgl_akhir,$rx[category_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(target_all($tgl_akhir,$rx[category_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($lastyear_detail/$pembagi,2,',','.')."</td>
     			    <td align='right'>".number_format($so_detail/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($do_detail/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($retur_detail/$pembagi,2,',','.')."</td>
					<td align='right'  bgcolor='#FFD700'>".number_format(($do_detail + $retur_detail)/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($sodo,2,',','.')." %</td>
					<td align='right'>".number_format($dob,2,',','.')." %</td>
					<td align='right'>".number_format($dot,2,',','.')." %</td>
					<td align='right'>".number_format($dol,2,',','.')." %</td>
				  </tr><tr><td colspan=8></td></tr>"; 
		}
		@$total_sodo = $netsales / $so * 100;
		@$total_dob = $netsales / $basic_target * 100;
		@$total_dot = $netsales / $target * 100;
		@$total_dol = $netsales / $lastyear * 100;
		$footer = "<tr style='font-weight:bold'>
				<td>TOTAL</td>
				<td align='right'>".number_format($basic_target,2,',','.')."</td>
				<td align='right'>".number_format($target,2,',','.')."</td>
				<td align='right'>".number_format($lastyear,2,',','.')."</td>
				<td align='right'>".number_format($so,2,',','.')."</td>
				<td align='right'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($retur,2,',','.')."</td>
				<td align='right' bgcolor='#FFD700'>".number_format($netsales,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
				<td align='right'>".number_format($total_dob,2,',','.')." %</td>
				<td align='right'>".number_format($total_dot,2,',','.')." %</td>
				<td align='right'>".number_format($total_dol,2,',','.')." %</td>
			 </tr></table>";
		$message = $message.$header.$isi.$footer;
		
		
//kirim email ke penerima
$user = mysql_query("select a.*,b.full_name,b.email,c.report_name,c.keterangan from email_notification a,sec_users b,master_report c 
                     where a.user_id=b.user_id and a.report_id=22 and a.report_id=c.report_id");
while($r = mysql_fetch_array($user)){						    
	if($rows == '')
		$rows .=$r[email];
	else
		$rows .=','.$r[email];
	
	$keterangan = $r[keterangan];
	$subject = $r[report_name];
	$fullname = $r[full_name];
	$keterangan = $r[keterangan];
}

$awal = "Dear All. ".ucwords($full_name)."<br><br>$keterangan - ".date('M Y')."<Br><Br>";
$akhir = "<br><br>This report generated by system<br>Date Generate : ".date('d M Y H:m:s')." WIB<br>";
$from = $rows; 
$headers = "From: no-reply\r\n";
$headers .= "Reply-to: ".$from."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
$message = $awal.$message.$akhir;

//kirim email 
$mail_sent = @mail($rows, $subject, $message, $headers);


?>