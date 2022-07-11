<?php

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

 // $conexion=mysql_connect("localhost","root","4868422Marco");
 // $bd=mysql_select_db("kidsplacesiat");

// $conexion=mysql_connect("localhost","root","12345678");
//  $bd=mysql_select_db("tiendaropa1000");

$conexion=mysql_connect("localhost","root","12345678");
$bd=mysql_select_db("kidsplace");

/*$conexion=mysql_connect("localhost","root","12345678");
$bd=mysql_select_db("tiendaropa1000");
*/

$sqlUTF=mysql_query("SET NAMES utf8");

date_default_timezone_set('America/La_Paz');

?>
