<?php
$aksi="modul/mod_company/aksi_company.php";
ses_module();
switch($_GET[act]){
  // Tampil company
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		$page_query = mysql_query("SELECT count(*) FROM company ORDER BY company_name");
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Master Perusahaan</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=company&act=tambahcompany';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>Kode Perusahaan</td><td>Nama Perusahaan</td><td>Alamat</td><td>Telpon</td><tD>Fax</td><td>Email</td><td>Website</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		$tampil=mysql_query("select * from company ORDER BY company_name limit $start,$per_page");
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
		            <td>$r[company_id]</td>
					<td>$r[company_name]</td>
					<td>$r[alamat]</td>
					<td>$r[telpon]</td>
					<td>$r[fax]</td>
					<td>$r[email]</td>
					<td>$r[website]</td>
		            <td><a href='index.php?r=company&act=editcompany&id=$r[company_id]'>Edit</a> | 
			              <a href='$aksi?r=company&act=hapus&id=$r[company_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=company&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=company&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=company&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=company&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahcompany":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=company&act=input'>
	          <fieldset><legend>Tambah Perusahaan</legend>
			  <label>Kode Perusahaan :</label>
			  <input type='text' name='company_id' required><br>
			  <label>Nama Perusahaan :</label>
			  <input type='text' name='company_name'  class='input-xxlarge' required><br>
			  <label>Alamat :</label>
			  <textarea name='alamat'></textarea><br>
			  <label>Telpon :</label>
			  <input type='text' name='telpon' class='easyui-numberbox'><br>
			  <label>Fax :</label>
			  <input type='text' name='fax' class='easyui-numberbox'><br>
			  <label>Email :</label>
			  <input type='email' name='email'><br>
			  <label>Website :</label>
			  <input type='url' name='website'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editcompany":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from company where company_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=company&act=update>
          <input type=hidden name=id value='$r[company_id]'>
          <fieldset><legend>Edit Perusahaan</legend>
		  <label>Kode Perusahaan :</label>
		  <input type='text' name='company_id' value='$r[company_id]' required><br>
		  <label>Nama Perusahaan :</label>
		  <input type='text' name='company_name'  class='input-xxlarge' value='$r[company_name]' required><br>
		  <label>Alamat :</label>
		  <textarea name='alamat'>$r[alamat]</textarea><br>
		  <label>Telpon :</label>
		  <input type='text' name='telpon' class='easyui-numberbox' value='$r[telpon]'><br>
		  <label>Fax :</label>
		  <input type='text' name='fax' class='easyui-numberbox' value='$r[fax]' ><br>
		  <label>Email :</label>
		  <input type='email' name='email' value='$r[email]' ><br>
		  <label>Website :</label>
		  <input type='url' name='website' value='$r[website]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
