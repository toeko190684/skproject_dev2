<script>
$(document).ready(function(){
	$('#distributor').combogrid({
			panelWidth : 500,
			idField : 'distributor_id',
			textField : 'distributor_name',
			url : '../function/get_data.php?data=json_distributor',
			columns : [[
				{field : 'area_id', title : 'Area ID', width : 60 },
				{field : 'area_name', title : 'Area Name', width : 150},
				{field : 'distributor_id',title: 'Dist. ID',width:60},
				{field : 'distributor_name',title: 'Dist Name',width:200}
			]],
			onChange : function(value){
				var g = $(this).combogrid('grid');
				var r = g.datagrid('getSelected');
				$('#area_id').val(r.area_id);
			
			}
	});
	
	$('#grouppromo').combogrid({
			panelWidth : 450,
			idField : 'grouppromo_id',
			textField : 'grouppromo_name',
			url : '../function/get_data.php?data=json_grouppromo',
			columns : [[
				{field : 'grouppromo_id',title: 'Group Promo',width:100},
				{field : 'grouppromo_name',title: 'GroupPromo Name',width:300},				
			]],
			onChange : function(value){
				var grouppromo = $(this).combogrid('getValue');
				$('#promotype').combogrid({
						panelWidth : 300,
						idField : 'promotype_id',
						textField : 'promotype_name',
						url : '../function/get_data.php?data=json_promotype&id='+grouppromo,
						columns : [[
							{field : 'promotype_id',title: 'PromoType ID',width:100},
							{field : 'promotype_name',title: 'PromoType Name',width:190}
						]],
						onChange : function(value){
								var promotype = $(this).combogrid('getValue');
								$('#class').combogrid({
										panelWidth : 600,
										idField : 'class_id',
										textField : 'class_name',
										url : '../function/get_data.php?data=json_class&id='+promotype,
										columns : [[
											{field : 'class_id',title: 'Class ID',width:100},
											{field : 'class_name',title: 'Class Name',width:400}
										]]
									
								});
						}
				});
			}		
	});

	$('#account').combogrid({
		panelWidth :  530,
		idField : 'account_id',
		textField : 'account_id',
		url : '../function/get_data.php?data=json_coa',
		columns : [[
			{field : 'account_id',title:'Account ID',width:100},
			{field : 'account_name',title:'Account Name',width : 300},
			{field : 'tipe_biaya',title:'Type',width:30},
			{field : 'typeofcost',title:'TypeofCost',width:60}
		]],
		onChange : function(value){
				   var account_id = $(this).combogrid('getValue');
					$.post('../function/get_data.php?data=jenis_biaya',{account_id:account_id},function(data){
						var obj = $.parseJSON(data);
						$('#jenis_biaya').val(obj.tipe_biaya);
						$('#typeofcost').val(obj.typeofcost);
						if(obj.typeofcost=='F'){
							$('#rasio').hide();
						}else{
							$('#rasio').show();
						}
					});
		}
	});
	
	$('#groupoutlet_id').combogrid({
			panelWidth : 300,
			idField :  'groupoutlet_id',
			textField : 'groupoutlet_name',
			url : '../function/get_data.php?data=json_groupoutlet',
			columns : [[
					{field : 'groupoutlet_id',title: 'GroupOutlet ID',width:100},
					{field : 'groupoutlet_name',title: 'GroupOutlet Name',width:190}
			]],
			onChange : function(value){
				$('#sales_target').focus();
			}
	});
	
	$('#divisi').combogrid({
			panelWidth : 300,
			idField : 'divisi_id',
			textField : 'divisi_name',
			url : '../function/get_data.php?data=json_divisi',
			columns : [[
					{field : 'divisi_id',title: 'Divisi ID',width:100},
					{field : 'divisi_name',title: 'Divisi Name',width:190}				
			]],
			onChange : function(value){
			    var divisi = $(this).combogrid('getValue');
				$('#departemen').combogrid({
						panelWidth : 300,
						idField : 'department_id',
						textField : 'department_name',
						url : '../function/get_data.php?data=json_department&id='+divisi,
						columns : [[
							{field : 'department_id',title: 'Dept ID',width:100},
							{field : 'department_name',title: 'Dept Name',width:190}				
						]],
						onChange : function(value){
							    var departemen = $(this).combogrid('getValue');
								$('#subdepartemen').combogrid({
										panelWidth : 300,
										idField : 'subdepartemen_id',
										textField : 'subdepartemen_name',
										url : '../function/get_data.php?data=json_subdepartemen&id='+departemen,
										columns : [[
											{field : 'subdepartemen_id',title: 'Sub Dept ID',width:100},
											{field : 'subdepartemen_name',title: 'Sub Dept Name',width:190}				
										]],
										onChange : function(value){
										   var subdepartemen = $(this).combogrid('getValue');
											$('#kode_budget').combogrid({
													panelWidth : 300,
													idField : 'kode_budget',
													textField : 'keterangan',
													url : '../function/get_data.php?data=json_budget&divisi='+divisi+'&departemen='+departemen+'&subdepartemen='+subdepartemen,
													columns : [[
														{field : 'kode_budget',title: 'Kode Budget',width:100},
														{field : 'keterangan',title: 'Keterangan',width:190}				
													]],
													onChange : function(value){
														var kode_budget = $(this).combogrid('getValue');
														//cari outstranding dan cost yang sudah di pakai
														$.post('../function/get_data.php?data=outstanding',{id : kode_budget},function(data){
															var obj = $.parseJSON(data);
															$('#budget_cost').numberbox('setValue',obj.cost);
															$('#outstanding_cost').numberbox('setValue',obj.outstanding_cost);
														});
														$('#alokasi').focus();
														
														var tgl_aktif = '<?php echo $_SESSION[tanggal]; ?>';
														var tgl_promo = $('#tgl_promo').datebox('getValue');
																												
														if(tgl_promo == ''){
															$.messager.alert("SKProject", "Tanggal Reco tidak boleh kosong!!","info");
															$('#kode_budget').combogrid('setValue',"");
														}else{
															var tgl_split = tgl_promo.split('/');
															var tgl_reco = "0"+tgl_split[0];
															var tgl_reco = tgl_split[2].substr(2,2)+ tgl_reco.substr((tgl_reco.length-2),2);
															$.post('../function/get_data.php?data=tgl_budget',{ id : kode_budget },function(data){
																if(data != tgl_reco){
																	$.messager.alert("SKProject","Cek kembali tanggal promo apakah sesuai dengan bulan budget","info");
																	$('#kode_budget').combogrid('setValue',"");
																}																
															});
														}
													}
											});
										}
								});
						}
				});
			}
	});
	
	$('#kode_budget').change(function(){
		$('#alokasi').focus();
	});
	
    function hitung_rasio(){
		var costofpromo  = $('#costofpromo').numberbox('getValue');
		var total_sales_target = $('#total_sales_target').numberbox('getValue');
		var costofrasio = eval(costofpromo)/eval(total_sales_target);
		$('#costrasio').numberbox('setValue',costofrasio);
	}

	
	//jquery untuk menambah promo grouop outlet
	$('#add_group').click(function(){
		var groupoutlet_id = $('#groupoutlet_id').combogrid('getValue');
		var sales_target = $('#sales_target').numberbox('getValue');
		var kode_reco = $('#kode_promo').val();
		var costofpromo = $('#costofpromo').numberbox('getValue');
		if(groupoutlet_id==""){  
			$.messager.alert('SKProject','Group Outlet harus dipilih.!!','info');
		}else if(sales_target==0){
			$.messager.alert('SKProject','Sales target tidak boleh 0.!!','info');
		}else if(costofpromo==0){
			$.messager.alert('SKProject','Cost of Promo tidak boleh 0.!!','info');
		}else{
			if(kode_reco==""){
				$.messager.alert('SKProject',"kode promo tidak boleh kosong",'info');
			}else{
				$.post('../function/get_data.php?data=save_reco_target',{kode_reco : kode_reco, groupoutlet_id : groupoutlet_id, sales_target:sales_target},
				function(data){
						if(data!=null){
						    $('tr').remove(".reco_target");
							var el = data;
							$(el).insertAfter('#promo_group');
							var total = $('#total_target').val();
							$('#total_sales_target').numberbox('setValue',total);
							hitung_rasio();
						}
				});
			}
		}
	});
	
	
	//jquery for delete promo group outlet element
	$('#hapus_group').click(function(){
	    var total_target_sales = $('#total_target_sales').val();
		var kode_reco = $('#kode_promo').val();
		var cek = $('#cekgroup:checked').val();
		if(cek==null){
			$.messager.alert('SKProject',"Pilih yang akan di hapus",'info');
		}else{
			$.post('../function/get_data.php?data=del_reco_target',{id : cek,kode_reco : kode_reco},function(data){
				if(data!=null){
						    $('tr').remove(".reco_target");
							var el = data;
							$(el).insertAfter('#promo_group');
							var total = $('#total_target').val();
							if(total==""){ total=0; }else{ total = total; }
							$('#total_sales_target').numberbox('setValue',total);		
							hitung_rasio();				
				}
			});
		}
		
	});
	
	//jquery untuk menambahkan reco item ke dalam tabel
	$('#add_item').click(function(){
		var kode_reco = $('#kode_promo').val();
		var divisi_id = $('#divisi').combogrid('getValue');
		var departemen_id = $('#departemen').combogrid('getValue');
		var subdepartemen_id = $('#subdepartemen').combogrid('getValue');
		var kode_budget = $('#kode_budget').combogrid('getValue');
		var alokasi = $('#alokasi').numberbox('getValue');
		var value = $('#value').numberbox('getValue');
		var outstanding_cost = $('#outstanding_cost').numberbox('getValue');
		var costofpromo = $('#costofpromo').numberbox('getValue');
			if(costofpromo=="" || costofpromo==0){
				$.messager.alert('SKProject',"Cost of Promo tidak boleh kosong..!",'info');
			}else if(divisi_id==""){
				$.messager.alert('SKProject','Divisi belum dipilih..!!','info');
			}else if(departemen_id==""){
				$.messager.alert('SKProject','Departemen belum dipilih..!!','info');
			}else if(subdepartemen_id==""){
				$.messager.alert('SKProject','Subdepartemen belum dipilih..!!','info');
			}else if(kode_budget==""){
				$.messager.alert('SKProject','Kode budget belum dipilih..!!','info');
			}else if(alokasi==0){
				$.messager.alert('SKProject','Alokasi tidak boleh 0..!','info');
			}else if(alokasi > 100 ){
				$.messager.alert('SKProject','Alokasi tidak boleh lebih dari 100 %','info');
			}else if(kode_reco==""){
				$.messager.alert('SKProject','Kode Reco tidak boleh kosong..!!','info');
			}else if(eval(outstanding_cost) < eval(value)){
				$.messager.alert('SKProject','Outstanding Cost tidak cukup..!','info');
			}else if(eval(costofpromo) < eval(value)){
				$.messager.alert('SKProject','Outstanding Cost tidak cukup..!','info');
			}else{
				$.post('../function/get_data.php?data=save_reco_item',{kode_reco : kode_reco, divisi : divisi_id, departemen:departemen_id,subdepartemen : subdepartemen_id, kode_budget : kode_budget,alokasi :alokasi, value : value},
				function(data){
						if(data!=null){
						    $('tr').remove(".reco_item");
							var el = data;
							$(el).insertAfter('#promo_item');
						}
				});
			}
	});
	
	//jquery for delete promo group outlet element
	$('#hapus_item').click(function(){
		var kode_reco = $('#kode_promo').val();
		var cek = $('#cekitem:checked').val();
		if(cek==null){
			$.messager.alert('SKProject',"Pilih yang akan di hapus",'info');
		}else{
			$.post('../function/get_data.php?data=del_reco_item',{id : cek,kode_reco : kode_reco},function(data){
				if(data!=null){
						    $('tr').remove(".reco_item");
							var el = data;
							$(el).insertAfter('#promo_item');				
				}
			});
		}
		
	});
	

	//jquery untuk melakukan penyimpanan data reco request
	$('#simpan').click(function(){
		var url = $('#url').val();
		var divisi_id = '<?php echo $_SESSION[divisi_id];?>';
		var department_id = '<?php echo $_SESSION[department_id]; ?>';
	    var user_id = $('#user_id').val();
	    var module_id = '<?php echo $_GET[mod]; ?>';
		var kode_promo = $('#kode_promo').val();
		var tgl_promo = $('#tgl_promo').datebox('getValue');
		var area = $('#area_id').val();
		var distributor = $('#distributor').combogrid('getValue');
		var grouppromo = $('#grouppromo').combogrid('getValue');
		var promotype = $('#promotype').combogrid('getValue');
		var classid = $('#class').combogrid('getValue');
		var account = $('#account').combogrid('getValue');
		var jenis_biaya = $('#jenis_biaya').val();
		var title = $('#title').val();
		var tgl_awal = $('#tgl_awal').datebox('getValue');
		var tgl_akhir = $('#tgl_akhir').datebox('getValue');
		var total_sales_target = $('#total_sales_target').numberbox('getValue');
		var background = $('#background').val();
		var promo_mechanishm = $('#promo_mechanishm').val();
		var claim_mechanishm = $('#claim_mechanishm').val();
		var claimtradeoff = $('#claimtradeoff').val();
		var costofpromo = $('#costofpromo').numberbox('getValue');
		var typeofcost = $('#typeofcost').val();
		var costrasio = $('#costrasio').numberbox('getValue');
		var total_detail_recoitem = $('#total_detail_recoitem').val();
		if(kode_promo ==""){
			$.messager.alert('SKProject',"Kode Reco tidak boleh kosong ...!",'info');
			$('#create').focus();
		}else if(tgl_promo ==""){
			$.messager.alert('SKProject',"Tanggal Reco tidak boleh kosong ..!",'info');
			$('#tgl_promo').focus();
		}else if(distributor==""){
			$.messager.alert('SKProject',"Distributor tidak boleh kosong..!",'info');
			$('#distributor').focus();
		}else if(grouppromo == ""){
			$.messager.alert('SKProject',"Group Promo tidak boleh kosong..!",'info');
			$('#grouppromo').focus();
		}else if(promotype ==""){
			$.messager.alert('SKProject',"Promo type tidak boleh kosong..!",'info');
			$('#promotype').focus();
		}else if(classid==""){
			$.messager.alert('SKProject',"Class tidak boleh kosong ..!",'info');
			$('#classid').focus();
		}else if(account==""){
			$.messager.alert('SKProject',"Account tidak boleh kosong ..!",'info');
			$('#classid').focus();
		}else if(title ==""){
			$.messager.alert('SKProject',"Title tidak boleh kosong..!",'info');
			$('#title').focus();
		}else if(tgl_awal==""){
			$.messager.alert('SKProject',"Tanggal awal tidak boleh kosong..!",'info');
			$('#tgl_awal').focus();
		}else if(tgl_akhir ==""){
			$.messager.alert('SKProject',"Tanggal akhir tidak boleh kosong..!",'info');
			$('#tgl_akhir').focus();
		}else if(background==""){
			$.messager.alert('SKProject',"Background tidak boleh kosong..!",'info');
			$('#background').focus();
		}else if(promo_mechanishm==""){
			$.messager.alert('SKProject',"Promo Mechanism tidak boleh kosong..!",'info');
			$('#promo_mechanishm').focus();
		}else if(claim_mechanishm==""){
			$.messager.alert('SKProject',"Claim mechanishm tidak boleh kosong..!",'info');
			$('#claim_mechanishm').focus();
		}else if(claimtradeoff == "" ){			
			$.messager.alert('SKProject',"Claim trade off tidak boleh kosong..!",'info');
			$('#claimtradeoff').focus();
		}else if(costofpromo == 0){
			$.mesager.alert('SKProject',"Cost of promo tidak boleh 0",'info');
			$('#costofpromo').focus();
		}else{		
			if(typeofcost=='F'){	
			    $.messager.confirm('SKProject','Yakin akan menyimpan reco '+kode_promo+'?',function(r){
					if(r){
						$.post('../function/get_data.php?data=simpanreco',{ url : url,
						                                                        divisi_id : divisi_id,
																				department_id : department_id,
							                                                    id : module_id,
							                                                    kode_promo : kode_promo, 
																				tgl_promo : tgl_promo,
																				area : area,
																				distributor : distributor,
																				grouppromo : grouppromo,
																				promotype : promotype,
																				classid : classid,
																				account : account,
																				jenis_biaya : jenis_biaya,
																				title : title,
																				tgl_awal : tgl_awal,
																				tgl_akhir : tgl_akhir,
																				total_sales_target : total_sales_target,
																				background : background,
																				promo_mechanishm : promo_mechanishm,
																				claim_mechanishm : claim_mechanishm,
																				claimtradeoff : claimtradeoff,
																				costofpromo : costofpromo,
																				typeofcost : typeofcost,
																				costrasio : costrasio,
																				user_id : user_id
																			},function(data){
								$(window).load(function() { $("#loading").fadeOut("slow"); })
								$.messager.alert('SKproject',data,'info');
						});		
					}
				});
			}else{
			    if(total_sales_target=="" || total_sales_target==0){
					$.messager.alert('SKProject','Total Sales Target tidak boleh 0 untuk Variabel..!','info');
				}else{    
					$.post('../function/get_data.php?data=simpanreco',{ url : url,
					                                                        divisi_id : divisi_id,
																			department_id : department_id,
						                                                    id : module_id,
						                                                    kode_promo : kode_promo, 
																			tgl_promo : tgl_promo,
																			area : area,
																			distributor : distributor,
																			grouppromo : grouppromo,
																			promotype : promotype,
																			classid : classid,
																			account : account,
																			jenis_biaya : jenis_biaya,
																			title : title,
																			tgl_awal : tgl_awal,
																			tgl_akhir : tgl_akhir,
																			total_sales_target : total_sales_target,
																			background : background,
																			promo_mechanishm : promo_mechanishm,
																			claim_mechanishm : claim_mechanishm,
																			claimtradeoff : claimtradeoff,
																			costofpromo : costofpromo,
																			typeofcost : typeofcost,
																			costrasio : costrasio,
																			user_id : user_id
																		},function(data){
							$(window).load(function() { $("#loading").fadeOut("slow"); })
							$.messager.alert('SKproject',data,'info');
						});
				}
			}
			$('#attach').removeAttr("disabled");
		}
		//location.reload();
	});
	
	//bikin running number untuk reco 
	$('#create').click(function(){
	    var tgl_promo = $('#tgl_promo').datebox('getValue');
		if(tgl_promo ==""){
			$.messager.alert("SKProject","Tanggal Reco tidak boleh kosong!!","info");
			$('#tgl_reco').focus();
		}else{
			var divisi_id = "<?php echo $_SESSION[divisi_id]; ?>";
			var department_id = "<?php echo $_SESSION[department_id]; ?>";
			var tanggal = "<?php echo $_SESSION[tanggal]; ?>";
			var module_id = $('#module_id').val();
			//poting untuk bikin number
			$.post('../function/get_data.php?data=reconumber',{id : module_id,divisi : divisi_id,departemen : department_id,tgl_promo : tgl_promo},function(data){
				var obj = $.parseJSON(data);
				$('#kode_promo').val(obj.kode_promo);
				
				//posting untuk hapus temporary
				$.post('../function/get_data.php?data=del_temp',{id : obj.kode_promo },function(data){
				});
			});
		}
	});
	
	
	$('#divisi').change(function(){
		var divisi_id = $(this).val();
		$.post('../function/get_data.php?data=departemen',{id : divisi_id },function(data){
			$('#departemen').html(data);
		});
	});
	
	$('#departemen').change(function(){
		var departemen_id = $(this).val();
		$.post('../function/get_data.php?data=subdepartemen',{id : departemen_id}, function(data){
			$('#subdepartemen').html(data);
		});
	});
	
	$('#subdepartemen').change(function(){
		var divisi= $('#divisi').val();
		var departemen = $('#departemen').val();
		var subdepartemen = $('#subdepartemen').val();
		$.post('../function/get_data.php?data=budget',{divisi :  divisi , departemen : departemen, subdepartemen: subdepartemen},function(data){
			$('#kode_budget').html(data);
		});
	});
	
	$('#costofpromo').keyup(function(e){
		var total_sales_target = $('#total_sales_target').numberbox('getValue');
		var x = $(e.target).val();
		var y = eval(x)/eval(total_sales_target);
		var value = eval($('#alokasi').numberbox('getValue'))*eval(x)/100;
		$('#costrasio').numberbox('setValue',y);
		$('#value').numberbox('setValue',value);
		if(x>value){
			
		}
		hitung_rasio();
	});
	
	$('#alokasi').keyup(function(e){
		var costofpromo = $('#costofpromo').numberbox('getValue');
		var x = $(e.target).val();
		var y = eval(costofpromo)*eval(x)/100;
		var outstanding = $('#outstanding_cost').numberbox('getValue');
		$('#value').numberbox('setValue',y);
		if(costofpromo=="" || costofpromo== 0){
			$.messager.alert('SKProject',"masukan dulu cost of promo..!",'info');
			$(this).numberbox('setValue',0);
		}else{
			if((y>outstanding) || (y>costofpromo)){
				$.messager.alert('SKProject','Outstanding Cost tidak mencukupi!!','warning');
				$('#value').numberbox('setValue',0);
				$(this).numberbox('setValue',0);
				$(this).focus();
			}
		}
	});
	
	$('#value').keyup(function(e){
		var costofpromo = $('#costofpromo').numberbox('getValue');
		var outstanding = $('#outstanding_cost').numberbox('getValue');
		if(costofpromo==0 || costofpromo==""){
			$.messager.alert('SKProject','Masukan dulu cost of promo.!','info');
			$(this).numberbox('setValue',0);
		}else{
			var x = $(e.target).val();
			var alokasi = eval(x)/eval(costofpromo)*100;
			$('#alokasi').numberbox('setValue',alokasi);
			if(x>outstanding){
				$.messager.alert('SKProject','Outstanding Cost tidak mencukupi!!','warning');
				$('#alokasi').numberbox('setValue',0);
				$(this).numberbox('setValue',0);
				$(this).focus();
			}			
		}
	});
	
	//cek apakah tanggal awal lebih kecil dari hari ini 
	$('#tgl_awal').datebox({
		onSelect : function(date){
				var tgl_awal = $(this).datebox('getValue');
				$.post('../function/get_data.php?data=tanggal',{id : tgl_awal},function(data){
						if(data=="no"){
							$.messager.alert('SKProject',"Tidak bisa input tanggal mundur..!!",'info');
							$('#tgl_awal').datebox('setValue','');
						}
				});
		}
	});
	
	//cek apakah tanggal awal lebih kecil dari hari ini 
	$('#tgl_akhir').datebox({
		onSelect : function(date){
			    var tgl_akhir = $(this).datebox('getValue');
				$.post('../function/get_data.php?data=tanggal',{id : tgl_akhir},function(data){
						if(data=="no"){
							$.messager.alert('SKProject',"Tidak bisa input tanggal mundur..!!",'info');
							$('#tgl_akhir').datebox('setValue','');
						}
				});
		}
	});
	
	$('#tgl_promo').datebox({
		onSelect: function(date){
			var tgl_aktif = '<?php echo $_SESSION[tanggal]; ?>';
			var day = (date.getMonth()+1).toString();
			if(day.length==1){ day = '0'+day; }else{ day = day; }
			var tgl_promo = (date.getFullYear()).toString().substr(2,2)+day;
			if(eval(tgl_promo) >= eval(tgl_aktif)){
				
			}else{
				$.messager.alert('SKProject','Tanggal Promo tidak sesuai dengan periode bulan & tahun yang di buka ..!','info');
				$(this).datebox('setValue','');
			}
		}
	});
	
	$('#reset').click(function(){
		window.location.reload();
	});
});

