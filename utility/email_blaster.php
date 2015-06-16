<?php
require_once("../configuration/connection_inc.php");

											
$sql = mysql_query("select * from email where status=''");
while($r = mysql_fetch_array($sql)){
    
	$mail_sent = @mail($r[to_cc], $r[subject], base64_decode($r[body]), $r[header]);
	
	$tgl = date('Y-m-d H:i:s');
	
	if($mail_sent){
		mysql_query("update email set status='ok', date = '$tgl' where id=$r[id]");
	}
}
echo date('Y-m-d H:i:s');
mysql_close($conn);
?>