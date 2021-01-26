<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);

$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
$precioProducto=$_POST['precio_producto'];
$costoProducto=$_POST['costo_producto'];

$codigoBarras=$_POST['codigo_barras'];
$color=$_POST['color'];
$codMarca=$_POST['cod_marca'];
$codSubGrupo=$_POST['cod_subgrupo'];

$sql_inserta="update material_apoyo set descripcion_material='$nombreProducto', cod_linea_proveedor='$codLinea', 
cod_grupo='$codGrupo', observaciones='$observaciones', cod_unidad='$codUnidad', codigo_barras='$codigoBarras', color='$color',
talla='$talla', cod_marca='$codMarca', cod_subgrupo='$codSubGrupo' where codigo_material='$codProducto'";
$resp_inserta=mysql_query($sql_inserta);

//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codProducto";
$respDel=mysql_query($sqlDel);

$sqlInsertPrecio="insert into precios values($codProducto, 0,$costoProducto,'$globalAgencia')";
$respInsertPrecio=mysql_query($sqlInsertPrecio);

$sqlInsertPrecio="insert into precios values($codProducto, 1,$precioProducto,'$globalAgencia')";
$respInsertPrecio=mysql_query($sqlInsertPrecio);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>