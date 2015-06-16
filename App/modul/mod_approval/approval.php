<?php
$aksi="modul/mod_approval/aksi_approval.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
	    echo "<blockquote>Approval </blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=approval&act=tambahapproval';\"><br><bR>
	          <table class='table table-condensed table-hover table-bordered' >
	          <thead>
				<tr style='background-color:lightblue'>
					<th>No</tH><th>User ID</th><th>Level</th><th>aksi</th>
				</tr>
			  </thead>";
	    
		$tampil=mysql_query("SELECT a.*,b.user_id FROM approval a,sec_users b where a.user_id=b.user_id ORDER BY a.user_id");
	    $no = 1;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
					<td>$no</td>
					<td>$r[user_id]</td>
					<td>$r[level]</td>
		            <td><a href='index.php?r=approval&act=editapproval&id=$r[user_id]'>Edit</a> | 
			              <a href='$aksi?r=approval&act=hapus&id=$r[user_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
	}else{
		msg_security();
	}
    break;

  case "tambahapproval":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=approval&act=input'>
	          <fieldset><legend>Tambah Approval</legend>
			  <label>User ID :</label>
			  <select name='user_id' required><option>--Pilih User--</option>";
				$user = mysql_query("select * from sec_users order by user_id");
				while($ruser = mysql_fetch_array($user)){
					echo "<option value='$ruser[user_id]'>$ruser[user_id]</option>";
				}
		echo "</select><br>
			  <label>Level :</label>
			  <select name='level' required><option>--Pilih Level--</option>
					  <option value='first'>first</option>
					  <option value='second'>second</option>
			  </select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editapproval":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.user_id FROM approval a,sec_users b where a.user_id=b.user_id and  a.user_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=approval&act=update>
          <input type=hidden name=id value='$r[coa_id]'>
          <fieldset><legend>Edit Approval</legend>
		  <input type=hidden name=id value='$r[user_id]'>
		  <label>User ID :</label>
			  <select name='user_id' required><option value='$r[user_id]'>$r[user_id]</option>";
				$user = mysql_query("select * from sec_users order by user_id");
				while($ruser = mysql_fetch_array($user)){
					echo "<option value='$ruser[user_id]'>$ruser[user_id]</option>";
				}
		echo "</select><br>
			  <label>Level :</label>
			  <select name='level' required><option value='$r[level]'>$r[level]</option>
					  <option value='first'>first</option>
					  <option value='second'>second</option>
			  </select><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
