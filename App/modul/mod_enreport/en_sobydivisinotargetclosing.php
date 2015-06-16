<?php
require_once("../../../configuration/connection_inc.php");
require_once("../../../function/email.php");
require_once("../../../function/menu.php");
require_once("get_sql.php");

$lebar = "style='width:100px;cellpadding:2px;color:#ffffff'";

$header = "<table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
             <tR style='background-color:#000F9F;border: solid 1px #ccc'>
		     <td align='center' $lebar>DIVISI</td>
			 <td align='center'  $lebar>SO ( * )</td>
			 <td align='center' $lebar>DO</td>
			 <td align='center' $lebar>DO vs SO %</td></tR>";
		
		$tgl_awal = date('Y-m-d',strtotime(date("Y")."-".(date("m")-1)."-01"));
		$tgl_akhir = date('Y-m-t',strtotime(date("Y")."-".(date("m")-1)."-".date("d")));
		
	    $so = 0;
		$do = 0;
		$netsales =0;
		$pembagi = 1000000;
		$sql = "select distinct category from product order by category";
		$qx = odbc_exec($conn2,$sql);
		while($rx = odbc_fetch_array($qx)){
			@$sodo = do_divisi($tgl_awal,$tgl_akhir,$rx[category])/so_divisi($tgl_awal,$tgl_akhir,$rx[category])*100;
			@$dol = do_divisi($tgl_awal,$tgl_akhir,$rx[category])/do_divisi($tgl_awal_lastyear,$tgl_akhir_lastyear,$rx[category])*100;
			
			@$so = $so + so_divisi($tgl_awal,$tgl_akhir,$rx[category])/$pembagi;
			@$do = $do + do_divisi($tgl_awal,$tgl_akhir,$rx[category])/$pembagi;
			
			$isi = $isi."<tR>
	    			<td>$rx[category]</td>
     			    <td align='right'>".number_format(so_divisi($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(do_divisi($tgl_awal,$tgl_akhir,$rx[category])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($sodo,2,',','.')." %</td>
				  </tr>"; 
		}
		@$total_sodo = $do / $so * 100;
		$footer = "<tr style='font-weight:bold'>
				<td>TOTAL</td>
				<td align='right'>".number_format($so,2,',','.')."</td>
				<td align='right'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
			 </tr></table>";
		
		$body = $header.$isi.$footer;
		
//kirim email ke penerima
$user = mysql_query("select a.*,b.full_name,b.email,c.report_name,c.keterangan from email_notification a,sec_users b,master_report c 
                     where a.user_id=b.user_id and a.report_id=11 and a.report_id=c.report_id");
while($r = mysql_fetch_array($user)){						    
	$awal = "";
	$awal = "Dear Mr. ".ucwords($r[full_name])."<br><br>$r[keterangan] - ".date('M Y',strtotime($tgl_awal))."<Br><Br>";
	$akhir = "<br><i>Note : </i>
			  <br><i>(*) SO Column including discontinued and non targeted product.</i>
			  <br><i>Report in million</i>
			  <br><i>Net Value = Quantity * Price list * 0.875 * 0.99 / 1.1 </i>
			  <br><i>Exclude Export Transaction</i>
			  <br><br>This report generated by system<br>Date Generate : ".date('d M Y H:m:s')." WIB<br>";
	$from = $r[email]; 
	$headers = "From: no-reply\r\n";
	$headers .= "Reply-to: ".$from."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
	$subject = $r[report_name];
	$message = $awal.$body.$akhir; 			    
	//kirim email 
	$mail_sent = @mail($from, $subject, $message, $headers);	
}
?>