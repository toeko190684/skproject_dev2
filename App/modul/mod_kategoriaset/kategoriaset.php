<?php
$aksi="modul/mod_kategoriaset/aksi_kategoriaset.php";
ses_module();
switch($_GET[act]){
  // Tampil kategoriaset
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM kategori_asset ORDER BY kategori_id");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Kategori</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=kategoriaset&act=tambahkategoriaset';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Kode kategori</td><td>Nama kategori</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("SELECT * from kategori_asset ORDER BY kategori_name limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
					<td>$r[kategori_id]</td>
					<td>$r[kategori_name]</td>
		            <td><a href='index.php?r=kategoriaset&act=editkategoriaset&id=$r[kategori_id]'>Edit</a> | 
			              <a href='$aksi?r=kategoriaset&act=hapus&id=$r[kategori_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=kategoriaset&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=kategoriaset&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=kategoriaset&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=kategoriaset&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahkategoriaset":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=kategoriaset&act=input'>
	          <fieldset><legend>Tambah Kategori</legend>
			  <label>Kode kategori :</label>
			  <input type='text' name='kategori_id' required><br>
			  <label>Nama Kategori :</label>
			  <input type='text' name='kategori_name'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editkategoriaset":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from kategori_asset where  kategori_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=kategoriaset&act=update>
          <input type=hidden name=id value='$r[kategori_id]'>
          <fieldset><legend>Edit kategori</legend>
		  <label>Kode Kategori :</label>
		  <input type='text' name='kategori_id' value='$r[kategori_id]' required><br>
		  <label>Nama Kategori :</label>
		  <input type='text' name='kategori_name' value='$r[kategori_name]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
