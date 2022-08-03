<script>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function ajaxSubGrupo(combo){
	var cod_grupo=combo.value;
	var contenedor;
	contenedor = document.getElementById('divSubGrupo');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxSubGrupo.php?cod_grupo='+cod_grupo+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxMarca(dato){
	//var cod_grupo=combo.value;
	var lineaproveedor=dato.value;
	var contenedor;
	contenedor = document.getElementById('divMarca');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxMarca.php?lineaproveedor='+lineaproveedor+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>

<head>

</head>
<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_grupo, m.cod_tipomaterial, 
	m.observaciones, m.cod_unidad, m.codigo_barras, m.color, m.talla, m.cod_marca, m.cod_subgrupo,
	m.codigo2
	from material_apoyo m where m.codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$estadoMaterialX=$datEdit[2];
	$codLineaX=$datEdit[3];
	/// obteniendo Codigo Proveedor
	$sqlAux="select cod_proveedor from proveedores_lineas where cod_linea_proveedor=$codLineaX";
	$respAux=mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	$proveedor=$datAux[0];
	/// Fin obteniendo Codigo Proveedor
	
	$codGrupoX=$datEdit[4];
	$codTipoX=$datEdit[5];
	$observacionesX=$datEdit[6];
	$codUnidadX=$datEdit[7];
	$codigoBarrasX=$datEdit[8];
	$colorX=$datEdit[9];
	$tallaX=$datEdit[10];
	$codMarcaX=$datEdit[11];
	$codSubGrupoX=$datEdit[12];
	$codigo2X=$datEdit[13];
}

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

echo "<form action='guarda_editarproducto.php' method='post' name='form1'>";

echo "<h1>Editar Producto</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";

echo "<tr><th align='left'>Nombre de Producto</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProductoX' required>
	</td>
	<th align='left'>Codigo Externo</th>
	<td align='left'>
	<input type='text' class='texto' name='codigo2' size='40' style='text-transform:uppercase;' value='$codigo2X' required>
	</td>
	</tr>";

echo "<tr><th align='left'>Código de Barras</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='codigo_barras' size='40' value='$codigoBarrasX' >
	</td>";	
echo "<td></td><td></td>";
echo "</tr>";

echo "<tr><th>Tipo</th>";
$sql1="select f.cod_tipomaterial, f.nombre_tipomaterial from tipos_material f where f.cod_tipomaterial in (1,2) order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipo=$dat1[0];
				$nombreTipo=$dat1[1];
				echo "<option value='$codTipo'>$nombreTipo</option>";
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Marca</th>";
$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
 $respMarca=mysqli_query($enlaceCon,$sqlMarca);
 if(mysqli_num_rows($respMarca)<=0){
	 $sqlMarca="select codigo, nombre from marcas where estado=1 order by nombre asc";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
}

echo "<td><div id='divMarca'>
			<select name='cod_marca' id='cod_marca' required>
			<option value=''></option>";
			while($datMarca=mysqli_fetch_array($respMarca))
			{	$codigoX=$datMarca[0];
				$nombreX=$datMarca[1];
				if($codMarcaX==$codigoX){
					echo "<option value='$codigoX' selected>$nombreX</option>";					
				}else{
					echo "<option value='$codigoX'>$nombreX</option>";
				}
			}
			echo "</select>
</div></td>";
echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.codigo, f.nombre from grupos f where f.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required onChange='ajaxSubGrupo(this);'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				if($codGrupoX==$codGrupo){
					echo "<option value='$codGrupo' selected>$nombreGrupo</option>";					
				}else{
					echo "<option value='$codGrupo'>$nombreGrupo</option>";					
				}
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Sub-Grupo</th>";
echo "<td>
<div id='divSubGrupo'>";
$sql="select codigo, nombre from subgrupos where cod_grupo in ($codGrupoX)";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select name='cod_subgrupo' class='texto' id='cod_subgrupo' required>";
echo "<option value=''>---</option>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	if($codSubGrupoX==$codigo){
		echo "<option value='$codigo' selected>$nombre</option>";
	}else{	
		echo "<option value='$codigo'>$nombre</option>";		
	}
}
echo "</select>";

echo "</div>";

echo "</td>";
echo "</tr>";

echo "<tr><th align='left'>Talla</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='talla' value='$tallaX' size='30'>
	</td>";

echo "<th align='left'>Color</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='color' value='$colorX' size='30'>
	</td></tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' value='$observacionesX' size='100' style='text-transform:uppercase;'>
	</td>";

echo "<tr><th>Unidad de Manejo</th>";
$sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_unidad' id='cod_unidad' required>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codUnidad=$dat1[0];
				$nombreUnidad=$dat1[1];
				$abreviatura=$dat1[2];
				if($codUnidadX==$codUnidad){
					echo "<option value='$codUnidad' selected>$nombreUnidad $abreviatura</option>";					
				}else{
					echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";					
				}
			}
			echo "</select>
</td>";
//echo "</tr>";


echo "<th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' type='file' class='boton2' disabled/> </td>";
echo "</tr>";

echo "<tr><th align='left'>Costo</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='costo_producto' id='costo_producto' value='$costo' step='0.1'>
	</td>";

echo "<th align='left'>Precio de Venta</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='precio_producto' id='precio_producto' value='$precio1' step='0.1'>
	</td></tr>";


echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>
