<?php
require_once("../../../configuration/connection_inc.php");
require_once("../../../function/email.php");
require_once("../../../function/menu.php");

$sql = mysql_query("select * from master_report");
while($r = mysql_fetch_array($sql)){
	$time = date('H:m');
	if($time == $r[jam]){
		include "$r[file]";
	}	
}
?>