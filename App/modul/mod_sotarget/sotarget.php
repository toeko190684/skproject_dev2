<?php
$aksi="modul/mod_sotarget/aksi_sotarget.php";
ses_module();
switch($_GET[act]){
  // Tampil sotarget
  default:
    $access = read_security();
	if($access=="allow"){
	    $per_page = 10;
		if ($_POST[key]==''){
			$page_query = mysql_query("SELECT count(*) FROM sales_order_target ");
		}else{
			$page_query = mysql_query("SELECT count(*) FROM sales_order_target where tahun='$_POST[key]'");
		}
		$pages = ceil(mysql_result($page_query,0)/$per_page);
		$page = (isset($_GET[page]))? (int)$_GET[page]:1;
		$start = ($page-1)*$per_page;
	    echo "<blockquote>Sales Order Target</blockquote>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=sotarget&act=tambahsotarget';\">
			  <form class='form-search' method=post action='?r=sotarget'>	
					<div class='input-append'>
					    <div class='span9'></div>
						<input class='span3 search-query' id='key' name='key' type='text' placeholder='Masukan Tahun..!'>
					    <button class='btn' type='submit'>Cari</button>
					</div><br>
			  </form><br><Br>
	          <table class='table table-condensed table-hovertable-bordered' >
	          <tdead>
				<tr class='success'>
					<td>No</td><td>Tahun</td><td>Bulan</td><td>Divisi</td><td>Value</td><td>aksi</td>
				</tr>
			  </tdead>";
	    if ($_POST[key]==''){
			$tampil=mysql_query("SELECT a.*,b.divisi_name,c.month_name FROM sales_order_target a,sales_order_divisi b,month c where a.divisi_id=b.divisi_id
	                     		and a.bulan=c.month_id ORDER BY tahun,bulan limit $start,$per_page");
		}else{
			$tampil=mysql_query("SELECT a.*,b.divisi_name,c.month_name FROM sales_order_target a,sales_order_divisi b,month c where a.divisi_id=b.divisi_id
	                    		and a.bulan=c.month_id and a.tahun='$_POST[key]' ORDER BY tahun,bulan limit $start,$per_page");
		}
	    $no = 1;
		$no = $no+$start;
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tbody<tr>
		            <td>$no</td>
					<td>$r[tahun]</td>
					<td>$r[month_name]</td>
					<td>$r[divisi_name]</td>
		            <td>".number_format($r[value],2,',','.')."</td>
					<td><a href='index.php?r=sotarget&act=editsotarget&id=$r[so_target_id]'>Edit</a> | 
			              <a href='$aksi?r=sotarget&act=hapus&id=$r[so_target_id]'>Hapus</a>
		            </td>
				</tr></tbody>";
				$no++;
	    }
	    echo "</table>";
		//memulai paginasi
		echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=sotarget&page=1'>First</a></li>";
		if($pages >= 1 && $page <= $pages){
		    for($x=1; $x<=$pages; $x++){
		        echo ($x == $page) ? '<li><a href="?r=sotarget&page='.$x.'">'.$x.'</a></li> ' : '<li><a href="?r=sotarget&page='.$x.'">'.$x.'</a></li>';
		    }
		}
		$x--;
		echo "<li><a href='?r=sotarget&page=$x'>Last</a></li></ul></div>";
	}else{
		msg_security();
	}
    break;

  case "tambahsotarget":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=sotarget&act=input'>
	          <fieldset><legend>Tambah SO Target</legend>
			  <label>Bulan :</label>
			  <select name='bulan' class='input-medium'>
				  <option value=''>--Pilih Bulan--</option>";
				  $bln = mysql_query("select * from month");
				  while($rbln = mysql_fetch_array($bln)){
					echo "<option value='$rbln[month_id]'>$rbln[month_name]</option>";
				  }
		echo "</select>
			  <select name='tahun' class='input-small'>
				  <option value='".date('Y')."'>".date('Y')."</option>
				  <option value='2012'>2012</option>
			  </select>
			  <br>
			  <label>Divisi :</label>
			  <select name='divisi' class='input-medium'><option value=''>--Pilih Divisi--</option>";
				$div = mysql_query("select * from sales_order_divisi");
				while($rdiv = mysql_fetch_array($div)){
					echo "<option value='$rdiv[divisi_id]'>$rdiv[divisi_name]</option>";
				}
		echo "</select>
			  <label>Value :</label>
			  <input type='text' name= 'value' id='value' class='easyui-numberbox input-medium'  required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "editsotarget":
	$access = update_security();
	if($access =="allow"){
	    $edit = mysql_query("SELECT a.*,b.divisi_name,c.month_name FROM sales_order_target a,sales_order_divisi b, month c 
		                    WHERE a.divisi_id=b.divisi_id and a.bulan=c.month_id and so_target_id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=sotarget&act=update>
	          <input type=hidden name=id value='$r[so_target_id]'>
	          <fieldset><legend>Edit SO Target</legend>
			  <label>Bulan :</label>
			  <select name='bulan' class='input-medium'>
				  <option value='$r[month_id]'>$r[month_name]</option>";
				  $bln = mysql_query("select * from month");
				  while($rbln = mysql_fetch_array($bln)){
					echo "<option value='$rbln[month_id]'>$rbln[month_name]</option>";
				  }
		echo "</select>
			  <select name='tahun' class='input-small'><option value='$r[tahun]'>$r[tahun]</option>
				  <option value='".date('Y')."'>".date('Y')."</option>
			  </select>
			  <br>
			  <label>Divisi :</label>
			  <select name='divisi' class='input-medium'><option value='$r[divisi_id]'>$r[divisi_name]</option>";
				$div = mysql_query("select * from sales_order_divisi");
				while($rdiv = mysql_fetch_array($div)){
					echo "<option value='$rdiv[divisi_id]'>$rdiv[divisi_name]</option>";
				}
		echo "</select>
			  <label>Value :</label>
			  <input type='text' name= 'value' id='value' class='easyui-numberbox input-medium' data-options='precision:2' value='$r[value]' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
