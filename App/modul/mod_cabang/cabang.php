<?php
$aksi="modul/mod_cabang/aksi_cabang.php";
ses_module();
switch($_GET[act]){
  // Tampil cabang
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM cabang ORDER BY cabang_id");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master cabang</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=cabang&act=tambahcabang';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Kode cabang</td><td>Nama cabang</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("SELECT * from cabang ORDER BY cabang_id limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
					<td>$r[cabang_id]</td>
					<td>$r[cabang_name]</td>
		            <td><a href='index.php?r=cabang&act=editcabang&id=$r[cabang_id]'>Edit</a> | 
			              <a href='$aksi?r=cabang&act=hapus&id=$r[cabang_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=cabang&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=cabang&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=cabang&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=cabang&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahcabang":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=cabang&act=input'>
	          <fieldset><legend>Tambah cabang</legend>
			  <label>Kode cabang :</label>
			  <input type='text' name='cabang_id' required><br>
			  <label>Nama cabang :</label>
			  <input type='text' name='cabang_name' required><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editcabang":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from cabang WHERE cabang_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=cabang&act=update>
          <input type=hidden name=id value='$r[cabang_id]'>
          <fieldset><legend>Edit cabang</legend>
		  <label>Kode cabang :</label>
		  <input type='text' name='cabang_id' value='$r[cabang_id]' required><br>
		  <label>Nama cabang :</label>
		  <input type='text' name='cabang_name' value='$r[cabang_name]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
