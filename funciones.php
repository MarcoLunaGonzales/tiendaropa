<?php
function obtenerValorConfiguracion($enlaceCon,$id){
	$estilosVenta=1;
	//require("conexionmysqli2.inc");
	$sql = "SELECT valor_configuracion from configuraciones c where id_configuracion=$id";
	$resp=mysqli_query($enlaceCon,$sql);
	$codigo=0;
	while ($dat = mysqli_fetch_array($resp)) {
	  $codigo=$dat['valor_configuracion'];
	}
	return($codigo);
}

function numeroCorrelativoCUFD($enlaceCon,$tipoDoc){
	//require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	//echo "GlobalCiudad".$globalCiudad;
	$globalAlmacen=$_COOKIE['global_almacen'];	 
	//echo "GlobalAlmacen".$globalAlmacen;
  $fechaActual=date("Y-m-d");
  $sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";
	 
  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $datCufd=mysqli_fetch_array($respCufd);
  $cufd=$datCufd[0];//$cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";
  $bandera=1;

  $anioActual=date("Y");
  $sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";  
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $datCuis=mysqli_fetch_array($respCuis);
  $cuis=$datCuis[0];//$cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen' ";		
	$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera,'');  
}

function numeroCorrelativoCUFD2($tipoDoc){
	require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];	 

  $fechaActual=date("Y-m-d");
  $sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";
	// echo $sqlCufd;
  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";$bandera=1;

  $anioActual=date("Y");
	$sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen' ";				
				$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera);  
}

function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formatonumero($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumeroDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function UltimoDiaMes($cadena_fecha)
{	
	list($anioX, $mesX, $diaX)=explode("-",$cadena_fecha);
	$fechaNuevaX=$anioX."-".$mesX."-01";
	
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'+1 month'));
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'-1 day'));

	return($fechaNuevaX);
}

function obtenerCodigo($enlaceCon,$sql)
{	//require("conexion.inc");
	$resp=mysqli_query($enlaceCon,$sql);
	$nro_filas_sql = mysqli_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysqli_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}

function stockProducto($enlaceCon,$almacen, $item){
	//
	//require("conexion.inc");
	$fechaActual=date("Y-m-d");
	$fechaInicioSistema="2020-11-19 00:00:00";
	
	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0";
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=0";
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}

function stockMaterialesEdit($enlaceCon,$almacen, $item, $cantidad){
	//
	//require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($enlaceCon,$codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysqli_query($enlaceCon,$sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysqli_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysqli_query($enlaceCon,$sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($enlaceCon,$tipoDoc){
	//require("conexion.inc");
	$banderaErrorFacturacion=0;
	//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$datConf=mysqli_fetch_array($respConf);
	$facturacionActivada=$datConf[0];
	//$facturacionActivada=mysql_result($respConf,0,0);

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];
	
	if($facturacionActivada==1 && $tipoDoc==1){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual'";
		$respValidar=mysqli_query($enlaceCon,$sqlValidar);
		$datValidar=mysqli_fetch_array($respValidar);		
		$numFilasValidar=$datValidar[0];	
		//$numFilasValidar=mysql_result($respValidar,0,0);
		
		if($numFilasValidar==1){
			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1";
			$respCodDosi=mysqli_query($enlaceCon,$sqlCodDosi);
			$datCodDosi=mysqli_fetch_array($respCodDosi);		
			$codigoDosificacion=$datCodDosi[0];
			//$codigoDosificacion=mysql_result($respCodDosi,0,0);
		
			if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(f.nro_factura)+1,1) from facturas_venta f where 
				cod_dosificacion='$codigoDosificacion'";	
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
			}
			//echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			$dat=mysqli_fetch_array($resp);		
			$codigo=$dat[0];
			//$codigo=mysql_result($resp,0,0);
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if(($facturacionActivada==1 && ($tipoDoc==2 || $tipoDoc==3)) || $facturacionActivada!=1){
		$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}

function unidadMedida($enlaceCon,$codigo){
	
	$consulta="select u.abreviatura from material_apoyo m, unidades_medida u
		where m.cod_unidad=u.codigo and m.codigo_material='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$unidadMedida=$registro[0];

	return $unidadMedida;
}


function nombreTipoDoc($enlaceCon,$codigo){
	$consulta="select u.abreviatura from tipos_docs u
		where u.codigo='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$nombre=$registro[0];

	return $nombre;
}

