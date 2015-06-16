<script>
$(document).ready(function(){
	$('#distributor').combogrid({
			panelWidth : 430,
			idField : 'distributor_id',
			textField : 'distributor_name',
			url : '../function/get_data.php?data=json_distributor',
			columns : [[
			    {field : 'distributor_id',title:'Account ID', width : 120 },
				{field : 'distributor_name', title : 'Account Name', width : 300}
			]],
			onChange : function(value){
				var distributor =  $(this).combogrid('getValue');
				$('#kodereco').combogrid({
						panelWidth : 630,
						idField : 'kode_promo',
						textField : 'kode_promo',
						url : 'modul/mod_claimrequest/get_data.php?data=json_reco&id='+distributor,
						columns : [[
						    {field : 'kode_promo',title:'Kode Reco', width : 200 },
							{field : 'title', title : 'title', width : 420}
						]],
						onChange : function(value){
							var status = $('#status').val();
							var kodereco =  $(this).combogrid('getValue');
							$.post('modul/mod_claimrequest/get_data.php?data=json_detailreco',{id : kodereco},function(data){
								var obj = $.parseJSON(data);
								if((obj.status=='rejected')||(obj.status=='pending')){
									$.messager.alert("SKProject","Kode "+kodereco+" statusnya "+obj.status+"!!","error");
									$('#batal').click();
								}else{
									$('#costofpromo').numberbox('setValue',obj.cost_of_promo);															
									$('#deskripsi').val(obj.title);
									$('#coa').combogrid('setValue',obj.account_id);
									//cari outstanding 
									$.post('../function/get_data.php?data=json_outstanding',{ id : kodereco },function(data1){
										var outstanding = eval(obj.cost_of_promo)-eval(data1);
										$('#outstanding').numberbox('setValue',outstanding);
										$('#claimapprovedamount').numberbox('setValue',outstanding);
										var totalclaimapproveamount = (eval($('#ppn').numberbox('getValue'))+eval(100))*eval(outstanding)/100;
										$('#totalclaimapprovedamount').numberbox('setValue',totalclaimapproveamount);	
									});
								}
							});	
                        }							
				});
			}
	});
	
	
	$('#coa').combogrid({
			panelWidth : 430,
			idField : 'account_id',
			textField : 'account_id',
			url : '../function/get_data.php?data=json_coa',
			columns : [[
					{field : 'account_id',title:'Account ID', width : 100 },
					{field : 'account_name', title : 'Account Name', width : 290}
			]]
	});
	
	$('#vendor_id').combogrid({
			panelWidth : 430,
			idField : 'vendor_id',
			textField : 'vendor_id',
			url : 'modul/mod_claimrequest/get_data.php?data=vendor',
			columns : [[
					{field : 'vendor_id',title:'Vendor ID', width : 100 },
					{field : 'vendor_name', title : 'Vendor Name', width : 160},
					{field : 'ap_account_type',title:'AP Acc Type', width : 80 },
					{field : 'ap_account_id',title:'AP Acc ID', width : 80 }
			]]
	});
	
	//bikin running number untuk reco 
	$('#create').click(function(){
	    var divisi_id = "<?php echo $_SESSION[divisi_id]; ?>";
		var department_id = "<?php echo $_SESSION[department_id]; ?>";
		var tanggal = "<?php echo $_SESSION[tanggal]; ?>";
		var module_id = "<?php echo $_GET[mod]; ?>";
		//poting untuk bikin number
		$.post('modul/mod_claimrequest/get_data.php?data=claimnumber',{id : module_id,divisi : divisi_id,departemen : department_id},function(data){
		    var obj = $.parseJSON(data);
			var number;
			if(obj.number.length==1){
				number = '000'+obj.number;
			}else if(obj.number.length==2){
			    number = '00'+obj.number;
			}else if(obj.number.length==3){
			    number = '0'+obj.number;
			}else if(obj.number.length==4){
			    number = obj.number;
			}
			var prefix = obj.prefix+tanggal+number;
			$('#claimnumbersystem').val(prefix);
		});
	});
	
	$('#detailbutton').click(function(){
		var kode_reco = $('#kodereco').combogrid('getValue');
		$.get('modul/mod_claimrequest/get_data.php?data=json_btndetailreco&id='+kode_reco, function(data){
				$('#detailbody').html(data);
		});
	});
	
    $('#simpan').click(function(){
		var module_id = '<?php echo $_GET[mod]; ?>';
		var divisi_id = '<?php echo $_SESSION[divisi_id]?>';
		var department_id = '<?php echo $_SESSION[department_id]; ?>';
		var user_id = '<?php echo $_SESSION[user_id]; ?> ';
		var claimnumbersystem = $('#claimnumbersystem').val();
		var claimnumberdist = $('#claimnumberdist').val();
		var distributor = $('#distributor').combogrid('getValue');
		var claimdate = $('#claimdate').datebox('getValue');
		var kodereco = $('#kodereco').combogrid('getValue');
		var costofpromo = $('#costofpromo').numberbox('getValue');
		var posonumber = $('#posonumber').val();
		var ppn = $('#ppn').numberbox('getValue');
		var cekppn = $('#cekppn').is(':checked');
		var nomorfakturpajak = $('#nomorfakturpajak').val();
		var deskripsi = $('#deskripsi').val();
		var claimapprovedamount = $('#claimapprovedamount').numberbox('getValue');
		var totalclaimapprovedamount = $('#totalclaimapprovedamount').numberbox('getValue');
		var coa = $('#coa').combogrid('getValue');
		var vendor_id = $('#vendor_id').combogrid('getValue');
		if(claimnumbersystem==''){
			$.messager.alert('SKProject','Claim Number System tidak boleh kosong..!','info');
		/*}else if(claimnumberdist==''){
			$.messager.alert('SKProject','Claim Number Dist tidak boleh kosong..!','info');*/
		}else if(distributor==''){
			$.messager.alert('SKProject','Distributor tidak boleh kosong..!','info');
		}else if(claimdate==''){
			$.messager.alert('SKProject','Claim Date tidak boleh kosong..!','info');
		}else if(kodereco==''){
			$.messager.alert('SKProject','Kode Reco tidak boleh kosong..!','info');
		}else if(costofpromo==''){
			$.messager.alert('SKProject','Cost Of Promo tidak boleh kosong..!','info');
		/*}else if(posonumber==''){
			$.messager.alert('SKProject','PO SO Number tidak boleh kosong..!','info');*/
		}else if(deskripsi==''){
			$.messager.alert('SKProject','Deskripsi tidak boleh kosong..!','info');
		}else if(claimapprovedamount=='' || claimapprovedamount==0){
			$.messager.alert('SKProject','Claim Approved Ammount tidak boleh kosong..!','info');
		}else if(totalclaimapprovedamount=='' || totalclaimapprovedamount==0){
			$.messager.alert('SKProject','Total Claim Approved Ammount tidak boleh kosong..!','info');
		}else if(coa==''){
			$.messager.alert('SKProject','Chart Of Account tidak boleh kosong..!','info');
		}else if(vendor_id==''){
			$.messager.alert('SKProject','Vendor ID tidak boleh kosong..!','info');
		}else{
		    if(cekppn=='true'){
			    if(nomorfakturpajak==''){
					$.messager.alert('SKProject','Nomor Faktur Pajak tidak boleh kosong jika ada PPN.!!','info');
				}else{
					$.post('modul/mod_claimrequest/get_data.php?data=simpan_claim',{
																	module_id : module_id,
																	divisi_id : divisi_id,
																	department_id : department_id,
																	user_id : user_id,
																	claimnumbersystem : claimnumbersystem,
																	claimnumberdist : claimnumberdist,
																	distributor : distributor,
																	claimdate : claimdate,
																	kodereco : kodereco,
																	costofpromo : costofpromo,
																	posonumber : posonumber,
																	ppn : ppn,
																	nomorfakturpajak : nomorfakturpajak,
																	deskripsi : deskripsi,
																	claimapprovedamount : claimapprovedamount,
																	totalclaimapprovedamount : totalclaimapprovedamount,
																	coa : coa,
																	vendor_id : vendor_id
																	},function(data){
							$.messager.alert('SKProject',data,'info');			
					});
				}
			}else{
				$.post('modul/mod_claimrequest/get_data.php?data=simpan_claim',{
																	module_id : module_id,
																	divisi_id : divisi_id,
																	department_id : department_id,
																	user_id : user_id,
																	claimnumbersystem : claimnumbersystem,
																	claimnumberdist : claimnumberdist,
																	distributor : distributor,
																	claimdate : claimdate,
																	kodereco : kodereco,
																	costofpromo : costofpromo,
																	posonumber : posonumber,
																	ppn : ppn,
																	nomorfakturpajak : nomorfakturpajak,
																	deskripsi : deskripsi,
																	claimapprovedamount : claimapprovedamount,
																	totalclaimapprovedamount : totalclaimapprovedamount,
																	coa : coa,
																	vendor_id : vendor_id
																	},function(data){
						$.messager.alert('SKProject',data,'info');			
				});
			}
		}
		$('#status').val('');
	});
	
	
	$('#edit').click(function(){
	    $('#status').val('edit');
		$.messager.prompt('SKProject','Masukan Nomor Claim :',function(r){
			if(r){
				$.post('modul/mod_claimrequest/get_data.php?data=json_claim',{id : r},function(data){
							 var obj = $.parseJSON(data);
							 if(obj.status=='pending'){									
									 $('#claimnumbersystem').val(obj.claim_number_system);
									 $('#claimnumberdist').val(obj.claim_number_dist);
									 $('#distributor').combogrid('setValue',obj.distributor_id);
									 $('#claimdate').datebox('setValue',obj.claim_date);
									 $('#kodereco').combogrid('setValue',obj.kode_promo);
									 $('#costofpromo').numberbox('setValue',obj.costofpromo);
									 $('#posonumber').val(obj.po_so_number);						 
									 $('#nomorfakturpajak').val(obj.nomor_faktur_pajak);
									 $('#deskripsi').val(obj.deskripsi);
									 $('#claimapprovedamount').numberbox('setValue',obj.claim_approved_ammount);
									 $('#totalclaimapprovedamount').numberbox('setValue',obj.total_claim_approved_ammount);
									 $('#coa').combogrid('setValue',obj.coa);	
									 $('#vendor_id').combogrid('setValue',obj.vendor_id);									 
									 if(obj.ppn>0){
										$('#cekppn').attr('checked',true);
										$('#nomorfakturpajak').removeAttr('disabled');
										$('#ppn').removeAttr('disabled');
										$('#nomorfakturpajak').val(obj.nomor_faktur_pajak);
										$('#ppn').numberbox('setValue',obj.ppn);						
									 }else{
									    $('#cekppn').attr('checked',false);
										$('#nomorfakturpajak').attr('disabled',true);
										$('#ppn').attr('disabled',true);								
									 }
									 $.post('../function/get_data.php?data=json_outstanding',{ id : $('#kodereco').combogrid('getValue') },function(data1){
											$('#outstanding').val(eval(obj.costofpromo)-eval(data1)+eval(obj.claim_approved_ammount));
									 });
							 }else{
									$('#status').val('');
									$.messager.alert('SKProject','Claim Number '+obj.claim_number_system+' tidak bisa di edit karena sudah di '+obj.status+' oleh : '+obj.approve_by+' tgl : '+obj.tgl_approve,'info');
							 }
				});
			}else{
			    $('#status').val('');
				$.messager.alert('SKProject','Permintaan dibatalkan..!','info');
			}
		});
	});
	
	$('#cekppn').change(function(){
        if($(this).is(':checked')){
		    $('#ppn').removeAttr('disabled');
			$('#nomorfakturpajak').removeAttr('disabled');
			$('#ppn').numberbox('setValue',0);
			$('#ppn').focus();
			var claimapprovedamount = $('#claimapprovedamount').numberbox('getValue');
			$('#totalclaimapprovedamount').numberbox('setValue',claimapprovedamount);
		}else{
		    $('#ppn').attr('disabled',true);
			$('#nomorfakturpajak').attr('disabled',true);
			$('#nomorfakturpajak').val('');
			$('#ppn').val(0);
			var claimapprovedamount = $('#claimapprovedamount').numberbox('getValue');
			$('#totalclaimapprovedamount').numberbox('setValue',claimapprovedamount);
		}
	});
	
	$('#ppn').keyup(function(e){
		var claimapprovedamount = $('#claimapprovedamount').numberbox('getValue');
		var x = $(e.target).val();
		var y = (100+eval(x))*eval(claimapprovedamount)/100;
		$('#totalclaimapprovedamount').numberbox('setValue',y);
	});
	
	$('#claimapprovedamount').keyup(function(e){
		var status = $('#status').val();
		var ppn = $('#ppn').numberbox('getValue');
		var x = $(e.target).val();
		var y = (100+eval(ppn))*eval(x)/100;
		var costofpromo = $('#costofpromo').val();
		if(status == 'edit'){
			$('#totalclaimapprovedamount').numberbox('setValue',y);
		}else{
			if( y > $('#outstanding').numberbox('getValue')){
				$.messager.alert('SKProject','Claim lebih besar dari outstanding..!','warning');
				$(this).numberbox('setValue',$('#outstanding').numberbox('getValue'));
				var totalclaimapproveamount = (eval(ppn)+eval(100)) * eval($('#outstanding').numberbox('getValue'))/100;
				$('#totalclaimapprovedamount').numberbox('setValue', totalclaimapproveamount);
			}else{
				$('#totalclaimapprovedamount').numberbox('setValue',y);
			}
		}
	});

});

