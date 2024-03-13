<?php
$indexGerencia=1;
require_once 'conexionmysqli.inc';
require_once 'estilos_almacenes.inc';


$global_almacen=$_COOKIE['global_almacen'];

?>
<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script-->
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
	var codGrupo=f.codGrupo.value;
	var nombreItem=f.itemNombreMaterial.value;
	var tipo=f.tipo.value;
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
	ajax.open("GET", "ajaxListaMateriales.php?codGrupo="+codGrupo+"&tipo="+tipo+"&codMarca="+codMarca+"&codBarraCod2="+codBarraCod2+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
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
	var tipo=f.tipo.value;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc+"&tipo="+tipo,true);
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
function setMateriales(f, cod, nombreMat, stock_producto, precio_producto, precio_productomayor){
	var numRegistro=f.materialActivo.value;
	
	console.log("numRegistro: "+numRegistro);
	console.log("codigoproducto: "+cod);

	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('precio_normal'+numRegistro).value=precio_producto;
	document.getElementById('precio_mayor'+numRegistro).value=precio_productomayor;
	

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
			ajax.open("GET","ajaxMaterialSalida.php?codigo="+num+"&tipo=2",true);
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
	//alert("num="+numero);
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

function mostrarItems(objlote){
	cantidad_items=1;

	   var myArray ;

     var lote;
     var numInsumos;
     var descProducto;
     var cantLote;
     var obsLote;
	if(objlote.value!=0){
     	myArray = (objlote.value).split("-");
     	lote=myArray[0];
     	numInsumos=myArray[1];
     	descProducto=myArray[2];
     	cantLote=myArray[3];
     	obsLote=myArray[4];
     		num=1;
 	}else{
 		lote=0;
     	numInsumos="";
     	descProducto="";
     	cantLote="";
     	obsLote="";
     	num=0;

 	}
  var contenedor;
	contenedor=document.getElementById("fiel");
	ajax=nuevoAjax();
	
	ajax.open("GET", "ajaxMaterialSalidaLote.php?lote="+lote+"&codigo="+num,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
	num=numInsumos;
	cantidad_items=numInsumos;
	document.getElementById('detalleLote').innerHTML=descProducto+' '+cantLote+' '+obsLote;
	//actualizarDetalleLote(descProducto);
}

function validar(f){
	
	f.cantidad_material.value=num;
	var cantidadItems=num;
	console.log("numero de items: "+cantidadItems);
	var tipoSalida=document.getElementById("tipoSalida").value;
	var lote=document.getElementById("lote").value;
	var almacenSalida=document.getElementById("almacen").value;
	//alert(tipoSalida+"  almacen: "+almacenSalida);
	if(tipoSalida==1000){
		if(almacenSalida==0 || almacenSalida==""){
			alert("Debe seleccionar un almacen de destino.");
			return(false);
		}
	}
	if(tipoSalida==1012){
		if(lote==0){
			alert("Cuando la Salida es por Produccion debe seleccionar un Lote.");
			return(false);
		}
	}

	if(cantidadItems>0){	
		var item="";
		var cantidad="";
		var stock="";
		var descuento="";
						
		for(var i=1; i<=cantidadItems; i++){
			console.log("valor i: "+i);
			console.log("objeto materiales: "+document.getElementById("materiales"+i));
			if(document.getElementById("materiales"+i)!=null){
				item=parseFloat(document.getElementById("materiales"+i).value);
				cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
				stock=parseFloat(document.getElementById("stock"+i).value);
		
				console.log("materiales"+i+" valor: "+item);
				console.log("stock: "+stock+" cantidad: "+cantidad);

				if(item==0){
					alert("Debe escoger un item en la fila "+i);
					return(false);
				}		
				if(stock<cantidad){
					alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
					return(false);
				}						
			}
		}
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}
	
	
</script>

		
<?php
echo "<body>";


$codLote=$_GET['codLote'];

	$sql="select lp.cod_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,lp.codigo_material,
mp.descripcion_material, lp.cant_lote,
lp.cod_estado_lote, lp.created_by,lp.created_date,
 lp.fecha_inicio_lote,lp.fecha_fin_lote 
from lotes_produccion lp
left join material_apoyo mp on(lp.codigo_material=mp.codigo_material) 
where lp.cod_lote=$codLote ";
//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp))
	{
		$cod_lote=$dat['cod_lote'];
		$nro_lote=$dat['nro_lote'];
		$nombre_lote=$dat['nombre_lote'];
		$obs_lote=$dat['obs_lote'];
		$codigo_material=$dat['codigo_material'];
		$descripcion_material=$dat['descripcion_material'];
		$cant_lote=$dat['cant_lote'];
		$cod_estado_lote=$dat['cod_estado_lote'];
		$nombre_estado_lote=$dat['nombre_estado_lote'];
		$created_by=$dat['created_by'];
		$created_date=$dat['created_date'];
}

if(isset($fecha)){
	$fecha=$fecha;
}else{
	$fecha="";
}


if($fecha=="")
{   $fecha=date("Y-m-d");
}
$fechaIni=date('Y-m-d',strtotime($fecha.'-5 days'));


?>
<form action='' method='POST' name='form1'>
<h1>Registrar Procesos<br/> LOTE:<?=$nombre_lote;?> </h1>
<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="9">
			<b>Detalle de la Transaccion   </b><input class="boton" type="button" value="Agregar (+)" onclick="mas(this)" />
		</td>
	</tr>
	<tr align="center">
		<th width="15%">-</th>
		<th width="35%">Proceso</th>
		<th width="35%">Proveedor</th>
		<th width="15%">Precio</th>
	</tr>
	</table>

	<?php
				$sqlDetalle="select pcp.cod_proceso_const,pc.nombre_proceso_const 
from procesos_construccion_producto pcp
left join procesos_construccion pc on (pcp.cod_proceso_const=pc.cod_proceso_const)
where pcp.cod_producto=".$codigo_material." order by pc.nombre_proceso_const asc";
				
			$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
			$indiceMaterial=1;
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$cod_proceso_const=$datDetalle['cod_proceso_const'];
				$nombre_proceso_const=$datDetalle['nombre_proceso_const'];
				
				$num=$indiceMaterial;
					
			?>

<div id="div<?php echo $num?>">

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="15%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="35%" align="left">
<input type="hidden" name="procesConst<?php echo $num;?>"
 id="procesConst<?php echo $num;?>" value="<?=$cod_proceso_const;?>">
<div id="descProcesConst<?php echo $num;?>" class='textoform'><?=$nombre_proceso_const;?></div>
</td>
<td width="35%" align="left">
<?php
$sql1="select cod_proveedor, nombre_proveedor from proveedores  order by nombre_proveedor";
$resp1=mysqli_query($enlaceCon,$sql1);
?>

	<select name='proveedor<?php echo $num;?>' id='proveedor<?php echo $num;?>' class='texto'>
<?php
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
 ?>
    <option value='<?=$codigo;?>'><?=$nombre;?></option>
<?php
}
?>
</select>
</td>
<td align="center" width="15%">
<input type="number" class="inputnumber" min="0.001" max="1000000" id="precio<?php echo $num;?>" name="precio<?php echo $num;?>" size="5"  value="0" step="0.001" onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)' required>
</td>

</tr>
</table>

</div>
			
			<?php
				$indiceMaterial++;
			}
			?>
	
