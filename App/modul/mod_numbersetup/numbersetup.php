<script >
	$(document).ready(function(){
		
		$('#divisi').combogrid({
				panelWidth : 300,
				idField : 'divisi_id',
				textField : 'divisi_name',
				url : '../function/get_data.php?data=json_divisi',
				columns : [[
					{field:'divisi_id',title:'Divisi ID',width : 90},
					{field:'divisi_name',title:'Divisi Name',width : 190}
				]],
				onChange : function(value){
					var divisi = $(this).combogrid('getValue');
					$('#departemen').combogrid({
							panelWidth : 300,
							idField : 'department_id',
							textField : 'department_name',
							url : '../function/get_data.php?data=json_department&id='+divisi,
							columns : [[
								{field:'department_id',title:'Dept. ID',width : 90},
								{field:'department_name',title:'Dept. Name',width : 190}							
							]],
							onChange : function(value){
								var departemen = $(this).combogrid('getValue');
								var jenis = $('#jenis').val();
								if(jenis=='Claim'){ pre = 'CL' }
								else if(jenis=='Payment'){ pre = 'PY' }
								else if(jenis=='Reco'){ pre = 'RC' }
								var prefix = pre+'/MKI/'+divisi+'/'+departemen+'/';
								$('#prefix').val(prefix);
							}
					});
				}
		});
		
		$('#example').dataTable();
	});
</script>
<?php
$aksi="modul/mod_numbersetup/aksi_numbersetup.php";
ses_module();
switch($_GET[act]){
  // Tampil numbersetup
  default:
    $access = read_security();
	if($access=="allow"){
	    echo "<h2>Master Number Setup</h2>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=numbersetup&act=tambahnumbersetup';\"><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100% >
	          <thead>
				<tr >
					<th>Jenis</th><th>Divisi ID</th><th>Dept.ID</th><th>Prefix</th><th>Number</th><th>Modul</th><th>aksi</th>
				</tr>
			  </thead>
			  <tbody>";
			  $tampil=mysql_query("SELECT a.*,b.module_name,c.divisi_name,d.department_name 
		                     FROM master_setup a,sec_app_module b,master_divisi c, master_department d 
		                     where a.module_id=b.module_id and a.divisi_id=c.divisi_id and a.department_id=d.department_id
		                    ORDER BY a.jenis,c.divisi_name,d.department_name");
				while ($r=mysql_fetch_array($tampil)){
				  echo "<tr>
							<td>$r[jenis]</td>
							<td>$r[divisi_name]</td>
							<td>$r[department_name]</td>
							<td>$r[prefix]</td>
							<td>$r[number]</td>
							<td>$r[module_name]</td>
							<td><a href='index.php?r=numbersetup&act=editnumbersetup&id=$r[id]'>Edit</a> | 
								  <a href='$aksi?r=numbersetup&act=hapus&id=$r[id]'>Hapus</a>
							</td>
						</tr>";
				}
		echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahnumbersetup":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=numbersetup&act=input'>
	          <fieldset><legend>Tambah Number Setup</legend>
			  <label>Setup :</label>
			  <select name='jenis' id='jenis'><option value=''>Pilih Jenis</option>
					<option value='Claim'>Claim</option>
					<option value='Payment'>Payment</option>
					<option value='Reco'>Reco</option>
			  </select><br>
			  <label>Divisi :</label>
			  <input name='divisi' id='divisi'><br>
			  <label>Departemen :</label>
			  <input name='departemen' id='departemen' class='easyui-combogrid'><br>
			  <label>Prefix :</label>
			  <input type='text' name='prefix' id='prefix' required><br>
			  <label>Number :</label>
			  <input type='text' name='number' class='easyui-numberbox input-small' value=1 required><br>
			  <label>Modul :</label>
			  <select name='module_id' ><option value=''>--Pilih Modul--</option>";
			  $mod = mysql_query("select * from sec_app_module order by module_name");
			  while($rmod =  mysql_fetch_array($mod)){
				echo "<option value='$rmod[module_id]'>$rmod[module_name]</option>";
			  }
		echo "</select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "editnumbersetup":
	$access = update_security();
	if($access =="allow"){
	    $edit = mysql_query("SELECT a.*,b.module_name,c.divisi_name,d.department_name 
		                     FROM master_setup a,sec_app_module b,master_divisi c, master_department d 
		                     where a.module_id=b.module_id and a.divisi_id=c.divisi_id and a.department_id=d.department_id and a.id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=numbersetup&act=update>
	          <input type=hidden name=id value='$r[id]'>
	          <fieldset><legend>Edit Number Setup</legend>
			  <label>Setup :</label>
			  <select name='jenis' id='jenis'><option value='$r[jenis]'>$r[jenis]</option>
					<option value='Claim'>Claim</option>
					<option value='Payment'>Payment</option>
					<option value='Reco'>Reco</option>
			  </select><br>
			  <label>Divisi :</label>
			  <input type='text' name='divisi' id='divisi' Value='$r[divisi_id]'><br>
			  <label>Departemen :</label>
			  <input type='text' name='departemen' id='departemen' Value='$r[department_id]' ><br>
			  <label>Prefix :</label>
			  <input type='text' name='prefix' id='prefix' value='$r[prefix]' required><br>
			  <label>Number :</label>
			  <input type='text' name='number' class='easyui-numberbox input-small' value='$r[number]' required><br>
			  <label>Modul :</label>
			  <select name='module_id' ><option value='$r[module_id]'>$r[module_name]</option>";
			  $mod = mysql_query("select * from sec_app_module order by module_name");
			  while($rmod =  mysql_fetch_array($mod)){
				echo "<option value='$rmod[module_id]'>$rmod[module_name]</option>";
			  }
		echo "</select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
