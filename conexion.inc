<?php

//header('Content-Type: text/html; charset=UTF-8'); 

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("tuadmintecniplast");



$conexion=mysql_connect("localhost","root","4868422Marco");
//$bd=mysql_select_db("tecniplast2810");
$bd=mysql_select_db("tiendatest");

?>
