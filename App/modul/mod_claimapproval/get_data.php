<?php
session_start();

include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";

if ($_GET[data]=='tabel'){
	echo "<table class='table table-condensed table-hover table-bordered' >
	         <tdead>
				<tr class='success'>
				<td>No</tD><td>Claim Number System</td><td>Claim Number Dist.</td><td>Distributor</td><td>Claim Date</td>
				<tD>Kode Reco</td><td>Deskripsi</td><td>Cost Of Promo</td>
				<td>Cost Of Promo Left</td><td>Claim Approved Ammount</td><tD>Journal ID</td><td>Status</td><td>Aksi</td>
				</tr>
		  </tdead>";
				    
	if($_POST[id]==''){							
		$tampil=mysql_query("SELECT * from claim_request where year(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[tahun] 
		                    and month(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[bulan] 
							and status='$_POST[status]' ORDER BY claim_date desc");
	}else{
		$tampil=mysql_query("SELECT * from claim_request where year(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[tahun] 
		                    and month(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[bulan] 
							and status='$_POST[status]' and $_POST[key] like '%$_POST[id]%' ORDER BY claim_date desc");
	}
	
	$no = 1;
	$no = $no+$start;
	$cek = mysql_num_rows($tampil);
	if($cek>0){
		while ($r=mysql_fetch_array($tampil)){
			if($r[status]=='pending'){ $color = 'orange'; }
			else if($r[status]=='approved'){ $color = 'green'; }
			else{ $color = 'red';}
									  
			//cari nilai cost of promo left
			$sisa = mysql_query("select a.cost_of_promo-sum(b.claim_approved_ammount) as sisa from reco_request a,
		                      claim_request b where a.kode_promo=b.kode_promo and a.kode_promo='$r[kode_promo]' 
							  and b.status<>'rejected'");
			$rsisa = mysql_fetch_array($sisa);
			
			
			$link = "<a href='?r=claimapproval&act=editclaimapproval&id=$r[claim_number_system]' target='_blank'>View</a>";
												  
			echo "<tr>
					<tD>$no</td>
					<td>$r[claim_number_system]</td>
					<td>$r[claim_number_dist]</td>
					<td>$r[distributor_id]</td>
					<td>$r[claim_date]</td>
					<td>$r[kode_promo]</td>
					<td>$r[deskripsi]</td>
					<td>".number_format($r[costofpromo],2,',','.')."</td>
					<td>".number_format($rsisa[sisa],2,',','.')."</td>
					<td>".number_format($r[claim_approved_ammount],2,',','.')."</td>
					<tD>$r[journal_id]</td>
					<td><font color=$color>$r[status]</font></td>
			        <td>$link</td>
				</tr>";
			$no++;
		}
	}else{
		echo "<tr><td colspan='12'>Tidak ada data ditemukan ..!</td></tr>";	
	}
	
	echo "</table>";
}

if ($_GET[data]=='all'){
	echo "<table class='table table-condensed table-hover table-bordered' >
	         <tdead>
				<tr class='success'>
				<td>No</tD><td>Claim Number System</td><td>Claim Number Dist.</td><td>Distributor</td><td>Claim Date</td>
				<tD>Kode Reco</td><td>Deskripsi</td><td>Cost Of Promo</td>
				<td>Cost Of Promo Left</td><td>Claim Approved Ammount</td><tD>Journal ID</td><td>Status</td><td>Aksi</td>
				</tr>
		  </tdead>";
				    
	if($_POST[id]==''){							
		$tampil=mysql_query("SELECT * from claim_request where year(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[tahun] 
		                    and month(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[bulan] 
							and status='$_POST[status]' ORDER BY claim_date desc");
	}else{
		$tampil=mysql_query("SELECT * from claim_request where year(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[tahun] 
		                    and month(DATE_FORMAT(STR_TO_DATE(claim_date, '%m/%d/%Y'),'%Y-%m-%d'))=$_POST[bulan] 
							and status='$_POST[status]' and $_POST[key]='$_POST[id]' ORDER BY claim_date desc");
	}
	
	$no = 1;
	$no = $no+$start;
	$cek = mysql_num_rows($tampil);
	if($cek>0){
		while ($r=mysql_fetch_array($tampil)){
			if($r[status]=='pending'){ $color = 'orange'; }
			else if($r[status]=='approved'){ $color = 'green'; }
			else{ $color = 'red';}
									  
			//cari nilai cost of promo left
			$sisa = mysql_query("select a.cost_of_promo-sum(b.claim_approved_ammount) as sisa from reco_request a,
		                      claim_request b where a.kode_promo=b.kode_promo and a.kode_promo='$r[kode_promo]' 
							  and b.status<>'rejected'");
			$rsisa = mysql_fetch_array($sisa);
						  
			if($r[status]<>'pending'){ $link='Ok';}else{
				$link = "<a href='?r=claimapproval&act=editclaimapproval&id=$r[claim_number_system]' target='_blank'>View</a>";
			}
									  
			echo "<tr>
					<tD>$no</td>
					<td>$r[claim_number_system]</td>
					<td>$r[claim_number_dist]</td>
					<td>$r[distributor_id]</td>
					<td>$r[claim_date]</td>
					<td>$r[kode_promo]</td>
					<td>$r[deskripsi]</td>
					<td>".number_format($r[costofpromo],2,',','.')."</td>
					<td>".number_format($rsisa[sisa],2,',','.')."</td>
					<td>".number_format($r[claim_approved_ammount],2,',','.')."</td>
					<tD>$r[journal_id]</td>
					<td><font color=$color>$r[status]</font></td>
			        <td>$link</td>
				</tr>";
			$no++;
		}
	}else{
		echo "<tr><td colspan='12'>Tidak ada data ditemukan ..!</td></tr>";	
	}
	
	echo "</table>";
}
?>
