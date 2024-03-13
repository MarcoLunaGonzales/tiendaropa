<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqli.php");
require("funciones.php");
$num=$_GET['codigo'];
$global_almacen=$_COOKIE['global_almacen'];
$global_agencia=$_COOKIE['global_agencia'];
$fechaActual=date("Y-m-d");


?>

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="35%" align="center">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="0">
<div id="cod_material<?php echo $num;?>" class='textograndenegro'>-</div>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" min="0.001" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="1" step="0.001" onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)' required>
</td>

<td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="0" required>
</td>


<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5" min="0.1" step="0.01"  
onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)'  <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?> required><br>
<input type="hidden" id='ultimoCosto<?php echo $num;?>' name='ultimoCosto<?php echo $num;?>' value=''>
<div id='divUltimoCosto<?php echo $num;?>'></div>
</td>
<td align="center" width="10%">
<input type="number" class="inputnumber" value="0" id="precioVenta<?php echo $num;?>" name="precioVenta<?php echo $num;?>" size="5" min="0.1" step="0.01" 
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required>
<br>

<div id='divPVenta<?php echo $num;?>'></div>


<td align="center" width="10%">
	<input type="number" class="inputnumber" value="0" id="precioVentaMayor<?php echo $num;?>" name="precioVentaMayor<?php echo $num;?>" size="5" min="0.1" step="0.01" 
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required>
<br>
<div id='divPVentaMayor<?php echo $num;?>'></div>
</td>


<td align="center" width="10%">
<div id='divPrecioTotal<?php echo $num;?>'></div>
</td>

<td align="center"  width="10%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>