<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_approvallimit/aksi_approvallimit.php";
ses_module();
switch($_GET[act]){
  default:
  $access = read_security();
  if($access=="allow"){
		if($_SESSION[pesan]<>""){
			echo "<div class=\"alert alert-info fade in\">
					<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
					<h4>Warning</h4>
					$_SESSION[pesan]
			      </div>";
			$_SESSION[pesan] = "";
		}
		
		$sql = $db->query("select * from approval_limit where id=1");
		$data = $sql->fetch(PDO::FETCH_OBJ);
		
		$user = $db->query("select * from sec_users order by user_id asc");
		$data1 = $user->fetchAll(PDO::FETCH_OBJ);	
		

		echo "<form method=POST action='$aksi?r=approval_limit&act=update'>
	          <fieldset><legend>Approval Limit</legend>
			  <label>Nominal :</label>
			  <input type='number' name='nominal'  value= $data->nominal required><br><br>
			  <label>User ID :</label>
			  <select name='user_id' ><option value=$data->user_id>$data->user_id</option>";
			  foreach($data1 as $key => $value){
					echo "<option value=$value->user_id>$value->user_id</option>";
			  }			  
		echo "</select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";

	}else{
		msg_security();
	}
    break;  
}
?>
