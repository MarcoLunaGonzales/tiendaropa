<?php
require("conexionmysqli.php");
require("estilos.inc");
require('funciones.php');

//recogemos variables

$codProducto=$_GET['cod_material'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_grupo, m.cod_tipomaterial, 
	m.observaciones, m.cod_unidad, m.codigo_barras, m.color, m.talla, m.cod_marca, m.cod_subgrupo, m.imagen from material_apoyo m where
	m.codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$estadoMaterialX=$datEdit[2];
	$codLineaX=$datEdit[3];
	$codGrupoX=$datEdit[4];
	$codTipoX=$datEdit[5];
	$observacionesX=$datEdit[6];
	$codUnidadX=$datEdit[7];
	$codigoBarrasX=$datEdit[8];
	$colorX=$datEdit[9];
	$tallaX=$datEdit[10];
	$codMarcaX=$datEdit[11];
	$codSubGrupoX=$datEdit[12];
	$imagenX=$datEdit[13];
}

$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//$codigo=mysql_result($resp,0,0);
$txtCopia="COPIA ";

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, codigo_barras, cod_subgrupo, cod_marca, color, talla) 
values ($codigo,'$txtCopia$nombreProductoX','1','$codLineaX','$codGrupoX','$codTipoX','1','$observacionesX','$imagenX','$codUnidadX',
'$codigoBarrasX','$codSubGrupoX','$codMarcaX','$colorX','$tallaX')";

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=0 and p.`codigo_material`='$codProducto'";
$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
$numFilas=mysqli_num_rows($respPrecio);
if($numFilas>=1){
	$datPrecio=mysqli_fetch_array($respPrecio);
	$costo=$datPrecio[0];
	//$costo=mysql_result($respPrecio,0,0);
	$costo=redondear2($costo);
}else{
	$costo=0;
	$costo=redondear2($costo);
}
$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`='$codProducto'";
$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
$numFilas=mysqli_num_rows($respPrecio);
if($numFilas>=1){
	$datPrecio=mysqli_fetch_array($respPrecio);
	$precio1=$datPrecio[0];
	//$precio1=mysql_result($respPrecio,0,0);
	$precio1=redondear2($precio1);
}else{
	$precio1=0;
	$precio1=redondear2($precio1);
}

//insertamos los precios
$sqlDel="delete from precios where codigo_material=$codigo";
$respDel=mysqli_query($enlaceCon,$sqlDel);
$sqlInsertPrecio="insert into precios values($codigo, 0,$costo,'$globalAgencia')";
$respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);
$sqlInsertPrecio="insert into precios values($codigo, 1,$precio1,'$globalAgencia')";
$respInsertPrecio=mysqli_query($enlaceCon,$sqlInsertPrecio);
echo $resp_inserta;
if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}	

?>