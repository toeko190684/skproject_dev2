<script>
$(document).ready(function(){
	$('#download').click(function(e){
	    var bulan = "<?php echo $_POST[bulan]; ?>";
		var tahun = "<?php echo $_POST[tahun]; ?>";
		$.post('modul/mod_acc_budget_report/get_acc_budget_report.php?data=acc_budget_report',
		                    { bulan : bulan,tahun : tahun },function(data){
				$('#tampil').html(data);	
				window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tabel').html()));
				e.preventDefault();
				$('#tampil').hide();
		});		
	});
});
</script>

<?php
ses_module();
switch($_GET[act]){
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
			$page_query = mysql_query("SELECT count(*) FROM master_budget");			
		}else{
			$page_query = mysql_query("SELECT count(*) FROM master_budget  
										WHERE bulan='$_POST[bulan]' and tahun='$_POST[tahun]' ");
		}
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<a class='btn btn-default' id='download' ><i class='icon-download-alt'></i></a>
			<form class='form-horizontal' method='post' action='?r=acc_budget_report'>
				<fieldset><legend>Acc Budget Report</legend>
					<label for='tgl'>Masukan Bulan & Tahun :</label>
						<select name='bulan' id='bulan' class='input-medium'>";
						$bln = mysql_query("select * from month");
						while($rbln = mysql_fetch_array($bln)){
							echo "<option value='$rbln[month_name]'>$rbln[month_name]</option>";
						}
		echo"			</select>&nbsp&nbsp-&nbsp&nbsp<select name='tahun' id='tahun' class='input-small'>";
						for($i=0;$i<5;$i++){
							$tahun = date('Y')-$i;
							echo "<option value='$tahun'>$tahun</option>";
						}
		echo "			</select>
						<input type=submit class='btn btn-primary' value='Cari' >
				</fieldset>
			  </form><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>No</td><td>Kode Budget</td><td>Keterangan</td><td>Bulan</td><td>Tahun</td>
					<td>Value</td><td>Used Ammount</td><td>Outstanding</td>
				</tr>
			  </tdead>";

		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
			$sql = "SELECT kode_budget,keterangan,bulan,tahun,value,ifnull((select sum(reco_request.cost_of_promo) 
					from detail_reco_item,reco_request where detail_reco_item.kode_reco=reco_request.kode_promo 
					and kode_budget=a.kode_budget and reco_request.status<>'rejected'),0)as used_ammount,
					value-ifnull((select sum(reco_request.cost_of_promo) from detail_reco_item,reco_request where 
					detail_reco_item.kode_reco=reco_request.kode_promo and kode_budget=a.kode_budget 
					and reco_request.status<>'rejected'),0)as outstanding FROM master_budget a 
					limit $start,$per_page";
			$tampil=mysql_query($sql);
		}else{
			$sql = "SELECT kode_budget,keterangan,bulan,tahun,value,ifnull((select sum(reco_request.cost_of_promo) 
					from detail_reco_item,reco_request where detail_reco_item.kode_reco=reco_request.kode_promo 
					and kode_budget=a.kode_budget and reco_request.status<>'rejected'),0)as used_ammount,
					value-ifnull((select sum(reco_request.cost_of_promo) from detail_reco_item,reco_request where 
					detail_reco_item.kode_reco=reco_request.kode_promo and kode_budget=a.kode_budget 
					and reco_request.status<>'rejected'),0)as outstanding FROM master_budget a 
					WHERE bulan='$_POST[bulan]' and tahun='$_POST[tahun]' limit $start,$per_page";
			$tampil=mysql_query($sql);
		}
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$no</td>
					<td>$r[kode_budget]</td>
					<td>$r[keterangan]</td>
					<td>$r[bulan]</td>
					<tD>$r[tahun]</td>
					<td>".number_format($r[value],0,'.',',')."</td>
					<td>".number_format($r[used_ammount],0,'.',',')."</td>
					<td>".number_format($r[outstanding],0,'.',',')."</td>
				</tr>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=acc_budget_report&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=acc_budget_report&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=acc_budget_report&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=acc_budget_report&page=$x'>Last</a></li></ul></div>";
		
		echo "<div id='tampil'></div>";
	}else{
		msg_security();
	}
    break;  
}
?>
