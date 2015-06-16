<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";

if ($_GET[data]=='tabel'){
	if(($_POST[grade_id]=='*')||($_POST[grade_id]=='***')){
		$sql = mysql_query("select a.*,b.atasan1 from master_budget a,sec_users b where a.user=b.user_id 
		                   and a.status='pending' and a.tahun='$_POST[tahun]' and a.bulan='$_POST[bulan]' order by tgl_input desc");
	}else{
		$sql = mysql_query("select a.*,b.atasan1 from master_budget a,sec_users b where a.user=b.user_id
			               and a.status='pending' and b.atasan1 = '$_SESSION[user_id]' and a.tahun='$_POST[tahun]' and a.bulan='$_POST[bulan]'
						   order by tgl_input desc");
	}
	
	echo " <table class='table table-condensed table-hover table-bordered' >
			<tr class='success'>
				<td>No</td><td>Kode Budget</td><td>Periode</td><td>Keterangan</td><td>Value</td><td>User</td><td>Tgl Approved</td>
				<td>Approved By</td><td>Status</td><td>Aksi</td>
			</tr>";
	
	$no =1 ;
	$cek = mysql_num_rows($sql);
	if($cek >0){
		while($r = mysql_fetch_array($sql)){
			if(trim($r[status])=='Rejected'){$color = 'red';}
			else if(trim($r[status])=='Approved'){ $color = 'green';}
			else{$color='orange';}
			
			if(trim($r[status])=='Pending'){ 
				$link = "<a href='index.php?r=approvalbudget&act=editapprovalbudget&id=$r[kode_budget]'>View</a>";
			}else{
				$link = 'Ok';
			}
			
			echo "<tr style='text-color:$color'>
					<td>$no</td>
					<td>$r[kode_budget]</td>
					<td>$r[bulan] $r[tahun]</td>
					<td>$r[keterangan]</td>
					<td>".number_format($r[value],0,'.','.')."</td>
					<td>$r[user]</td>
					<td>$r[tgl_approval1]</td>
					<td>$r[approval1]</td>
					<td><font color=$color>$r[status]</font></td>
					<td>$link</td>
					</tr>";
			$no++;			
		}
	}else{
		echo "<tr><td colspan=10>Tidak ada data ditemukan..!</td></tr>";
	}
	echo "</table>";
}

if ($_GET[data]=='all'){
	if(($_POST[grade_id]=='*')||($_POST[grade_id]=='***')){
		$sql = mysql_query("select a.*,b.atasan1 from master_budget a,sec_users b where a.user=b.user_id 
		                   and a.status='pending' and a.tahun='$_POST[tahun]' order by tgl_input desc");
	}else{
		$sql = mysql_query("select a.*,b.atasan1 from master_budget a,sec_users b where a.user=b.user_id
			               and a.status='pending' and b.atasan1 = '$_SESSION[user_id]' 
						   and a.tahun='$_POST[tahun]' order by tgl_input desc");
	}
	
	echo " <table class='table table-condensed table-hover table-bordered' >
			<tr class='success'>
				<td>No</td><td>Kode Budget</td><td>Periode</td><td>Keterangan</td><td>Value</td><td>User</td><td>Tgl Approved</td>
				<td>Approved By</td><td>Status</td><td>Aksi</td>
			</tr>";
	
	$no =1 ;
	$cek = mysql_num_rows($sql);
	if($cek >0){
		while($r = mysql_fetch_array($sql)){
			if(trim($r[status])=='Rejected'){$color = 'red';}
			else if(trim($r[status])=='Approved'){ $color = 'green';}
			else{$color='orange';}
			
			if(trim($r[status])=='Pending'){ 
				$link = "<a href='index.php?r=approvalbudget&act=editapprovalbudget&id=$r[kode_budget]'>View</a>";
			}else{
				$link = 'Ok';
			}
			
			echo "<tr style='text-color:$color'>
					<td>$no</td>
					<td>$r[kode_budget]</td>
					<td>$r[bulan] $r[tahun]</td>
					<td>$r[keterangan]</td>
					<td>".number_format($r[value],0,'.','.')."</td>
					<td>$r[user]</td>
					<td>$r[tgl_approval1]</td>
					<td>$r[approval1]</td>
					<td><font color=$color>$r[status]</font></td>
					<td>$link</td>
					</tr>";
			$no++;			
		}
	}else{
		echo "<tr><td colspan=10>Tidak ada data ditemukan..!</td></tr>";
	}
	echo "</table>";
}
?>
