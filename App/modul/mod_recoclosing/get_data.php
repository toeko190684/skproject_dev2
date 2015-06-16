<?php
session_start();
ini_set('max_execution_time', 300);
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";

if ($_GET[data]=='master'){
    $result = array();
    $rs = mysql_query("SELECT count(*) FROM reco_request where upper(status)<>'REJECTED' and status=0");
    $row = mysql_fetch_row($rs);
    $result["draw"] = 1;
	$result["recordsTotal"] = $row[0];
	$result["recordsFiltered"] = $row[0];
     
    if(($_GET[grade]=="*")||($_GET[grade]=="**")||($_GET[grade]=="***")){
		$rs = mysql_query("SELECT kode_promo,title,cast(cost_of_promo as decimal(15,0))as cost_of_promo,
						   ifnull((select sum(claim_approved_ammount) from claim_request 
						   where kode_promo=a.kode_promo and upper(status)<>'rejected'),0) as claim, 
						   cost_of_promo-ifnull((select sum(claim_approved_ammount) 
						   from claim_request where kode_promo=a.kode_promo  and upper(status)<>'rejected'),0) as sisa 
						   FROM reco_request a where a.close=0");
    }else{
		$rs = mysql_query("SELECT kode_promo,title,cast(cost_of_promo as decimal(15,0))as cost_of_promo,
						   ifnull((select sum(claim_approved_ammount) from claim_request 
						   where kode_promo=a.kode_promo and upper(status)<>'rejected'),0) as claim, 
						   cost_of_promo-ifnull((select sum(claim_approved_ammount) 
						   from claim_request where kode_promo=a.kode_promo  and upper(status)<>'rejected'),0) as sisa 
						   FROM reco_request a, detail_reco_item b  where a.kode_promo=b.kode_reco and b.divisi_id='$_GET[divisi]'
						   and b.departemen_id='$_GET[departemen]' and  a.close=0");
	}	
    $items = array();
    while($row = mysql_fetch_object($rs)){
    array_push($items, $row);
    }
    $result["data"] = $items;
     
    echo json_encode($result);
}

if ($_GET[data]=='detail'){
	echo "<table cellpadding='5' cellspacing='0' border='0' style='padding-left:50px;'>";
	$rs = mysql_query("select * from claim_request where kode_promo='$_POST[kode_promo]'");
	$cek = mysql_num_rows($rs);
	if($cek>0){
	    $no =1;
		while($row = mysql_fetch_array($rs)){
			echo "<tr>
					<td>$no</td>
					<td>$row[claim_number_system]</td>
					<td>$row[claim_date]</td>
					<td>$row[deskripsi]</td>
					<td>".number_format($row[claim_approved_ammount],0,',','.')."</td>
					<tD>$row[status]</td>
				 </tr>";
			$no++;
		}
	}else{
		echo "<tr><td colspan='5'>Data tidak ditemukan..!</td></tr>";
	}
	echo "</table>";
}

if ($_GET[data]=='update'){
	$access = update_security();
    if($access=="allow"){
		$result = mysql_query("update reco_request set close=1 where kode_promo='$_POST[kode_promo]'");
		if($result){ echo "sukses"; }else{ echo "gagal"; }
	}else{
		msg_security();
	}
}
?>
