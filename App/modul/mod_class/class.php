<script>
$(document).ready(function(){
	$('#divisi').change(function(){
		var divisi_id = $(this).val(); 
		$.post('../function/get_data.php?data=departemen',{id:divisi_id},function(data){
			$('#departemen').html(data);
		});
	});
	
	$('#departemen').change(function(){
		var departemen_id = $(this).val();
		$.post('../function/get_data.php?data=subdepartemen',{id:departemen_id},function(data){
			$('#subdepartemen').html(data);
		});
	});
	
	$('#subdepartemen').change(function(){
		var promotype_id = $(this).val();
		$.post('../function/get_data.php?data=promotype',{id:promotype_id},function(data){
			$('#promotype').html(data);
		});
	});
	
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
});
</script>

<?php
$aksi="modul/mod_class/aksi_class.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Class</h2>
	          <button type=button class='btn btn-primary' 
			  onclick=\"window.location.href='index.php?r=class&act=tambahclass';\">Tambah</button><br><br>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode Promo</th><th>Nama Promo</th><th>Kode Class</th><th>Nama Class</th><th>aksi</th>
				</tr>
			  </thead><tbody>";

		$tampil=mysql_query("SELECT * FROM master_class a,master_promotype b where a.promotype_id=b.promotype_id  
		                    ORDER BY b.promotype_name,a.class_id ");
		while ($r=mysql_fetch_array($tampil)){
		      echo "<tr>
						<td>$r[promotype_id]</td>
						<td>$r[promotype_name]</td>
						<td>$r[class_id]</td>
						<td>$r[class_name]</td>
			            <td><a href='index.php?r=class&act=editclass&id=$r[class_id]'><i class='icon-pencil'></i></a>
				              <a href='$aksi?r=class&act=hapus&id=$r[class_id]'><i class='icon-trash'></i></a>
			            </td>
					</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahclass":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=class&act=input'>
	          <fieldset><legend>Tambah Class</legend>
			  <label>Divisi :</label>
			  <select name='divisi' id='divisi'><option>--Pilih Divisi--</option>";
			  $divisi = mysql_query("select * from master_divisi order by divisi_name");
			  while($rdivisi = mysql_fetch_array($divisi)){
				echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>";
			  }
		echo "</select><br>
			  <label>Kode Departemen :</label>
			  <select name='departemen_id' id='departemen'><option>--Pilih Departemen--</option></select><br>
		      <label>Kode Sub Departemen :</label>
		      <select name='subdepartemen_id' id='subdepartemen'><option value='$r[subdepartemen_id]'>$r[subdepartemen_name]</option></select><br>
			  <label>Kode Promo :</label>
			  <select name='promotype_id' id='promotype'><option>--Pilih Promo--</option></select><br>
			  <label>Kode Class :</label>
			  <input type='text' name='class_id' required><br>
			  <label>Nama Class :</label>
			  <input type='text' name='class_name' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editclass":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM master_class WHERE class_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=class&act=update>
          <input type=hidden name=id value='$r[class_id]'>
          <fieldset><legend>Edit Type class</legend>
		  <label>Divisi :</label>
			  <select name='divisi' id='divisi'><option>--Pilih Divisi--</option>";
			  $divisi = mysql_query("select * from master_divisi order by divisi_name");
			  while($rdivisi = mysql_fetch_array($divisi)){
				echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>";
			  }
	echo "</select><br>
		  <label>Kode Departemen :</label>
		  <select name='departemen_id' id='departemen'><option>--Pilih Departemen--</option></select><br>
	      <label>Kode Sub Departemen :</label>
	      <select name='subdepartemen_id' id='subdepartemen'><option>--Pilih SubDepartemen--</option></select><br>
		  <label>Kode Promo :</label>
		  <select name='promotype_id' id='promotype'><option>--Pilih Promo--</option></select><br>
		  <label>Kode Type class :</label>
		  <input type='text' name='class_id' value='$r[class_id]' required><br>
		  <label>Nama Type class :</label>
		  <input type='text' name='class_name' value='$r[class_name]' required><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
