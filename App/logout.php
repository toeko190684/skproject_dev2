<?php
/*
created by toeko triyanto
logout.php is a file that used for destroy session user in web browser
*/
  session_start();
  session_unset();
  session_destroy();
  header('location:../login.php?r=1&n=Budgeting');

// Apabila setelah logout langsung menuju halaman utama website, aktifkan baris di bawah ini:

//  header('location:http://www.alamatwebsite.com');
?>
