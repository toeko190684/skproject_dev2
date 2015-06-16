<!DOCTYPE html>
<html lang="en">
<head>
<title>SKPROJECT - PT MORINAGA KINO INDONESIA</title>
	    <link rel="stylesheet" type="text/css" href="../media/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="../media/js/jquery.js"></script>
		<script type="text/javascript" src="../media/js/jquery.dataTables.js"></script>
		
		<script>
			$(document).ready(function(){
				$('#myTable').dataTable();
			});
		</script>
</head>
<body>
<?php
include "../configuration/connection_inc.php";
?>
<table id='myTable' class='display' celspacing=0 width=80%>
<thead><tr><th>area_id</th><th>area name</th></tr><thead>
<tbody>
<?php
$sql = mysql_query("select * from reco_request");
while($r = mysql_fetch_array($sql)){
	echo "<tr><td>$r[kode_promo]</td><td>$r[title]</td></tr>";
}
?>
</tbody>
</table>
</body>
</html>