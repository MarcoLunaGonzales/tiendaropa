<html>
<body>
<table align='center' class="texto">
<tr>
<th>Cod</th><th>Producto</th><th>Marca</th><th>C</th><th>T</th><th>Precio Compra</th><th>Precio Venta</th></tr>
<?php
require("conexionmysqli.inc");
require("funciones.php");
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codMarca=$_GET['codMarca'];
$codBarraCod2=$_GET['codBarraCod2'];

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
where cod_proveedor in (select cod_proveedor from funcionarios_proveedores where codigo_funcionario=".$global_usuario.") ";
$respAux1=mysqli_query($enlaceCon,$sqlAux1);
$cantAux1=mysqli_num_rows($respAux1);
}	


	$sql="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion, ma.color,ma.talla, s.nombre,g.nombre,m.nombre, ma.codigo2,
		ma.codigo_barras
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
	
	if($codBarraCod2!=""){
		$sql=$sql. " and (ma.codigo_barras like '%$codBarraCod2%' or  ma.codigo2 like '%$codBarraCod2%')";
	}
	if($codMarca!=0){
		$sql=$sql. " and ma.cod_marca='$codMarca' ";
	}
	if($nombreItem!=""){
		$sql=$sql. " and ma.descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and ma.cod_grupo = '$codTipo' ";
	}
	
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
			$codigoBarras=$dat[9];
						/// SACAMOS PRECIO DE VENTA
			///////////////
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo and p.cod_ciudad='".$globalAgencia."'";
					$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
					$numFilas=mysqli_num_rows($respPrecio);
					if($numFilas==1){
						$datPrecio=mysqli_fetch_array($respPrecio);
						$precio0=$datPrecio[0];
						
						$precio0=redondear2($precio0);
					}else{
						$precio0=0;
						$precio0=redondear2($precio0);
					}
			///////////////
			
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

			$costoItem=round($costoItem);
			$precio0=round($precio0);

			echo "<tr>
			<td>$codigo2 $codigoBarras</td>
			<td align='left'>
			<div class='texto'><a href='javascript:setMateriales(form1, $codigo, \"<strong>$codigo2 $codigoBarras</strong>$nombre $talla $color ($nombre_marca)\", $cantidadPresentacion, $costoItem, $precio0)'>$nombre </a></div></td>
			<td>$nombre_marca</td>
			<td>$talla</td>
			<td>$color</td>
		
			<td>$costoItem</td>
			<td>$precio0</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}

?>
</table>

</body>
</html>