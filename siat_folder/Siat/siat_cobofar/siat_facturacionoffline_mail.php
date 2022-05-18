<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioOperaciones;

// use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\siat_cobofar\siat_factura_online;


use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionElectronica;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionFactura;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionPaquete;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioValidacionRecepcionPaquete;

use enviar_correo\php\PHPMailer\send;

class FacturacionOffLine
{
	/**
	 * 
	 * @return SiatConfig
	 */
	public static function buildConfig()
	{
		include dirname(__DIR__). SB_DS ."conexionSiat.php";
		//echo $siat_codigoSistema;	
		return new SiatConfig([
			'nombreSistema'	=> $siat_nombreSistema,
			'codigoSistema'	=> $siat_codigoSistema,
			'tipo' 			=> $siat_tipo,
			'nit'			=> $siat_nit,
			'razonSocial'	=> $siat_razonSocial,
			'modalidad'     => ServicioSiat::MOD_ELECTRONICA_ENLINEA,
			'ambiente'      => ServicioSiat::AMBIENTE_PRUEBAS,
			'tokenDelegado'	=> $siat_tokenDelegado,
			'cuis'			=> null,
			'cufd'			=> null,
		]);
	}
	public static function buildInvoice($codigoPuntoVenta = 0, $codigoSucursal = 0, $modalidad = 0)
	{
		$subTotal = 0;
		$factura = $modalidad == ServicioSiat::MOD_ELECTRONICA_ENLINEA ? new ElectronicaCompraVenta() : new CompraVenta();
			
		for($i = 0; $i < 1; $i++)
		{
			$detalle = new InvoiceDetail();
			$detalle->cantidad				= 1;
			$detalle->actividadEconomica	= '471110';
			$detalle->codigoProducto		= 'D001';
			$detalle->codigoProductoSin		= 621739; //SERVICIOS DE DISEÑO Y DESARROLLO DE TI PARA APLICACIONES
			$detalle->descripcion			= 'Nombre del producto #0' . ($i + 1);
			$detalle->precioUnitario		= 10;
			$detalle->montoDescuento		= 0;
			$detalle->subTotal				= $detalle->cantidad * $detalle->precioUnitario;
			$subTotal += $detalle->subTotal;
			$factura->detalle[] = $detalle;
		}
		$factura->cabecera->cufd ='';
		$factura->cabecera->razonSocialEmisor	= '';
		$factura->cabecera->municipio			= 'La Paz';
		$factura->cabecera->telefono			= '88867523';
		$factura->cabecera->numeroFactura		= rand(1, 1000000);
		// $factura->cabecera->numeroFactura		= 100;
		$factura->cabecera->codigoSucursal		= $codigoSucursal;
		$factura->cabecera->direccion			= 'Pedro Kramer #109';
		$factura->cabecera->codigoPuntoVenta	= $codigoPuntoVenta;
		// $factura->cabecera->fechaEmision		= date('Y-m-d\TH:i:s.v'); 
		$factura->cabecera->fechaEmision		= '2022-03-23T09:45:00.000'; 
		$factura->cabecera->nombreRazonSocial	= 'Perez';
		$factura->cabecera->codigoTipoDocumentoIdentidad	= 1; //CI - CEDULA DE IDENTIDAD
		$factura->cabecera->numeroDocumento		= 2287567;
		$factura->cabecera->codigoCliente		= 'CC-2287567';
		$factura->cabecera->codigoMetodoPago	= 1;
		$factura->cabecera->montoTotal			= $subTotal;
		$factura->cabecera->montoTotalMoneda	= $factura->cabecera->montoTotal;
		$factura->cabecera->montoTotalSujetoIva	= $factura->cabecera->montoTotal;
		$factura->cabecera->descuentoAdicional	= 0;
		$factura->cabecera->codigoMoneda		= 1; //BOLIVIANO
		$factura->cabecera->tipoCambio			= 1;
		$factura->cabecera->usuario				= 'MonoBusiness User 01';
		
		return $factura;
	}

