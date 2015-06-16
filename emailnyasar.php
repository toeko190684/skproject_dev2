<?php
include "configuration/connection_inc.php";

$sql = mysql_query("select * from email where status=''");
while($r = mysql_fetch_array($sql)){
	echo $r[id]."<br>";
}

?>