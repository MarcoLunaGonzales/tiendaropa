<?php
require_once 'conexion.inc';
require_once 'funciones.php';
require 'PHPMailer/send.php';


$codigo_salida=$_GET['codigo'];
$evento_salida=$_GET['evento'];

//SACAMOS LA VARIABLE PARA LA PERSONA QUE RECIBE EL CORREO
$correo=obtenerValorConfiguracion(9);

//$correo="mluna@minkasoftware.com";
$personal="2";
$evento="Anulacion de Venta";
$titulo="Anulacion de Venta";
$mensaje=""; 

//INICIO MENSAJE

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysql_query($sqlEmpresa);
	$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=mysql_result($respEmpresa,0,2);
	
	
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, (select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	(select c.dir_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	s.monto_total, s.descuento, s.monto_final, (select td.abreviatura from tipos_docs td where td.codigo=s.cod_tipo_doc)as tipodoc
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes='$codigo_salida'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$territorio_destino=$dat[5];
	$almacen_destino=$dat[6];
	$nombreCliente=$dat[7];
	$direccionCliente=$dat[8];
	$montoNota=$dat[9];
	$montoNota=redondear2($montoNota);
	$descuentoNota=$dat[10];
	$descuentoNota=redondear2($descuentoNota);
	$montoFinal=$dat[11];
	$montoFinal=redondear2($montoFinal);
	$tipoDocumento=$dat[12];

	$mensaje.="<span>Estimad@ Usuari@: TuFarma comunica que tuvo lugar la anulacion de la siguiente Venta:</span><br>";
		
	$mensaje.="<table border='1' cellspacing='0' cellpadding='0' align='center'>";
	$mensaje.="<tr><th align='left' width='30%'>$nombreEmpresa</th>
	<th align='center' width='30%'>$tipoDocumento<br>Nro. $nro_correlativo</th>
	<th align='right' width='30%'>Fecha: $fecha_salida_mostrar</th>
	</tr>";
	
	$mensaje.="<tr><td align='left' class='bordeNegroTdMod'>Cliente: $nombreCliente</td>
	<td align='center' class='bordeNegroTdMod'>NIT: $nitCliente</td><td align='right'>Obs.: $obs_salida</td></tr>";
			
	$mensaje.="</table><br>";

	$mensaje.="<table border='1' cellspacing='0' cellpadding='0' width='90%' align='center'>";
	
	$mensaje.="<tr><th>Producto</th><th>Lote</th><th>FVenc.</th>
	<th>Cant.</th><th>Precio</th>
		<th>Desc.U.</th><th>Importe</th></tr>";
	
	$mensaje.="<tr><td colspan='7'>&nbsp;</td></tr>";
	$mensaje.="<form method='post' action=''>";
	
	$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento, 
		s.cantidad_unitaria, s.precio_unitario, s.`descuento_unitario`, s.`monto_unitario` 
		from salida_detalle_almacenes s, material_apoyo m
		where s.cod_salida_almacen='$codigo' and s.cod_material=m.codigo_material";
	
	$resp_detalle=mysql_query($sql_detalle);
	$indice=0;
	$montoTotal=0;
	$pesoTotal=0;

	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$nombre_material=$dat_detalle[1];
		$loteProducto=$dat_detalle[2];
		$fechaVencimiento=$dat_detalle[3];
		$cantidad_unitaria=$dat_detalle[4];
		$precioUnitario=$dat_detalle[5];
		$precioUnitario=redondear2($precioUnitario);
		$descuentoUnitario=$dat_detalle[6];
		$descuentoUnitario=redondear2($descuentoUnitario);
		$montoUnitario=$dat_detalle[7];
		$montoUnitario=redondear2($montoUnitario);
		
		$mensaje.="<tr><td class='bordeNegroTdMod'>$nombre_material</td>
			<td align='center' class='bordeNegroTdMod'>$loteProducto</td>
			<td align='center' class='bordeNegroTdMod'>$fechaVencimiento</td>
			<td class='bordeNegroTdMod'>$cantidad_unitaria</td>
			<td class='bordeNegroTdMod'>$precioUnitario</td>
			<td class='bordeNegroTdMod'>$descuentoUnitario</td>
			<td class='bordeNegroTdMod' align='center'>$montoUnitario</td></tr>";
		$indice++;
		$montoTotal=$montoTotal+$montoUnitario;
		$montoTotal=redondear2($montoTotal);
	
	}	
	$mensaje.="<tr><th></th><th></th><th></th><th></th><th></th><th>Total Venta</th><th>$montoNota</th></tr>";
	$mensaje.="<tr><th></th><th></th><th></th><th></th><th></th><th>Descuento</th><th>$descuentoNota</th></tr>";
	$mensaje.="<tr><th></th><th></th><th>-</th><th>-</th><th></th><th>Total Final</th><th>$montoFinal</th></tr>";
	$mensaje.="</table><br><br><br>";

//FIN MENSAJE
    
    $mail_username="noresponse@minkasoftware.com";//Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail_userpassword="minka@2019";//Tu contraseña de gmail
	$mail_addAddress=$correo;//correo electronico que recibira el mensaje
	$template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
				
	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$mail_setFromEmail=$mail_username;
	$mail_setFromName="TuFarma";
	$txt_message=$mensaje;
	$mail_subject=$titulo; //el subject del mensaje
	
	$flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0);			
     if($flag!=0){
     	//echo "SE ENVIO CORRECTAMENTE";     
		echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegadorVentas.php';			
			</script>";
	 }else{
	 	echo "****** error ******";
	 }
?>