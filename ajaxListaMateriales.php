<html>
<body>
<table align='center' class="texto">
<tr>
<th>Producto</th><th>Marca</th><th>Color</th><th>Talla</th><th>Stock</th><th>Precio</th></tr>
<?php
require("conexion.inc");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];



	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor)
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),m.cod_marca,m.talla,m.color from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and m.cod_grupo='$codTipo' ";
	}
	$sql=$sql." order by 2";
	//echo $sql;
	$resp=mysql_query($sql);

	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$linea=$dat[2];
			
			$stockProducto=stockProducto($globalAlmacen, $codigo);
			$marcaProducto=obtieneMarcaProducto($dat[3]);
			$tallaProducto=$dat[4];
			$colorProducto=$dat[5];
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			$rs=mysql_query($consulta);
			$registro=mysql_fetch_array($rs);
			$precioProducto=$registro[0];
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			$precioProducto=redondear2($precioProducto);
			$nombreEnvio=$nombre." (".$marcaProducto." ".$colorProducto." ".$tallaProducto.")";
			echo "<tr><td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreEnvio\")'>$nombre</a></div></td>
			
			<td>$marcaProducto</td>
			<td>$colorProducto</td>
			<td>$tallaProducto</td>
			<td>$stockProducto</td>
			<td>$precioProducto</td>
			</tr>";
		}
	}else{
		echo "<tr><td colspan='6'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>