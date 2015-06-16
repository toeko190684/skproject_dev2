<script>
$(document).ready(function(){
	$('#download').click(function(e){
	    var tgl_awal = "<?php echo $_POST[tgl_awal]; ?>";
		var tgl_akhir = "<?php echo $_POST[tgl_akhir]; ?>";
		$.post('modul/mod_acc_claim_report/get_acc_claim_report.php?data=acc_claim_report',
		                    { tgl_awal : tgl_awal,tgl_akhir : tgl_akhir },function(data){
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
  // Tampil master_nasional
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		if(($_POST[tgl_awal]=="")||($_POST[tgl_akhir]=="")){
			$page_query = mysql_query("SELECT count(*) FROM claim_request ORDER BY claim_number_system");			
		}else{
			$page_query = mysql_query("select count(*)  from claim_request where 
									   str_to_date(claim_date,'%m/%d/%Y') 
									   between str_to_date('$_POST[tgl_awal]','%m/%d/%Y') 
									   and str_to_date('$_POST[tgl_akhir]','%m/%d/%Y')");
		}
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<a class='btn btn-default' id='download' ><i class='icon-download-alt'></i></a>
			<form class='form-horizontal' method='post' action='?r=acc_claim_report'>
				<fieldset><legend>Acc Claim Report</legend>
					<label for='tgl'>Masukan Tanggal :</label>
						<input type='text' name='tgl_awal' id='tgl_awal' class='easyui-datebox input-small'>&nbsps/d&nbsp
						<input type='text' name='tgl_akhir' id='tgl_akhir' class='easyui-datebox input-small'>
						<input type=submit class='btn btn-primary' value='Cari' >
				</fieldset>
			  </form><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>No</td><td>Journal ID</td><td>Deskripsi</td><td>Kode Promo</td><td>CostOfPromoLeft</td>
					<td>Claim Number System</td><td>Claim Date</td><td>Claim Approved Ammount</td>
					<td>Total Claim Approved Ammount</td><td>Status</td>
				</tr>
			  </tdead>";

		if(($_POST[tgl_awal]=="")||($_POST[tgl_akhir]=="")){
			$sql = "select journal_id,deskripsi,kode_promo,costofpromo-(select sum(claim_approved_ammount) 
					from claim_request where kode_promo=a.kode_promo and status<>'rejected')as costofpromoleft,claim_number_system,
					claim_date,claim_approved_ammount, total_claim_approved_ammount,status  from claim_request a
					group by journal_id,deskripsi, kode_promo,claim_number_system,claim_approved_ammount,
					total_claim_approved_ammount,status  limit $start,$per_page";
			$tampil=mysql_query($sql);
		}else{
			$sql = "select journal_id,deskripsi,kode_promo,costofpromo-(select sum(claim_approved_ammount) from claim_request 
					where kode_promo=a.kode_promo and status<>'rejected')as costofpromoleft,claim_number_system, claim_date,
					claim_approved_ammount, total_claim_approved_ammount,status from claim_request a where 
					str_to_date(claim_date,'%m/%d/%Y') between
					str_to_date('$_POST[tgl_awal]','%m/%d/%Y') and 
					str_to_date('$_POST[tgl_akhir]','%m/%d/%Y') 
					group by journal_id,deskripsi, kode_promo,claim_number_system,claim_approved_ammount, 
					total_claim_approved_ammount,status limit $start,$per_page";
			$tampil=mysql_query($sql);
		}
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$no</td>
					<td>$r[journal_id]</td>
					<td>$r[deskripsi]</td>
					<td>$r[kode_promo]</td>
					<tD>".number_format($r[costofpromoleft],0,'.',',')."</td>
					<td>$r[claim_number_system]</td>
					<td>$r[claim_date]</td>
					<td>".number_format($r[claim_approved_ammount],0,'.',',')."</td>
					<tD>".number_format($r[total_claim_approved_ammount],0,'.',',')."</td>
					<td>$r[status]</td>
				</tr>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=acc_claim_report&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=acc_claim_report&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=acc_claim_report&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=acc_claim_report&page=$x'>Last</a></li></ul></div>";
		
		echo "<div id='tampil'></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahnasional":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=nasional&act=input'>
	          <fieldset><legend>Tambah Nasional Sales</legend>
			  <label>Kode nasional :</label>
			  <input type='text' name='nasional_id' required><br>
			  <label>Nama nasional :</label>
			  <input type='text' name='nasional_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi' size=60><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editnasional":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM nasional_sales WHERE nasional_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=nasional&act=update>
          <input type=hidden name=id value='$r[nasional_id]'>
          <fieldset><legend>Edit Nasional Sales </legend>
		  <label>Kode nasional :</label>
		  <input type='text' name='nasional_id' value='$r[nasional_id]' required><br>
		  <label>Nama nasional :</label>
		  <input type='text' name='nasional_name' value='$r[nasional_name]' required><br>
		  <label>Deskripsi :</label>
		  <input type='text' name='deskripsi' value='$r[deskripsi]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
