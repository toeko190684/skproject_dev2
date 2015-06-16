<script>
$(document).ready(function(){
	$('#divisi').change(function(){
		var divisi_id = $(this).val(); 
		var departemen_id = '<?php echo $_SESSION[department_id]; ?>';
		var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
		$.post('../function/get_data.php?data=departemen',{id:divisi_id,departemen_id : departemen_id, grade_id : grade_id},function(data){
			$('#departemen').html(data);
		});
	});

	$('#departemen').change(function(){
		var department_id = $(this).val();
		$.post('../function/get_data.php?data=subdepartemen',{id : department_id},function(data){
			$('#subdepartemen').html(data);
		});
	});
	
	$('#subdepartemen').change(function(){
		var subdepartemen_id = $(this).val();
		$.post('../function/get_data.php?data=promotype',{id : subdepartemen_id},function(data){
			$('#promotype').html(data);
		});
	});
	
	$('#promotype').change(function(){
		var class_id = $(this).val();
		$.post('../function/get_data.php?data=class',{id : class_id},function(data){
			$('#class').html(data);
		});
	});
	
	$('#tahun').change(function(){
		var tahun = $(this).val();
		$.post('../function/get_data.php?data=bulan',{id:tahun},function(data){
			$('#bulan').html(data);
		});
	});

	$('#kode_budget').blur(function(){
	    var kode_budget = $('#kode_budget').val();
		$.post('../function/get_data.php?data=cek_kodebudget',{id:kode_budget},function(data){
			 if(data=="ketemu"){
				$.messager.alert('SKProject',"Kode "+kode_budget+", sudah pernah diinput..!",'info'); 
				$('#kode_budget').val("");
				$('#kode_budget').focus();
			}
		});
	});
	
	$('#cari').click(function(){
		var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
		var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
		var department_id = '<?php echo $_SESSION[department_id]; ?>';
		var tahun = $('#thn').val();
		var bulan = $('#bln').val();
		if (tahun==''){
			$.messager.alert('SKProject','Tahun tidak boleh kosong!!','info');
		}else if (bulan==''){
			$.messager.alert('SKProject','Bulan tidak boleh kosong!!','info');
		}else{
			$.post('modul/mod_masterbudget/get_data.php?data=tabel',{grade_id : grade_id,
																	divisi_id : divisi_id, 
																	department_id : department_id,
																	bulan : bulan,
																	tahun : tahun},function(data){
				$('#tampil').html(data);
			});
		}
	});
	
	$('#tampil').html(function(){
		var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
		var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
		var department_id = '<?php echo $_SESSION[department_id]; ?>';
		var tahun = $('#thn').val();
		$.post('modul/mod_masterbudget/get_data.php?data=all',{grade_id : grade_id,
																divisi_id : divisi_id, 
																department_id : department_id,
																tahun : tahun},function(data){
			$('#tampil').html(data);
		});
	});
	
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
	
});

function formatAngka(objek, separator) {
	  a = objek.value;
	  b = a.replace(/[^d]/g,"");
	  c = "";
	  panjang = b.length;
	  j = 0;
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;
}

