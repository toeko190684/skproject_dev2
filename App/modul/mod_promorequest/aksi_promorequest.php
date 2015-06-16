<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='promorequest' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM reco_request WHERE kode_promo ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='promorequest' AND $act=='input'){
  $sql1 = mysql_query("select * from reco_request where kode_promo='$_POST[kode_promo]'");
	$cek = mysql_num_rows($sql1);
	$tgl = date('d M Y H:m:s');
	if($cek>0){
			echo "update data";
	}else{
			$sql = mysql_query("insert into reco_request(kode_promo,
														tgl_promo,
														area_id,
														distributor_id,
														grouppromo_id,
														promotype_id,
														class_id,
														title,
														tgl_awal,
														tgl_akhir,
														total_sales_target,
														background,
														promo_mechanisme,
														claim_mechanisme,
														claimtradeoff,
														cost_of_promo,
														cost_rasio,
														status,
														created_by,
														last_update)
												values('$_POST[kode_promo]',
														'$_POST[tgl_promo]',
														'$_POST[area]',
														'$_POST[distributor]',
														'$_POST[grouppromo]',
														'$_POST[promotype]',
														'$_POST[classid]',
														'$_POST[title]',
														'$_POST[tgl_awal]',
														'$_POST[tgl_akhir]',
														$_POST[total_sales_target],
														'$_POST[background]',
														'$_POST[promo_mechanishm]',
														'$_POST[claim_mechanishm]',
														'$_POST[claimtradeoff]',
														$_POST[costofpromo],
														$_POST[costrasio],
														'pending',
														'$_POST[user_id]',
														'$tgl')");
														
			if($sql){ 
				//update running number ditabel master setup
				mysql_query("update master_setup set number=number+1 where module_id = $_POST[id]"); 
				
				//masukan temp_detail_reco_target ke tabel detail_reco_target
				mysql_query("insert into detail_reco_target select * from temp_detail_reco_target where kode_reco='$_POST[kode_promo]'");
				
				//masukan temp_detail_reco_item ke tabel detail_reco_item
				mysql_query("insert into detail_reco_item select * from temp_detail_reco_item where kode_reco='$_POST[kode_promo]'");
				
				//masukan temp-detail_reco_budget ke tabel detail_reco_budget
				mysql_query("insert into detail_reco_budget select * from temp_detail_reco_budget where kode_reco='$_POST[kode_promo]'");
				
				//cari username pemilik budget
				$sql = mysql_query("select a.*,b.user,c.email from detail_reco_budget a,master_budget b,sec_users c 
				                   where a.kode_budget=b.kode_budget and b.user=c.user_id and kode_reco='$_POST[kode_promo]'");
				while($r = mysql_fetch_array($sql)){
					//kirim email untuk 
				}
				
				//echo "Reco request nomor : $_POST[kode_promo] berhasil ditambahkan..!!"; 
			}else{ 
				//echo "Gagal menyimpan Reco request nomor : $_POST[kode_promo]..!";
			}
	}
    header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='promo' AND $act=='update'){
  mysql_query("UPDATE master_promotype SET promotype_name = '$_POST[promotype_name]',
                                           subdepartemen_id = '$_POST[subdepartemen_id]'  
                          WHERE promotype_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
