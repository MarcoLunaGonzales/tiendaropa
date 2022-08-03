<?php
require("conexionmysqli2.inc");
require("funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$codigoItem=$_GET['codigo'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

	$sql="select m.codigo_material, m.descripcion_material, m.cantidad_presentacion,m.cod_grupo,m.cod_marca,m.color,m.talla from material_apoyo m where estado=1 
		and m.codigo_barras = '$codigoItem'";
	$sql=$sql." limit 1";
	$resp=mysqli_query($enlaceCon, $sql);
	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$grupo=$dat[3];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];			
			
			/*//SACAMOS EL PRECIO
			$sqlUltimoCosto="select id.precio_bruto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
			id.cod_material='$codigo' and i.cod_almacen='$globalAlmacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
			$respUltimoCosto=mysqli_query($enlaceCon, $sqlUltimoCosto);
			$numFilas=mysqli_num_rows($respUltimoCosto);
			$costoItem=0;
			if($numFilas>0){
				$costoItem=mysql_result($respUltimoCosto,0,0);
			}else{
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysqli_query($enlaceCon, $sqlCosto);
				$numFilas2=mysqli_num_rows($respCosto);
				if($numFilas2>0){
					$costoItem=mysql_result($respCosto,0,0);
				}
			}
			*/
			$costoItem=0;

			$sqlGrupo="select g.codigo from grupos g where estado=1 and g.codigo = '$grupo'";
	        $respGrupo=mysqli_query($enlaceCon, $sqlGrupo);
	        $envioGrupo=-1;
		    while($datGrupo=mysqli_fetch_array($respGrupo)){
              $envioGrupo=$datGrupo[0];
		    }
		    $marcaProducto=obtieneMarcaProducto($enlaceCon, $dat[4]);
			$tallaProducto=$dat[6];
			$colorProducto=$dat[5];
			echo "1#####".$codigo."#####".$nombre."#####".$cantidadPresentacion."#####".$costoItem."#####".$envioGrupo."#####".$marcaProducto."#####".$tallaProducto."#####".$colorProducto;
		}
	}else{
		echo "0#####_#####_#####_#####_#####_#####_#####_#####_";
	}

?>