<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.php');
require("funciones.php");

$rptOrdenar=$_GET["rpt_ordenar"];
$rptGrupo=$_GET["rpt_grupo"];
$rptMarca=$_GET["rpt_marca"];
$rptFormato=$_GET["rpt_formato"];
$rptBarCode=$_GET["rpt_barcode"];
$rpt_ver=$_GET["rpt_ver"];


$rptFechaInicio="2020-11-20";
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);


//$rpt_fecha=cambia_formatofecha($rpt_fecha);
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysqli_query($enlaceCon,$sql_nombre_territorio);
	$datos_nombre_territorio=mysqli_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
	
	echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Existencias Almacen
	<br>Territorio: $nombre_territorio <br>Nombre Almacen: <strong>$nombre_almacen</strong>
	<br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</td></tr></table>";
	
		//desde esta parte viene el reporte en si
		
		
			$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
			(select g.nombre from grupos g where g.codigo=ma.cod_grupo)as nombregrupo, ma.peso, ma.color, ma.talla, ma.codigo_barras,
			(select mar.nombre from marcas mar where mar.codigo=ma.cod_marca)as marca,ma.codigo2
			from material_apoyo ma
			where ma.codigo_material<>0 and ma.estado='1' 
			and ma.cod_grupo in ($rptGrupo) ";


			if($globalTipoFuncionario==2){
				if($cantFuncProv>0){
					$sql_item= $sql_item." and ma.cod_marca in ( select codigo from proveedores_marcas where cod_proveedor in
					( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario))";						
				}
			}else{
				$sql_item= $sql_item." and ma.cod_marca in ($rptMarca) ";
			}	
			
			if($rptBarCode!=""){
				$sql_item.=" and ma.codigo_barras like '$rptBarCode%' ";
			}
			if($rptOrdenar==1){
				$sql_item.=" order by ma.descripcion_material";
			}
			if($rptOrdenar==2){
				$sql_item.=" order by 4,2";
			}
			if($rptOrdenar==3){
				$sql_item.=" order by 9,4,2";
			}
			
		
		
		
		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		if($rptOrdenar==1){
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>COD INT</th><th>COD BARRA /COD PROV</th><th>Marca</th>><th>Grupo</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th></tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>COD INT</th><th>COD BARRA / COD PROV</th><th>Marca</th><th>Grupo</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>";
			}

		}else{

			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>COD INT</th><th>COD BARRA / COD PROV</th><th>Marca</th><th>Grupo</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th></tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>COD INT</th><th>COD BARRA / COD PROV</th><th>Marca</th><th>Grupo</th><th>Producto</th><th>Precio Actual</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead>";
			}
		}		
		$indice=1;
		$totalStock=0;
		while($datos_item=mysqli_fetch_array($resp_item))
		{	
	
			$codigo_item=$datos_item[0];
			
			///////////////
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo_item and p.cod_ciudad='".$_COOKIE['global_agencia']."'";
					$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
					$numFilas=mysqli_num_rows($respPrecio);
					if($numFilas==1){
						$datPrecio=mysqli_fetch_array($respPrecio);
						$precio0=$datPrecio[0];
						
						$precio0=redondear2($precio0);
					}else{
						$precio0=0;
						$precio0=redondear2($precio0);
					}
			///////////////
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreGrupo=$datos_item[3];
			$pesoItem=$datos_item[4];
			$colorItem=$datos_item[5];
			$tallaItem=$datos_item[6];
			$barCode=$datos_item[7];
			$nombreMarca=$datos_item[8];
			$codigo2=$datos_item[9];
			
			
			$cadena_mostrar="";
			if($rptOrdenar==1){
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td><td>$pesoItem</td>";
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$barCode $codigo2</td><td>$nombreMarca</td><td>$nombreGrupo</td><td>$nombre_item</td><td>$precio0</td>";
				}else{//para inventario
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$barCode $codigo2</td><td>$nombreMarca</td><td>$nombreGrupo</td><td>$nombre_item</td><td>$precio0</td>";
				}
			}else{
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombreLinea</td><td>$nombre_item</td><td>$pesoItem</td>";				
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$barCode $codigo2</td><td>$nombreMarca</td><td>$nombreGrupo</td>
					<td>$nombre_item</td><td>$precio0</td>";			
				}else{
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$barCode $codigo2</td><td>$nombreMarca</td><td>$nombreGrupo</td>
					<td>$nombre_item</td><td>$precio0</td>";				
				}
			}
			
			$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$rptFechaInicio 00:00:00' and '$rpt_fecha 23:59:59' and i.cod_almacen='$rpt_almacen'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=0";
			
			//echo $sql_ingresos;
			
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$rptFechaInicio 00:00:00' and '$rpt_fecha 23:59:59' and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codigo_item' and s.salida_anulada=0";
			
			//echo $sql_salidas;
			
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
			//echo $stock2;
			
			$stock_real=$stock2;
			
			if($stock2<0)
			{	$cadena_mostrar.="<td align='center'>0</td>";
				
			}
			else{	
			
				$cadena_mostrar.="<td align='center'>$stock2</td>";
				
			}
			
			if($rptFormato==2){//para inventario
				$cadena_mostrar.="<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			}
			
			$cadena_mostrar.="</tr>";
			
			$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysqli_query($enlaceCon,$sql_linea);			
			$num_filas=mysqli_num_rows($resp_linea);
			
			
			if($rpt_ver==1)
			{	echo $cadena_mostrar;
				//echo "rptver=".$rpt_ver."<br>";
				$indice++;
				$totalStock=$totalStock+$stock2;
			}
			if($rpt_ver==2 and $stock_real>0)
			{	echo $cadena_mostrar;
				//echo "rptver=".$rpt_ver."<br>";
				$totalStock=$totalStock+$stock2;
				$indice++;
			}
			if($rpt_ver==3 and $stock_real==0)
			{	//echo "rptver=".$rpt_ver."<br>";
		
				echo $cadena_mostrar;
				$totalStock=$totalStock+$stock2;
				$indice++;
			}
			
		}
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><strong>Total Productos</strong></td><td align='center'>$totalStock</td><td>&nbsp;</td></tr>";
		//$cadena_mostrar.="</tbody>";
		echo "</table>";
		
				include("imprimirInc.php");

?>