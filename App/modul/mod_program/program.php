<script>
	$(document).ready(function(){
		$('#example').dataTable();
	});
</script>
<?php
$aksi="modul/mod_program/aksi_program.php";
ses_module();
switch($_GET[act]){
  // Tampil master_app
  default:
    $access = read_security();
	if($access=="allow"){
	       echo "<h2>Master Program</h2>
		          <input type=button class='btn btn-primary' value='Tambah' 
				  onclick=\"window.location.href='index.php?r=program&act=tambahprogram';\"><br><bR>
		          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
		          <thead>
					<tr>
						<td>Program ID</td><td>Program Name</td><td>Program Location</td><td>Image</td><td>aksi</td>
					</tr>
				  </thead>
				  <tbody>";
				  $tampil=mysql_query("SELECT * FROM sec_pro ORDER BY pro_name");
					while ($r=mysql_fetch_array($tampil)){
					  //menampilkan gambar
					  echo "<tr>
								<td>$r[pro_id]</td>
								<td>$r[pro_name]</td>
								<td>$r[pro_location]</td>
								<td>$r[image]</td>
								<td><a href='index.php?r=program&act=editprogram&id=$r[pro_id]'>Edit</a> | 
									  <a href='$aksi?r=program&act=hapus&id=$r[pro_id]'>Hapus</a>
								</td>
							</tr>";
					}
			echo "</tbody></table>";
		   /* $per_page = 10;
			$page_query = mysql_query("SELECT count(*) FROM sec_pro ORDER BY pro_name");
			$pages = ceil(mysql_result($page_query,0)/$per_page);
			$page = (isset($_GET[page]))? (int)$_GET[page]:1;
			$start = ($page-1)*$per_page;
		    echo "<blockquote>Master Program</blockquote>
		          <input type=button class='btn btn-primary' value='Tambah' 
				  onclick=\"window.location.href='index.php?r=program&act=tambahprogram';\"><br><bR>
		          <table class='table table-condensed table-hover table-bordered' >
		          <tdead>
					<tr class='success'>
						<td>No</td><td>Program ID</td><td>Program Name</td><td>Program Location</td><td>Image</td><td>aksi</td>
					</tr>
				  </tdead>";
		    
			$tampil=mysql_query("SELECT * FROM sec_pro ORDER BY pro_name limit $start,$per_page");
		    $no = 1;
			$no = $no+$start;
			while ($r=mysql_fetch_array($tampil)){
			  //menampilkan gambar
		      echo "<tbody<tr>
			            <td>$no</td>
						<td>$r[pro_id]</td>
						<td>$r[pro_name]</td>
						<td>$r[pro_location]</td>
						<td>$r[image]</td>
			            <td><a href='index.php?r=program&act=editprogram&id=$r[pro_id]'>Edit</a> | 
				              <a href='$aksi?r=program&act=hapus&id=$r[pro_id]'>Hapus</a>
			            </td>
					</tr></tbody>";
					$no++;
		    }
		    echo "</table>";
			//memulai paginasi
			echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=sec_app&page=1'>First</a></li>";
			if($pages >= 1 && $page <= $pages){
			    for($x=1; $x<=$pages; $x++){
			        echo ($x == $page) ? '<li><a href="?r=sec_app&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=program&page='.$x.'">'.$x.'</a></li>';
			    }
			}
			$x--;
			echo "<li><a href='?r=sec_app&page=$x'>Last</a></li></ul></div>";
			*/
	}else{
		msg_security();
	}
    break;

  case "tambahprogram":
	$access = create_security();
	if($access=="allow"){
			echo "<form method=POST action='$aksi?r=program&act=input' enctype='multipart/form-data'>
		          <fieldset><legend>Tambah Program</legend>
				  <label>Nama Program :</label>
				  <input type='text' name='pro_name' required><br>
				  <label>Program Location :</label>
				  <input type='text' name='pro_location' required><br>
				  <label>Image :</label>
				  <input type='file' name='gambar'><br>
				  <i>size 260 x 180 pixel</i><br><br>
				  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
				  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
				  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editprogram":
    $access = update_security();	
    if($access=="allow"){
			$edit = mysql_query("SELECT * FROM sec_pro WHERE pro_id='$_GET[id]'");
		    $r    = mysql_fetch_array($edit);

		    echo "<form method=POST action='$aksi?r=program&act=update' enctype='multipart/form-data'>
		          <input type=hidden name=id value='$r[pro_id]'>
		          <fieldset><legend>Edit Program</legend>
				  <label>Nama Program :</label>
				  <input type='text' name='pro_name' value='$r[pro_name]' required><br>
				  <label>Program Location :</label>
				  <input type='text' name='pro_location' value='$r[pro_location]' required><br>
				  <label>Image :</label>
				  <input type='file' name='gambar'><br>
				  <i>size 260 x 180 pixel</i><br><br>
				  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
				  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
				  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
