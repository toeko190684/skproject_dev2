<?php
	include "../configuration/connection_inc.php";
	$sql = "select journal_id,count(journal_id) from ap_journal where year(journal_date)=2014
			and month(journal_date)=1 group by journal_id having (count(journal_id)>1) order by journal_id";
	$rsql = odbc_exec($conn2,$sql);
	while($r = odbc_fetch_array($rsql)){
		echo $r[journal_id]."<br>";
	}
?>