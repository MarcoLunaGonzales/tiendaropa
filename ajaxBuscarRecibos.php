<?php
require("conexionmysqli.php");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$proveedor=$_GET['proveedor'];
$tipoRecibo=$_GET['tipoRecibo'];
$cliente=$_GET['cliente'];
$detalle=$_GET['detalle'];
$global_agencia=$_COOKIE['global_agencia'];


if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
}
?>
<br><center><table class='texto'>
<tr>
<th>&nbsp;</th>
<th>Tipo Recibo</th>
<th>Recibo</th>
<th>Fecha</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Contacto</th>
<th>Nro de Contacto</th>
<th>Descripcion</th>
<th>&nbsp;</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th>&nbsp;</th>
</tr>
<?php
$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor
from recibos r 
inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
where r.cod_ciudad=".$global_agencia;
if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
	$consulta = $consulta." AND '$fechaIniBusqueda'<=r.fecha_recibo AND r.fecha_recibo<='$fechaFinBusqueda' ";
}
if(!empty($tipoRecibo)){
	$consulta=$consulta." and r.cod_tiporecibo like '%".$tipoRecibo."%' ";
} 
if(!empty($proveedor)){
	$consulta=$consulta." and r.cod_proveedor like '%".$proveedor."%' ";
} 
if(!empty($cliente)){
	$consulta=$consulta." and r.nombre_recibo like '%".$cliente."%' ";
} 
if(!empty($detalle)){
	$consulta=$consulta." and r.desc_recibo like '%".$detalle."%' ";
} 
	$consulta=$consulta." order by r.id_recibo DESC,r.cod_ciudad desc ";

$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tiporecibo= $dat['cod_tiporecibo'];
	$nombre_tiporecibo= $dat['nombre_tiporecibo'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$usuMod ="";
	 if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	 }
	////////////
	  $color_fondo = "";
	if ($recibo_anulado == 1) {
        $color_fondo = "#ff8080";
        
    }

?>	
   
	<tr style="background-color: <?=$color_fondo;?>;">
	<td><?php 
	if ($recibo_anulado == 0) {
	?>	
		<input type="checkbox" name="id_recibo" id="id_recibo" value="<?=$id_recibo;?>">
	<?php 
	}
	?>	
	</td>
	<td><?=$nombre_tiporecibo;?></td>	
	<td><?=$id_recibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto_recibo;?></td>
	<td><?=$nombre_recibo;?></td>
	<td><?=$cel_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>		
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
	
	<td><a href="formatoRecibo.php?idRecibo=<?=$id_recibo;?>" target="_BLANK">Ver Recibo</a>
	</tr>
	
<?php	
}
echo "</table></center><br>";


?>
