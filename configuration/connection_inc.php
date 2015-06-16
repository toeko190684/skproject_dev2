<?php
/*
 created by toeko triyanto 
 date : 21-10-2013
 this file is consist of configuration to connect with server database, which can be used and call other php file, so there is no rewrite script for
 connect to the database server

*/

$host = "localhost";
$username = "root";
$password = "m0r1n@g@";
$database = "skproject_dev";

$conn = mysql_connect($host,$username,$password) or die("couldn't connect to mysql server");
mysql_select_db($database)or die("couldn't connect to database skproject");

$conn2 = odbc_connect("kinosentraacc_dev","sa","") or die("could not connct to kinosentraacc database");

/*koneksi dengan pdo
koneksi create tanggal 13-05-2015
==========================================================================================================*/

$db=new PDO('mysql:host=localhost;dbname=skproject_dev;charset=utf8','root','m0r1n@g@');




?>