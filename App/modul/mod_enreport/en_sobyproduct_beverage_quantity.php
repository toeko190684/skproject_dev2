<?php
require_once("../../../configuration/connection_inc.php");
require_once("../../../function/email.php");
require_once("../../../function/menu.php");
require_once("get_sql.php");

$lebar = "style='width:120px;cellpadding:2px;color:#ffffff'";
$lebar2 = "style='width:350px;cellpadding:2px;color:#ffffff'";

$header = "<table style='border: solid 1px #cccccc;border-padding:5px;font-size:12;font-type:arial'>
             <tR style='background-color:#000F9F;border: solid 1px #ccc'>
		     <td align='center' $lebar>PRODUCT ID</td>
			 <td align='center'  $lebar2>PRODUCT NAME</td>
			 <td align='center'  $lebar>SO ( * )</td>
			 <td align='center' $lebar>DO</td>
			 <td align='center' $lebar>DO vs SO %</td>
			 <td align='center' $lebar>STOCK</td>";
		
		$tgl_awal = substr(date('Y-m-d',strtotime(date('m/d/Y'))),0,8).'01';
		$tgl_akhir = date('Y-m-d',strtotime(date('m/d/Y')));
		
		$tgl_awal_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_awal)));
		$tgl_akhir_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_akhir)));
		
	    $so = 0;
		$do = 0;
		$netsales =0;
		$pembagi = 1;
		$sql = "select distinct a.product_id from sales_order_item a,sales_order b 
		        where a.quotation_id=b.quotation_id and  CONVERT(VARCHAR(10), b.quotation_date, 120) 
                between '$tgl_awal' and '$tgl_akhir' and product_id<>'' and product_id in
                (select product_id from product where category='beverage')	 order by a.product_id";
		$qx = odbc_exec($conn2,$sql);
		while($rx = odbc_fetch_array($qx)){
			$product = "select product_name from product where product_id='$rx[product_id]'";
			$qproduct = odbc_exec($conn2,$product);
			$rproduct = odbc_fetch_array($qproduct);
			@$sodo = do_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])/so_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])*100;
			
			@$so = $so + so_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])/$pembagi;
			@$do = $do + do_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])/$pembagi;
			@$stok = $stok + stok($rx[product_id]);
			
			$isi = $isi."<tR>
	    			<td>$rx[product_id]</td>
					<td>$rproduct[product_name]</td>
     			    <td align='right'>".number_format(so_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format(do_product_quantity($tgl_awal,$tgl_akhir,$rx[product_id])/$pembagi,2,',','.')."</td>
					<td align='right'>".number_format($sodo,2,',','.')." %</td>
					<td align='right'>".number_format(stok($rx[product_id]),2,',','.')."</td>
				  </tr><tr><td colspan=8></td></tr>"; 
		}
		@$total_sodo = $do / $so * 100;

		$footer = "<tr style='font-weight:bold'>
				<td colspan=2>TOTAL</td>
				<td align='right'>".number_format($so,2,',','.')."</td>
				<td align='right'>".number_format($do,2,',','.')."</td>
				<td align='right'>".number_format($total_sodo,2,',','.')." %</td>
				<td align='right'>".number_format($stok,2,',','.')."</td>
			 </tr></table>";
		
		$body = $header.$isi.$footer;

//kirim email ke penerima
$user = mysql_query("select a.*,b.full_name,b.email,c.report_name,c.keterangan from email_notification a,sec_users b,master_report c 
                    where a.user_id=b.user_id and a.report_id=20 and a.report_id=c.report_id");
while($r = mysql_fetch_array($user)){						    
	$awal = "";
	$awal = "Dear Mr. ".ucwords($r[full_name])."<br><br>$r[keterangan] - ".date('M Y')."<Br><Br>";
	$akhir = "<br><i>Note : </i>
			  <br><i>(*) SO Column including discontinued and non targeted product.</i>
			  <br><i>Report in Quantity</i>
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