<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");
$global_almacen=$_COOKIE['global_almacen'];

?>
<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="functionsGeneral.js"></script>
		
		
<script type='text/javascript' language='javascript'>
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

function listaMateriales(f){
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	
	var codMarca=f.itemMarca.value;
	var codBarraCod2=f.itemCodBarraCod2.value;
	contenedor = document.getElementById('divListaMateriales');

	var arrayItemsUtilizados=new Array();	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
			i++;
		}
	}
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&codMarca="+codMarca+"&codBarraCod2="+codBarraCod2+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxTipoDoc(f){
	var contenedor;
	contenedor=document.getElementById("divTipoDoc");
	ajax=nuevoAjax();
	var codTipoSalida=(f.tipoSalida.value);
	ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}


function ajaxPesoMaximo(codVehiculo){
	var contenedor;
	contenedor=document.getElementById("divPesoMax");
	ajax=nuevoAjax();
	var codVehiculo=codVehiculo;
	ajax.open("GET", "ajaxPesoMaximo.php?codVehiculo="+codVehiculo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function actStock(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	
	
}

function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();	
	
}
function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();

	actStock(numRegistro);
}
function calculaMontoMaterial(){
	console.log('enter calcula monto');
}

num=0;
cantidad_items=0;

function mas(obj) {
	if(num>=1000){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);
  
		if(banderaItems0==0){
			num++;
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialSalida.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}

	}
	
}
		
function menos(numero) {
	cantidad_items--;
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
function validar(f){
	
	f.cantidad_material.value=num;
	var cantidadItems=num;
	console.log("numero de items: "+cantidadItems);
	var tipoSalida=document.getElementById("tipoSalida").value;
	var almacenSalida=document.getElementById("almacen").value;
	//alert(tipoSalida+"  almacen: "+almacenSalida);
	if(tipoSalida==1000){
		if(almacenSalida==0 || almacenSalida==""){
			alert("Debe seleccionar un almacen de destino.");
			return(false);
		}
	}


	var sw=0;
 
	var valor=0;
	var stock=0;
	var cantSal=0;
 	var inputs = $('form input[name^="cod_material"]');
		inputs.each(function() {

  			valor = $(this).val();
  			stock=document.getElementById("stock"+valor).value;
  			cantSal=document.getElementById("cantidad_unitaria"+valor).value;
  			//alert("hola3 stock"+valor+"="+stock+"cantsal="+cantSal);
  		  	
  		  if( cantSal>stock || cantSal<0){


  		  	sw=1;
  		  	document.getElementById("cantidad_unitaria"+valor).focus();	

  		  }  	
  		
    	});

		if(sw==1){
    	 	alert(" Existen cantidades de Salida que son mayores a las de su stock o Cantidades de Salida menores a 0.");

    	 	return (false);
		}


}
	
	
</script>

		
<?php
echo "<body>";




if(isset($fecha)){
	$fecha=$fecha;
}else{
	$fecha="";
}


if($fecha=="")
{   $fecha=date("Y-m-d");
}
$fechaIni=date('Y-m-d',strtotime($fecha.'-5 days'));

$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' order by cod_salida_almacenes desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
?>
<form action='' method='POST' name='form1'>
<h1>Registrar Salida de Almacen</h1>

<table class='texto' align='center' width='90%'>
<tr><th>Tipo de Salida</th><th>Tipo de Documento</th><th>Nro. Salida</th><th>Fecha</th><th>Almacen Destino</th></tr>
<tr>
<td align='center'>
	<select name='tipoSalida' id='tipoSalida' onChange='ajaxTipoDoc(form1)' required>
		<option value="">--------</option>
<?php
	$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida<>1001 order by 2";
	$respTipo=mysqli_query($enlaceCon,$sqlTipo);
	while($datTipo=mysqli_fetch_array($respTipo)){
		$codigo=$datTipo[0];
		$nombre=$datTipo[1];
?>
		<option value='<?php echo $codigo?>'><?php echo $nombre?></option>
<?php		
	}
?>
	</select>
</td>
<td align='center'>
	<div id='divTipoDoc'>
		<select name='tipoDoc' id='tipoDoc'><option value="0"></select>
	</div>
</td>
<td align='center'>
	<div id='divNroDoc' class='textogranderojo'>
	</div>
</td>

<td align='center'>
	<input type='date' class='texto' value='<?=$fecha;?>' id='fecha' name='fecha' min='<?=$fechaIni;?>' max='<?=$fecha;?>'>
</td>

<td align='center'>
	<select name='almacen' id='almacen' class='texto'>
		<option value=''>-----</option>
<?php
	$sql3="select cod_almacen, nombre_almacen from almacenes where cod_almacen not in ($global_almacen) order by nombre_almacen";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3)){
		$cod_almacen=$dat3[0];
		$nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
?>
		<option value="<?php echo $cod_almacen?>"><?php echo $nombre_almacen?></option>
<?php		
	}
?>
	</select>
</td>
</tr>

<tr>
	<th>Observaciones</th>
	<th align='center' colspan="4">
		<input type='text' class='texto' name='observaciones' value='' size='100' rows="2">
	</th>
</tr>
</table>

