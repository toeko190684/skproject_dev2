<?php
include "connection_inc.php";
$query = mysql_query("select count(*) as jml  from claim_request where claim_number_system='$_POST[claimnumbersystem]'");
	$r = mysql_fetch_array($query);
	echo $r[jml];
	
?>