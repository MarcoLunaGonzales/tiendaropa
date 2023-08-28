<html>
<body>
<table align='center' class="texto">
<tr>
<th>Cod</th><th>Producto</th><th>Marca</th><th>C</th><th>T</th><th>Stock</th><th>Precio</th></tr>
<?php
require("conexionmysqli.php");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codMarca="";
$codBarraCod2="";
if(isset($_GET['codMarca'])){
	$codMarca=$_GET['codMarca'];	
}
if(isset($_GET['codBarraCod2'])){
	$codBarraCod2=$_GET['codBarraCod2'];
}
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];
 echo "globalAgencia".$globalAgencia;



	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor)from proveedores p, proveedores_lineas pl 
	where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
	m.cod_marca,m.talla,m.color,m.codigo_barras, m.codigo2
	from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($codBarraCod2!=""){
		$sql=$sql. " and (m.codigo_barras like '%$codBarraCod2%' or  m.codigo2 like '%$codBarraCod2%')";
	}
	if($codMarca!=0){
		$sql=$sql. " and m.cod_marca='$codMarca' ";
	}
	if($nombreItem!=""){
		$sql=$sql. " and m.descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and m.cod_grupo='$codTipo' ";
	}
	$sql=$sql." order by 2";
	
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	//echo $numFilas;
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$linea=$dat[2];			
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			$marcaProducto=obtieneMarcaProducto($enlaceCon,$dat[3]);
			$tallaProducto=$dat[4];
			$colorProducto=$dat[5];
			$codigoBarras=$dat[6];
			$codigo2=$dat[7];
			$precioProducto=0;
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$precioProducto=$registro[0];
			}
			
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			$precioProducto=redondear2($precioProducto);
			$nombreEnvio=$codigo2.$codigoBarras." ".$nombre." (<small>".$marcaProducto." ".$colorProducto." ".$tallaProducto."</small>)";
			echo "<tr><td>$codigo2 $codigoBarras </td>
			<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreEnvio\")'>$nombre</a></div></td>			
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