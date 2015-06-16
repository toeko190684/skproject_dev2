<script>
$(document).ready(function(){
	$('#batal').click(function(){
		window.location = "index.php?r=claimapproval";
	});
	
	$('#simpan').click(function(){
	    var status = $('input[name="approve"]:checked').val();
		var user_id = '<?php echo $_SESSION[user_id];?>';
		var claim_number_system = $('#id').val();
		if(status=='approved'){
				$.messager.confirm('Confirm','Yakin akan diteruskan ke posting jurnal ?',function(r){
						if(r){	
							$.post('../function/get_data.php?data=claim_approval',{
							                                                       user_id : user_id,
																				   status : status,
																				   id : claim_number_system
														                           },function(data){
								    $.messager.alert('SKProject',data,'info');		
							});
						}else{
							$.messager.alert('SKProject','Permintaan dibatalkan.! ','info');
						}
					});
		}else{//jika direject
					$.post('../function/get_data.php?data=claim_approval',{
							                                                       user_id : user_id,
																				   status : status,
																				   id : claim_number_system
														                           },function(data){
								    $.messager.alert('SKProject',data,'info');	
							});
		}
	});
	
	$('#cari').click(function(){
		var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
		var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
		var department_id = '<?php echo $_SESSION[department_id]; ?>';
		var tahun = $('#thn').val();
		var bulan = $('#bln').val();
		var status = $('#status').val();
		var key = $('#key').val();
		var id = $('#id').val();
		if (tahun==''){
			$.messager.alert('SKProject','Tahun tidak boleh kosong!!','info');
		}else if (bulan==''){
			$.messager.alert('SKProject','Bulan tidak boleh kosong!!','info');
		}else{
			$.post('modul/mod_claimapproval/get_data.php?data=tabel',{grade_id : grade_id,
																	divisi_id : divisi_id, 
																	department_id : department_id,
																	bulan : bulan,
																	tahun : tahun,
																	status : status,
																	key : key,
																	id : id},function(data){
				$('#tampil').html(data);
			});
		}
	});
	
	$('#tampil').html(function(){
			var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
			var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
			var department_id = '<?php echo $_SESSION[department_id]; ?>';
			var tahun = $('#thn').val();
			var bulan = $('#bln').val();
			var status = $('#status').val();
			var key = $('#key').val();
			var id = $('#id').val();
			$.post('modul/mod_claimapproval/get_data.php?data=all',{grade_id : grade_id,
																	divisi_id : divisi_id, 
																	department_id : department_id,
																	tahun : tahun,
																	bulan : bulan,
																	status : status,
																	key : key,
																	id : id},function(data){
				$('#tampil').html(data);
			});
		});
	
	$('#key').change(function(){
		$('#id').focus();
	});
	
});

