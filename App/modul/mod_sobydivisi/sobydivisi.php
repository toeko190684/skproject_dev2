<?php
$aksi="modul/mod_sobydivisi/aksi_sobydivisi.php";
ses_module();
switch($_GET[act]){
  // Tampil master_sobydivisi
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<form class='form-inline' method='post' action='?r=sobydivisi'>
				<fieldset><legend>Laporan By Divisi</legend>
				    <label>Tgl Awal : </label><input type='text' name='tgl_awal' class='easyui-datebox input-small' placeholder='Email'>
				    <label>Tgl Akhir : </label><input type='text' name='tgl_akhir' class='easyui-datebox input-small' placeholder='Password'>
				    <button type='submit' class='btn btn-primary btn-small'>Kirim</button>
				</fieldset>
			  </form>";  
		echo "<table class='table table-condensed table-hover table-bordered'><tR class='success'>
		     <td>DIVISI</td><td>TARGET</td><td>LAST YEAR</td><td>SO</td><td>DO</td><td>SO vs DO %</td>
			 <td>DO vs Target</td><td>DO vs LAST YEAR</td></tR>";
		
		$tgl_awal = date('Y-m-d',strtotime($_POST[tgl_awal]));
		$tgl_akhir = date('Y-m-d',strtotime($_POST[tgl_akhir]));
		
		$tgl_awal_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_awal)));
		$tgl_akhir_lastyear = date('Y-m-d',strtotime('-1 year',strtotime($tgl_akhir)));
		
		if(($_POST[tgl_awal]=="")and($_POST[tgl_akhir]=="")){
		    echo "<tr><td colspan=8>Tidak ada data ditemukan ..!</td></tr>";
		}else{
			$so = 0;
			$do = 0;
			$netsales =0;
			$sql = "select distinct category from product order by category";
			$qx = odbc_exec($conn2,$sql);
			while($rx = odbc_fetch_array($qx)){
				
				@$sodo = do_divisi($tgl_awal,$tgl_akhir,$rx[category])/so_divisi($tgl_awal,$tgl_akhir,$rx[category])*100;
				@$dot = do_divisi($tgl_awal,$tgl_akhir,$rx[category])/ target($tgl_akhir,$rx[category])*100;
				@$dol = do_divisi($tgl_awal,$tgl_akhir,$rx[category])/ target($tgl_akhir_lastyear,$rx[category])*100;
				
				@$target = $target + target($tgl_akhir,$rx[category]);
				@$lastyear = $lastyear + target($tgl_akhir_lastyear,$rx[category]);
				@$so = $so + so_divisi($tgl_awal,$tgl_akhir,$rx[category]);
				@$do = $do + do_divisi($tgl_awal,$tgl_akhir,$rx[category]);
				
				echo "<tR>
						  <td>$rx[category]</td>
						  <td>".number_format(target($tgl_akhir,$rx[category]),2,',','.')."</td>
						  <td>".number_format(target($tgl_akhir_lastyear,$rx[category]),2,',','.')."</td>
					      <td>".number_format(so_divisi($tgl_awal,$tgl_akhir,$rx[category]),2,',','.')."</td>
						  <td>".number_format(do_divisi($tgl_awal,$tgl_akhir,$rx[category]),2,',','.')."</td>
						  <td>".number_format($sodo,2,',','.')." %</td>
						  <td>".number_format($dot,2,',','.')."</td>
						  <td>".number_format($dol,2,',','.')."</td>
					  </tr>"; 
			}
			@$total_sodo = $do / $so * 100;
			@$total_dot = $do / $target * 100;
			@$total_dol = $do / $lastyear * 100;

			echo "<tr style='font-weight:bold'>
					<td>TOTAL</td>
					<td>".number_format($target,2,',','.')."</td>
					<td>".number_format($lastyear,2,',','.')."</td>
					<td>".number_format($so,2,',','.')."</td>
					<td>".number_format($do,2,',','.')."</td>
					<td>".number_format($total_sodo,2,',','.')." %</td>
					<td>".number_format($total_dot,2,',','.')."</td>
					<td>".number_format($total_dol,2,',','.')."</td>
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
