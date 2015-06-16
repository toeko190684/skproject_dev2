<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_promo/aksi_promo.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Promo</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=promo&act=tambahpromo';\">Tambah</button><br><br>

	          <table id='example' class='table table-striped table-bordered table-hover' >
			  <thead>
				<tr'>
					<th>Kode Group Promo</th><th>Nama Group Promo</th><th>Kode Promo</th><th>Nama Promo</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
		$tampil=mysql_query("SELECT * FROM master_promotype a,master_grouppromo b 
		                     where a.grouppromo_id=b.grouppromo_id ORDER BY b.grouppromo_name,a.promotype_name");

		while ($r=mysql_fetch_array($tampil)){
		      echo "<tr>
						<td>$r[grouppromo_id]</td>
						<td>$r[grouppromo_name]</td>
						<td>$r[promotype_id]</td>
						<td>$r[promotype_name]</td>
			            <td><a href='index.php?r=promo&act=editpromo&id=$r[promotype_id]'><i class='icon-pencil'></i></a> 
				              <a href='$aksi?r=promo&act=hapus&id=$r[promotype_id]'><i class='icon-trash'></i></a>
			            </td>
					</tr>";
		}
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahpromo":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=promo&act=input'>
	          <fieldset><legend>Tambah Type Promo</legend>
			  <label>Kode Group Promo :</label>
			  <select name='grouppromo' id='grouppromo'><option value=''>--Pilih Group Promo--</option>";
			  $group = mysql_query("select * from master_grouppromo order by grouppromo_name");
			  while($rgroup = mysql_fetch_array($group)){
					echo "<option value='$rgroup[grouppromo_id]'>$rgroup[grouppromo_name]</option>";	
			 }
	    echo "</select><br> 
			  <label>Kode Type Promo :</label>
			  <input type='text' name='promotype_id' required><br>
			  <label>Nama Type Promo :</label>
			  <input type='text' name='promotype_name' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editpromo":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.grouppromo_name FROM master_promotype a,master_grouppromo b  WHERE a.grouppromo_id=b.grouppromo_id and  a.promotype_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=promo&act=update>
          <input type=hidden name=id value='$r[promotype_id]'>
          <fieldset><legend>Edit Type Promo</legend>
		  <label>Kode Group Promo :</label>
			  <select name='grouppromo' id='grouppromo'><option value='$r[grouppromo_id]'>$r[grouppromo_name]</option>";
			  $group = mysql_query("select * from master_grouppromo order by grouppromo_name");
			  while($rgroup = mysql_fetch_array($group)){
					echo "<option value='$rgroup[grouppromo_id]'>$rgroup[grouppromo_name]</option>";	
			 }
	    echo "</select><br> 
			  <label>Kode Type Promo :</label>
			  <input type='text' name='promotype_id' value='$r[promotype_id]' required><br>
			  <label>Nama Type Promo :</label>
			  <input type='text' name='promotype_name' value='$r[promotype_name]' required><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
