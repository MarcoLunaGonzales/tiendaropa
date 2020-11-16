<?php
ini_set('post_max_size','100M');
?>

<script language='Javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function modifPrecioB(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioB').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[2].firstChild.value=datoNuevo;
	}

}

function modifPrecioC(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioC').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[3].firstChild.value=datoNuevo;
	}

}

function modifPrecioF(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioF').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[4].firstChild.value=datoNuevo;
	}
}

function modifPrecioG(){
   var main=document.getElementById('main');
   var numFilas=main.rows.length;
   var subtotal=0;
   var datoModif=parseFloat(document.getElementById('valorPrecioG').value);
   datoModif=datoModif/100;
	for(var i=1; i<=numFilas-1; i++){
		var dato=parseFloat(main.rows[i].cells[1].firstChild.value);
		var datoNuevo=dato+(datoModif*dato);
		main.rows[i].cells[5].firstChild.value=datoNuevo;
	}
}

function modifPrecios(indice){
	var main=document.getElementById("main");

	var datoModif=parseFloat(document.getElementById('valorPrecioB').value);
	datoModif=datoModif/100;
	var dato=parseFloat(main.rows[indice].cells[2].firstChild.value);
	var datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[2].firstChild.value=datoNuevo;

	datoModif=parseFloat(document.getElementById('valorPrecioC').value);
	datoModif=datoModif/100;
	dato=parseFloat(main.rows[indice].cells[3].firstChild.value);
	datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[3].firstChild.value=datoNuevo;

	datoModif=parseFloat(document.getElementById('valorPrecioF').value);
	datoModif=datoModif/100;
	dato=parseFloat(main.rows[indice].cells[4].firstChild.value);
	datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[4].firstChild.value=datoNuevo;
	
	datoModif=parseFloat(document.getElementById('valorPrecioG').value);
	datoModif=datoModif/100;
	dato=parseFloat(main.rows[indice].cells[5].firstChild.value);
	datoNuevo=dato+(datoModif*dato);
	main.rows[indice].cells[5].firstChild.value=datoNuevo;

}

function modifPreciosAjax(indice){
	var item=document.getElementById('item_'+indice).value;
	var precio1=document.getElementById('precio1_'+indice).value;
	var precio2=document.getElementById('precio2_'+indice).value;
	var precio3=document.getElementById('precio3_'+indice).value;
	var precio4=document.getElementById('precio4_'+indice).value;
	var precio5=document.getElementById('precio5_'+indice).value;
	contenedor = document.getElementById('contenedor_'+indice);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxGuardarPrecios.php?item="+item+"&precio1="+precio1+"&precio2="+precio2+"&precio3="+precio3+"&precio4="+precio4+"&precio5="+precio5,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML="Guardando...";
		}
	}
	ajax.send(null)
	
}

function cambiarPrecioIndividual(indice){
	var item=document.getElementById('item_'+indice).value;
	var precio1=document.getElementById('precio1_'+indice).value;
	precio1=parseFloat(precio1);
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioB').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[2].firstChild.value=datoNuevo;
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioC').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[3].firstChild.value=datoNuevo;
	
	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioF').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[4].firstChild.value=datoNuevo;

	var porcentajeCambiar=parseFloat(document.getElementById('valorPrecioG').value);
	porcentajeCambiar=porcentajeCambiar/100;
	var datoNuevo=precio1+(porcentajeCambiar*precio1);
	main.rows[indice].cells[5].firstChild.value=datoNuevo;
		
}

function enviar(f){
	f.submit();
}
</script>

