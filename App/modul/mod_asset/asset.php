<?php
$aksi="modul/mod_asset/aksi_asset.php";
ses_module();
switch($_GET[act]){
  // Tampil asset
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM asset ORDER BY asset_id");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Asset</blockquote>
	          <div class='input-append'>
				<div class='span11'>
					<input type=button class='btn btn-primary' value='Tambah' 
			        onclick=\"window.location.href='index.php?r=asset&act=tambahasset';\">
                </div>					
					<form method=post action='?r=asset'>
					    <input class='span3' id='key' name='key' type='text' placeholder='Masukan Kode Asset..!'>
					    <button class='btn' type='submit'>Cari</button>
					</form>
			  </div><br>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Tgl Beli</td><td>Kode Asset</td><td>Nama Asset</td><td>Perusahaan</td><td>Cabang</td><td>Kategori</td>
					<td>Lokasi</td><td>Unit</td><td>Qty</td><td>Price</td><td>Keterangan</td><td>Created By</td>
					<Td>User ID</td><td>Last Update</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		if($_POST[key]==""){
			$tampil=mysql_query("SELECT * from asset ORDER BY asset_id limit $start,$per_page");
		}else{
			$tampil=mysql_query("SELECT * from asset where asset_id='$_POST[key]' ORDER BY asset_id limit $start,$per_page");
		}
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
		            <td>$r[tgl_beli]</td>
					<td>$r[asset_id]</td>
					<td>$r[asset_name]</td>
					<td>$r[company_id]</td>
					<td>$r[cabang_id]</td>
					<td>$r[kategori_id]</td>
					<td>$r[location_id]</td>
					<td>$r[unit_name]</td>
					<td>$r[quantity]</td>
					<td>".number_format($r[price],2,',','.')."</td>
					<td>$r[keterangan]</td>
					<td>$r[created_by]</td>
					<td>$r[user_id]</td>
					<td>$r[last_update]</td>
		            <td><a href='index.php?r=asset&act=editasset&id=$r[asset_id]'>Edit</a> | 
			              <a href='$aksi?r=asset&act=hapus&id=$r[asset_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=asset&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=asset&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=asset&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=asset&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahasset":
	$access = create_security();
	if($access =="allow"){
	    echo "<form class='form-horizontal' method='post' action='$aksi?r=asset&act=input'>
					<fieldset><legend>Tambah Asset</legend>
						<div class='control-group'>
							<label class='control-label' for='kode_aset'>Kode Asset</label>
							<div class='controls'>
								<input type='text' id='kode_asset' name='kode_asset' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='asset_name'>Nama Asset</label>
							<div class='controls'>
								<input type='text' id='asset_name' name='asset_name' class='input-xxlarge' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='kategori_id'>Kategori</label>
							<div class='controls'>
								<select id='kategori_id' name='kategori_id'><option>--Pilih Kategori--</option>";
									$kategori = mysql_query("select * from kategori_asset order by kategori_name");
									while($rkategori = mysql_fetch_array($kategori)){
										echo "<option value='$rkategori[kategori_id]'>$rkategori[kategori_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='company_id'>Perusahaan</label>
							<div class='controls'>
								<select id='company_id' name='company_id'><option>--Pilih Perusahaan--</option>";
									$company = mysql_query("select * from company order by company_name");
									while($rcompany = mysql_fetch_array($company)){
										echo "<option value='$rcompany[company_id]'>$rcompany[company_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='cabang_id'>Cabang</label>
							<div class='controls'>
								<select id='cabang_id' name='cabang_id'><option>--Pilih Cabang--</option>";
									$cabang = mysql_query("select * from cabang order by cabang_name");
									while($rcabang = mysql_fetch_array($cabang)){
										echo "<option value='$rcabang[cabang_id]'>$rcabang[cabang_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='location_id'>Lokasi</label>
							<div class='controls'>
								<select id='location_id' name='location_id'><option>--Pilih Lokasi--</option>";
									$location = mysql_query("select * from location order by location_name");
									while($rlocation = mysql_fetch_array($location)){
										echo "<option value='$rlocation[location_id]'>$rlocation[location_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='unit_name'>Unit</label>
							<div class='controls'>
								<select id='unit_name' name='unit_name'><option>--Pilih Unit--</option>";
									$unit = mysql_query("select * from unit_asset order by unit_name");
									while($runit = mysql_fetch_array($unit)){
										echo "<option value='$runit[unit_name]'>$runit[unit_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='tgl_beli'>Tanggal Beli</label>
							<div class='controls'>
								<input type='text' id='tgl_beli' name='tgl_beli'  class='easyui-datebox input-small'>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='quantity'>Quantity</label>
							<div class='controls'>
								<input type='text' id='quantity' name='quantity'  class='easyui-numberbox input-small'>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='price'>Harga</label>
							<div class='controls'>
								<input type='text' id='price' name='price'  class='easyui-numberbox' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='keterangan'>Keterangan</label>
							<div class='controls'>
								<textarea id='asset_id' name='asset_id'></textarea>
							</div>
						</div>
						<div class='control-group'>
							<div class='controls'>
									<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
									<input type='reset' class='btn btn-danger' onclick='history.go(-1)'  value='Batal'>
							</div>
						</div>
					</fieldset>
				</form>";
	}else{
		msg_security();
	}
     break;
 
  case "editasset":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from asset WHERE asset_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form class='form-horizontal' method='post' action='$aksi?r=asset&act=update'>
					<fieldset><legend>Tambah Asset</legend>
						<div class='control-group'>
							<label class='control-label' for='kode_aset'>Kode Asset</label>
							<div class='controls'>
								<input type='text' id='kode_asset' name='kode_asset' value='$r[asset_id]' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='asset_name'>Nama Asset</label>
							<div class='controls'>
								<input type='text' id='asset_name' name='asset_name' class='input-xxlarge' value='asset_name' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='kategori_id'>Kategori</label>
							<div class='controls'>
								<select id='kategori_id' name='kategori_id'><option value='$r[kategori_id]'>$r[kategori_id]</option>";
									$kategori = mysql_query("select * from kategori_asset order by kategori_name");
									while($rkategori = mysql_fetch_array($kategori)){
										echo "<option value='$rkategori[kategori_id]'>$rkategori[kategori_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='company_id'>Perusahaan</label>
							<div class='controls'>
								<select id='company_id' name='company_id'><option value='$r[company_id]'>$r[company_id]</option>";
									$company = mysql_query("select * from company order by company_name");
									while($rcompany = mysql_fetch_array($company)){
										echo "<option value='$rcompany[company_id]'>$rcompany[company_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='cabang_id'>Cabang</label>
							<div class='controls'>
								<select id='cabang_id' name='cabang_id'><option value='$r[cabang_id]'>$r[cabang_id]</option>";
									$cabang = mysql_query("select * from cabang order by cabang_name");
									while($rcabang = mysql_fetch_array($cabang)){
										echo "<option value='$rcabang[cabang_id]'>$rcabang[cabang_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='location_id'>Lokasi</label>
							<div class='controls'>
								<select id='location_id' name='location_id'><option value='$r[location_id]'>$r[location_id]</option>";
									$location = mysql_query("select * from location order by location_name");
									while($rlocation = mysql_fetch_array($location)){
										echo "<option value='$rlocation[location_id]'>$rlocation[location_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='unit_name'>Unit</label>
							<div class='controls'>
								<select id='unit_name' name='unit_name'><option value='$r[unit_name]'>$r[unit_name]</option>";
									$unit = mysql_query("select * from unit_asset order by unit_name");
									while($runit = mysql_fetch_array($unit)){
										echo "<option value='$runit[unit_name]'>$runit[unit_name]</option>";
									}
		echo "					</select>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='tgl_beli'>Tanggal Beli</label>
							<div class='controls'>
								<input type='text' id='tgl_beli' name='tgl_beli'  class='easyui-datebox input-small' value='$r[tgl_beli]' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='quantity'>Quantity</label>
							<div class='controls'>
								<input type='text' id='quantity' name='quantity'  class='easyui-numberbox input-small' value='$r[quantity]' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='price'>Harga</label>
							<div class='controls'>
								<input type='text' id='price' name='price'  class='easyui-numberbox' value='$r[price]' required>
							</div>
						</div>
						<div class='control-group'>
							<label class='control-label' for='keterangan'>Keterangan</label>
							<div class='controls'>
								<textarea id='keterangan' name='keterangan'>$r[keterangan]</textarea>
							</div>
						</div>
						<div class='control-group'>
							<div class='controls'>
									<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
									<input type='reset' class='btn btn-danger' onclick='history.go(-1)'  value='Batal'>
							</div>
						</div>
					</fieldset>
				</form>";
}else{
		msg_security();
	}
    break;  
}
?>
