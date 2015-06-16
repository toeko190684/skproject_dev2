<script>
$(document).ready(function(){
	$('#jml_file').change(function(){
		var jml_file = $(this).val();
		var string = "";
		for(i=0;i<=jml_file-1;i++){
			string = string + "<input type='file' name=\"namafile"+i+"\" ><br>";
		}
		$('#list').html(string);
	});
});
</script>
<?php
$aksi="modul/mod_recoattachment/aksi_recoattachment.php";
ses_module();
switch($_GET[act]){
  // Tampil recoattachment
  default:
  $access = read_security();
  if($access=="allow"){
	    $per_page = 10;
		if($_POST[key]==""){
			$page_query = mysql_query("SELECT count(*) FROM detail_reco_attachment ORDER BY kode_promo,nama_file");
		}else{
			$page_query = mysql_query("SELECT count(*) FROM detail_reco_attachment where kode_promo ='$_POST[key]' ORDER BY kode_promo,nama_file");
		}
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
		echo "<blockquote>Reco Attachment</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=recoattachment&act=tambahrecoattachment';\">
			  <form class='form-search' method=post action='?r=recoattachment'>	
					<div class='input-append'>
					    <div class='span9'></div>
						<input class='span3 search-query' id='key' name='key' type='text' placeholder='Masukan Kode Reco..!'>
					    <button class='btn' type='submit'>Cari</button>
					</div><br>
			  </form>
	          <table class='table table-condensed table-hover table-bordered' >
	          <tdead>
				<tr class='success'>
					<td>No</td><td>Kode Promo</td><td>Nama File</td><td>Type</td><td>Size</td><td>aksi</td>
				</tr>
			  </tdead>";
	    
		if($_POST[key]==""){
			$tampil = mysql_query("SELECT * from detail_reco_attachment order by kode_promo,nama_file limit $start,$per_page");
		}else{
			$tampil = mysql_query("SELECT * from detail_reco_attachment where kode_promo='$_POST[key]' ORDER BY kode_promo,nama_file limit $start,$per_page");
		}
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
		            <td>$no</td>
					<td>$r[kode_promo]</td>
					<td>$r[nama_file]</td>
					<td>$r[type]</td>
					<td>$r[size]</td>
		            <td>
			              <a href='$aksi?r=recoattachment&act=hapus&id=$r[id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=recoattachment&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=recoattachment&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=recoattachment&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=recoattachment&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahrecoattachment":
	$access = create_security();
	if($access =="allow"){
	    echo "<form class='form-horizontal' method='post' action='$aksi?r=recoattachment&act=input' enctype = 'multipart/form-data'>
	          <fieldset><legend>Tambah Reco Attachment</legend>
				<div class='control-group'>
					<label class='control-label' for='kode_promo'>Kode Reco</label>
					<div class='controls'>
						<input type='text' id='kode_promo' name='kode_promo' placeholder='Kode Promo' class='input-large'>
					</div>
				</div>
				<div class='control-group'>
					<label class='control-label' for='jml_file'>Jumlah File</label>
					<div class='controls'>
						<select name='jml_file' id='jml_file' class='input-mini'><option value=''></option>";
							for($i=1;$i<=10;$i++){
								echo "<option value='".$i."'>".$i."</option>";
							}
		echo "			</select>
					</div>
				</div>
				<div class='control-group'>
					<div class='controls' id='list'>
						
					</div>
				</div>
				<div class='control-group'>
					<div class='controls'>
						<input type='submit' class='btn btn-primary' value='Simpan'>
						<input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
					</div>
				</div>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 }
?>