<br>

	<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="9">
			<b>DETALLE</b>
		</td>
	</tr>
		<tr align="center">
		<th width="10%">-</th>
		<th width="10%">Marca</th>
		<th width="10%">Subgrupo</th>
		<th width="20%">Material</th>
		<th width="10%">Talla</th>
		<th width="10%">Color</th>
		<th width="10%">Stock</th>
		<th width="10%">Cantidad</th>
		<th width="10%">&nbsp;</th>
	</tr>
	<?php

  $sql="select mp.codigo_material, mp.descripcion_material,mp.estado,mp.cod_grupo, g.nombre as grupo , mp.cod_tipomaterial,
 mp.cantidad_presentacion, mp.observaciones, mp.imagen, mp.cod_unidad, um.nombre as unidadmedida, mp.peso, mp.cod_subgrupo, sg.nombre as subgrupo,
 mp.cod_marca,ma.nombre as marca ,mp.codigo_barras,mp.talla,mp.color,mp.codigo_anterior,mp.codigo2, mp.fecha_creacion
from material_apoyo mp
left join unidades_medida um on( mp.cod_unidad=um.codigo)
left join subgrupos sg on( mp.cod_subgrupo=sg.codigo)
left join grupos g on( sg.cod_grupo=g.codigo)
left join marcas ma on( mp.cod_marca=ma.codigo)
where mp.cod_marca in (select codigo from proveedores_marcas where cod_proveedor=1)
and mp.estado=1
order by mp.codigo_material asc";
$rptFechaInicio="2020-11-20";
$rpt_fecha=date("Y-m-d");
$resp = mysqli_query($enlaceCon,$sql);
$nro=0;
$cantProductosGral=0;
while ($dat = mysqli_fetch_array($resp)) {

	$codigo_material=$dat['codigo_material'];
	$descripcion_material=$dat['descripcion_material'];
	$estado=$dat['estado'];
	$cod_grupo=$dat['cod_grupo'];
	$grupo=$dat['grupo'];
	$cod_tipomaterial=$dat['cod_tipomaterial'];
 	$cantidad_presentacion=$dat['cantidad_presentacion'];
 	$observaciones=$dat['observaciones'];
 	$imagen=$dat['imagen'];
 	$cod_unidad=$dat['cod_unidad'];
 	$unidadmedida=$dat['unidadmedida'];
 	$peso=$dat['peso'];
 	$cod_subgrupo=$dat['cod_subgrupo'];
 	$subgrupo=$dat['subgrupo'];
 	$cod_marca=$dat['cod_marca'];
 	$marca=$dat['marca'];
 	$codigo_barras=$dat['codigo_barras'];
 	$talla=$dat['talla'];
 	$color=$dat['color'];
 	$codigo_anterior=$dat['codigo_anterior'];
 	$codigo2=$dat['codigo2'];
 	$fecha_creacion=$dat['fecha_creacion'];

 	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$rptFechaInicio 00:00:00' 
	and '$rpt_fecha 23:59:59' and i.cod_almacen='$global_almacen' and id.cod_material='$codigo_material' 
	and i.ingreso_anulado=0";
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	$dat_ingresos=mysqli_fetch_array($resp_ingresos);
	$cant_ingresos=$dat_ingresos[0];
			
	$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$rptFechaInicio 00:00:00' and '$rpt_fecha 23:59:59' and s.cod_almacen='$global_almacen'
	and sd.cod_material='$codigo_material' and s.salida_anulada=0";
	//echo $sql_salidas;
	$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
	$dat_salidas=mysqli_fetch_array($resp_salidas);
	$cant_salidas=$dat_salidas[0];
	$stock2=$cant_ingresos-$cant_salidas;
	$stock_real=$stock2;

	if($stock2>0){
		$cantProductosGral=$cantProductosGral+$stock2;
		$nro++;
 ?>

 <tr bgcolor="#FFFFFF">
<td width="10%" align="center"><?php echo $nro;?></td>

<td width="10%" ><?php echo $marca;?></td>
<td width="10%" ><?php echo $subgrupo;?></td>
<td width="20%" >
	<input type="hidden" name="cod_material<?php echo $codigo_material;?>" id="cod_material<?php echo $codigo_material;?>" 
	value="<?php echo $codigo_material;?>">
	<input type="hidden" name="desc_producto<?php echo $codigo_material;?>" id="desc_producto<?php echo $codigo_material;?>" 
	value="<?php echo $descripcion_material;?>">
<?php echo $codigo_material." - ".$descripcion_material;?>
</td>
<td width="10%" ><?php echo $talla;?></td>
<td width="10%" ><?php echo $color;?></td>
<td width="10%" align="center">
	<input type='hidden' id='stock<?php echo $codigo_material;?>' name='stock<?php echo $codigo_material;?>' value='<?php echo $stock2;?>'>	
	<?php echo $stock2;?>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="<?php echo $stock2;?>" min="0.01" 
	id="cantidad_unitaria<?php echo $codigo_material;?>" onKeyUp='calculaMontoMaterial(<?php echo $codigo_material;?>);' name="cantidad_unitaria<?php echo $codigo_material;?>" onChange='calculaMontoMaterial(<?php echo $codigo_material;?>);' step="0.01" required> 
</td>


<td align="center"  width="10%" ></td>

</tr>
<?php 
	}
?>
<?php

 }


	?>
	<tr><td colspan="6"></td><td align="center"><?php echo $cantProductosGral;?></td><td></td></tr>

	</table>
</fieldset>

<?php

echo "<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_salidamateriales.php\"'>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>





<input type='hidden' id='totalmat' value='<?=$cantidad_material;?>'>
<input type='hidden' id='codalmacen' value='<?=$global_almacen;?>'>
<input type='hidden' id='global_almacen' value='<?=$global_almacen;?>'>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

<input type='hidden' name='no_venta' value="1">

</form>
</body>