</script>
<?php
$aksi="modul/mod_claimapproval/aksi_claimapproval.php";
ses_module();
switch($_GET[act]){
  // Tampil claimapproval
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<ul class='nav nav-tabs' id='myTab'>
				  <li class='active'><a href='#claim_approval' data-toggle='tab'>Claim Approval</a></li>
			  </ul>
			  <div class='tab-content'>
				  <div class='tab-pane active' id='claim_approval'>
				          <form class='form-inline' >	
					<div class='input-append'>
					    <div class='span1'></div>
						<select name='thn' id='thn' class='input-small'>";
						for($i=0;$i<5;$i++){
							$thn = date('Y');
							$thn = $thn-$i;
							echo "<option value='$thn'>$thn</option>";
						}
		echo "			</select>
						<select name='bln' id='bln' class='input-medium'>";
							$bln = mysql_query("select * from month");
							while($rbln = mysql_fetch_array($bln)){
								echo "<option value='$rbln[month_id]'>$rbln[month_name]</option>";
							}
		echo "			</select>
						<select name='status' id='status' class='input-medium'>
							<option value='pending'>Pending</option>
							<option value='approved'>Approved</option>
							<option value='rejected'>Rejected</option>
		     			</select>
						<select name='key' id='key' class='input-large'>
							<option value='claim_number_system'>Claim Number System</option>
							<option value='kode_promo'>Kode Reco</option>
		     			</select>
						<input type='text' id='id' class='input-large' >
					    <button id='cari' class='btn' type='button'>Cari</button>
					</div><br>
				    </form>					  
				    <div id='tampil' ></div>
						  				</div>";
	}else{
		msg_security();
	}
    break;

  
  case "editclaimapproval":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from claim_request WHERE claim_number_system='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
	
    $sisa = mysql_query("select a.cost_of_promo-sum(b.claim_approved_ammount) as sisa from reco_request a,
	                    claim_request b where a.kode_promo=b.kode_promo and a.kode_promo='$r[kode_promo]' 
	        		    and b.status<>'rejected'");
	$rsisa = mysql_fetch_array($sisa);
	
	if(strtoupper($r[status])=='PENDING'){ $class = "label label-warning"; }
	elseif(strtoupper($r[status])=='APPROVED'){ $class = "label label-success"; }
	else{ $class = "label label-important"; }	
	
	echo "<form><fieldset><legend>Claim Approval</legend>
	      <input type=hidden id='id' value = '$r[claim_number_system]'>
	      <table>
		  <tR><td>Claim Number System</td><td>:</td><td>$r[claim_number_system]</td></tr>
		  <tR><td>Claim Number Dist</td><td>:</td><td>$r[claim_number_dist]</td></tr>
		  <tR><td>Distributor</td><td>:</td><td>$r[distributor_id]</td></tr>
		  <tR><td>Claim Date</td><td>:</td><td>$r[claim_date]</td></tr>
		  <tR><td>Kode Promo</td><td>:</td><td>$r[kode_promo]</td></tr>
		  <tR><td>PO SO Number</td><td>:</td><td>$r[po_so_number]</td></tr>
		  <tR><td>PPN</td><td>:</td><td>$r[ppn]</td></tr>
		  <tR><td>PPH</td><td>:</td><td>$r[pph]</td></tr>
		  <tR><td>Nomor Faktur Pajak</td><td>:</td><td>$r[nomor_faktur_pajak]</td></tr>
		  <tR><td>Deskripsi</td><td>:</td><td>$r[deskripsi]</td></tr>
		  <tR><td>Cost Of Promo</td><td>:</td><td>Rp. ".number_format($r[costofpromo],2,'.',',')."</td></tr>
		  <tR><td>Cost Of Promo Left</td><td>:</td><td>Rp. ".number_format($rsisa[sisa],2,'.',',')."</td></tr>
		  <tR><td>Claim approved ammount</td><td>:</td><td>Rp. ".number_format($r[claim_approved_ammount],2,'.',',')."</td></tr>
		  <tR><td>Claim approved ammount + PPN</td><td>:</td><td>Rp. ".number_format($r[total_claim_approved_ammount],2,'.',',')."</td></tr>
		  <tR><td>COA</td><td>:</td><td>$r[coa]</td></tr>
		  <tR><td>Vendor ID</td><td>:</td><td>$r[vendor_id]</td></tr>
		  <tR><td>Status</td><td>:</td><td><span class='$class'>$r[status]</span></td></tr>
		  <tR><td>Created by</td><td>:</td><td>$r[created_by]</td></tr>
		  <tR><td>Tgl Create</td><td>:</td><td>$r[last_update]</td></tr>
		  <tR><td>AP Journal</td><td>:</td><td>$r[journal_id]</td></tr>
		  <tr><td colspan=3><label class='radio inline'>";
		  if(trim($r[status])=='pending'){
				echo "<input type='radio' id='approve' name='approve' value='approved' checked>Approve</label>
		              <label class='radio inline'><input type='radio' id='approve' name='approve' value='rejected'>Reject</label>";
		  }else if(trim($r[status])=='approved'){
				echo "<input type='radio' id='approve' name='approve' value='approved' checked>Approve</label>
		              <label class='radio inline'><input type='radio' id='approve' name='approve' value='rejected'>Reject</label>";
		  }else if(trim($r[status])=='rejected'){
				echo "<input type='radio' id='approve' name='approve' value='approved'>Approve</label>
		              <label class='radio inline'><input type='radio' id='approve' name='approve' value='rejected' checked>Reject</label>";
		  }
	echo "</td></tr>		  
		  </table><br><BR>";
		  if(trim($r[status])=='pending'){
				echo "<input type='button' id='simpan' value='Simpan' class='btn btn-primary'>";
		  }else{
				echo "<input type='button' id='simpan' value='Simpan' class='btn btn-primary' disabled>";
		  }
	echo "&nbsp&nbsp<input type='button' id='batal' value='Batal' class='btn btn-danger'>
	      </fieldset></form>";
    
	}else{
		msg_security();
	}
    break;  
}
?>
