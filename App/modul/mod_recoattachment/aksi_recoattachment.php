<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus recoattachment
if ($module=='recoattachment' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM detail_reco_attachment WHERE id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input recoattachment
elseif ($module=='recoattachment' AND $act=='input'){
	// membaca nilai n dari form upload
	$n = $_POST['jml_file']; 
	// setting nama folder tempat upload
	$uploaddir = '../../../attachment/';
	// proses upload yang diletakkan dalam looping
	for ($i=0; $i<=$n-1; $i++)
	{
		  $fileName = $_FILES['namafile'.$i]['name'];  
		  $fileType = $_FILES['namafile'.$i]['type'];		  
		  $fileSize = $_FILES['namafile'.$i]['size'];
		  $tmpName  = $_FILES['namafile'.$i]['tmp_name']; 
		  $uploadfile = $uploaddir . $fileName;
		  if ($fileSize > 0)
		  {
			  if (move_uploaded_file($tmpName, $uploadfile)){
				  mysql_query("insert into detail_reco_attachment(
															kode_promo,
															nama_file, 
															file, 
															type,
															size)
													values('$_POST[kode_promo]',
															'$fileName',
															'$fileName',
															'$fileType',
															$fileSize)");
				  header('location:../../index.php?r='.$module);
			  }
		  }		  
	}
	
}

// Update recoattachment
elseif ($module=='recoattachment' AND $act=='update'){
  mysql_query("UPDATE recoattachment SET recoattachment_name = '$_POST[recoattachment_name]'  
                          WHERE recoattachment_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
