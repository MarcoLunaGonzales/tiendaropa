<?php

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("tuadmintecniplast");


$conexion=mysql_connect("localhost","root","");
$bd=mysql_select_db("tienda3000");

$sqlUTF=mysql_query("SET NAMES utf8");

?>
