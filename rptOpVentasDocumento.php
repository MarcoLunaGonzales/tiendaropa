<?php

require("conexion.inc");
require("estilos_almacenes.inc");

//$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Documento</th></tr></table><br>";
echo"<form method='post' action='rptVentasDocumento.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Documento:</th>
	<td><select name='rpt_tipodoc[]' class='texto' multiple required>";
	echo "<option value='1'>FACTURA</option>";
	echo "<option value='2'>NOTA DE REMISION</option>";
	echo "</select></td></tr>";
	
	/*echo "<tr><th align='left'>Ver:</th>
	<td><select name='rpt_ver' class='texto'>";
	echo "<option value='0'>Todos</option>";
	echo "<option value='1'>Ver No Cancelados</option>";
	echo "</select></td></tr>";*/
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' id='exafinicial' name='exafinicial' required>";
    		echo" </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' id='exaffinal' name='exaffinal' required>";
    		echo" </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
?>