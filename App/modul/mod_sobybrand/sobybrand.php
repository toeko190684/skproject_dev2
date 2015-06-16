<?php
$aksi="modul/mod_sobybrand/aksi_sobybrand.php";
ses_module();
switch($_GET[act]){
  // Tampil master_sobydivisi
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<form class='form-inline' method='post' action='?r=sobybrand'>
				<fieldset><legend>Laporan By Brand</legend>
				    <label>Tgl Awal : </label><input type='text' name='tgl_awal' class='easyui-datebox input-small' placeholder='Email'>
				    <label>Tgl Akhir : </label><input type='text' name='tgl_akhir' class='easyui-datebox input-small' placeholder='Password'>
				    <button type='submit' class='btn btn-primary btn-small'>Kirim</button>
				</fieldset>
			  </form>";
			  
		echo "<table class='table table-condensed table-hover table-bordered'><tR class='success'>
		     <td>BRAND</td><td>SO</td><td>DO</td><td>SO vs DO %</td></tR>";
		
		$tgl_awal = date('Y-m-d',strtotime($_POST[tgl_awal]));
		$tgl_akhir = date('Y-m-d',strtotime($_POST[tgl_akhir]));
		
		if(($_POST[tgl_awal]=="")and($_POST[tgl_akhir]=="")){
		    echo "<tr><td colspan=5>Tidak ada data ditemukan ..!</td></tr>";
		}else{
		    $so = 0;
			$do = 0;
			$netsales =0;
			$sql = "select distinct brand from product order by brand";
			$qx = odbc_exec($conn2,$sql);
			while($rx = odbc_fetch_array($qx)){
				@$sodo = do_brand($tgl_awal,$tgl_akhir,$rx[brand])/so_brand($tgl_awal,$tgl_akhir,$rx[brand])*100;
				@$so = $so + so_brand($tgl_awal,$tgl_akhir,$rx[brand]);
				@$do = $do + do_brand($tgl_awal,$tgl_akhir,$rx[brand]);
				//@$netsales = $netsales + netsales_brand($tgl_awal,$tgl_akhir,$rx[brand]);
				echo "<tR>
						  <td>$rx[brand]</td>
					      <td>".number_format(so_brand($tgl_awal,$tgl_akhir,$rx[brand]),2,',','.')."</td>
						  <td>".number_format(do_brand($tgl_awal,$tgl_akhir,$rx[brand]),2,',','.')."</td>
						  <td>".number_format($sodo,2,',','.')." %</td>
					  </tr>"; 
			}
			@$total = $do / $so * 100;

			echo "<tr style='font-weight:bold'>
					<td>TOTAL</td>
					<td>".number_format($so,2,',','.')."</td>
					<td>".number_format($do,2,',','.')."</td>
					<td>".number_format($total,2,',','.')." %</td>
				 </tr>";
		}	          
		echo "</table>";
	}else{
		msg_security();
	}
  break;

  case "tambahsobydivisi":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=sobydivisi&act=input'>
	          <fieldset><legend>Tambah sobydivisi Sales</legend>
			  <label>Kode sobydivisi :</label>
			  <input type='text' name='sobydivisi_id' required><br>
			  <label>Nama sobydivisi :</label>
			  <input type='text' name='sobydivisi_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi' size=60><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editsobydivisi":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM sobydivisi_sales WHERE sobydivisi_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=sobydivisi&act=update>
          <input type=hidden name=id value='$r[sobydivisi_id]'>
          <fieldset><legend>Edit sobydivisi Sales </legend>
		  <label>Kode sobydivisi :</label>
		  <input type='text' name='sobydivisi_id' value='$r[sobydivisi_id]' required><br>
		  <label>Nama sobydivisi :</label>
		  <input type='text' name='sobydivisi_name' value='$r[sobydivisi_name]' required><br>
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
