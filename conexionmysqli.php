<?php
header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/La_Paz');

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();

	if(!isset($estilosVenta)){
        //verificar niveles en url para insertar librerias
        $niv_url=substr_count($_SERVER["REQUEST_URI"], '/'); 
        switch ($niv_url) {
          case 2:include("librerias.php");$dirNoti="";break;
          case 3:include("librerias2.php");$dirNoti="../";break;
          case 4:include("librerias3.php");$dirNoti="../../";break;
        }
		}
}else{
}

//$enlaceCon=mysqli_connect("localhost","root","","kidsplace1000");

// $enlaceCon=mysqli_connect("localhost","root","4868422Marco","kidsplace1905");
$enlaceCon=mysqli_connect("localhost","root","12345678","tiendaropa");
// $enlaceCon=mysqli_connect("localhost","root","4868422Marco","kidsplacesiat");


if (mysqli_connect_errno())
{
	echo "Error en la conexión: " . mysqli_connect_error();
}
mysqli_set_charset($enlaceCon,"utf8");
?>