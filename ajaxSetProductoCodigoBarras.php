<?php
require("conexion.inc");
$codigoItem=$_GET['codigo'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

	$sql="select m.codigo_material, m.descripcion_material, m.cantidad_presentacion from material_apoyo m where estado=1 
		and m.codigo_barras = '$codigoItem'";
	$sql=$sql." limit 1";
	$resp=mysql_query($sql);
	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];			
			//SACAMOS EL PRECIO
			$sqlUltimoCosto="select id.precio_bruto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
			id.cod_material='$codigo' and i.cod_almacen='$globalAlmacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
			$respUltimoCosto=mysql_query($sqlUltimoCosto);
			$numFilas=mysql_num_rows($respUltimoCosto);
			$costoItem=0;
			if($numFilas>0){
				$costoItem=mysql_result($respUltimoCosto,0,0);
			}else{
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysql_query($sqlCosto);
				$numFilas2=mysql_num_rows($respCosto);
				if($numFilas2>0){
					$costoItem=mysql_result($respCosto,0,0);
				}
			}
			
			echo "1#####".$codigo."#####".$nombre."#####".$cantidadPresentacion."#####".$costoItem;
		}
	}else{
		echo "0#####_#####_#####_#####_";
	}

?>