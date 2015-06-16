<?php
/*
  created by toeko triyanto
  date : 21-10-2013
  this file is first user interface when user open this web application. 

*/
session_start();
require_once("configuration/connection_inc.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>SKPROJECT - PT MORINAGA KINO INDONESIA</title>
		<link href='bootstrap/css/bootstrap.min.css' rel='stylesheet'>
		<link rel="stylesheet" type="text/css" href="jeasyui/themes/default/easyui.css">
	    <link rel="stylesheet" type="text/css" href="jeasyui/themes/icon.css">
	    <link rel="stylesheet" type="text/css" href="=jeasyui/demo/demo.css">
		<script type="text/javascript" src="jquery-1.10.2.js"></script>
	    <script type="text/javascript" src="jeasyui/jquery.easyui.min.js"></script>
</head>
<body>
<div class="jumbotron" style="text-align:center">
	<div class="container" style="margin-top:30px">
	    <div class='page-header'><img src='images/logo_company.jpg' width='300px'></div>
		<br>
		<ul class="thumbnails">
		    <?php
			$sql = mysql_query("select * from sec_pro order by pro_name");
			while($r = mysql_fetch_array($sql)){
				echo "<li class='span3'>
					    <a href='login.php?r=$r[pro_id]&n=$r[pro_name]' class='thumbnail'>
							<img src='$r[image]' alt='$r[pro_name]' width='200' />  
					    </a>  
					  </li>";
			}
			?>
			<li class='span3'>
					<a href='../smsgateway' class='thumbnail'>
					<img src='../smsgateway/images/smsgateway.jpg' alt='SMS Gateway' width='200' /></a>
			</li>
			<li class='span3'>
					<a href='../payroll' class='thumbnail'>
					<img src='../payroll/images/payroll.jpg' alt='Payroll' width='200' /></a>
			</li>
		</ul>
		<ul class ="thumbnails">
						<li class='span3'>
					<a href='../itaset' class='thumbnail'>
					<img src='../itaset/images/Asset-logo.jpg' alt='IT Asset' width='200' /></a>
			</li>
			<li class='span3'>
					<a href='../utility' class='thumbnail' title="Utility">
					<img src='../utility/images/utility.png' alt='Utility' width='200' /></a>
			</li>
		</ul>
		<div class="page-footer"><pre>PT Morinaga Kino Indonesia.<br>Copyright <?php echo date('Y'); ?></pre>
		</div>
	</div>
</div>
    <script src='bootstrap/js/bootstrap.min.js'></script>
</body>
</html>