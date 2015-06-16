<script>
$(document).ready(function(){
	$('#divisi').combogrid({
			panelWidth : 300,
			idField : 'divisi_id',
			textField : 'divisi_name',
			url : '../function/get_data.php?data=json_divisi&grade='+'<?php echo $_SESSION[grade_id];?>'+'&divisi='+'<?php echo $_SESSION[divisi_id];?>',
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
	
	$('#cetak').click(function(){
		var tgl_awal = $('#tgl_awal').datebox('getValue');
		var tgl_akhir = $('#tgl_akhir').datebox('getValue');
		var divisi = $('#divisi').combogrid('getValue');
		var departemen = $('#departemen').combogrid('getValue');
		var subdepartemen = $('#subdepartemen').combogrid('getValue');
		var budget = $('#kode_budget').combogrid('getValue');
		var approve = $('input[name=approve]:checked').val();
		var groupby = $('input[name=groupby]:checked').val();
		if(tgl_awal==''){
			$.messager.alert('SKProject','Tanggal awal tidak boleh kosong.!','info');
		}else if(tgl_akhir==''){
			$.messager.alert('SKProject','Tanggal akhir tidak boleh kosong..!','info');
		/*}else if(divisi==''){
			$.messager.alert('SKProject','Divisi tidak boleh kosong..!','info');
		}else if(departemen==''){
			$.messager.alert('SKProject','Departemen tidak boleh kosong..!','info');
		}else if(subdepartemen==''){
			$.messager.alert('SKProject','Sub Departemen tidak boleh kosong.!','info');
		}else if(budget==''){
			$.messager.alert('SKProject','Kode budget tidak boleh kosong.!','info');*/
		}else{
			$.messager.confirm('SKProject','Yakin akan melihat laporan ?',function(r){
				$.post('modul/mod_budgetreport/get_budgetreport.php?data=budget_report',{
																							tgl_awal : tgl_awal,
																							tgl_akhir : tgl_akhir,
																							divisi : divisi,
																							departemen : departemen,
																							subdepartemen : subdepartemen,
																							budget : budget,
																							approve : approve,
																							groupby : groupby,
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
		$('#filter').toggle();
	});
	
	function hide(){
	    $('#menu').hide();
		$('#footer').hide();
		$('#cari').hide();
		$('#print').hide();	
		$('#download').hide();
	}
	
	function show(){
		$('#menu').show();
		$('#footer').show();
		$('#cari').show();
		$('#print').show();	
		$('#download').show();
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
	          <fieldset><legend>Budget Report</legend>
			  <div class='span6'>
					  <div class='control-group'>
							<label class='control-label' for='Periode'>Periode</label>
							<div class='controls'>
								<input type='text' id='tgl_awal' name='tgl_awal' class='easyui-datebox input-small'>&nbspS/D&nbsp
								<input type='text' id='tgl_akhir' name='tgl_akhir' class='easyui-datebox input-small'>
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
							<label class='control-label' for='kode_budget'>Kode Budget</label>
							<div class='controls'>
								<input type='text' id='kode_budget' name='kode_budget' class='easyui-combogrid'>
							</div>
					  </div>		
			  </div>
			  <div class='span5'>
			          <div class='control-group'>
					        <label class='control-label' for='approval_status'>Approval Status</label>
							<div class='controls'>
								<label class='radio inline'><input type='radio' id='approve' name='approve' value='all' checked>All</label>
								<label class='radio inline'><input type='radio' id='approve' name='approve' value='Approved'>Approved</label>
								<label class='radio inline'><input type='radio' id='approve' name='approve' value='Rejected'>Rejected</label>
								<label class='radio inline'><input type='radio' id='approve' name='approve' value='Pending'>Pending</label>
							</div>
					  </div>
					  <div class='control-group'>
					        <label class='control-label' for='groupby'>Group By</label>
							<div class='controls'>
								<label class='radio inline'><input type='radio' id='groupby' name='groupby' value='divisi_id' checked>Divisi</label>
								<label class='radio inline'><input type='radio' id='groupby' name='groupby' value='department_id'>Departemen</label>
								<label class='radio inline'><input type='radio' id='groupby' name='groupby' value='subdepartemen_id'>Sub Departemen</label>
								<label class='radio inline'><input type='radio' id='groupby' name='groupby' value='kode_budget'>Budget</label>
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
