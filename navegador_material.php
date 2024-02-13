<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav(f){

			var tipo=f.tipo.value
			var estado=f.estado.value
			location.href='registrar_material_apoyo.php?tipo='+tipo+'&estado='+estado;
		}

		function adicionargrupo(f){

			var tipo=f.tipo.value
			var estado=f.estado.value
			location.href='registrar_material_apoyo_masivo.php?tipo='+tipo+'&estado='+estado;
		}
		function eliminar_nav(f)
		{

			var tipo=f.tipo.value
			var estado=f.estado.value
			
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
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					
					location.href='eliminar_material_apoyo.php?datos='+datos+'&tipo='+tipo+'&estado='+estado;
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{

				var tipo=f.tipo.value
			var estado=f.estado.value
			

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
					location.href='editar_material_apoyo.php?cod_material='+j_ciclo+'&tipo='+tipo+'&estado='+estado;
				}
			}
		}
    function cambiar_vista(f)
		{
			var tipo;
			var estado;
		
			tipo=f.tipo.value;
			estado=f.estado.value;			
			var grupo=$('#grupo').val();
			var marca=$('#marca').val();
		
			var material=$('#material').val();
			var barra=$('#barra').val();
			location.href='navegador_material.php?tipo='+tipo+'&estado='+estado+'&grupo='+grupo+'&marca='+marca+'&material='+material+'&barra='+barra;
		}
		function duplicar(f)
		{
				var tipo;
			var estado;
		
			tipo=f.tipo.value;
			estado=f.estado.value;
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
					location.href='duplicarProducto.php?cod_material='+j_ciclo+'&tipo='+tipo+'&estado='+estado;
				}
			}
		}
		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script><?php
	$estado=$_GET['estado'];
	$tipo=$_GET['tipo'];
	$marca=$_GET['marca'];
	$grupo=$_GET['grupo'];
	
	$material=$_GET['material'];
	$barra=$_GET['barra'];
	$globalAgencia=$_COOKIE['global_agencia'];
    

	echo "<h3 align='center'>Registro de Productos</h3>";

	echo "<form method='post' action=''>";
	echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";
	

		$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,
		 m.talla, tal.nombre as nombreTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, 
		m.cod_material, mat.nombre as nombreMaterial, 
		m.cod_genero, gen.nombre as nombreGenero, es.nombre_estado
		from material_apoyo m
		left join estados es on (m.estado=es.cod_estado)
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		where m.cod_tipo=$tipo ";
	
	if($estado<>-1){
		$sql.=" and m.estado in ($estado) ";
	}

	if($marca<>0){
		$sql.=" and m.cod_marca in ($marca) ";
	}

	if($grupo<>0){
		$sql.=" and sgru.cod_grupo in ($grupo) ";
	}

  if($material!=""){
      $sql.=" and m.descripcion_material like '%".$_GET["material"]."%'";
      
  }
 if($barra!=""){
      $sql.=" and m.codigo_barras='".$_GET["barra"]."'";
   
  }
	$sql=$sql." order by m.descripcion_material asc";		
