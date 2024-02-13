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
	ajax.open('GET', 'ajaxSubGrupoInsumo.php?cod_grupo='+cod_grupo+'',true);
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

echo "<form enctype='multipart/form-data' action='guarda_lote.php' method='post' name='form1'>";

echo "<h2>Adicionar Lote</h2>";
echo "<center><table class='texto'>";

echo "<tr>";
echo "<th align='left'>Nombre Lote</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre_lote' id='nombre_lote' size='40' style='text-transform:uppercase;' >
	</td> ";
echo "</tr>";
echo "<tr>";
echo" <th>Producto</th>";
$sql1="select codigo_material,descripcion_material from material_apoyo where estado=1 and cod_tipo=1 order by descripcion_material asc";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='codigo_material' id='codigo_material' required >
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo_material=$dat1['codigo_material'];
				$descripcion_material=$dat1['descripcion_material'];
				echo "<option value='$codigo_material'>$descripcion_material</option>";
			}
			echo "</select>
</td>";
echo "</tr>";
echo "<tr><th align='left'>Observacion</th>";
echo "<td align='left' >
	<input type='text' class='texto' name='obs_lote' id='obs_lote' size='100' style='text-transform:uppercase;'>
	</td>";
echo "</tr>";

echo "<tr><th>Cantidad de Produccion</th>
<td><input type='number' class='inputnumber'  id='cant_lote' name='cant_lote' size='6'  value='0'></td>
</tr>";

echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_lotes.php\"'>
</div>";
echo "</form>";
?>

