<?php

require("conexion.inc");
require("estilos.inc");

    $sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysql_query($sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysql_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}

echo "<form action='guarda_dolar.php' method='post'>";

echo "<h1>Cambiar Cotización Dolar</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Monto en Bs.</th>";
echo "<td align='center'>
	<input type='number' step='any' class='texto' name='monto_dolar' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required value='".$tipoCambio."'>
</td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
";

echo "</form>";
?>