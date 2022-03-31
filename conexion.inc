<?php

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

$conexion=mysql_connect("localhost","root","4868422Marco");
$bd=mysql_select_db("kidsplace3");


$sqlUTF=mysql_query("SET NAMES utf8");

date_default_timezone_set('America/La_Paz');

?>
