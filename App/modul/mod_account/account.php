<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_account/aksi_account.php";
ses_module();
switch($_GET[act]){
  // Tampil account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Account</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=account&act=tambahaccount';\">Tambah</button><br><br>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode account</th><th>Nama account</th><th>Type</th><th>Type of Cost</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
		$tampil = "select account_id,account_name,substring(created_by,1,1)as tipe_biaya,substring(created_by,2,1)as typeofcost from 
		          (select *,row_number() over( order by account_id) as 'rownumber' from account where substring(created_by,1,1) in('O','P') ) a
				   order by account_id;";
		$qtampil = odbc_exec($conn2,$tampil);
		while ($r=odbc_fetch_array($qtampil)){
		      echo "<tr>
						<td>$r[account_id]</td>
						<td>$r[account_name]</td>
						<td>$r[tipe_biaya]</td>
						<td>$r[typeofcost]</td>
			            <td><a href='index.php?r=account&act=editaccount&id=$r[account_id]'><i class='icon-pencil'></i></a> | 
						    <a href='$aksi?r=account&id=$r[account_id]&act=hapus'><i class='icon-trash'></i></a>
			            </td>
					</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahaccount":
	$access = create_security();
	if($access =="allow"){
	    echo "<form class='form-horizontal' method='post' action='$aksi?r=account&act=update'>
				<fieldset><legend>Tambah Account</legend>
					<div class='control-group'>
							<label class='control-label' for='id'>Kode Account</label>
							<div class='controls'>
								<input type='text' id='id' name='id' >
							</div>
					</div>
					<div class='control-group'>
							<label class='control-label' for='tipe_biaya'>Tipe Biaya</label>
							<div class='controls'>
								<select name='tipe_biaya' class='input-mini' required>
									<option value= 'P'>P</option> 
									<option value= 'O'>O</option> 
								</select>
							</div>
					</div>
					<div class='control-group'>
							<label class='control-label' for='typeofcost'>Type of Cost</label>
							<div class='controls'>
								<select name='typeofcost' class='input-mini' required>
									<option value= 'F'>F</option> 
									<option value= 'V'>V</option> 
								</select>
							</div>
					</div>
					<div class='control-group'>
							<div class='controls'>
								<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
								<input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
							</div>
					</div>				
				</fieldset>
			  </form>";
	}else{
		msg_security();
	}
     break;
 
  case "editaccount":
	$access = update_security();
	if($access=="allow"){
    $edit = "SELECT account_id,account_name,substring(created_by,1,1)as tipe_biaya,
			substring(created_by,2,1)as typeofcost FROM account WHERE account_id='$_GET[id]'";
	$qedit = odbc_exec($conn2,$edit);
    $r    = odbc_fetch_array($qedit);

     echo "<form class='form-horizontal' method='post' action='$aksi?r=account&act=update'>
				<fieldset><legend>Edit Account</legend>
					<div class='control-group'>
							<label class='control-label' for='id'>Kode Account</label>
							<div class='controls'>
								<input type=text name=id value='$r[account_id]' >
							</div>
					</div>
					<div class='control-group'>
							<label class='control-label' for='account_name'>Nama Account</label>
							<div class='controls'>
								<input type=text name=account_name value='$r[account_name]' class='input-xlarge'>
							</div>
					</div>
					<div class='control-group'>
							<label class='control-label' for='tipe_biaya'>Tipe Biaya</label>
							<div class='controls'>
								<select name='tipe_biaya' class='input-mini' required>
								    <option value='$r[tipe_biaya]' selected>$r[tipe_biaya]</option>
									<option value= 'P'>P</option> 
									<option value= 'O'>O</option> 
								</select>
							</div>
					</div>
					<div class='control-group'>
							<label class='control-label' for='typeofcost'>Type of Cost</label>
							<div class='controls'>
								<select name='typeofcost' class='input-mini' required>
								    <option value='$r[typeofcost]' selected>$r[typeofcost]</option>
									<option value= 'F'>F</option> 
									<option value= 'V'>V</option> 
								</select>
							</div>
					</div>
					<div class='control-group'>
							<div class='controls'>
								<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
								<input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
							</div>
					</div>				
				</fieldset>
			  </form>";
	}else{
		msg_security();
	}
    break;  
}
?>
