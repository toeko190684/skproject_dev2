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
	
	
	$('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "modul/mod_claimrequest/json_claimrequest.php",		
		"aoColumns": 	[
							{ "width": "150px" },
							{ "width": "50px" },
							{ "width": "150px"  },  
							{  "sClass": "dt-right" }
						],
        "aoColumnDefs": [							
							{
							   "aTargets":[5],
							   "fnCreatedCell": function(nTd, sData, oData, iRow, iCol)
							   {
								   $(nTd).css('text-align', 'center');
							   },
							   "mData": null,
							   "mRender": function( data, type, full) {    // You can use <img> as well if you want
								   return '<td><a href="index.php?r=claimrequest&mod=23&act=detail_claimrequest&id='+full[0]+'" class="btn btn-info btn-small"><i class="icon-search"></i> Detail</a> '+
									   '<a href="index.php?r=claimrequest&mod=23&act=edit_claimrequest&id='+full[0]+'" class="btn btn-success btn-small"><i class="icon-pencil"></i> Edit</a> '+
									   '<a href="modul/mod_claimrequest/aksi_claimrequest.php?r=claimrequest&act=hapus&id='+full[0]+'" class="btn btn-danger btn-small"><i class="icon-trash"></i> Hapus</a></td>';
							   }
						   }
						]
					
	} );
	
	$("#listax td:nth-child(4)").live("click", function (e) {
            e.preventDefault();
            var nRow = $(this).parents('tr')[0];
  
            var nTr = this.parentNode;
            var aData = oListax.fnGetData( nTr );
            // aData[0] will have the id value of the record
  
    });  
	
	$('#detailclaim').dataTable();
	
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
				if($_SESSION[pesan]<>""){
					echo "<div class=\"alert alert-info fade in\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
							$_SESSION[pesan]
					      </div>";
					$_SESSION[pesan] = "";
				}
				
				echo "<h2>Claim Request</h2><a href='index.php?r=claimrequest&mod=23&act=tambahclaim' class='btn btn-primary'>
					  <i class='icon-plus'></i> Tambah</a><br><Br>
					  
					  <table id='example' class='table table-striped table-hover table-bordered'  cellspacing=0 width=100%>
					  <thead>
						<tr class='success'>
							<th>Claim Number</th><th>Claim Date</th><th>Kode Reco</th><th>Claim Approved Ammount</th><th>Status</th><th>Aksi</th>
						</tr>
					  </thead>
					  <tfoot>
						<tr class='success'>
							<th>Claim Number</th><th>Claim Date</th><th>Kode Reco</th><th>Claim Approved Ammount</th><th>Status</th><th>Aksi</th>
						</tr>
					  </tfoot>
					  </table>";
			}else{
				msg_security();
			}
     break;
	 
	 case "tambahclaim" :
			$access = create_security();
			if($access =="allow"){
				//action='$aksi?r=claimrequest&act=input'
				echo "<input id='status' type='hidden' value=''>";
				echo "<form class='form-horizontal' method='post' action='modul/mod_claimrequest/aksi_claimrequest.php?r=claimrequest&act=input' >
					  <fieldset><legend>Tambah Claim </legend>
						  <div class='span7'>
								  <div class='control-group'>
										<label class='control-label' for='claimnumbersystem'>Claim Number System</label>
										<div class='controls'>
											<input type=hidden name='divisi' value='$_SESSION[divisi_id]'>
											<input type=hidden name='departemen' value='$_SESSION[department_id]'>
											<input type=hidden name='module' value='$_SESSION[mod]'>
											<input type='text' id='claimnumbersystem' name='claimnumbersystem' readonly >
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
											<input  id='kodereco' name='kodereco'  class='easyui-combogrid' >
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
										<div class =\"controls\">
											<input type='submit' id='simpan' class='btn btn-primary' value='Simpan'>
											<a href=\"index.php?r=claimrequest&mod=23\" class=\"btn btn-danger\" >Batal</a>
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
	 
/*===================================================detail claim request ==============================================================*/
	 
	 case "detail_claimrequest" :
			$access = read_security();
			if($access =="allow"){
				// cari data dengan pdo 
				$sql = $db->query("select a.*,b.distributor_name,a.costofpromo-(select sum(claim_approved_ammount)from claim_request 
								where kode_promo=a.kode_promo and upper(status)<>'REJECTED') as costofpromoleft  
								from claim_request a,distributor b where a.distributor_id=b.distributor_id  
				                and a.claim_number_system='".$_GET[id]."'");
			    $data = $sql->fetch(PDO::FETCH_OBJ);
				if(strtoupper($data->status) == "APPROVED"){
						$class_warna = "label label-success";
				}elseif(strtoupper($data->status) == "PENDING"){
						$class_warna = "label label-warning";
				}else{
						$class_warna = "label label-important";
				}
				
				
				echo "<div class=\"span8\">
							<h4>Detail : #$_GET[id]</h4>
				            <table id='detail_claim' class='table table-striped table-hover '  cellspacing=0 width=100%>
								<tbody>
									<tr><td>Claim Number System </td><td><strong>$data->claim_number_system</strong></td></tr>
									<tr><td>Claim Number Dist </td><td><strong>$data->claim_number_dist </strong></td></tr>
									<tr><td>Distributor </td><td><strong> $data->distributor_name </strong></td></tr>
									<tr><td>Claim Date </td><td><strong> ".date('d M Y',strtotime($data->claim_date))."</strong></td></tr>
									<tr><td>Kode Reco </td><td> <strong>".strtoupper($data->kode_promo)." </strong></td></tr>
									<tr><td>Po So Number </td><td><strong> $data->po_so_number </strong></td></tr>
									<tr><td>PPN </td><td><strong> $data->ppn %</strong></td></tr>
									<tr><td>PPH </td><td><strong> $data->pph </strong></td></tr>
									<tr><td>Nomor Faktur Pajak </td><td><strong> $data->nomor_faktur_pajak </strong></td></tr>
									<tr><td>Deskripsi </td><td><strong> $data->deskripsi </strong></td></tr>
									<tr><td>Cost Of Promo </td><td><strong> IDR. ".number_format($data->costofpromo,2,',','.')." </strong></td></tr>
									<tr><td>Cost Of Promo Left </td><td><strong> IDR. ".number_format($data->costofpromoleft,2,',','.')." </strong></td></tr>
									<tr><td>Claim Approved Ammount </td><td><strong> IDR. ".number_format($data->claim_approved_ammount,2,',','.')." </strong></td></tr>
									<tr><td>Total Claim Approved Ammount </td><td><strong> IDR. ".number_format($data->total_claim_approved_ammount,2,',','.')." </strong></td></tr>
									<tr><td>COA </td><td><strong> $data->coa </strong></td></tr>
									<tr><td>Vendor ID </td><td><strong> $data->vendor_id </td></tr>
									<tr><td>Status </td><td><span class=\"$class_warna\"><strong>$data->status</span> </strong></td></tr>
									<tr><td>Created by </td><td><strong>$data->created_by </strong></td></tr>
									<tr><td>Last Update </td><td><strong> ".date('d M Y',strtotime($data->last_update))." </strong></td></tr>
									<tr><td>Approve by </td><td><strong> $data->approve_by </strong></td></tr>
									<tr><td>Tgl Approve </td><td><strong> ".date('d M Y',strtotime($data->tgl_approve))." </strong></td></tr>
									<tr><td>Journal ID </td><td> <strong>$data->journal_id </strong></td></tr> 						  
								</tbody>
							</table>
							<a href= \"index.php?r=claimrequest&mod=23\" class=\"btn btn-primary\"><i class=\"icon-backward\"></i> Back</a>
					  </div>";
			}else{
				msg_security();
			}
	 break;
	 
/*======================================BEGIN EDIT CLAIM REQUEST =========================================================================*/	

 
	 case "edit_claimrequest" :
			$access = update_security();
			if($access =="allow"){				
				// cari data dengan pdo 
				$sql = $db->query("select a.*,b.distributor_name from claim_request a,distributor b where a.distributor_id=b.distributor_id  
				                  and a.claim_number_system='".$_GET[id]."'");
			    $data = $sql->fetch(PDO::FETCH_OBJ);
				
				
				//cari OUTSTANDING atau costofpromo lefet
				$query1 = $db->query("SELECT costofpromo+$data->claim_approved_ammount-sum(claim_approved_ammount)as outstanding FROM claim_request 
				                               where status <> 'rejected' and kode_promo='$data->kode_promo'");	
				$data1 = $query1->fetch(PDO::FETCH_OBJ);	
				
				
				if(strtoupper($data->status) != "PENDING"){
					echo "<div class=\"alert alert-block alert-error fade in\">
								<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>
								<h4 class=\"alert-heading\">Upss, You got an error !</h4>
								<p>Hallo $_SESSION[user_id], Claim Number System : $data->claim_number_system tidak bisa anda update datanya karena sudah di $data->status oleh Pengguna $data->approve_by pada tanggal ".date('d M Y',strtotime($data->tgl_approve))." Pukul ".date('H:m:s',strtotime($data->tgl_approve))." WIB</p>
								<p>
								  <a class=\"btn btn-danger\" href=\"index.php?r=claimrequest\">Close</a>
								</p>
						  </div>";
				}else{
					echo "<form class='form-horizontal' method='post' action='modul/mod_claimrequest/aksi_claimrequest.php?r=claimrequest&act=update' >
								  <fieldset><legend>Edit Claim</legend>
								  <div class='span7'>
										  <div class='control-group'>
												<label class='control-label' for='claimnumbersystem'>Claim Number System</label>
												<div class='controls'>
													<input type='text' id='claimnumbersystem' name='claimnumbersystem' value='$data->claim_number_system' disabled>
													<input type='hidden' name='id' value='$data->claim_number_system'>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='claimnumberdist'>Claim Number Dist.</label>
												<div class='controls'>
													<input type='text' id='claimnumberdist' name='claimnumberdist' value='$data->claim_number_dist'>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='distributor'>Distributor</label>
												<div class='controls'>
													<input id='distributor' name='distributor' value='$data->distributor_id'>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='claimdate'>Claim Date</label>
												<div class='controls'>
													<input type='text' id='claimdate' name='claimdate' class='easyui-datebox input-small' value='$data->claim_date'>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='kodepromo'>Kode Reco</label>
												<div class='controls'>
													<input type=\"text\" id='kodepromo' name='kodepromo' value='$data->kode_promo'>
												</div>
										  </div>			
										  <div class='control-group'>
												<label class='control-label' for='costofpromo'>Cost Of Promo</label>
												<div class='controls'>
													<input type='text' id='costofpromo' name='costofpromo' class='easyui-numberbox input-medium' value='$data->costofpromo' groupSeparator=',' data-options='min:0' disabled >
												</div>
										  </div>	
										  <div class='control-group'>
												<label class='control-label' for='outstanding' >Cost of Promo Left</label>
												<div class='controls'>
													<input type='text' id='outstanding' name='outstanding' class='easyui-numberbox input-medium' value='$data1->outstanding' groupSeparator=',' data-options='min:0'  disabled value='$data->costofpromoleft'>

												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='posonumber'>PO SO Number</label>
												<div class='controls'>
													<input type='text' id='posonumber' name='posonumber' value=\"$data->po_so_number\">
												</div>
										  </div>
								  </div>
								  <div class='span5'>
										  <div class='control-group'>
												<label class='control-label' for='cekppn'><input type=checkbox name='cekppn' id='cekppn' value=10>&nbsp&nbspPPN&nbsp&nbsp</label>
												<div class='controls'>
													<input type='text' id='ppn' name='ppn' class='easyui-numberbox input-mini' value='$data->ppn' groupSeparator=',' disabled>&nbsp%
												</div>
										  </div>	
										  <div class='control-group'>
												<label class='control-label' for='nomorfakturpajak'>Nomor Faktur Pajak</label>
												<div class='controls'>
													<input type='text' id='nomorfakturpajak' name='nomorfakturpajak' value=\"$data->nomor_faktur_pajak\" disabled='disabled'>
												</div>
										  </div>	
										  <div class='control-group'>
												<label class='control-label' for='deskripsi'>Deskripsi</label>
												<div class='controls'>
													<textarea id='deskripsi' name='deskripsi'>$data->deskripsi</textarea>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='claimapprovedamount'>Claim Approved Amount</label>
												<div class='controls'>
													<input type='text' id='claimapprovedamount' name='claimapproveamount' class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value='$data->claim_approved_ammount'>
												</div>
										  </div>	
										  <div class='control-group'>
												<label class='control-label' for='totalclaimapprovedamount'>Claim Approved Amount + PPN </label>
												<div class='controls'>
													<input type='text' id='totalclaimapprovedamount' name='totalclaimapproveamount' readonly class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' value='$data->total_claim_approved_ammount'>
												</div>
										  </div>
										  <div class='control-group'>
												<label class='control-label' for='coa'>Chart of Account</label>
												<div class='controls'>
													<input type='text' id='coa' name='coa' class='easyui-combogrid input-large' value='$data->coa'>
												</div>
										  </div>		
										  <div class='control-group'>
												<label class='control-label' for='vendor_id'>Vendor</label>
												<div class='controls'>
													<input type='text' id='vendor_id' name='vendor_id' class='easyui-combogrid input-medium' value='$data->vendor_id'>
												</div>
										  </div>					  
										  <div class='control-group'>
												<div class='controls'>
													<input type='submit' id='simpan' class='btn btn-primary' value='Simpan'>
													<button type='button' class='btn btn-danger' onClick='window.location=\"index.php?r=claimrequest&mod=23\"'>Batal </button> 
												</div>
										  </div>					  
								  </div>
								  </fieldset></form>";
				}
			}else{
				msg_security();
			}	
	 break;

/*======================================================END EDIT CLAIM REQUEST ===========================================================*/
}
?>
