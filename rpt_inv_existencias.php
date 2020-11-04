<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');

$rptOrdenar=$_GET["rpt_ordenar"];
$rptGrupo=$_GET["rpt_grupo"];
$rptFormato=$_GET["rpt_formato"];


$rpt_fecha=cambia_formatofecha($rpt_fecha);
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysql_query($sql_nombre_territorio);
	$datos_nombre_territorio=mysql_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
	$datos_nombre_almacen=mysql_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
		echo "<h1>Reporte Existencias Almacen<br>Territorio: <strong>$nombre_territorio</strong> Nombre Almacen: <strong>$nombre_almacen</strong> <br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</h1>";
		//desde esta parte viene el reporte en si
		
		if($rptOrdenar==1){
			$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
			(select g.nombre_grupo from grupos g where g.cod_grupo=ma.cod_grupo)as nombregrupo, ma.peso
			from material_apoyo ma
			where ma.codigo_material<>0 and ma.estado='1' and ma.cod_grupo in ($rptGrupo) order by ma.descripcion_material";
		}else{
			$sql_item="select m.codigo_material,
			m.descripcion_material, cantidad_presentacion, g.nombre_grupo, m.peso from grupos g, 
			material_apoyo m where g.cod_grupo=m.cod_grupo and m.estado='1' 
			and m.cod_grupo in ($rptGrupo) order by 4,2";
		}
		
		//echo $sql_item;
		
		$resp_item=mysql_query($sql_item);
		
		if($rptOrdenar==1){
			/*echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>Codigo</th><th>Material</th><th>Peso[Kg]</th>
				<th>Cantidad</th></tr>
			</thead>";*/
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Material</th><th>Cantidad</th></tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>";
			}

		}else{
			/*echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>Codigo</th><th>Grupo</th><th>Material</th><th>Peso[Kg]</th>
				<th>Cantidad</th></tr>
			</thead>";*/
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Grupo</th><th>Material</th><th>Cantidad</th></tr>
				</thead>";				
			}else{//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Grupo</th><th>Material</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead>";
			}
		}
		
		$indice=1;
		while($datos_item=mysql_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreLinea=$datos_item[3];
			$pesoItem=$datos_item[4];
			
			$cadena_mostrar="<tbody>";
			if($rptOrdenar==1){
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td><td>$pesoItem</td>";
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td>";
				}else{//para inventario
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombre_item</td>";
				}
			}else{
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombreLinea</td><td>$nombre_item</td><td>$pesoItem</td>";				
				if($rptFormato==1){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombreLinea</td><td>$nombre_item</td>";			
				}else{
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombreLinea</td><td>$nombre_item</td>";				
				}
			}
			
			$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$rpt_almacen'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=0";
			$resp_ingresos=mysql_query($sql_ingresos);
			$dat_ingresos=mysql_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codigo_item' and s.salida_anulada=0";
			$resp_salidas=mysql_query($sql_salidas);
			$dat_salidas=mysql_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;

			$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
			$resp_stock=mysql_query($sql_stock);
			$dat_stock=mysql_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			
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
			$resp_linea=mysql_query($sql_linea);
			
			$num_filas=mysql_num_rows($resp_linea);
			if($rpt_linea!=0 and $num_filas==0)
			{	//no se muestra nada
			}
			else
			{	if($rpt_ver==1)
				{	echo $cadena_mostrar;
				}
				if($rpt_ver==2 and $stock_real>0)
				{	echo $cadena_mostrar;
				}
				if($rpt_ver==3 and $stock_real==0)
				{	echo $cadena_mostrar;
				}
				$indice++;
			}
		}
		$cadena_mostrar.="</tbody>";
		echo "</table>";
		
				include("imprimirInc.php");

?>