<script>
$(document).ready(function(){
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
						panelWidth : 500,
						idField : 'department_id',
						textField : 'department_name',
						url : '../function/get_data.php?data=json_department&id='+divisi,
						columns : [[
							{field : 'department_id',title: 'Dept ID',width:100},
							{field : 'department_name',title: 'Dept Name',width:350}				
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
														$('#alokasi').focus();
													}
											});
										}
								});
						}
				});
			}
	});

	$('#area').combogrid({
			panelWidth : 600,
			idField : 'area_id',
			textField : 'area_name',
			url : '../function/get_data.php?data=json_area',
			columns : [[
				{field : 'area_id',title: 'Area ID',width:60},
				{field : 'area_name',title: 'Area name',width:160},
				{field : 'regional_id',title: 'Regional ID',width:60},
				{field : 'regional_name',title: 'Regional Name',width:250}
			]],
			onChange : function(value){
				var area = $(this).combogrid('getValue');
				$('#distributor').combogrid({
						panelWidth : 350,
						idField : 'distributor_id',
						textField : 'distributor_name',
						url : '../function/get_data.php?data=json_distributor&id='+area,
						columns : [[
							{field : 'distributor_id',title: 'Dist. ID',width:100},
							{field : 'distributor_name',title: 'Dist Name',width:220}
						]],
						onChange : function(value){
							var distributor =  $(this).combogrid('getValue');
							$('#kode_promo').combogrid({
									panelWidth : 630,
									idField : 'kode_promo',
									textField : 'kode_promo',
									url : '../function/get_data.php?data=json_reco&id='+distributor,
									columns : [[
									    {field : 'kode_promo',title:'Kode Reco', width : 200 },
										{field : 'title', title : 'title', width : 420}
									]],
									onChange : function(value){
										var kodereco =  $(this).combogrid('getValue');
										$('#claim_number_system').combogrid({
												panelWidth : 630,
												idField : 'claim_number_system',
												textField : 'deskripsi',
												url : '../function/get_data.php?data=json_claimnumbersystem&id='+kodereco,
												columns : [[
												    {field : 'claim_number_system',title:'Claim Number System', width : 200 },
													{field : 'deskripsi', title : 'Deskripsi', width : 420}
												]]
										});
			                        }							
							});
						}
				});
			}
	});
	
	$('#promogroup').combogrid({
			panelWidth : 450,
			idField : 'grouppromo_id',
			textField : 'grouppromo_name',
			url : '../function/get_data.php?data=json_grouppromo',
			columns : [[
				{field : 'grouppromo_id',title: 'Group Promo',width:100},
				{field : 'grouppromo_name',title: 'GroupPromo Name',width:300},				
			]],
			onChange : function(value){
				var promogroup = $(this).combogrid('getValue');
				$('#promotype').combogrid({
						panelWidth : 300,
						idField : 'promotype_id',
						textField : 'promotype_name',
						url : '../function/get_data.php?data=json_promotype&id='+promogroup,
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
	
	

	$('#cetak').click(function(){
        var tgl_awal = $('#tgl_awal').datebox('getValue');
		var tgl_akhir = $('#tgl_akhir').datebox('getValue');
		var divisi = $('#divisi').combogrid('getValue');
		var departemen = $('#departemen').combogrid('getValue');
		var subdepartemen = $('#subdepartemen').combogrid('getValue');
		var area = $('#area').combogrid('getValue');
		var distributor = $('#distributor').combogrid('getValue');
		var promogroup = $('#promogroup').combogrid('getValue');
		var promotype = $('#promotype').combogrid('getValue');
		var classpromo = $('#class').combogrid('getValue');
		var kode_reco = $('#kode_promo').combogrid('getValue');
		var claimtradeoff = $('input[name=claimtradeoff]:checked').val();
		var approve = $('input[name=approve]:checked').val();
		var groupby = $('input[name=groupby]:checked').val();
		var display = $('input[name=display]:checked').val();
		if(tgl_awal==''){
			$.messager.alert('SKProject','Tanggal awal tidak boleh kosong.!','info');
		}else if(tgl_akhir==''){
			$.messager.alert('SKProject','Tanggal akhir tidak boleh kosong..!','info');
		}else if(divisi==''){
			$.messager.alert('SKProject','Divisi tidak boleh kosong..!','info');
		}else if(departemen==''){
			$.messager.alert('SKProject','Departemen tidak boleh kosong..!','info');
		}else if(subdepartemen==''){
			$.messager.alert('SKProject','Sub Departemen tidak boleh kosong.!','info');
		}else if(area==''){
			$.messager.alert('SKProject','Area tidak boleh kosong.!','info');
		}else if(distributor==''){
			$.messager.alert('SKProject','Distributor tidak boleh kosong.!','info');
		}else{
			$.messager.confirm('SKProject','Yakin akan melihat laporan ?',function(r){
				$.post('modul/mod_recoreport/get_recoreport.php?data=reco_report',{
																							tgl_awal : tgl_awal,
																							tgl_akhir : tgl_akhir,
																							divisi : divisi,
																							departemen : departemen,
																							subdepartemen : subdepartemen,
																							area : area,
																							distributor : distributor,
																							promogroup : promogroup,
																							promotype : promotype,
																							classpromo : classpromo,
																							kode_reco : kode_reco,
																							claimtradeoff : claimtradeoff,
																							approve : approve,
																							groupby : groupby,
																							display : display
																						},function(data){
					$('#filter').hide();					
					$('#tampil').html(data);
				});
			});
		}
	});
	
	$('#download').click(function(e){
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tabel').html()));
        e.preventDefault();
	});

	$('#cari').click(function(){
		$('#filter').toggle('explode');
	});
	
	function hide(){
	    $('#menu').hide();
		$('#footer').hide();
		$('#cari').hide();
		$('#print').hide();	
	}
	
	function show(){
		$('#menu').show();
		$('#footer').show();
		$('#cari').show();
		$('#print').show();	
	}
	
	$('#print').click(function(){
		hide();
		window.print();
		show();
	});

});
</script>
<?php
$aksi="modul/mod_nasional/aksi_nasional.php";
ses_module();
switch($_GET[act]){
  // Tampil master_nasional
  default:
  $access = read_security();
  if($access=="allow"){
	   echo "<a class='btn btn-default' id='cari' ><i class='icon-search'></i></a>
		    <a class='btn btn-default' id='print' ><i class='icon-print'></i></a>
			<a class='btn btn-default' id='download' ><i class='icon-download-alt'></i></a>
		    <div id='filter'><form class='form-horizontal' method='post' >
	          <fieldset><legend>Reco Report</legend>
			  <div class='span6'>
					  <div class='control-group'>
							<label class='control-label' for='Periode'>Tanggal Reco</label>
							<div class='controls'>
								<input type='text' id='tgl_awal' name='tgl_awal' class='easyui-datebox input-mini'>&nbspS/D&nbsp
								<input type='text' id='tgl_akhir' name='tgl_akhir' class='easyui-datebox input-mini'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='divisi'>Divisi</label>
							<div class='controls'>
								<input type='text' id='divisi' name='divisi' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='departemen'>Departemen</label>
							<div class='controls'>
								<input type='text' id='departemen' name='departemen' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='subdepartemen'>Sub Departemen</label>
							<div class='controls'>
								<input type='text' id='subdepartemen' name='subdepartemen' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='area'>Area</label>
							<div class='controls'>
								<input type='text' id='area' name='area' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='distributor'>Distributor</label>
							<div class='controls'>
								<input type='text' id='distributor' name='distributor' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='promogroup'>Promo Group</label>
							<div class='controls'>
								<input type='text' id='promogroup' name='promogroup' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='promotype'>Promo Type</label>
							<div class='controls'>
								<input type='text' id='promotype' name='promotype' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='class'>Class</label>
							<div class='controls'>
								<input type='text' id='class' name='class' class='easyui-combogrid'>
							</div>
					  </div>
					  <div class='control-group'>
							<label class='control-label' for='kode_reco'>Kode Reco</label>
							<div class='controls'>
								<input type='text' id='kode_promo' name='kode_promo' class='easyui-combogrid'>
							</div>
					  </div>
			  </div>
			  <div class='span6'>
			          <div class='control-group'>
					        <label class='control-label' for='display'>Claim Trade Off</label>
							<div class='controls'>
								<label class='radio inline'><input type='radio' id='claimtradeoff' name='claimtradeoff' value='uang' checked>Uang</label>
								<label class='radio inline'><input type='radio' id='claimtradeoff' name='claimtradeoff' value='barang'>Barang</label>
							</div>
					  </div>
			          <div class='control-group'>
					        <label class='control-label' for='approval_status'>Approval Status</label>
							<div class='controls'>
								<label class='radio'><input type='radio' id='approve' name='approve' value='all' checked>All</label>
								<label class='radio'><input type='radio' id='approve' name='approve' value='approved'>Approved</label>
								<label class='radio'><input type='radio' id='approve' name='approve' value='rejected'>Rejected</label>
								<label class='radio'><input type='radio' id='approve' name='approve' value='pending'>Pending</label>
							</div>
					  </div>
					  <div class='control-group'>
					        <label class='control-label' for='groupby'>Group By</label>
							<div class='controls'>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='divisi_id' checked>Divisi</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='department_id'>Departemen</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='subdepartemen_id'>Subdepartemen</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='area_id'>Area</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='distributor_id'>Distributor</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='promogroup_id'>Promo Group</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='promotype_id'>Promo Type</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='class_id'>Class</label>
								<label class='radio'><input type='radio' id='groupby' name='groupby' value='kode_promo'>Kode Reco</label>
							</div>
					  </div>
					  <div class='control-group'>
					        <label class='control-label' for='display'>Display</label>
							<div class='controls'>
								<label class='radio inline'><input type='radio' id='display' name='display' value='summary' checked>Summary</label>
								<label class='radio inline'><input type='radio' id='display' name='display' value='detail'>Detail</label>
							</div>
					  </div>						  
					  <div class='control-group'>
							<div class='controls'>
								<input type='button' id='cetak' class='btn btn-primary' value='Cetak'>
							</div>
					  </div>
			  </div>
			  </fieldset></form></div>";
			  
	    echo "<div id='tampil'></div>";
	}else{
		msg_security();
	}
    break;

}
?>
