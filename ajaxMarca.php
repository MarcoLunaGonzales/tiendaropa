<?php
require("conexionmysqli.php");
$lineaproveedor=$_GET['lineaproveedor'];

$sqlAux="select cod_proveedor from proveedores_lineas where cod_linea_proveedor=$lineaproveedor";
$respAux=mysqli_query($enlaceCon,$sqlAux);
$datAux=mysqli_fetch_array($respAux);
$proveedor=$datAux[0];

$sqlMarca="select codigo, nombre from marcas where estado=1 and codigo in( select codigo from proveedores_marcas where cod_proveedor=$proveedor)
 order by nombre asc";
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
	echo "<option value='$codigoX'>$nombreX</option>";
}
echo "</select>";

?>
