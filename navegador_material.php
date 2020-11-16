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
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
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
			location.href='navegador_material.php?vista='+modo_vista+'&vista_ordenar='+modo_orden+'&grupo='+grupo;
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
	if($vista_ordenar==0){
		$sql=$sql." order by 4,2";
	}
	if($vista_ordenar==1){
		$sql=$sql." order by 2";	
	}
	if($vista_ordenar==2){
		$sql=$sql." order by 6,2";	
	}
	
	
	//echo $sql;
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
		
	echo "</form>";
?>
