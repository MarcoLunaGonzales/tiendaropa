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
<?php
require("conexionmysqli2.inc");
require('estilos.inc');

echo "<form enctype='multipart/form-data' action='guarda_material_apoyo.php' method='post' name='form1'>";

echo "<h1>Adicionar Producto</h1>";


echo "<center><table class='texto'>";

echo "<tr><th align='left'>Nombre Producto</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' required>
	</td> <th align='left'>Codigo Proveedor</th>
	<td align='left'><input type='text' class='texto' name='codigo2' id='codigo2' size='20' style='text-transform:uppercase;'> </td>
	</tr>";

echo "<tr><th align='left'>Código de Barras</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='codigo_barras' size='40' >
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

echo "<td>";



$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
 $respMarca=mysqli_query($enlaceCon,$sqlMarca);
 if(mysqli_num_rows($respMarca)<=0){
	 $sqlMarca="select codigo, nombre from marcas where estado=1 order by nombre asc";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
}


echo "<select name='cod_marca' id='cod_marca' class='texto' required>";
echo "<option value=''>---</option>";
while($datMarca=mysqli_fetch_array($respMarca)){
	$codigoX=$datMarca[0];
	$nombreX=$datMarca[1];
	echo "<option value='$codigoX'>$nombreX $codigoX</option>";
}
echo "</select></td>";

echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.codigo, f.nombre from grupos f 
where f.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required onChange='ajaxSubGrupo(this);'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				echo "<option value='$codGrupo'>$nombreGrupo</option>";
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Sub-Grupo</th>";
echo "<td>
<div id='divSubGrupo'></div>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Talla</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='talla' size='30'>
	</td>";

echo "<th align='left'>Color</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='color' size='30'>
	</td></tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' size='100' style='text-transform:uppercase;'>
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
				echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";
			}
			echo "</select>
</td>";



echo "<th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' type='file' class='boton2'/> </td>";
echo "</tr>";

echo "<tr><th align='left'>Costo</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='costo_producto' id='costo_producto' step='0.1'>
	</td>";

echo "<th align='left'>Precio de Venta</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='precio_producto' id='precio_producto' step='0.1'>
	</td></tr>";

?>	

	
<?php
echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>

