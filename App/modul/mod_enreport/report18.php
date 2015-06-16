<?php
require_once("../../../configuration/connection_inc.php");
require_once("../../../function/menu.php");
require_once("get_sql.php");

$lebar = "style='width:100px;cellpadding:2px;color: #ffffff;'";

$header = "<table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
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
					<td align='right' bgcolor='#FFD700'>".number_format(do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(so_divisi_min($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format((do_divisi_plus($tgl_awal,$tgl_akhir,$rx[category])+so_divisi_min($tgl_awal,$tgl_akhir,$rx[category]))/$pembagi,2,',','.')."</td>
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
				<td align='right' bgcolor='#FFD700'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($retur,2,',','.')."</td>
				<td align='right'>".number_format($netsales,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
				<td align='right'>".number_format($total_dob,2,',','.')." %</td>
				<td align='right'>".number_format($total_dot,2,',','.')." %</td>
				<td align='right'>".number_format($total_dol,2,',','.')." %</td>
			 </tr></table>";
		
		$body = $header.$isi.$footer;
		
//kirim email ke penerima
$user = mysql_query("select a.*,b.full_name,b.email,c.report_name,c.keterangan from email_notification a,sec_users b,master_report c 
                     where a.user_id=b.user_id and a.report_id=21 and a.report_id=c.report_id");
while($r = mysql_fetch_array($user)){						    
	if($rows == '')
		$rows .=$r[email];
	else
		$rows .=','.$r[email];
	
	$keterangan = $r[keterangan];
	$subject = $r[report_name];
}

$awal = "Dear All,<br><br>$keterangan - ".date('M Y')."<Br><Br>";
$akhir = "<br><i>Note : </i>
		  <br><i>(*) SO Column including discontinued and non targeted product. SO column show the real order from distributor</i>
		  <br><i>DO real transaction including </i>
		  <br><i>NET SALES = DO - Retur </i>
		  <br><i>Report in million</i>
		  <br><i>Net Value = Quantity * Price list * 0.875 * 0.99 / 1.1 </i>
		  <br><i>Exclude Export Transaction</i>
		  <br><br>This report generated by system<br>Date Generate : ".date('d M Y H:m:s')." WIB<br>";
$from = $rows; 
$headers = "From: no-reply\r\n";
$headers .= "Reply-to: ".$from."\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
$message = $awal.$body.$akhir; 	
//echo $message;
//kirim email 
$mail_sent = @mail($rows, $subject, $message, $headers);
?>