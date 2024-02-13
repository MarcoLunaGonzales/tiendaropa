<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$codLote=$_POST['codLote'];
$nombre_lote=$_POST['nombre_lote'];

$codigo_material=$_POST['codigo_material'];
$obs_lote=$_POST['obs_lote'];
$cant_lote=$_POST['cant_lote'];

$sql_inserta="update lotes_produccion set 
 nombre_lote='$nombre_lote', 
 obs_lote='$obs_lote',
codigo_material='$codigo_material',  
cant_lote='$cant_lote'
where cod_lote='$codLote'";
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);



if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_lotes.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>