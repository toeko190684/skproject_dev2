<?php
$aksi="modul/mod_transaksiasset/aksi_transaksiasset.php";
ses_module();
switch($_GET[act]){
  // Tampil transaksi_asset
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM transaksi_asset ORDER BY transaksi_id");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Transaksi</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=transaksiasset&act=tambahtransaksi_asset';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Transaksi ID</td><td>Tgl Transaksi</td><td>Asset ID</td><td>Pengguna</td><td>User ID</td><td>Keterangan</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("SELECT * from transaksi_asset ORDER BY transaksi_id limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
		            <td>$r[transaksi_id]</td>
					<td>$r[tgl_transaksi]</td>
					<td>$r[asset_id]</td>
					<td>$r[touser]</td>
					<td>$r[user_id]</td>
					<td>$r[keterangan]</td>
		            <td><a href='index.php?r=transaksiasset&act=edittransaksi_asset&id=$r[transaksi_id]'>Edit</a> | 
			              <a href='$aksi?r=transaksiasset&act=hapus&id=$r[transaksi_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=transaksi_asset&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=transaksi_asset&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=transaksi_asset&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=transaksi_asset&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahtransaksi_asset":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=transaksiasset&act=input'>
	          <fieldset><legend>Tambah Transaksi</legend>
			  <label>Tgl Transaksi :</label>
			  <input type='text' name='tgl_transaksi' class='easyui-datebox input-small'><br>
			  <label>Kode Asset :</label>
			  <input type='text' name='asset_id' required><br>
			  <label>Pengguna :</label>
			  <select name='touser'><option value=''>--Pilih Keterangan--</option>";
				$sql = mysql_query("select * from sec_users order by user_id");
				while($r = mysql_fetch_array($sql)){
					echo "<option value='$r[user_id]'>$r[user_id]</option>";
				}
		echo "</select><br>
			  <label>Keterangan</label>
			  <select name='keterangan'>
					<option>--Pilih Keterangan--</option>
					<option value='Dipinjam'>Dipinjam</option>
					<option value='Dikembalikan'>Dikembalikan</option>
					<option value='Dijual'>Dijual</option>
					<option value='Penyusutan'>Penyusutan</option>
			  </select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "edittransaksi_asset":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from transaksi_asset WHERE transaksi_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=transaksiasset&act=update>
          <input type=hidden name=id value='$r[transaksi_id]'>
          <fieldset><legend>Edit Transaksi</legend>
	      <label>Tgl Transaksi :</label>
			  <input type='text' name='tgl_transaksi' class='easyui-datebox input-small' value='$r[tgl_transaksi]'><br>
			  <label>Kode Asset :</label>
			  <input type='text' name='asset_id' value='$r[asset_id]'required><br>
			  <label>Pengguna :</label>
			  <select name='touser'><option value='$r[touser]'>$r[touser]</option>";
				$x = mysql_query("select * from sec_users order by user_id");
				while($rx = mysql_fetch_array($x)){
					echo "<option value='$rx[user_id]'>$rx[user_id]</option>";
				}
		echo "</select><br>
			  <label>Keterangan</label>
			  <select name='keterangan'>
					<option value='$r[keterangan]'>$r[keterangan]</option>
					<option value='Dipinjam'>Dipinjam</option>
					<option value='Dikembalikan'>Dikembalikan</option>
					<option value='Dijual'>Dijual</option>
					<option value='Penyusutan'>Penyusutan</option>
			  </select><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
