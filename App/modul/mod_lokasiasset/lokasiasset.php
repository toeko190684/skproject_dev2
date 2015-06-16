<?php
$aksi="modul/mod_lokasiasset/aksi_lokasiasset.php";
ses_module();
switch($_GET[act]){
  // Tampil lokasiasset
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM location ORDER BY location_id");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Lokasi</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=lokasiasset&act=tambahlokasiasset';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Kode Lokasi</td><td>Nama Lokasi</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("SELECT * from location ORDER BY location_name limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
					<td>$r[location_id]</td>
					<td>$r[location_name]</td>
		            <td><a href='index.php?r=lokasiasset&act=editlokasiasset&id=$r[location_id]'>Edit</a> | 
			              <a href='$aksi?r=lokasiasset&act=hapus&id=$r[location_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=lokasiasset&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=lokasiasset&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=lokasiasset&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=lokasiasset&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahlokasiasset":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=lokasiasset&act=input'>
	          <fieldset><legend>Tambah Lokasi</legend>
			  <label>Kode Lokasi :</label>
			  <input type='text' name='location_id' required><br>
			  <label>Nama Lokasi :</label>
			  <input type='text' name='location_name'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editlokasiasset":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from location where  location_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=lokasiasset&act=update>
          <input type=hidden name=id value='$r[location_id]'>
          <fieldset><legend>Edit Lokasi</legend>
		  <label>Kode Lokasi :</label>
		  <input type='text' name='location_id' value='$r[location_id]' required><br>
		  <label>Nama Lokasi :</label>
		  <input type='text' name='location_name' value='$r[location_name]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
