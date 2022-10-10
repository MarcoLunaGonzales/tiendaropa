<?php
require("conexionmysqli.inc");
require("estilos.inc");
require("funciones.php");
?>

<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type="text/javascript" src="functionsGeneral.js"></script>
        <script src="lib/sweetalert2/sweetalert2.all.js"></script>
<script>

function validar(f)
{    return(true);
}
function cancelar(f)
{  location.href="listaRecibos.php";
}
	</script>
<?php



if($fecha=="")
{   $fecha=date("d/m/Y");
}
$global_agencia=$_COOKIE['global_agencia'];
?>
<form id='guarda_recibo' action='guarda_recibo.php' method='post' name='form1' >
<table border='0' class='textotit' align='center'><tr><th>Registro de Recibo</th></tr></table><br>
<table border="0" class="texto" cellspacing="0" align="center" width="80%" style="border:#ccc 1px solid;">
<tr><th>Tipo Recibo</th><th>Nro de Recibo</th><th>Fecha de Recibo</th><th>Forma Pago</th><th>Monto Recibido</th></tr>
<tr>
<td>
	<select name="tipoRecibo" id="tipoRecibo" class="texto"  >
<?php	
	$sqlTipoRecibo="select cod_tiporecibo, nombre_tiporecibo from tipos_recibo where estado=1  order by cod_tiporecibo asc";
	$respTipoRecibo=mysqli_query($enlaceCon,$sqlTipoRecibo);
	while($datTipoRecibo=mysqli_fetch_array($respTipoRecibo))
	{	
?>
<?php	$codTiporecibo=$datTipoRecibo[0];
		$nombreTiporecibo=$datTipoRecibo[1];
	?>
		<option value="<?=$codTiporecibo;?>" ><?=$nombreTiporecibo;?></option>
		
<?php	}?>
	</select>
	</td>

<?php
$sql="select id_recibo from recibos where cod_ciudad=".$global_agencia." order by id_recibo desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}
?>
<td align="center"><?=$nro_correlativo;?></td>
<td align="center">
<input type="text" align="left" disabled="true" class="texto" value="<?=$fecha;?>" id="fecha" size="10" name="fecha">
<img id="imagenFecha" src="imagenes/fecha.bmp">
</td>
<td>
	<select name="tipoPago" id="tipoPago" class="texto"  >
<?php	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	
?>
<?php	$codTipopago=$datTipoPago[0];
		$nombreTipopago=$datTipoPago[1];
	?>
		<option value="<?=$codTipopago;?>" ><?=$nombreTipopago;?></option>
		
<?php	}?>
	</select>
	</td>
<td align="left"><input type="number" class="texto" name="monto"  id="monto" required></td>

</tr>

<tr><th>Cliente</th><th>Telefono Cliente</th><th colspan="2">Detalle</th><th>Proveedor</th></tr>
<tr>
<td align="left" ><input type="text" class="texto" name="nombre" size="35"  id="nombre" required></td>
<td align="left" ><input type="text" class="texto" name="nro_contacto"  size="15" id="nro_contacto" required></td>
<td colspan="2" ><input type='text' class='texto' name='desc_recibo' size='60'></td>
<td>
	<select name="proveedor" id="proveedor" class="texto"  >
	<option value="" >NINGUNO</option>
<?php	
	$sql3="select cod_proveedor, nombre_proveedor from proveedores where estado=1  order by nombre_proveedor asc";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3))
	{	
?>
<?php	$codProveedor=$dat3[0];
		$nombreProveedor=$dat3[1];
	?>
		<option value="<?=$codProveedor;?>" ><?=$nombreProveedor;?></option>
		
<?php	}?>
	</select>
</td>


</td>
</tr>

</table>"


<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);"></center>
<input type="button" class="boton2" value="Cancelar" onClick="cancelar(this.form);"></center>
</div>
</div>



</form>
</body>