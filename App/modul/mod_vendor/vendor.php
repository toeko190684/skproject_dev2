<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_vendor/aksi_vendor.php";
ses_module();
switch($_GET[act]){
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Vendor</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=vendor&act=tambahvendor';\">Tambah</button><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode vendor</th><th>Nama vendor</th><th>AP Account Type</th><th>AP Account ID</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
	    
		$tampil=mysql_query("SELECT * FROM vendor ORDER BY vendor_id");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$r[vendor_id]</td>
					<td>$r[vendor_name]</td>
					<td>$r[ap_account_type]</td>
					<td>$r[ap_account_id]</td>
		            <td><a href='index.php?r=vendor&act=editvendor&id=$r[vendor_id]'><i class='icon-pencil'></i></a> 
			              <a href='$aksi?r=vendor&act=hapus&id=$r[vendor_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahvendor":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=vendor&act=input'>
	          <fieldset><legend>Tambah vendor</legend>
			  <label>Kode vendor :</label>
			  <input type='text' name='vendor_id' required><br>
			  <label>Nama vendor :</label>
			  <input type='text' name='vendor_name' required><br>
			  <label>AP Account Type :</label>
			  <input type='text' name='ap_account_type' required><br>
			  <label>AP Account ID :</label>
			  <input type='text' name='ap_account_id' required><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editvendor":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM vendor WHERE vendor_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=vendor&act=update>
          <input type=hidden name=id value='$r[vendor_id]'>
          <fieldset><legend>Edit vendor </legend>
		  <label>Kode vendor :</label>
		  <input type='text' name='vendor_id' value='$r[vendor_id]' required><br>
		  <label>Nama vendor :</label>
		  <input type='text' name='vendor_name' value='$r[vendor_name]' required><br>
		  <label>AP Account Type :</label>
		  <input type='text' name='ap_account_type' value='$r[ap_account_type]' required><br>
		  <label>AP Account ID :</label>
		  <input type='text' name='ap_account_id' value='$r[ap_account_id]' required><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