	public static function SolicitudCuis($codigoPuntoVenta,$codigoSucursal)
	{				
		
		$config = self::buildConfig();
		$config->validate();
		
		$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
		$servCodigos->setConfig((array)$config);
		$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);

		$cuis=$resCuis->RespuestaCuis->codigo;
		return $cuis;
	}
	public static function RecepcionEvento($codigoClasificador,$descripcion,$codigoPuntoVenta,$codigoSucursal,$cufd,$cufdAntiguo,$fechaInicio,$fechaFin,$cuis)
	{				
		
		$config = self::buildConfig();
		$config->validate();
	
		$serviceOps = new ServicioOperaciones($cuis, $cufd);
		$serviceOps->setConfig((array)$config);	
		$serviceOps->codigoPuntoVenta=$codigoPuntoVenta;
		$serviceOps->codigoSucursal=$codigoSucursal;
		$serviceOps->cufd=$cufd;
		$serviceOps->cuis=$cuis;
		 // echo $fechaInicio."--->".$fechaFin."<br>".$cufdAntiguo;
		$resEvent = $serviceOps->registroEventoSignificativo($codigoClasificador, $descripcion, $cufdAntiguo, $fechaInicio, $fechaFin,$codigoSucursal,$codigoPuntoVenta);
		    // print_r($resEvent);
		echo "<br>Evento:<br>";
		print_r($resEvent);
		echo "<br>";

		if(isset($resEvent->RespuestaListaEventos->codigoRecepcionEventoSignificativo)){
			$codigoEvento=$resEvent->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
			// echo $codigoEvento;
			$descripcion="";
		}else{
			$codigoEvento=-1;
			$descripcion=$resEvent->RespuestaListaEventos->mensajesList->descripcion;
		}
		return array($codigoEvento,$descripcion);	
	}
	public static function RecepcionPaqueteFactura($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis,$codigoEvento)
	{
		try
		{	
			
			// $tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE;
			// $tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL;
			// instanciar*****
			$tipoEmision = 2;
			$tipoFactura = 1;

			require_once "siat_factura_online.php";
			// require "../../../../enviar_correo/php/PHPMailer/send.php";

			// echo "aqui";
			$template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
			// $privCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . 'privatekey.pem';
			// $pubCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . 'CORPORACION_BOLIVIANA_DE_FARMACIAS_SA_CER.pem';

			// $config = self::buildConfig();
			// $config->validate();
			
			// $service = new ServicioFacturacionElectronica($cuis, $cufd, $config->tokenDelegado);
			// $service->setConfig((array)$config);
			// $service->setPrivateCertificateFile($privCert);
			// $service->setPublicCertificateFile($pubCert);
			// $service->debug = true;
			// $service->fechaEnvio = date("Y-m-d\TH:i:s.000");
			// $facturas = [];
			// $i = 0;
			// $sqlUpdateFacturas="";
			// $sql="SELECT s.cod_salida_almacenes,(select siat_cafc from dosificaciones d where d.cod_dosificacion=s.cod_dosificacion and d.tipo_dosificacion=2 and d.tipo_descargo=2)as cafc
			//     FROM salida_almacenes s 
			//     WHERE s.cod_salida_almacenes in ($string_codigos) and s.cod_almacen=$cod_almacen and s.fecha = '$fecha'";
			//     //echo  $sql;
		 //    $valor=0;
		    require("../../conexionmysqli.inc");
		    require("../../funciones.php");
		 //    // echo $sql;
		 //    $resp=mysqli_query($enlaceCon,$sql);
		 //    $facturax= new FacturaOnline();
		 //    $cafc=null;
		 //    $string_codigosEnviados="";
		 //    while($row=mysqli_fetch_array($resp)){ 
		 //      	$cod_salida_almacenes=$row['cod_salida_almacenes'];
		 //      	$string_codigosEnviados.=$cod_salida_almacenes",";
		 //      	$cafc=$row['cafc'];
			// 	$factura=$facturax::testRecepcionFacturaElectronica($cod_salida_almacenes,2);   
			// 	// $facturax::testRecepcionFacturaElectronica($cod_salida_almacenes,2);      
			// 	// echo "+++";
			// 	if($cafc>0){
			// 		$factura->cabecera->cafc=$cafc;
			// 	}
			// 	$codigo_cuf=$factura->cabecera->cuf;
			// 	$facturas[$i] = $factura;
			// 	$sqlUpdateFacturas.="UPDATE salida_almacenes set siat_cuf='$codigo_cuf',siat_codigoRecepcion='$codigoEvento',siat_estado_facturacion='1' where cod_salida_almacenes='$cod_salida_almacenes';";
		 //    }
		 //     // print_r($factura);
		 //    $service->codigoSucursal=$cod_impuestos;
		 //    $service->codigoPuntoVenta=$codigoPuntoVenta;
		 //    // $service->cafc=$codigoPuntoVenta;
		    
			// $res = $service->recepcionPaqueteFactura($facturas, $codigoEvento,$tipoEmision,$tipoFactura,$cafc);
			//  // print_r($res);
			// if(isset($res->RespuestaServicioFacturacion->codigoRecepcion)){//si todo ok, validamos el Paquete
			// 	$codigoRecepcion = $res->RespuestaServicioFacturacion->codigoRecepcion;
			// 	$res2=$service->validacionRecepcionPaqueteFactura($codigoRecepcion);
			// 	if(isset($res2->RespuestaServicioFacturacion->codigoRecepcion) ){
			// 		$codigoRecepcion=$res2->RespuestaServicioFacturacion->codigoRecepcion;
			// 		$sqlUpdate="UPDATE siat_eventos set codigoRecepcionPaquete='$codigoRecepcion' where codigoRecepcionEventoSignificativo='$codigoEvento' and codigoRecepcionPaquete is null";
			// 		// echo "<br>**".$sqlUpdateFacturas."<br>**";
			// 		$resp=mysqli_query($enlaceCon,$sqlUpdate);
			// 		$resp2=mysqli_query($enlaceCon,$sqlUpdateFacturas);

					//si se envió correctamente al SIAT, enviamos al correo del cliente
					$string_codigosEnviados =trim($string_codigosEnviados,",");

					$consulta = "SELECT i.cod_salida_almacenes, i.fecha, i.hora_salida, i.razon_social, i.nro_correlativo, i.salida_anulada,(select p.nombre_cliente from clientes p where p.cod_cliente=i.cod_cliente) as cliente,i.cod_cliente,i.cod_chofer,i.siat_fechaemision,i.siat_cuf,i.monto_final FROM salida_almacenes i WHERE i.salida_anulada!=1 and i.cod_salida_almacenes in ($string_codigos) ";     
					$resp = mysqli_query($enlaceCon,$consulta);
					$descripcionCorreo="";
					// echo $consulta;
					while ($dat = mysqli_fetch_array($resp)) {


					  $cod_salida_almacenes = $dat['cod_salida_almacenes'];
					  $fecha_salida_mostrar=date("d-m-Y",strtotime($dat['siat_fechaemision']));

					  
					  
					  $nro_correlativo = $dat[4];
					  $proveedor=$dat['cliente'];
					  $idProveedor=$dat['cod_cliente'];
					  $cuf=$dat['siat_cuf'];
					  $monto_final=number_format($dat['monto_final'],2,'.',',');
					  $existePedidos++;
					  $correosProveedor=obtenerCorreosListaCliente($idProveedor);
					  
					  if($correosProveedor<>null and $correosProveedor<>'' and $correosProveedor<>' '){
					  	
					  	$stringMensaje="Estimado Cliente(a) ".$proveedor.": Adjuntamos la factura Nro.".$nro_correlativo." Gracias por su Compra!";
						$tituloMensajeEnvio="ENVIO DE FACTURA: ".$proveedor." Nro.".$nro_correlativo;

						// $rutaArchivo=trim(cargarDocumentosPDF($cod_salida_almacenes));
						// $rutaArchivoCSV=trim(cargarDocumentosXML($cod_salida_almacenes));
						$rutaArchivo="";
						$rutaArchivoCSV="";

						$name = trim($correosProveedor);
						$email = "";//copia
						$txt_message = trim($stringMensaje);
						$mail_subject=trim($tituloMensajeEnvio);//el subject del mensaje
						$idproveedor=$idProveedor;
						$codPedidos="";
						// $fechaActual=date("Y-m-d H:m:s");
						$mail_username="FARMACIAS BOLIVIA";//Correo electronico emisor
						$mail_userpassword="";// contraseña correo emisor
						//llamamos a la funcio que enviará correo
						$mail_addAddress=$name;

					    if($email!=""){
					      $mail_addAddress.=",".$email;  
					    }
						
					    /*Inicio captura de datos enviados por $_POST para enviar el correo */
					    $mail_setFromEmail=$mail_username;
					    $mail_setFromName=$mail_username;
					    // $txt_message=$contact_message;
					    // $mail_subject=$titulo_pedido_email; //el subject del mensaje
					    // echo "**";
						// $flag=sendemailFiles($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$rutaArchivo,$rutaArchivoCSV);
						$flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0);
						echo $flag."**";
					    $flag=1;
					    if($flag!=0){//se envio correctamente
					      $corr=explode( ',', $mail_addAddress);
					      // for($i = 0; $i < count($corr); $i++) {      
					      //   $correHist=$corr[$i]; 
					      //   $sql_detalle="DELETE FROM correos_historico where correo='$correHist'"; 
					      //   mysqli_query($enlaceCon,$sql_detalle);
					      //   $sql_detalle="INSERT INTO correos_historico (cod_funcionario,correo,cod_proveedor) VALUES('','$correHist','$idproveedor'); "; 
					      //   mysqli_query($enlaceCon,$sql_detalle);                

					      // }
					      // mysqli_close($enlaceCon);
					      $descripcionCorreo.="Factura Nro.".$nro_correlativo.". CORREO ENVIADO,";
					    }else{//error al enviar el correo
					      $descripcionCorreo.="Factura Nro.".$nro_correlativo." ERROR EN ENVIO,";
					    }
					  }else{
					  	$descripcionCorreo.="Factura Nro.".$nro_correlativo." CORREO NO REGISTRADO,";
					  }
						echo $descripcionCorreo;
					}
			// 	}
			// }

			//borramos los archivos temporales
			$files = glob('../Siat/temp/*.tar'); //obtenemos todos los nombres de los ficheros
			foreach($files as $file){
			    if(is_file($file))
			    unlink($file); //elimino el fichero
			}

			// echo "<br>Envio Paquete:<br>";
			// print_r($res);
			// echo "<br>";

			// echo "<br>Validación Paquete:<br>";
			// print_r($res2);
			// echo "<br>";
			$codigoEvento=1;
			$descripcion="DATOS RECEPCIONADOS CORRECTAMENTE."." ->>".$descripcionCorreo;;
			// if(isset($res2->RespuestaServicioFacturacion->codigoRecepcion)){
			// 	$codigoEvento=1;
			// 	if(isset($res2->RespuestaServicioFacturacion->mensajesList->descripcion)){
			// 		$descripcion=$res2->RespuestaServicioFacturacion->mensajesList->descripcion." ->>".$descripcionCorreo;
			// 	}else{
			// 		$descripcion="DATOS RECEPCIONADOS CORRECTAMENTE."." ->>".$descripcionCorreo;;

			// 	}
			// }else{
			// 	$codigoEvento=-1;
			// 	$descripcion=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;
			// }
			return array($codigoEvento,$descripcion);
			
		}
		catch(Exception $e)
		{
			echo "\033[0;31m", $e->getMessage(), "\033[0m", "\n\n";
			print $e->getTraceAsString();
		}
	}

}


?>