</script>
<?php
$aksi="modul/mod_masterbudget/aksi_masterbudget.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Budget</h2><button type='button' class='btn btn-primary'  
			  onclick=\"window.location.href='index.php?r=masterbudget&act=tambahmasterbudget';\">
			  Tambah</button><br><Br>
			  
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
	          <thead>
				<tr class='success'>
					<th>Kode Budget</th><th>Periode</th><th>Keterangan</th><th>Value</th><th>Outstanding</th>
					<th>Cost</th><th>Status</th><th>aksi</th>
				</tr>
			  </thead><tbody>";
		
		if(($_SESSION[grade_id]=="*")||($_SESSION[grade_id]=="***")){
			$tampil=mysql_query("SELECT * FROM master_budget");
		}else{
			$tampil=mysql_query("SELECT * FROM master_budget where department_id='$_SESSION[department_id]'");
		}

		while ($r=mysql_fetch_array($tampil)){
            $cost =mysql_query("select cast($r[value] as decimal(15,2))-ifnull(sum(cast(a.value as decimal(15,2))),0)as outstanding_cost,ifnull(sum(a.value),0)as cost from detail_reco_item a, 
							   reco_request c where a.kode_reco=c.kode_promo and a.kode_budget='$r[kode_budget]' and upper(c.status)<>'REJECTED'");
			$rcost = mysql_fetch_array($cost);
			
			echo "<tr>
						<td>$r[kode_budget]</td>
						<td>$r[bulan] $r[tahun]</td>
						<td>$r[keterangan]</td>
						<td>".number_format($r[value],0,'.','.')."</td>
						<td>".number_format($rcost[outstanding_cost],0,'.','.')."</td>
						<td>".number_format($rcost[cost],0,'.','.')."</td>
						<td>$r[status]</td>
						<td><a href='index.php?r=masterbudget&act=editmasterbudget&id=$r[kode_budget]' class='btn btn-success btn-small'><i class='icon-pencil'></i> Edit</a>
				             <a href='$aksi?r=masterbudget&act=hapus&id=$r[kode_budget]' class='btn btn-danger btn-small'><i class='icon-trash'></i> Hapus</a>
							 <a href='index.php?r=masterbudget&act=emailsend&id=$r[kode_budget]' class='btn btn-info btn-small'><i class='icon-envelope'></i> Email</a>
							 
							 
						</td>
				  </tr>";
		}
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahmasterbudget":
	$access = create_security();
	if($access =="allow"){
	    echo "<form class='form-horizontal' method='POST' action='$aksi?r=masterbudget&act=input'>
	          <fieldset><legend>Tambah Master Budget</legend>
			  	<label >Kode Budget :</label>
				<input type='text' name='kode_budget' id='kode_budget' class='input-small' required><br>
			    <label >Keterangan :</label>
				<input type='text' name='keterangan'  required><br>
				<label >Kode Divisi :</label>
				<select name='divisi_id' id='divisi'  required><option>--Pilih Divisi--</option>";
					if($_SESSION[grade_id]=='*'){
						$divisi = mysql_query("select * from master_divisi order by divisi_name");
						while($rdivisi = mysql_fetch_array($divisi)){
							echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>";
						}
					}else{
						$divisi = mysql_query("select * from master_divisi where divisi_id='$_SESSION[divisi_id]'");
						$rdivisi = mysql_fetch_array($divisi);
						echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>"; 
					}
		echo "	</select><bR>
				<label>Kode Departemen :</label>
				<select name='departemen_id' id='departemen'  required><option>--Pilih Departemen--</option>";
					if($_SESSION[grade_id]=='*'){
						$departemen = mysql_query("select * from master_department order by department_name");
						while($rdepartemen = mysql_fetch_array($departemen)){
							echo "<option value='$rdepartemen[department_id]'>$rdepartemen[department_name]</option>";
						}
					}else{
					    if($_SESSION[grade_id]=='**'){
						    $departemen = mysql_query("select * from master_department where divisi_id='$rdivisi[divisi_id]' 
							                          order by department_name");
						}else{
							$departemen = mysql_query("select * from master_department where divisi_id='$rdivisi[divisi_id]' 
							                          and department_id='$_SESSION[department_id]' order by department_name");
						}
						while($rdepartemen = mysql_fetch_array($departemen)){
							echo "<option value='$rdepartemen[department_id]'>$rdepartemen[department_name]</option>";
						}
					}
		echo "	</select><br>
				<label>Sub Departemen:</label>
				<select name='subdepartemen_id' id='subdepartemen'  required><option>--Pilih SubDepartemen--</option>
				</select><br>
				<label>Periode :</label>
				<select name='tahun' id='tahun' class='input-small' required><option value=''>Tahun</option>";
				 $thn = mysql_query("select distinct tahun from periode where status='open' order by tahun");
				 while($rthn = mysql_fetch_array($thn)){
					echo "<option value='$rthn[tahun]'>$rthn[tahun]</option>";
				 }
		echo "  </select>&nbsp-&nbsp
				<select name='bulan' id='bulan' class='input-medium' required><option value='$_SESSION[bulan]'>$_SESSION[bulan]</option></select><br>
                <label>Tanggal Input :</label>
				<input type='text' class='easyui-datebox input-small' id='tgl_input' name='tgl_input' ><br>
				<label>Value :</label>
				<input type='text' name='value' class='easyui-numberbox'  groupSeparator=',' data-options='min:0' required><br><br>
				<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
				<input type='button' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			</fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editmasterbudget":
	$access = update_security();
	if($access=="allow"){
		$edit = mysql_query("SELECT * FROM v_master_budget where kode_budget='$_GET[id]'");
		$r    = mysql_fetch_array($edit);
		
	
		if(strtoupper($r[status])=='PENDING'){
		    echo "<form method=POST action=$aksi?r=masterbudget&act=update>
		          <input type=hidden name=id value='$r[kode_budget]'>
		          <fieldset><legend>Edit Master Budget</legend>
				  <label >Kode Budget :</label>
						<input type='text' name='kode_budget' class='input-small'  value='$r[kode_budget]' required><br>
					    <label >Keterangan :</label>
						<input type='text' name='keterangan' value='$r[keterangan]' required><br>
						<label >Kode Divisi :</label>
						<select name='divisi_id'  id='divisi' required><option value='$r[divisi_id]'>$r[divisi_name]</option></select><bR>
						<label>Kode Departemen :</label>
						<select name='departemen_id'  id='departemen' required><option value='$r[department_id]'>$r[department_name]</option>
						</select><br>
						<label>Sub Departemen:</label>
						<select name='subdepartemen_id' id='subdepartemen'	required><option value='$r[subdepartemen_id]'>$r[subdepartemen_name]</option>";
								$subdepartemen = mysql_query("select * from master_subdepartemen order by subdepartemen_name");
								while($rsubdepartemen = mysql_fetch_array($subdepartemen)){
									echo "<option value='$rsubdepartemen[subdepartemen_id]'>$rsubdepartemen[subdepartemen_name]</option>";
								}						
				echo "	</select><br>
						<label>Periode :</label>
						<select name='tahun' id='tahun' class='input-small' required><option value='$r[tahun]'>$r[tahun]</option>";
						$thn = mysql_query("select distinct tahun from periode where status='open' order by tahun");
						 while($rthn = mysql_fetch_array($thn)){
							echo "<option value='$rthn[tahun]'>$rthn[tahun]</option>";
						 }
				echo "  </select>&nbsp;&nbsp
						</select>&nbsp;&nbsp
						<select name='bulan' id='bulan' class='input-medium' required><option value='$r[bulan]'>$r[bulan]</option></select><br>
		                <label>Tanggal Input :</label>
						<input type='text' class='easyui-datebox input-small' name='tgl_input' value='$r[tgl_input]'><br>
						<label>Value :</label>
						<input type='text' name='value' class='easyui-numberbox' value='$r[value]' groupSeparator=',' data-options='min:0' required><br><br>
						<input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
						<input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
					</fieldset></form>";
		}else{
				echo "<div class='alert alert-error'>
							Kode budget $r[kode_budget] sudah di $r[status] oleh $r[approval1] Tgl $r[tgl_approval1]
					  </div>";
		}
	}else{
		msg_security();
	}
    break;  
	
	case "emailsend" :
		/* tgl modifikasi tanggal 13-5-215 penambahan case emailsend dengan selectnya menggunakan pdo. */		
		$access = read_security();
		if($access=="allow"){				
			?>  
			  <h2>#Email Status</h2><button type='button' class='btn btn-primary'  
			  onclick="window.history.go(-1)">
			  Back</button><br><Br>				  
				  <table id='example' class='compact table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
				  <thead>
					<tr>
						<th>ID</th><th>To_Cc</th><th>Subject</th><th>Kode</th><th>Status</th>
						<th>Tanggal</th>
					</tr>
				  </thead>
				  <tbody>
				  
			<?php
			$sql = $db->query("select * from email where kode='".$_GET[id]."'");
			while($data = $sql->fetch(PDO::FETCH_OBJ)){
					echo "<tr>
							<td>$data->id</td>
							<td>$data->to_cc</td>
							<td>$data->subject</td>
							<td>$data->kode</td>
							<td>$data->status</td>
							<td>".date('d M Y',strtotime($data->date))."</td>
					  </tr>";
			}
			?>
				  </tbody>
			</table>
			<?php
		}else{
			msg_security();
		}
	break;
}
?>