<?php

	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	$globalAlmacen=$_COOKIE['global_almacen'];
	
	echo "<form method='POST' action='guardarPrecios.php' name='form1'>";
	
	//NOMBRES DE Precios
	$sql="select nombre from tipos_precio where codigo=1";
	$resp=mysql_query($sql);
	$nombrePrecio1=mysql_result($resp,0,0);
	
	$sql="select nombre from tipos_precio where codigo=2";
	$resp=mysql_query($sql);
	$nombrePrecio2=mysql_result($resp,0,0);
	
	$sql="select nombre from tipos_precio where codigo=3";
	$resp=mysql_query($sql);
	$nombrePrecio3=mysql_result($resp,0,0);
	
	$sql="select nombre from tipos_precio where codigo=4";
	$resp=mysql_query($sql);
	$nombrePrecio4=mysql_result($resp,0,0);
	
	$sql="select nombre from tipos_precio where codigo=5";
	$resp=mysql_query($sql);
	$nombrePrecio5=mysql_result($resp,0,0);
	
	$sql="select codigo_material, descripcion_material, p.nombre_linea_proveedor, codigo_barras
		from material_apoyo ma, proveedores_lineas p 
		where ma.cod_linea_proveedor=p.cod_linea_proveedor and ma.estado=1  and
		ma.cod_tipomaterial in (1) order by 3,2";

	//echo $sql;
	
	$resp=mysql_query($sql);
	echo "<h1>Registro y Edición de Precios</h1>";
	
	echo "<center><table class='texto' id='main'>";

	echo "<tr><th>Material</th>
	<th>$nombrePrecio1</th>
	<th>$nombrePrecio2<br><input type='text' size='2' name='valorPrecioB' id='valorPrecioB' value='0'>
	<a href='javascript:modifPrecioB()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>$nombrePrecio3<br><input type='text' size='2' name='valorPrecioC' id='valorPrecioC' value='0'>
	<a href='javascript:modifPrecioC()'><img src='imagenes/edit.png' width='30' alt='Editar'></a></th>
	<th>$nombrePrecio4<br><input type='text' size='2' name='valorPrecioF' id='valorPrecioF' value='0'>
	<a href='javascript:modifPrecioF()'><img src='imagenes/edit.png' width='30' alt='Editar'></th>
	<th>$nombrePrecio5<br><input type='text' size='2' name='valorPrecioG' id='valorPrecioG' value='0'>
	<a href='javascript:modifPrecioG()'><img src='imagenes/edit.png' width='30' alt='Editar'></th>
	<th>-</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];
		$barCode=$dat[3];


		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio1=mysql_result($respPrecio,0,0);
			$precio1=redondear2($precio1);
		}else{
			$precio1=0;
			$precio1=redondear2($precio1);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=2 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio2=mysql_result($respPrecio,0,0);
			$precio2=redondear2($precio2);
		}else{
			$precio2=0;
			$precio2=redondear2($precio2);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=3 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio3=mysql_result($respPrecio,0,0);
			$precio3=redondear2($precio3);
		}else{
			$precio3=0;
			$precio3=redondear2($precio3);
		}

		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=4 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio4=mysql_result($respPrecio,0,0);
			$precio4=redondear2($precio4);
		}else{
			$precio4=0;
			$precio4=redondear2($precio4);
		}
		
		$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=5 and p.`codigo_material`=$codigo";
		$respPrecio=mysql_query($sqlPrecio);
		$numFilas=mysql_num_rows($respPrecio);
		if($numFilas==1){
			$precio5=mysql_result($respPrecio,0,0);
			$precio5=redondear2($precio5);
		}else{
			$precio5=0;
			$precio5=redondear2($precio5);
		}
		
		//sql ultimo precio compra
		$sqlUltimaCompra="select id.precio_neto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where id.cod_ingreso_almacen=i.cod_ingreso_almacen and i.ingreso_anulado=0 and 
		i.cod_almacen='$globalAlmacen' and id.cod_material='$codigo' order by i.fecha desc limit 0,1";
		$respUltimaCompra=mysql_query($sqlUltimaCompra);
		$numFilasUltimaCompra=mysql_num_rows($respUltimaCompra);
		$precioBase=0;
		if($numFilasUltimaCompra>0){
			$precioBase=mysql_result($respUltimaCompra,0,0);
		}
		$precioBase=redondear2($precioBase);
		
		$sqlMargen="select p.margen_precio from material_apoyo m, proveedores_lineas p
			where m.cod_linea_proveedor=p.cod_linea_proveedor and m.codigo_material='$codigo'";
		$respMargen=mysql_query($sqlMargen);
		$numFilasMargen=mysql_num_rows($respMargen);
		$porcentajeMargen=0;

		if($numFilasMargen>0){
			$porcentajeMargen=mysql_result($respMargen,0,0);			
		}
		
		$precioConMargen=$precioBase+($precioBase*($porcentajeMargen/100));
		
		//(Ultima compra: $precioBase  --  Precio+Margen: $precioConMargen)
		echo "<tr><td>$nombreMaterial ($barCode)<a href='javascript:modifPreciosAjax($indice)'>
		<img src='imagenes/save3.png' title='Guardar este item.' width='30'></a>
		<a href='javascript:cambiarPrecioIndividual($indice)'>
		<img src='imagenes/flecha.png' title='Aplicar Porcentaje a este item.' width='30'></a>
		</td>";
		echo "<input type='hidden' name='item_$indice' id='item_$indice' value='$codigo'>";
		echo "<td align='center'><input type='text' size='5' value='$precio1' id='precio1_$indice' name='$codigo|1'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio2' id='precio2_$indice' name='$codigo|2'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio3' id='precio3_$indice' name='$codigo|3'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio4' id='precio4_$indice' name='$codigo|4'></td>";
		echo "<td align='center'><input type='text' size='5' value='$precio5' id='precio5_$indice' name='$codigo|5'></td>";
		echo "<td><div id='contenedor_$indice'></div></td>";
		echo "</tr>";
		
		$indice++;

	}
	echo "</table></center>";

	/*echo "<div class='divBotones'>
	<input type='button' value='Guardar Todo' name='adicionar' class='boton' onclick='enviar(form1)'>
	</div>";*/
	echo "</form>";
?>