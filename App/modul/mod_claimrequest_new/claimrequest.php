<script>
$(document).ready(function(){
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
	
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
	
	$('#detailbutton').click(function(){
		var kode_reco = $('#kodereco').combogrid('getValue');
		$.get('modul/mod_claimrequest/get_data.php?data=json_btndetailreco&id='+kode_reco, function(data){
				$('#detailbody').html(data);
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

//============================================== function untuk membuat nomor claim=================================================================================================
function nomor(){
		var divisi_id = "<?php echo $_SESSION[divisi_id]; ?>";
		var department_id = "<?php echo $_SESSION[department_id]; ?>";
		var module_id = "<?php echo $_GET[mod]; ?>";
		var claim_date = $('#claimdate').datebox('getValue');
		var tgl = claim_date.split("/");
		if(tgl[0].length == 1){ prefix = tgl[2].substring(2,4)+"0"+tgl[0]; }else{ prefix = tgl[2].substring(2,4)+tgl[0]; } 
		$.post('modul/mod_claimrequest/get_data.php?data=claimnumber',{id : module_id,divisi : divisi_id,departemen : department_id,claim_date : claim_date },function(data){
			var number;
			if(data == "false"){
				number = "1";
			}else{
			    var obj = $.parseJSON(data);
				number = eval(obj.number) + 1;
			}
			if(number.length==1){
				number = '000'+number;
			}else if(number.length==2){
			    number = '00'+number;
			}else if(number.length==3){
			    number = '0'+number;
			}else if(number.length==4){
			    number = number;
			}
			var claim_code = 'CL\\MKI\\'+divisi_id+'\\'+department_id+'\\'+prefix+number;
			$('#claimnumbersystem').val(claim_code);
		});
	};
	
//===============================================================================================================================================================
</script>

<?php
$aksi="modul/mod_claimrequest/aksi_claimrequest.php";
ses_module();
switch($_GET[act]){
  // Tampil claimrequest
    
    default:
		echo "<h2>View All Claim Request</h2>
		      <a class='btn btn-primary' href='index.php?r=claimrequest&act=tambah_claim'>Tambah</a> 
		<br><Br>
			  
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
	          <thead>
				<tr class='success'>
					<th>Claim Number</th><th>Claim Date</th><th>Kode Reco</th><th>Deskripsi</th><th>Claim Approved Ammount</th>
					<th>Status</th><th>aksi</th>
				</tr>
			  </thead><tbody>";

	    $tampil=mysql_query("SELECT * FROM claim_request order by claim_number_system");

		while ($r=mysql_fetch_array($tampil)){
            echo "<tr>
						<td>$r[claim_number_system]</td>
						<td>$r[claim_date]</td>
						<td>$r[kode_promo]</td>
						<td>$r[deskripsi]</td>
						<td>$r[claim_approved_ammount]</td>
						<td>$r[status]</td>
						<td><a href='index.php?r=claimrequest&act=edit_claim&id=$r[claim_number_system]'><i class='icon-pencil'></i></a>
				             <a href='$aksi?r=claimrequest&act=hapus&id=$r[claim_number_system]'><i class='icon-trash'></i></a>
						</td>
				  </tr>";
		}
	    echo "</tbody></table>";
    break;
   
    case "tambah_claim":
		$access = read_security();
		if($access =="allow"){
			echo "<form id='form_tambah' class='form-horizontal' method='post' action='$aksi?r=claimrequest&act=input' >
				  <fieldset><legend>Tambah Claim Request</legend>
				  <div class='span7'>
						  <div class='control-group'>
								<label class='control-label' for='claimnumbersystem'>Claim Number System</label>
								<div class='controls'>
									<input type=hidden name='divisi' value='$_SESSION[divisi_id]'>
									<input type=hidden name='departemen' value='$_SESSION[department_id]'>
									<input type=hidden name='module' value='$_SESSION[mod]'>
									<input type='text' id='claimnumbersystem' name='claimnumbersystem' required disabled>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='claimnumberdist'>Claim Number Dist.</label>
								<div class='controls'>
									<input type='text' id='claimnumberdist' name='claimnumberdist' >
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
									<a href='#detailreco' id='detailbutton' role='button' class='btn btn-primary btn-small' data-toggle='modal'>Detail</a>
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
									<input type='text' id='nomorfakturpajak' name='nomorfakturpajak' disabled>
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
									<input type='text' id='totalclaimapprovedamount' name='totalclaimapproveamount' class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value=0 disabled>
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
									<input type='submit' id='simpan' class='btn btn-primary' value='Simpan'>
									<input type ='reset' id='batal' class='btn btn-danger'  value='Batal' onclick=\"window.location='?r=claimrequest' \">
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
	
	case "edit_claim" :
		$access = update_security();
		if($access =="allow"){
			//action='$aksi?r=claimrequest&act=input'
			echo "<input id='status' type='hidden' value=''>";
			echo "<form class='form-horizontal' method='post' >
				  <fieldset><legend>Edit Claim Request</legend>
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
									<input type ='reset' id='batal' class='btn btn-danger'  value='Batal' onclick=\"window.history.go(-1) \">
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
	
	case "result" :
		echo "<div class='alert alert-info'>
				<button type='button' class='close' data-dismiss='alert' onclick='window.history.go(-1)'>&times;</button>
				$_SESSION[pesan]
			</div>";
	break;
}
?>
