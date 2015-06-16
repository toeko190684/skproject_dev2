<script>
$(document).ready(function(){
	$('#example').dataTable();

});
</script>
<?php
$aksi="modul/mod_rule/aksi_sec_rule.php";
ses_module();
switch($_GET[act]){
  // Tampil  security rule
  default:
    $access = read_security();
	if($access =="allow"){
	    echo "<h2>Master Rule</h2>
		      <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=sec_rule&act=tambah_sec_rule';\"><br><bR>
			  <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100% >
	          <thead>
				<tr>
					<th>User ID</th><th>Nama Lengkap</th><th>Grade ID</th><th>aksi</th>
				</tr>
			  </thead>
			  </tbody>";
			  $tampil = mysql_query("select user_id,full_name,grade_id from sec_users where grade_id='*' or
		                          user_id in(select distinct user_id from sec_user_rules)");
			  while ($r=mysql_fetch_array($tampil)){
				  echo "<tr>
							<td>$r[user_id]</td>
							<td>$r[full_name]</td>
							<td>$r[grade_id]</td>
							<td><a href='index.php?r=sec_rule&act=edit_sec_rule&id=$r[user_id]'>Edit</a> | 
								  <a href='$aksi?r=sec_rule&act=hapus&id=$r[user_id]'>Hapus</a>
							</td>
						</tr>";
				}				  
		echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambah_sec_rule":
    $access = create_security();
		if($access == "allow"){
	    echo "<form method=POST action='$aksi?r=sec_rule&act=input'>
	          <fieldset><legend>Tambah Rule</legend>
			  <label>User ID :</label>
			  <select name='user_id' required><option>--Pilih User ID--</option>";
			  $sql = mysql_query("select * from sec_users where user_id not in(select distinct user_id from sec_user_rules) and grade_id<>'*'");
			  while($r = mysql_fetch_array($sql)){
					echo "<option value='$r[user_id]'>$r[user_id]</option>";
			  }
		echo "</select><br><br>";
		$app = mysql_query("select * from sec_app order by app_name");
		echo "<ul>";
		$i = 0;
		while($rapp = mysql_fetch_array($app)){
			echo "<li><b>$rapp[app_name]</b>";
			echo "<table class='table table-bordered'>
				<tR style='background-color:lightblue'><td>Nama Modul</td><td>Create</td><td>Read</td><td>Update</td><td>Delete</td></tr>";
			$module = mysql_query("select * from sec_app_module where app_id='$rapp[app_id]' order by module_name");
			$cek_mod = mysql_num_rows($module);
			if($cek_mod>0){
			while($rmodule = mysql_fetch_array($module)){
				echo "<tr>
						<td>$rmodule[module_name]<input type=hidden name='module_id$i' value='$rmodule[module_id]'></td>
						<td><input type='checkbox' name='c$i' value='1'></td>
						<td><input type='checkbox' name='r$i' value='1'></td>
						<td><input type='checkbox' name='u$i' value='1'></td>
						<td><input type='checkbox' name='d$i' value='1'></td>
				     </tr>";
				$i++;
			}
			}else{
				echo "<tr>
						<td colspan=4></td></tr>";
			}
			echo "</table></li>";
		}	
		echo "</ul>";
		echo "<br><input type='hidden' name='jml' value=$i><input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "edit_sec_rule":
    $access = update_security();
		if($access == "allow"){
	    echo "<form method=POST  action=$aksi?r=sec_rule&act=update>
	          <fieldset><legend>Edit Rule</legend>
			  <label>User ID :</label>
			  <input type='text' value='$_GET[id]'><input type='hidden' name='user_id' value='$_GET[id]'><br><br>";
		$app = mysql_query("select * from sec_app order by app_name");
		$i =0;
		while($rapp = mysql_fetch_array($app)){
			echo "<h4>$rapp[app_name]</h4>";
			echo "<table class='table table-bordered'><tr style='background-color:lightblue'>
			     <td>Nama Modul</td><td>Create</td><td>Read</td><td>Update</td><td>Delete</td></tr>";
			$module = mysql_query("select * from sec_app_module where app_id='$rapp[app_id]' order by module_name");
			while($rmodule = mysql_fetch_array($module)){
				//cek apakah dichecklist atau tidak
				$cek = mysql_query("select * from sec_user_rules where module_id='$rmodule[module_id]' and user_id='$_GET[id]'");
				$rcek = mysql_fetch_array($cek);
				echo "<input type=hidden name='id$i' value='$rcek[id]'><input type=hidden name='module_id$i' value='$rmodule[module_id]'>";
				//cek untuk create
			    echo "<tr><td>$i.$rmodule[module_name] </td>";
				if($rcek[c]=='1'){
					echo "<td><input type='checkbox' name='c$i' value='1' checked=true></td>";
				}else{
				    echo "<td><input type='checkbox' name='c$i' value='1' ></td>";
				}
				//cek untuk read
				if($rcek[r]=='1'){
					echo "<td><input type='checkbox' name='r$i' value='1' checked=true></td>";
				}else{
				    echo "<td><input type='checkbox' name='r$i' value='1' ></td>";
				}
				//cek untuk update
				if($rcek[u]=='1'){
					echo "<td><input type='checkbox' name='u$i' value='1' checked=true></td>";
				}else{
				    echo "<td><input type='checkbox' name='u$i' value='1' ></td>";
				}
				//cek untuk delete
				if($rcek[d]=='1'){
					echo "<td><input type='checkbox' name='d$i' value='1' checked=true></td></tr>";
				}else{
				    echo "<td><input type='checkbox' name='d$i' value='1' ></td></tr>";
				}
				$i++;
			}
			echo "</table>";
		}
		echo "<br><input type='hidden' name='jml' value='$i'><input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
