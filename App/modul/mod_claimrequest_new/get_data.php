<?php
session_start();

include "../../../configuration/connection_inc.php";

if ($_GET[data]=='vendor'){
	$rows = array();	
	$sql = mysql_query("select * from vendor order by vendor_id"); 
	while($r = mysql_fetch_assoc($sql)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_reco'){
    if($_GET[id]==''){
		$sth = mysql_query("SELECT kode_promo,title from reco_request where status='approved' 
		                   and kode_promo not in(select kode_promo from claim_request)and close=0 order by kode_promo");
	}else{
		$sth = mysql_query("SELECT kode_promo,title from reco_request a where a.status='approved' and  a.distributor_id='$_GET[id]' 
		                   and a.cost_of_promo>(select sum(claim_approved_ammount) from claim_request where 
						   kode_promo=a.kode_promo and status<>'rejected')and close=0");
	}
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	mysql_free_result($sth);
	print json_encode($rows);
}

if($_GET[data]=='json_detailreco'){
    if($_POST[id]==''){
		$sth = mysql_query("SELECT kode_promo,title from reco_request order by kode_promo");
		$rows = array();
		while($r = mysql_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		print json_encode($rows);
	}else{
	    $kode_reco = trim($_POST[id]);
		$sth = mysql_query("SELECT * from reco_request where kode_promo='$kode_reco'");	
		$rows = array();
		while($r = mysql_fetch_assoc($sth)){
			$rows = $r;
		}
		print json_encode($rows);
	}	
}

if($_GET[data]=='claimnumber'){
	$tgl = explode("/",$_POST[claim_date]);
	$bln = $tgl[0];
	$thn = substr($tgl[2],2,4);
	$sql = mysql_query("SELECT substring(reverse(substring(reverse(a.claim_number_system),1,8)),5,4) as number 
	                   FROM `claim_request` a, detail_reco_item b where a.kode_promo=b.kode_reco and b.divisi_id='$_POST[divisi_id]' 
					   and b.departemen_id='$_POST[departemen]' and substring(reverse(substring(reverse(a.claim_number_system),1,8)),3,2)=$bln 
					   and substring(reverse(substring(reverse(a.claim_number_system),1,8)),1,2)=$thn
					   order by substring(reverse(substring(reverse(a.claim_number_system),1,8)),5,4) desc limit 1");
	$r = mysql_fetch_array($sql);
	if($r[number]==''){
		$number = 1;
	}else{
		$number = $r[number];
	}
	
	if(strlen($number)==1){
		$number = '000'.$number;
	}else if(strlen($number)==2){
	    $number = '00'.$number;
	}else if($trlen($number)==3){
	    $number = '0'.$number;
	}else if(strlen($number)==4){
	    $number = $number;
	}
	
	$nomor = 'CL\MKI\'.divisi_id.'\'.department_id.'\'.$thn.$bln.number;
	print $nomor;
}

if($_GET[data]=='json_btndetailreco'){
        $sth = mysql_query("select distinct d.kode_promo,d.tgl_promo,d.area_id,d.distributor_id,d.grouppromo_id,d.promotype_id,
		                    d.class_id, d.title,d.tgl_awal,d.tgl_akhir,d.total_sales_target,d.background,d.promo_mechanisme,
							d.claim_mechanisme, d.claimtradeoff,d.cost_of_promo,d.typeofcost,d.cost_rasio,d.status,
							d.created_by,d.last_update,e.area_name, f.distributor_name,g.grouppromo_name,h.promotype_name,
							i.class_name from reco_request d,area e,distributor f, master_grouppromo g,master_promotype h,
							master_class i where d.area_id=e.area_id and d.distributor_id=f.distributor_id and d.grouppromo_id=g.grouppromo_id 
							and d.promotype_id=h.promotype_id and d.class_id=i.class_id and h.promotype_id=i.promotype_id and 
							d.kode_promo ='$_GET[id]' ");
							
		$r = mysql_fetch_array($sth);
		echo "<table>
				  <tr><td>Kode Reco</td><td>&nbsp:&nbsp&nbsp&nbsp</td><td>$r[kode_promo]</td></tr>
				  <tr><td>Tgl Reco</td><td>&nbsp:&nbsp</td><td>$r[tgl_promo]</td></tr>
				  <tr><td>Area</td><td>&nbsp:&nbsp</td><td>$r[area_name]</td></tr>
				  <tr><td>Distributor</td><td>&nbsp:&nbsp</td><td>$r[distributor_name]</td></tr>
				  <tr><td>Promo Group</td><td>&nbsp:&nbsp</td><td>$r[grouppromo_name]</td></tr>
				  <tr><td>Promo Type</td><td>&nbsp:&nbsp</td><td>$r[promotype_name]</td></tr>
				  <tr><td>Class</td><td>&nbsp:&nbsp</td><td>$r[class_name]</td></tr>
				  <tr><td>Title/Theme</td><td>&nbsp:&nbsp</td><td>$r[title]</td></tr>
				  <tr><td>Periode</td><td>&nbsp:&nbsp</td><td>$r[tgl_awal] s/d $r[tgl_akhir]</td></tr>
				  <tr><td>Total Sales Target</td><td>&nbsp:&nbsp</td><td>".number_format($r[total_sales_target],2,'.',',')."</td></tr>
				  <tr><td>Background</td><td>&nbsp:&nbsp</td><td>$r[background]</td></tr>
				  <tr><td>Promo Mechanisme</td><td>&nbsp:&nbsp</td><td>$r[promo_mechanisme]</td></tr>
				  <tr><td>Claim Mechanisme</td><td>&nbsp:&nbsp</td><td>$r[claim_mechanisme]</td></tr>
				  <tr><td>Claim Trade Off</td><td>&nbsp:&nbsp</td><td>$r[claimtradeoff]</td></tr>
				  <tr><td>Cost of Promo</td><td>&nbsp:&nbsp</td><td>".number_format($r[cost_of_promo],2,'.',',')."</td></tr>
				  <tr><td>Type of Cost</td><td>&nbsp:&nbsp</td><td>$r[typeofcost]</td></tr>
				  <tr><td>Cost Rasio</td><td>&nbsp:&nbsp</td><td>".number_format($r[cost_rasio],2,'.',',')." %</td></tr>
			  </table>";
}

if($_GET[data]=='simpan_claim'){
	$tgl = date('d-m-Y H:m:s');
    $cek = mysql_query("select * from claim_request where claim_number_system='$_POST[claimnumbersystem]'");
	$rcek = mysql_num_rows($cek);

	$sql = mysql_query("INSERT INTO claim_request (claim_number_system,
				                                 claim_number_dist,
												 distributor_id,
												 claim_date,
												 kode_promo,
												 po_so_number,
												 ppn,
												 nomor_faktur_pajak,
												 deskripsi,
												 costofpromo,
												 costofpromoleft,
												 claim_approved_ammount,
												 total_claim_approved_ammount,
												 coa,
												 vendor_id,
												 status,
												 created_by,
												 last_update) 
										VALUES ('$_POST[claimnumbersystem]', 
										        '$_POST[claimnumberdist]',
												'$_POST[distributor]',
												'$_POST[claimdate]',
												'$_POST[kodereco]',
												'$_POST[posonumber]',
												'$_POST[ppn]',
												'$_POST[nomorfakturpajak]',
												'$_POST[deskripsi]',
												'$_POST[costofpromo]',
												'$_POST[costofpromoleft]',
												'$_POST[claimapprovedamount]',
												'$_POST[totalclaimapprovedamount]',
												'$_POST[coa]',
												'$_POST[vendor_id]',
												'pending',
												'$_POST[user_id]',
												'$tgl')");
											  
	//jika berhasil disimpan ke tabel claim_request maka update tabel master setup
	if($sql){
	    mysql_query("update master_setup set number=number +1 where divisi_id='$_POST[divisi_id]' and department_id='$_POST[department_id]' 
					  and module_id='$_POST[module_id]'");  
		echo "Nomor Claim : $_POST[claimnumbersystem] berhasil disimpan..!";
	}else{
	    echo "Nomor Claim : $_POST[claimnumbersystem] gagal disimpan..!";
	}
}

if($_GET[data]=='json_claim'){
    $sth = mysql_query("SELECT * from claim_request where claim_number_system='$_POST[id]'");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows = $r;
	}
	print json_encode($rows);
}


?>