function precioVenta($enlaceCon,$codigo,$agencia){
	
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and p.cod_ciudad='$agencia'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}
//COSTO 
function costoVentaFalse($enlaceCon,$codigo,$agencia){	
	$consulta="select sd.costo_almacen from salida_almacenes s, salida_detalle_almacenes sd where 
		s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen in  
		(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and s.salida_anulada=0 and 
		sd.cod_material='$codigo' limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}

function costoVenta($enlaceCon,$codigo,$agencia){	
	$consulta="select id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id where 
	i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen in  
			(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and i.ingreso_anulado=0 
	and id.cod_material='$codigo' order by i.cod_ingreso_almacen desc limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}


function codigoSalida($enlaceCon,$cod_almacen){	
	$consulta="select IFNULL(max(s.cod_salida_almacenes)+1,1) as codigo from salida_almacenes s";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$codigo=$registro[0];

	return $codigo;
}

function obtieneIdProducto($enlaceCon,$idProducto){
	$sql="select m.codigo_material from material_apoyo m where m.codigo_anterior='$idProducto'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$idProducto=$dat[0];
	return($idProducto);	
}

function obtieneMarcaProducto($enlaceCon,$idMarca){
	$sql="select m.nombre from marcas m where m.codigo='$idMarca'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombreMarca=$dat[0];
	return($nombreMarca);	
}
function fechaInicioSistema($enlaceCon){
	//6 FECHA DE INICIO DE OPERACIONES
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$datConf=mysqli_fetch_array($respConf);
	$fechaInicioOperaciones=$datConf[0];
	//$fechaInicioOperaciones=mysqli_result($respConf,0,0);	
	return($fechaInicioOperaciones);
}

function montoVentaDocumento($enlaceCon,$codVenta){
	$sql="select (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.cod_salida_almacenes=$codVenta";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$totalVenta=0;
	while($datos=mysqli_fetch_array($resp)){	
		
		$montoVenta=$datos[0];
		$cantidad=$datos[1];

		$descuentoVenta=$datos[2];
		$montoNota=$datos[3];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		$totalVenta=$totalVenta+$montoVenta;
	}
	return($totalVenta);	
}


function obtenerEstadoSalida($codSalida){
  	$estilosVenta=1;
  	require("conexionmysqli2.inc");
  	$sql_detalle="SELECT salida_anulada FROM salida_almacenes where cod_salida_almacenes='$codSalida'";
  	$estado=0;	
  	$resp=mysqli_query($enlaceCon,$sql_detalle);
  	while($detalle=mysqli_fetch_array($resp)){	
       $estado=$detalle[0]; 	
  	} 
  	mysqli_close($enlaceCon); 
  	return $estado;
	}

  function guardarPDFArqueoCajaVerticalFactura($nom,$html,$rutaGuardado,$codSalida){
    //aumentamos la memoria  
    ini_set("memory_limit", "128M");
    // Cargamos DOMPDF
    require_once 'assets/libraries/dompdf/dompdf_config.inc.php';
    $mydompdf = new DOMPDF();
    $mydompdf->set_paper('letter', 'portrait');    

    ob_clean();
    $mydompdf->load_html($html);
    $mydompdf->render();
    $canvas = $mydompdf->get_canvas();
    $canvas->page_text(540, 750, "{PAGE_NUM}/{PAGE_COUNT}", Font_Metrics::get_font("sans-serif"), 7, array(0,0,0)); 

    $estado=obtenerEstadoSalida($codSalida);
    if($estado!=0){ //facturas anuladas MARCA DE AGUA ANULADO
      //marca de agua
      $canvas2 = $mydompdf->get_canvas(); 
      $w = $canvas2->get_width(); 
      $h = $canvas2->get_height(); 
      $font = Font_Metrics::get_font("times"); 
      $text = "ANULADO"; 
      $txtHeight = -100; 
      $textWidth = 250; 
      $canvas2->set_opacity(.5); 
      $x = (($w-$textWidth)/2); 
      $y = (($h-$txtHeight)/2); 
      $canvas2->text($x, $y, $text, $font, 100, $color = array(100,0,0), $word_space = 0.0, $char_space = 0.0, $angle = -45);
    //fin marca agua
     }

    $output = $mydompdf->output();    
    file_put_contents($rutaGuardado, $output);
  }

?>