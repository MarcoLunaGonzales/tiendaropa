<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_material_apoyo.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_material_apoyo.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_material_apoyo.php?cod_material='+j_ciclo+'';
				}
			}
		}
        function cambiar_vista(f)
		{
			var modo_vista;
			var modo_orden;
			var grupo;
			modo_vista=f.vista.value;
			modo_orden=f.vista_ordenar.value;
			grupo=f.grupo.value;			

			var grupo2=$('#itemGrupoBusqueda').val();
			var marca=$('#itemMarcaBusqueda').val();
			var talla=$('#itemTallaBusqueda').val();
			var color=$('#itemColorBusqueda').val();
			var nombre=$('#itemNombreBusqueda').val();
			var cod_barras=$('#input_codigo_barras').val();
			location.href='navegador_material.php?vista='+modo_vista+'&vista_ordenar='+modo_orden+'&grupo='+grupo+'&gr='+grupo2+'&ma='+marca+'&ta='+talla+'&cl='+color+'&nm='+nombre+'&cb='+cod_barras;
		}
		function duplicar(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para duplicarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para duplicarlo.');
				}
				else
				{
					location.href='duplicarProducto.php?cod_material='+j_ciclo+'&tipo=1';
				}
			}
		}
		
		</script>";
		
	require("conexion.inc");
	require('estilos.inc');
	require("funciones.php");
	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script><?php
	$vista_ordenar=$_GET['vista_ordenar'];
	$vista=$_GET['vista'];
	$globalAgencia=$_COOKIE['global_agencia'];
	$grupo=$_GET['grupo'];
    

	echo "<h1>Registro de Productos</h1>";

	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre from grupos e where e.codigo=m.cod_grupo), 
		(select t.nombre from marcas t where t.codigo=m.cod_marca), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen, m.color, m.talla, m.codigo_barras
		from material_apoyo m
		where m.estado='1' and m.cod_tipomaterial in (1,2)";
	if($vista==1)
	{	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre from grupos e where e.codigo=m.cod_grupo), 
		(select t.nombre from marcas t where t.codigo=m.cod_marca),
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor),
		m.observaciones, imagen, m.color, m.talla, m.codigo_barras
		from material_apoyo m
		where m.estado='0' and m.cod_tipomaterial in (1,2)";
	}
	if($grupo!=0){
		$sql.=" and m.cod_grupo in ($grupo) ";
	}

	//nuevos filtros
  $gr=0;$ma=0;$ta="";$cl="";$nm="";$cb="";
  if(isset($_GET['gr'])&&$_GET['gr']!=0){
      $sql.=" and m.cod_grupo in (".$_GET["gr"].")";
      $gr=$_GET['gr'];
  }
  if(isset($_GET['ma'])&&$_GET['ma']!=0){
      $sql.=" and m.cod_marca in (".$_GET["ma"].")";
      $ma=$_GET['ma'];
  }
  if(isset($_GET['ta'])&&$_GET['ta']!=""){
      $sql.=" and m.talla='".$_GET["ta"]."'";
      $ta=$_GET['ta'];
  }
  if(isset($_GET['cl'])&&$_GET['cl']!=""){
      $sql.=" and m.color like '%".$_GET["cl"]."%'";
      $cl=$_GET['cl'];
  }
  if(isset($_GET['nm'])&&$_GET['nm']!=""){
      $sql.=" and m.descripcion_material like '%".$_GET["nm"]."%'";
      $nm=$_GET['nm'];
  }
  if(isset($_GET['cb'])&&$_GET['cb']!=""){
      $sql.=" and m.codigo_barras='".$_GET["cb"]."'";
      $cb=$_GET['cb'];
  }

   if($vista_ordenar==0){
		$sql=$sql." order by 4,2";
	}
	if($vista_ordenar==1){
		$sql=$sql." order by 2";	
	}
	if($vista_ordenar==2){
		$sql=$sql." order by 6,2";	
	}
    if($gr==0&&$ma==0&&$ta==""&&$cl==""&&$nm==""&&$cb==""){
      $sql=$sql." limit 50";
    }
	

	
	echo $sql;
	$resp=mysql_query($sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Productos:
	<select name='vista' class='texto' onChange='cambiar_vista(this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	echo "</select>
	</th>
	
	<th>Filtrar Grupo:
	<select name='grupo' class='texto' onChange='cambiar_vista(this.form)'>";
	echo "<option value='0'>-</option>";
	$sqlGrupo="select codigo, nombre from grupos where estado=1 order by 2";
	$respGrupo=mysql_query($sqlGrupo);
	while($datGrupo=mysql_fetch_array($respGrupo)){
		$codGrupoX=$datGrupo[0];
		$nombreGrupoX=$datGrupo[1];
		if($codGrupoX==$grupo){
			echo "<option value='$codGrupoX' selected>$nombreGrupoX</option>";
		}else{
			echo "<option value='$codGrupoX'>$nombreGrupoX</option>";
		}
	}
	echo "</select>
	</th>
	
	<th>
	Ordenar por:
	<select name='vista_ordenar' class='texto' onChange='cambiar_vista(this.form)'>";
	if($vista_ordenar==0)	echo "<option value='0' selected>Por Grupo y Producto</option><option value='1'>Por Producto</option><option value='2'>Por Linea y Producto</option>";
	if($vista_ordenar==1)	echo "<option value='0'>Por Grupo y Producto</option><option value='1' selected>Por Producto</option><option value='2'>Por Linea y Producto</option>";
	if($vista_ordenar==2)	echo "<option value='0'>Por Grupo y Producto</option><option value='1'>Por Producto</option><option value='2' selected>Por Linea y Producto</option>";
	echo "</select>
	</th>
	</tr></table><br>";
	
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton' onclick='duplicar(this.form)'>
		<a href='#' class='boton-verde' onclick='mostrarBusqueda()'><i class='fa fa-search'></i></a>
		</div>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th><th>Nombre</th><th>Descripcion</th>
		<th>Marca</th><th>Grupo/SubGrupo</th><th>Color</th><th>Talla</th>
		<th>Precio de Venta [Bs]</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$grupo=$dat[3];
		$tipoMaterial=$dat[4];
		$nombreLinea=$dat[5];
		$observaciones=$dat[6];
		$imagen=$dat[7];
		$color=$dat[8];
		$talla=$dat[9];
		$codigoBarras=$dat[10];
		
		$precioVenta=precioVenta($codigo,$globalAgencia);
		$precioVenta=$precioVenta;
		
		if($imagen==""){
			$imagen="default.png";
		}
		
		if($imagen=='default.png'){
			$tamanioImagen=80;
		}else{
			$tamanioImagen=80;
		}
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombreProd</td><td>($codigoBarras) $observaciones</td>
		<td>$tipoMaterial</td>
		<td>$grupo</td>
		<td>$color</td>
		<td>$talla</td>
		<td align='center'>$precioVenta</td>
		<td align='center'><img src='imagenesprod/$imagen' width='$tamanioImagen'></td>
		<td><a href='reemplazarImagen.php?codigo=$codigo&nombre=$nombreProd'><img src='imagenes/change.png' width='40' title='Reemplazar Imagen'></a></td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton' onclick='duplicar(this.form)'>
		</div>";
		
	echo "";
?>


<script>
function mostrarBusqueda(){
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
</script>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Grupo</th><th>Marca</th></tr>
			<tr>
			<td><select name='itemGrupoBusqueda' id="itemGrupoBusqueda" class="textomedianorojo" style="width:300px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from grupos g
			where g.estado=1 order by 2;";
			$respTipo=mysql_query($sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysql_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				if($codTipoMat==$gr){
				  echo "<option value=$codTipoMat selected>$nombreTipoMat</option>";	
				}else{
					echo "<option value=$codTipoMat>$nombreTipoMat</option>";
				}
			}
			?>
			</select>
			</td>
			<td>
				<select name='itemMarcaBusqueda' id="itemMarcaBusqueda" class="textomedianorojo" style="width:300px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from marcas g
			where g.estado=1 order by 2;";
			$respTipo=mysql_query($sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysql_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				if($codTipoMat==$ma){
				  echo "<option value=$codTipoMat selected>$nombreTipoMat</option>";	
				}else{
					echo "<option value=$codTipoMat>$nombreTipoMat</option>";
				}
				
			}
			?>
			</select>
			</td>
			</tr>
			<tr><th>Talla</th><th>Color</th></tr>
			<tr>
			<td>
				<input type='text' name='itemTallaBusqueda' id="itemTallaBusqueda" class="textomedianorojo"  onkeypress="return pressEnter(event, this.form);" value="<?=$ta?>">
			</td>
			<td>
				<input type='text' name='itemColorBusqueda' id="itemColorBusqueda" class="textomedianorojo"  onkeypress="return pressEnter(event, this.form);" value="<?=$cl?>">
			</td>
			</tr>
			<tr><th colspan="2">Nombre Producto</th></tr>
			<tr>
			<td colspan="2">
				<input type='text' style="width:100%" name='itemNombreBusqueda' id="itemNombreBusqueda" class="textomedianorojo"  onkeypress="return pressEnter(event, this.form);" value="<?=$nm?>">
			</td>
			</tr>
			<tr><th colspan="2">Codigo de Barras</th></tr>
			<tr>
			<td colspan="2" style="text-align:center;">
				<div class="codigo-barras div-center">
               <input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el codigo de barras." autofocus autocomplete="off">
         </div>
			</td>
			</tr>
		</table>
		<div class="div-center">
             <input type='button' class='boton-verde' value='Buscar Producto' id="btnBusqueda" onClick="cambiar_vista(this.form)">
		</div>
	
	</div>
</div>
</form>