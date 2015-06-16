<?php
$aksi="modul/mod_suratprogram/aksi_suratprogram.php";
ses_module();
switch($_GET[act]){
  // Tampil master_suratprogram
  default:
  $access = read_security();
  if($access=="allow"){
	   echo " <form class='form-horizontal' method='post' >
	          <fieldset><legend>Surat Program</legend>
			  <div class='span6'>
					  <div class='control-group'>
							<label class='control-label' for='no_reco'>Masukan Nomor Reco : </label>
								<input type='text' id='no_reco' name='no_reco' class='input-large'>
								<input type='submit' id='cari' class='btn btn-primary' value='Cari'>
					  </div>
			  </div>
			  </fieldset></form>";
		echo "<div id='suratprogram'>
					<table class='table table-condensed table-stripped table-hover'>
					<tr class='success'><td>No.</td><td>Kode Promo</td><tD>Nama File</td><tD>Type</td><tD>Size</td><td>Download</td></tr>";
					$i = 1;
					$sql = mysql_query("select * from detail_reco_attachment where kode_promo ='$_POST[no_reco]'");
					$cek = mysql_num_rows($sql);
					if($cek>0){
						while($r = mysql_fetch_array($sql)){
							echo "<tr>
										<td>$i</td>
										<td>$r[kode_promo]</td>
									    <td>$r[nama_file]</td>
										<td>$r[type]</td>
										<td>$r[size]</td>
										<td><a href='../attachment/$r[nama_file]'>$r[nama_file]</a></td>
								  </tr>";
							$i++;
						}
					}else{
						echo "<tr><td colspan='6' align='center'>Tidak ada data ditemukan</td></tR>";
					}
		echo "		</table>
			  </div>";
			  

	}else{
		msg_security();
	}
    break;

}
?>
