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
{   f.submit();
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
$global_almacen=$_COOKIE['global_almacen'];
$idReciboEditar=$_GET["idRecibo"];

	$sql=" select fecha_recibo,nombre_recibo,
desc_recibo,monto_recibo,created_by,modified_by,created_date,modified_date, cel_recibo,recibo_anulado,cod_tipopago 
from recibos  where id_recibo='".$idReciboEditar."' and cod_ciudad='".$global_agencia."'";	
	 

	$resp= mysqli_query($enlaceCon,$sql);				
	while($dat=mysqli_fetch_array($resp)){	
		$fecha_recibo=$dat['fecha_recibo'];
		$vector_fecha_recibo=explode("-",$fecha_recibo);
		$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
		$nombre_recibo=$dat['nombre_recibo'];
		$desc_recibo=$dat['desc_recibo'];
		$monto_recibo=$dat['monto_recibo'];
		$created_by=$dat['created_by'];
		$modified_by=$dat['modified_by'];
		$created_date=$dat['created_date'];
		$modified_date=$dat['modified_date'];
		$cel_recibo=$dat['cel_recibo'];
		$recibo_anulado=$dat['recibo_anulado'];
		$cod_tipopago=$dat['cod_tipopago'];
	}

?>
<form id='guarda_recibo' action='guarda_editarRecibo.php' method='post' name='form1' >
<input type="hidden" name="idReciboEditar" id="idReciboEditar" value="<?=$idReciboEditar;?>">
<table border='0' class='textotit' align='center'><tr><th>Edicion de Recibo</th></tr></table><br>
<table border="0" class="texto" cellspacing="0" align="center" width="80%" style="border:#ccc 1px solid;">
<tr><th>Nro de Recibo</th><th>Fecha de Recibo</th><th>Monto Recibido</th><th>Tipo Pago</th></tr>
<tr>

<td align="center"><?=$idReciboEditar;?></td>

<td align="left"><?=$fecha_recibo_mostrar;?></td>

<td align="left"><input type="number" class="texto" name="monto"  id="monto"  value="<?=$monto_recibo;?>" required>
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
		<option value="<?=$codTipopago;?>"  <?php if($codTipopago==$cod_tipopago){echo "selected";}?>><?=$nombreTipopago;?></option>
		
<?php	}?>
	</select>
	</td>
</tr>

<tr><th colspan="2">Cliente</th><th colspan="2">Telefono Cliente</th></tr>
<tr>
<td align="left" colspan="2"><input type="text" class="texto" name="nombre" size="80"  id="nombre" value="<?=$nombre_recibo;?>" required></td>
<td align="left" colspan="2"><input type="text" class="texto" name="nro_contacto"  size="60" id="nro_contacto" value="<?=$cel_recibo;?>" required></td>

</td>
</tr>
<tr><th colspan="4">Detalle</th></tr>
<td colspan="4" ><input type='text' class='texto' name='desc_recibo' value="<?=$desc_recibo;?>" size='150'></td>

</tr>
</table>"


<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);"></center>
<input type="button" class="boton2" value="Cancelar" onClick="cancelar(this.form);"></center>
</div>
</div>



</form>
</body>