</script>
<?php
$aksi='modul/mod_promo/aksi_promo.php';
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = create_security();
  if($access=='allow'){
		$aksi = 'modul/mod_promorequest/aksi_promorequest.php';
		echo "<form class='form-horizontal' method='post' >
              <ul class='nav nav-tabs' id='myTab'>
				  <li class='active'><a href='#reco_request' data-toggle='tab'>Reco Request</a></li>
				  <li><a href='#reco_group' data-toggle='tab'>Reco Target</a></li>
				  <li><a href='#reco_item' data-toggle='tab'>Reco Budget</a></li>
			  </ul>

			  <div class='tab-content'>
				  <div class='tab-pane active' id='reco_request'> 
				        <div class='span7'>
							<div class='control-group'>
								<label class='control-label' for='kode_promo'>Kode Reco</label>
								<div class='controls'>
									<input type='hidden' name='module_id' id= 'module_id' value='$_SESSION[mod]'>
									<input type='hidden' name='user_id' id= 'user_id' value='$_SESSION[user_id]'>
									<input type='hidden' name='url' id= 'url' value='$_SESSION[url]'>
									<input type='text' id='kode_promo' name='kode_promo' placeholder='Kode Promo' class='input-large' disabled >
									<button type='button' id='create' class='btn btn-primary btn-xs'>Create</button>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='tgl_promo'>Tanggal Reco</label>
								<div class='controls'>
									<input type='text' id='tgl_promo' name='tgl_promo' class='easyui-datebox input-small'>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='distributor'>Distributor</label>
								<div class='controls'>
									<input type=text name='distributor' id='distributor' class='easyui-combogrid input-medium'>
									<input type='hidden' name='area_id' id='area_id' >
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='grouppromo'>Promo Group</label>
								<div class='controls'>
									<input type=text name='grouppromo' id='grouppromo' class='easyui-combogrid'>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='promotype'>Promo Type</label>
								<div class='controls'>
									<input name='promotype' id='promotype' class='easyui-combogrid' >
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='class'>Class</label>
								<div class='controls'>
									<input name='class' id='class' class='easyui-combogrid' >
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='account'>Account ID</label>
								<div class='controls'>
									<input name='class' id='account' class='easyui-combogrid' >
									<input type='hidden' id='jenis_biaya' >
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='title'>Title / Theme</label>
								<div class='controls'>
									<textarea name='title' id='title'></textarea>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='periode'>Periode</label>
								<div class='controls'>
									<input type='text' id='tgl_awal' name='tgl_awal' class='easyui-datebox input-small'>&nbspS/D&nbsp
									<input type='text' id='tgl_akhir' name='tgl_akhir' class='easyui-datebox input-small'>
								</div>
							</div>
						</div>
						<div class=span5>
						    <div class='control-group'>
								<label class='control-label' for='total_sales_target'>Total Sales Target</label>
								<div class='controls'>
									<input type='text' id='total_sales_target' name='total_sales_target' value=0 class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0' disabled>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='background'>Background</label>
								<div class='controls'>
									<textarea name='background' id='background'></textarea>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='promo_mechanisme'>Promo Mecanishm</label>
								<div class='controls'>
									<textarea name='promo_mechanishm' id='promo_mechanishm'></textarea>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='claim_mechanisme'>Claim Mecanishm</label>
								<div class='controls'>
									<textarea name='claim_mechanishm' id='claim_mechanishm'></textarea>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='claimtradeoff'>Claim Trade Off</label>
								<div class='controls'>
									<label class='radio inline'>
										<input type='radio' id='claimtradeoff' name='claimtradeoff' value='uang' checked>Uang
									</label>
									<label class='radio inline'>
										<input type='radio' id='claimtradeoff' name='claimtradeoff'  value='barang'>Barang
									</label>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='costofpromo'>Cost Of Promo</label>
								<div class='controls'>
									<input type='text' id='costofpromo' name='costofpromo' value=0 class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0'  required>
									<input type='hidden' id='typeofcost'>
								</div>
							</div>
							<div class='control-group' id='rasio'>
								<label class='control-label' for='costrasio'>Cost Rasio</label>
								<div class='controls'>
									<input type='text' id='costrasio' name='costrasio' value=0 class='easyui-numberbox input-mini' groupSeparator=',' data-options='min:0' disabled>&nbsp%
								</div>
							</div>
							<div align='right'>
										<button type='button' id='simpan' class='btn btn-primary' >Simpan</button>";
	echo "								<button type='button' id='reset' class='btn btn-danger' >Reset</button>
						    </div>
						</div>
     			  </div>
				  <div class='tab-pane' id='reco_group'>
				      <div class='span7'>
						  <table class='table table-condensed table-hover table-bordered'>
							<tr class='success'>
								<td>Group Outlet</td><td>Sales Target</td>
								<td>Aksi</td>
							</tr>
							<tr>
								<td><input name='groupoutlet_id' id='groupoutlet_id' ></td>
								<td><input type='text' name='sales_target' id='sales_target' value=0 class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0'></td>
								<td><button type='button' id='add_group' class='btn btn-success btn-mini'>+</button>
								<button type='button' id='hapus_group' class='btn btn-danger btn-mini'>-</button></td>
							</tr>
							<tR id='promo_group'></tR>
						  </table>	
					  </div>
				  </div>
				  <div class='tab-pane' id='reco_item'>
				      <div class='span6'>
					      <div class='control-group'>
								<label class='control-label' for='divisi'>Divisi</label>
								<div class='controls'>
									<input name='divisi' id='divisi' >
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='departemen'>Departemen</label>
								<div class='controls'>
									<input name='departemen' id='departemen' class='easyui-combogrid'>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='subdepartemen'>Sub Departemen</label>
								<div class='controls'>
									<input name='subdepartemen' id='subdepartemen' class='easyui-combogrid'>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='kode_budget'>Kode Budget</label>
								<div class='controls'>
									<input name='kode_budget' id='kode_budget' class='easyui-combogrid'>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='budget_cost'>Realisasi Budget</label>
								<div class='controls'>
									<input type='text' name='budget_cost' id='budget_cost' value=0 disabled class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0'>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='outstanding_cost'>Sisa Budget</label>
								<div class='controls'>
									<input type='text' name='outstanding_cost' id='outstanding_cost' value=0 disabled class='easyui-numberbox input-medium' groupSeparator=',' data-options='min:0'>
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='alokasi'>Alokasi</label>
								<div class='controls'>
									<input type='text' name='alokasi' id='alokasi' value=0 class='easyui-numberbox input-mini' groupSeparator=',' data-options='min:0'>%
								</div>
						  </div>
						  <div class='control-group'>
								<label class='control-label' for='value'>Value</label>
								<div class='controls'>
									<input type='text' name='value' id='value' value=0 class='easyui-numberbox input-small' groupSeparator=',' data-options='min:0'>
								</div>
						  </div>
						  <div class='control-group'>
								<div class='controls'>
									<button type='button' id='add_item' class='btn btn-success btn-mini'>+</button>
									<button type='button' id='hapus_item' class='btn btn-danger btn-mini'>-</button>
								</div>
						  </div>
					  </div>
					  <div class='span6'>
					  <table class='table table-condensed table-hover table-bordered'>
						<tr class='success'>
							<td>Divisi</td><td>Departemen</td><td>SubDepartemen</td><td>Kode Budget</td><tD>Alokasi%</td><td>Value</td><td>Aksi</td>
						</tr>
						<tr id='promo_item'></tr>
						<tR><td colspan='7'></td></tr>
					  </table>
					  </div>
				  </div>
			  </div>
			  </form>";
	}else{
		msg_security();
	}
    break;

}
?>
