<?php
require("conexion.inc");
$codGrupo=$_GET['cod_grupo'];

$sql="select codigo, nombre from subgrupos where cod_grupo in ($codGrupo)";
$resp=mysql_query($sql);

echo "<select name='cod_subgrupo' class='texto' id='cod_subgrupo' required>";
echo "<option value=''>---</option>";
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];

	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";

?>
