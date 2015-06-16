<?php
$aksi="modul/mod_unitasset/aksi_unitasset.php";
ses_module();
switch($_GET[act]){
  // Tampil unitasset
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM unit_asset ORDER BY unit_name");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Unit</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=unitasset&act=tambahunitasset';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Nama Unit</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("select * from unit_asset ORDER BY unit_name limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
					<td>$r[unit_name]</td>
		            <td><a href='index.php?r=unitasset&act=editunitasset&id=$r[unit_name]'>Edit</a> | 
			              <a href='$aksi?r=unitasset&act=hapus&id=$r[unit_name]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=unitasset&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=unitasset&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=unitasset&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=unitasset&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahunitasset":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=unitasset&act=input'>
	          <fieldset><legend>Tambah Unit</legend>
			  <label>Unit Name :</label>
			  <input type='text' name='unit_name'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editunitasset":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("select * from unit_asset where  unit_name='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=unitasset&act=update>
          <input type=hidden name=id value='$r[unit_name]'>
          <fieldset><legend>Edit Unit</legend>
		  <label>Nama Unit :</label>
		  <input type='text' name='unit_name' value='$r[unit_name]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