//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Productos:
<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql2="select es.cod_estado, es.nombre_estado from estados es order by es.cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		echo"	<option value='-1' selected>TODOS</option>";
			while($dat2=mysqli_fetch_array($resp2)){
				$codEstado=$dat2[0];
				$nombreEstado=$dat2[1];
				if($codEstado==$estado){
				  echo "<option value=$codEstado selected>$nombreEstado</option>";	
				}else{
					echo "<option value=$codEstado>$nombreEstado</option>";
				}
			}
			echo " </select>
	</th>
	

	</tr></table><br>";
	
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
				<input type='button' value='Adicionar en Grupo' name='adicionar' class='boton' onclick='adicionargrupo(this.form)'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		<input type='button' value='Duplicar' name='Duplicar' class='boton' onclick='duplicar(this.form)'>
		<a href='#' class='boton-verde' onclick='mostrarBusqueda()'><i class='fa fa-search'></i></a>
		</div> <br> <br>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nro</th><th>Codigo</th><th>Marca</th><th>Nombre</th>
		<th>Grupo/SubGrupo</th><th>Modelo</th><th>Genero</th><th>Material</th>
		<th>Color/<br/>Talla</th>
		<th>Precios</th><th>Fecha Creacion</th><th>Imagen</th>
		<th>Fijar Precios</th><th>Estado</th>
	
		</tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{


		$codigo=$dat['codigo_material'];
		$nombreProd=$dat['descripcion_material'];
		$estado=$dat['estado'];
		$grupo=$dat['nombreGrupo'];
		$subgrupo=$dat['nombreSubgrupo'];
		$marca=$dat['nombreMarca'];
		//$tipoMaterial=$dat[4];

		$observaciones=$dat['observaciones'];
		$imagen=$dat['imagen'];
		$color=$dat['color'];
		$nombreColor=$dat['nombreColor'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$codigoBarras=$dat['codigo_barras'];
		$codigo2=$dat['codigo2'];
		$fechaCreacion=$dat['fecha_creacion'];
		$nombreModelo=$dat['nombreModelo'];
		$nombreMaterial=$dat['nombreMaterial'];
		$nombreGenero=$dat['nombreGenero'];
		$nombre_estado=$dat['nombre_estado'];
				

		if($imagen==""){
			$imagen="default.png";
		}
		
		if($imagen=='default.png'){
			$tamanioImagen=50;
		}else{
			$tamanioImagen=50;
		}
		echo "<tr>
		<td align='center'><input type='checkbox' name='codigo' value='$codigo'></td>
		<td align='center'>$indice_tabla</td>
		<td>$codigoBarras -$codigo2</td>
		<td>$marca</td>
		<td>$nombreProd</td>
		<td>$grupo <br/> $subgrupo</td>
		
		<td>$nombreModelo</td>
		<td>$nombreGenero</td>
		<td>$nombreMaterial</td>
		<td>$nombreColor<br/><center>T:$nombreTalla</center></td>
		<td align='center'>";
		$sqlListPrecios="select p.codigo_material,p.cod_precio,gp.nombre ,gp.abreviatura ,p.precio,p.cod_ciudad,c.nombre_ciudad,
		p.cant_inicio,p.cant_final, p.created_by, 
		concat(f.nombres,' ',f.paterno,' ',f.materno) as creado_por, p.created_date
		
		from precios p
		left join grupos_precio gp on (p.cod_precio=gp.codigo)
		left join ciudades c on (p.cod_ciudad=c.cod_ciudad)
		left join funcionarios f on (p.created_by=f.codigo_funcionario)
	
		where p.codigo_material='".$codigo."'and p.cod_ciudad='".$globalAgencia."' order by p.cod_precio asc";


		$respListPrecios=mysqli_query($enlaceCon,$sqlListPrecios);
		echo" <table border='0'>";
		while($datListPrecios=mysqli_fetch_array($respListPrecios)){
			$nombreGrupoPrecio=$datListPrecios['nombre'];
			$abrevGrupoPrecio=$datListPrecios['abreviatura'];
			$precio=$datListPrecios['precio'];

			echo "<tr><td>".redondear2($precio)."</td><td>".$abrevGrupoPrecio."</td></tr>";

		}
		echo" </table>";



		echo" </td>";
		echo "<td align='center'>$fechaCreacion</td>
		<td align='center'><img src='imagenesprod/$imagen' width='$tamanioImagen'><br><a href='reemplazarImagen.php?codigo=$codigo&nombre=$nombreProd'><img src='imagenes/change.png' width='20' title='Reemplazar Imagen'></a></td>
		<td><a href='listaPrecios.php?codigo=$codigo&nombre=$nombreProd'>
		<img src='imagenes/fijarPrecio.jpg' width='30' title='Fijar Precio'></a></td>
		<td>$nombre_estado</td>
	
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
			<td><select name='grupo' id="grupo" class="textomedianorojo" style="width:300px">
			<?php
			$sqlGrupo="select g.codigo, g.nombre from grupos g
			where estado=1 and cod_tipo=".$tipo." order by 2;";
			$respGrupo=mysqli_query($enlaceCon,$sqlGrupo);
			echo "<option value='0'>TODOS</option>";
			while($datGrupo=mysqli_fetch_array($respGrupo)){
				$codGrupoB=$datGrupo[0];
				$nombreGrupoB=$datGrupo[1];
				if($codGrupoB==$grupo){
				  echo "<option value=$codGrupoB selected>$nombreGrupoB</option>";	
				}else{
					echo "<option value=$codGrupoB>$nombreGrupoB</option>";
				}
			}
			?>
			</select>
			</td>
			<td>
				<select name='marca' id="marca" class="textomedianorojo" style="width:300px">
			<?php
			$sqlMarcas="select g.codigo, g.nombre from marcas g
			where g.estado=1 order by g.nombre;";
			$respMarcas=mysqli_query($enlaceCon,$sqlMarcas);
			echo "<option value='0'>TODOS</option>";
			while($datMarca=mysqli_fetch_array($respMarcas)){
				$codMarcaB=$datMarca[0];
				$nombreMarcaB=$datMarca[1];
				if($codMarcaB==$marca){
				  echo "<option value=$codMarcaB selected>$nombreMarcaB</option>";	
				}else{
					echo "<option value=$codMarcaB>$nombreMarcaB</option>";
				}
				
			}
			?>
			</select>
			</td>
			</tr>			
			<tr><th colspan="2">Nombre Producto</th></tr>
			<tr>
			<td colspan="2">
				<input type='text' style="width:100%" name='material' id="material" class="textomedianorojo"  onkeypress="return pressEnter(event, this.form);" value="<?=$material?>">
			</td>
			</tr>
			<tr><th colspan="2">Codigo de Barras</th></tr>
			<tr>
			<td colspan="2" style="text-align:center;">
				<div class="codigo-barras div-center">
               <input type="text" class="form-codigo-barras" name="barra"id="barra" placeholder="Ingrese el codigo de barras." autofocus autocomplete="off">
         </div>
			</td>
			</tr>
		</table>
		<div class="div-center">
             <input type='button' class='boton-verde' value='Buscar Producto' id="btnBusqueda" onClick="javascript:cambiar_vista(this.form);">
		</div>
	
	</div>
</div>


</form>