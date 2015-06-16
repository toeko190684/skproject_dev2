<script type="text/javascript">	
	$(document).ready(function() {
			var table = $('#example').DataTable( {
				"ajax": "modul/mod_recoclosing/get_data.php?data=master&divisi=<?php echo $_SESSION[divisi_id]; ?>&departemen=<?php echo $_SESSION[department_id]; ?>&grade=<?php echo $_SESSION[grade_id]; ?>",
				"multipleSelection" : true,
				"columns": [
					{
						"class":          'details-control',
						"orderable":      false,
						"data":           null,
						"defaultContent": ''
					},
					{ "data": "kode_promo" },
					{ "data": "title" },
					{ "data": "cost_of_promo" },
					{ "data": "claim" },
					{ "data": "sisa" },
					{
						"class":          'details-close',
						"orderable":      false,
						"data":           null,
						"defaultContent": ''
					}
				],
				"order": [[1, 'asc']]
			} );
			 
			// Add event listener for opening and closing details
			$('#example tbody').on('click', 'td.details-control', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
		 
				if ( row.child.isShown() ) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
				}
				else {
					// Open this row
					$.post('modul/mod_recoclosing/get_data.php?data=detail',{ kode_promo : row.data().kode_promo},function(data){
						row.child( data ).show();
						tr.addClass('shown');
					});
				}
			} );
			
			// Add event listener to close reco
			$('#example tbody').on('click', 'td.details-close', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
	            var kode_promo = row.data().kode_promo;
				$.messager.confirm('Confirm', 'Yakin akan menutup Reco '+row.data().kode_promo+'?', function(r){
					if (r){
						$.post('modul/mod_recoclosing/get_data.php?data=update',{kode_promo : kode_promo },function(data){
							if(data=='sukses'){ 
								$.messager.alert("SKProject","Kode Reco "+kode_promo+" berhasil diclose!","info");
								table.ajax.url("modul/mod_recoclosing/get_data.php?data=master&divisi=<?php echo $_SESSION[divisi_id]; ?>&departemen=<?php echo $_SESSION[department_id]; ?>&grade=<?php echo $_SESSION[grade_id]; ?>").load(); }
							else if(data=='gagal'){ $.messager.alert("SKProject","Kode Reco "+kode_promo+" gagal didelete!","error"); }else{
								$.messager.alert("SKProject",data,"warning");
							}
						});
					}
				});
			} );
		} );
</script>

<?php
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
    default:
	    $access = read_security();
	    if($access=="allow"){
			  echo "<table id='example' class='display' width='100%' cellspacing='0'>
					<thead>
						<tr>
							<th></th>
							<th>Kode Promo</th>
							<th>Title</th>
							<th>Reco</th>
							<th>Claim</th>
							<th>Selisih</th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th></th>
							<th>Kode Promo</th>
							<th>Title</th>
							<th>Reco</th>
							<th>Claim</th>
							<th>Selisih</th>
							<th></th>
						</tr>
					</tfoot>
				</table>";
		}else{
			msg_security();
		}
    break;
}
?>