</fieldset>

<?php

echo "<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_lotes.php?estado=\"'>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1100px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1220px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:1050px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Grupo</th><th>Marca</th><th>Cod.Barra/Cod.Prov</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select class="texto" name='codGrupo' id='codGrupo' style="width:120px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from grupos g
			where g.estado=1 order by 2;";
			echo $sqlTipo;
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>

			</select>
			</td>
			<td><select class="texto" name='itemMarca' style="width:120px">
			<?php
			$sqlMarca="select m.codigo, m.nombre from marcas m
			where m.estado=1 order by 2;";
			echo $sqlMarca;
			$respMarca=mysqli_query($enlaceCon,$sqlMarca);
			echo "<option value='0'>--</option>";
			while($datMarca=mysqli_fetch_array($respMarca)){
				$codMarca=$datMarca[0];
				$nombreMarca=$datMarca[1];
				echo "<option value=$codMarca> $codMarca - $nombreMarca</option>";
			}
			?>

			</select>
			</td>
			<td>
				<input type='text' name='itemCodBarraCod2' id='itemCodBarraCod2' style="width:120px" class="texto" onkeypress="return pressEnter(event, this.form);">
			</td>			
			<td>
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' style="width:180px" class="texto" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>

<input type='hidden' id='totalmat' value='<?=$cantidad_material;?>'>
<input type='hidden' id='codalmacen' value='<?=$global_almacen;?>'>
<input type='hidden' id='global_almacen' value='<?=$global_almacen;?>'>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

<input type='hidden' name='no_venta' value="1">

</form>
</body>
</html