<html>
<body>
<table align='center' class="texto">
<tr>
<th>Producto</th><th>Stock</th></tr>
<?php
require("conexionmysqli.php");
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$codProveedor=$_GET['codProveedor'];
//$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$itemsNoUtilizar="0";
$cantAux1=0;
if($globalTipoFuncionario==2){
	$sqlAux1="select count(*) from proveedores_marcas 
where cod_proveedor in (select cod_proveedor from funcoionarios_proveedores where codigo_funcionario=".$global_usuario.") ";
$respAux1=mysqli_query($enlaceCon,$sqlAux1);
$cantAux1=mysqli_num_rows($respAux1);
}	


	$sql="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion, ma.color,ma.talla, s.nombre,g.nombre,m.nombre, ma.codigo2 
	    from material_apoyo ma
		left join subgrupos s on (ma.cod_subgrupo=s.codigo)
		left join grupos g on (s.cod_grupo=g.codigo)
		left join marcas m on (ma.cod_marca=m.codigo)
		where ma.estado=1 
		";
	if($globalTipoFuncionario==2 && $cantAux1>0){
		$sql=$sql." and ma.cod_marca in ( select codigo from proveedores_marcas where cod_proveedor=$codProveedor )";
	}
	$sql=$sql." and ma.cod_marca not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and ma.descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and ma.cod_grupo = '$codTipo' ";
	}
	$sql=$sql." order by 2";

	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];
			$talla=$dat[3];
			$color=$dat[4];
			$nombre_subgrupo=$dat[5];
			$nombre_grupo=$dat[6];
			$nombre_marca=$dat[7];
			$codigo2=$dat[8];
			
			//SACAMOS EL PRECIO
			$sqlUltimoCosto="select id.precio_bruto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
			id.cod_material='$codigo' and i.cod_almacen='$globalAlmacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
			$respUltimoCosto=mysqli_query($enlaceCon,$sqlUltimoCosto);
			$numFilas=mysqli_num_rows($respUltimoCosto);
			$costoItem=0;
			if($numFilas>0){
				$datUltimoCosto=mysqli_fetch_array($respUltimoCosto);
				$costoItem=$datUltimoCosto[0];
				//$costoItem=mysql_result($respUltimoCosto,0,0);
			}else{
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysqli_query($enlaceCon,$sqlCosto);
				$numFilas2=mysqli_num_rows($respCosto);
				if($numFilas2>0){
					$datCosto=mysqli_fetch_array($respCosto);
					$costoItem=$datCosto[0];
					//$costoItem=mysql_result($respCosto,0,0);
				}
			}
			
			echo "<tr><td align='left'><div class='textoform'><a href='javascript:setMateriales(form1, $codigo, \"<strong>$codigo2</strong>$nombre $talla $color ($nombre_marca)\", $cantidadPresentacion, $costoItem)'><strong>$codigo2</strong>$nombre $talla $color ($nombre_marca)</a></div></td><td><div class='textograndenegro'>-</a></div></td></tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}

?>
</table>

</body>
</html>