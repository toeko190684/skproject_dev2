<?php
/*
created by toeko triyanto
index.php for security application 
*/
session_start();
require_once("../configuration/connection_inc.php");
require_once("../function/security.php");
require_once("../function/menu.php");
require_once("../function/get_sql.php");


//bagian pengecekan 
$auth = mysql_query("select * from sec_users where user_id='".$_SESSION['user_id']."' and password='".$_SESSION['password']."'");
$cauth = mysql_num_rows($auth);	

$secure = mysql_query("select distinct c.app_id,c.app_name from sec_user_rules a,sec_app_module b,sec_app c 
                      where a.module_id=b.module_id and b.app_id=c.app_id and a.user_id='".$_SESSION['user_id']."' and c.app_name='".$_SESSION['app']."'");

$rsecure = mysql_num_rows($secure);



if(($cauth == 0) and  ($rsecure == 0)){
	$ip = ip();
    $host = host($ip);
    echo "<p align='center'><br><bR><b>Percobaan Ilegal ke system</b><br><br>IP Anda : $ip<br>Nama Komputer : $host<br><br>
         <a href='../index.php'>Klik disini untuk Login</a></p>";
}else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Security</title>
		<link href='../bootstrap/css/bootstrap.min.css' rel='stylesheet'>
		<link rel="stylesheet" type="text/css" href="../jeasyui/themes/default/easyui.css">
	    <link rel="stylesheet" type="text/css" href="../jeasyui/themes/icon.css">
	    <link rel="stylesheet" type="text/css" href="=../jeasyui/demo/demo.css">
		<link rel="stylesheet" type="text/css" href="../media/css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../media/css/jquery.dataTables.tableTools.css">
		<script type="text/javascript" src="../jquery-1.10.2.js"></script>
	    <script type="text/javascript" src="../jeasyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../jeasyui/datagrid-detailview.js"></script>
		<script type="text/javascript" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../media/js/dataTables.tableTools.js"></script>
		<script type="text/javascript">
			$(window).load(function() { $("#loading").fadeOut("slow"); })
		</script>
		<style>
			.container{ font-size:12px;}
			#loading {
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 9999;
				background: url(../images/loading.gif) 50% 50% no-repeat #ede9df;
			}
			
				
			.footer {
			   position:fixed;
			   left:0px;
			   bottom:0px;
			   height:40px;
			   width:100%;
			   background:#999;
			}
		</style>
</head>
<body style='font-size:8px'>
<div class="row">
	<?php menu();?><br><br><br><br><div class="container">
	
	<?php include "content.php"; ?> 
				
<br><br></div><br><BR><br><br>
	<div id='footer'  class='footer'>
				<pre style='text-align:center'>Copyright <?php echo date('Y');?> PT Morinaga Kino Indonesia</pre>
	</div>
</div>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src='../bootstrap/js/bootstrap.min.js'></script>
</body>
</html>
<?php 
    mysql_free_result($auth);
	mysql_free_result($secure);
	mysql_close($conn);
	odbc_close($conn2);
} ?>
