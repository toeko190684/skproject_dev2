<?php
include ("../configuration/connection_inc.php");

function cek_claim($journal_id){
	$sql = mysql_query("select * from claim_request where journal_id='$journal_id'");
	$r = mysql_num_rows($sql);
	if($r>0){
		return "ok";
	}else{
		return "no";
	}
}

$sql = "select * from ap_journal where journal_id in(
        select journal_id from ap_journal group by journal_id having count(journal_id)>1)";
$qsql = odbc_exec($conn2,$sql);
while($rsql = odbc_fetch_array($qsql)){
	$thn = substr($r[journal_date],0,4);
	$bln = substr($r[journal_date],5,2);
	$tgl = substr($r[journal_date],8,2);
	
	$nomor = odbc_exec($conn2, "select  convert(varchar,year(journal_date)) +
								right('0'+convert(varchar,month(journal_date)),2) +
								right('0'+convert(varchar,day(journal_date)),2)+
								right('0000' + convert(varchar,isnull(max(substring(journal_id,9,4)),0)+1),4)as nomor
								from ap_journal where year(journal_date)=$thn
								and month(journal_date)=$bln and day(journal_date)=$thn
								group by journal_date");
								
	$rnomor = odbc_fetch_array($nomor);
	$nomor  = $thn.$bln.$tgl.$rnomor[nomor];
	
	if (cek_claim($rsql[journal_id])=='no'){
		echo $rsql[journal_date]." = ".cek_claim($rsql[journal_id])."<br>";
	}else{
		
	}
}
?>