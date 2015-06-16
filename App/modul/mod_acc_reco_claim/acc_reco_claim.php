<script>
$(document).ready(function(){
	$('#download').click(function(e){
	    var bulan = $('#bulan').val();
		var tahun = $('#tahun').val();
		$.post('modul/mod_acc_reco_claim/get_acc_reco_claim.php?data=acc_reco_claim',
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
		echo "<form class='form-horizontal' method='post' action='?r=acc_reco_claim'>
				<fieldset><legend>Acc Reco vs Claim</legend>
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
						<input type=button class='btn btn-primary' id='download' value='Download' >
				</fieldset>
			  </form>";
		echo "<div id='tampil'></div>";
	}else{
		msg_security();
	}
    break;
}
?>