</script>
<?php
$aksi="modul/mod_claimrequest/aksi_claimrequest.php";
ses_module();
switch($_GET[act]){
  // Tampil claimrequest
  default:
			$access = create_security();
			if($access =="allow"){
				
			}else{
				msg_security();
			}
     break;
	 
	 case "tambahclaim" :
			$access = insert_security();
			if($access =="allow"){
				//action='$aksi?r=claimrequest&act=input'
				echo "<input id='status' type='hidden' value=''>";
				echo "<form class='form-horizontal' method='post' >
					  <fieldset><legend>Claim Request</legend>
					  <div class='span7'>
							  <div class='control-group'>
									<label class='control-label' for='claimnumbersystem'>Claim Number System</label>
									<div class='controls'>
										<input type=hidden name='divisi' value='$_SESSION[divisi_id]'>
										<input type=hidden name='departemen' value='$_SESSION[department_id]'>
										<input type=hidden name='module' value='$_SESSION[mod]'>
										<input type='text' id='claimnumbersystem' name='claimnumbersystem' disabled>
										<button type=button id='create' class='btn btn-primary'>Create</button>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='claimnumberdist'>Claim Number Dist.</label>
									<div class='controls'>
										<input type='text' id='claimnumberdist' name='claimnumberdist'>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='distributor'>Distributor</label>
									<div class='controls'>
										<input id='distributor' name='distributor' >
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='claimdate'>Claim Date</label>
									<div class='controls'>
										<input type='text' id='claimdate' name='claimdate' class='easyui-datebox input-small'>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='kodereco'>Kode Reco</label>
									<div class='controls'>
										<input  id='kodereco' name='kodereco'  class='easyui-combogrid'>
										<a href='#detailreco' id='detailbutton' role='button' class='btn btn-primary' data-toggle='modal'>Detail Reco</a>
									</div>
							  </div>			
							  <div class='control-group'>
									<label class='control-label' for='costofpromo'>Cost Of Promo</label>
									<div class='controls'>
										<input type='text' id='costofpromo' name='costofpromo' class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value=0 disabled>
									</div>
							  </div>	
							  <div class='control-group'>
									<label class='control-label' for='outstanding'>Outstanding Amount</label>
									<div class='controls'>
										<input type='text' id='outstanding' name='outstanding' class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value=0 disabled>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='posonumber'>PO SO Number</label>
									<div class='controls'>
										<input type='text' id='posonumber' name='posonumber' >
									</div>
							  </div>
					  </div>
					  <div class='span5'>
							  <div class='control-group'>
									<label class='control-label' for='cekppn'><input type=checkbox name='cekppn' id='cekppn' value=10>&nbsp&nbspPPN&nbsp&nbsp</label>
									<div class='controls'>
										<input type='text' id='ppn' name='ppn' class='easyui-numberbox input-mini' value=0 groupSeparator=',' disabled>&nbsp%
									</div>
							  </div>	
							  <div class='control-group'>
									<label class='control-label' for='nomorfakturpajak'>Nomor Faktur Pajak</label>
									<div class='controls'>
										<input type='text' id='nomorfakturpajak' name='nomorfakturpajak' disabled='disabled'>
									</div>
							  </div>	
							  <div class='control-group'>
									<label class='control-label' for='deskripsi'>Deskripsi</label>
									<div class='controls'>
										<textarea id='deskripsi' name='deskripsi'></textarea>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='claimapprovedamount'>Claim Approved Amount</label>
									<div class='controls'>
										<input type='text' id='claimapprovedamount' name='claimapproveamount' class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value=0>
									</div>
							  </div>	
							  <div class='control-group'>
									<label class='control-label' for='totalclaimapprovedamount'>Claim Approved Amount + PPN </label>
									<div class='controls'>
										<input type='text' id='totalclaimapprovedamount' name='totalclaimapproveamount' disabled class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value=0>
									</div>
							  </div>
							  <div class='control-group'>
									<label class='control-label' for='coa'>Chart of Account</label>
									<div class='controls'>
										<input type='text' id='coa' name='coa' class='easyui-combogrid input-large'>
									</div>
							  </div>		
							  <div class='control-group'>
									<label class='control-label' for='vendor_id'>Vendor</label>
									<div class='controls'>
										<input type='text' id='vendor_id' name='vendor_id' class='easyui-combogrid input-medium'>
									</div>
							  </div>					  
							  <div class='control-group'>
									<div class='controls'>
										<input type='button' id='simpan' class='btn btn-primary' value='Simpan'>
										<input type='button' id='edit' class='btn btn-success' value='Edit'>
										<input type ='reset' id='batal' class='btn btn-danger'  value='Batal'>
									</div>
							  </div>					  
					  </div>
					  </fieldset></form>";
					  
					  //modal yang bisa muncul untuk detail kode reco 
				echo "<div id='detailreco' class='modal hide fade span9' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
							<h3 id='myModalLabel'>Reco Detail</h3>
						</div>
						<div class='modal-body'>
							<div id='detailbody'></div>
						</div>
						<div class='modal-footer'>
							<button class='btn btn-danger' data-dismiss='modal' aria-hidden='true'>Close</button>
						</div>
						</div>";	
			}else{
				msg_security();
			}	 
	 break;
}
?>
