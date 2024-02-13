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



</script>

<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');

$codProcesoConst=$_GET['codigo'];
$estado=$_GET['estado'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select nro_lote,nombre_lote,obs_lote,codigo_material,cant_lote,cod_estado_lote,created_by,
created_date,fecha_inicio_lote,fecha_fin_lote from lotes_produccion
 where cod_lote='$codLote'";

$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){

	$nro_loteX=$datEdit['nro_lote'];
	$nombre_loteX=$datEdit['nombre_lote'];
	$obs_loteX=$datEdit['obs_lote'];
	$codigo_materialX=$datEdit['codigo_material'];
	$cant_loteX=$datEdit['cant_lote'];
	$cod_estado_loteX=$datEdit['cod_estado_lote'];


}


echo "<form action='guarda_editarlote.php' method='post' name='form1'>";

echo "<h1>Editar Lote</h1>";


echo "<input type='hidden' name='codLote' id='codLote' value='$codLote'>";

echo "<center><table class='texto'>";
echo "<tr>";
echo "<th align='left'>Nro Lote</th>";
echo "<td align='left'>$nro_loteX</td> ";
echo "</tr>";
echo "<tr>";
echo "<th align='left'>Nombre Lote</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre_lote' id='nombre_lote' value='$nombre_loteX' size='40' style='text-transform:uppercase;' >
	</td> ";
echo "</tr>";
echo "<tr>";
echo" <th>Producto</th>";
$sql1="select codigo_material,descripcion_material from material_apoyo where estado=1 order by descripcion_material asc";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='codigo_material' id='codigo_material' required >";
			
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo_material=$dat1['codigo_material'];
				$descripcion_material=$dat1['descripcion_material'];

				if($codigo_materialX==$codigo_material){
					echo "<option value='$codigo_material' selected>$descripcion_material</option>";
				}else{
					echo "<option value='$codigo_material'>$descripcion_material</option>";
				}

				
			}
			echo "</select>
</td>";
echo "</tr>";
echo "<tr><th align='left'>Observacion</th>";
echo "<td align='left' >
	<input type='text' class='texto' name='obs_lote' id='obs_lote' value='$obs_loteX' size='100' style='text-transform:uppercase;'>
	</td>";
echo "</tr>";

echo "<tr><th>Cantidad de Produccion</th>
<td><input type='number' class='inputnumber'  id='cant_lote' name='cant_lote' value='$cant_loteX' size='6'  value='0'></td>
</tr>";


echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_lotes.php\"'>
</div>";
echo "</form>";
?>
