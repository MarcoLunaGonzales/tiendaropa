<?php
	require_once("../conexionmysqli.php");
	require_once("../estilos2.inc");
	require_once("configModule.php");


echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='$urlRegister';
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
			{	alert('Debe seleccionar al menos un registro para eliminar.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='$urlDelete?datos='+datos+'';
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
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar.');
				}
				else
				{
					location.href='$urlEdit?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	
	
	echo "<form method='post' action=''>";
	$sql="select codigo, nombre, abreviatura, estado from $table where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Lista de $moduleNamePlural</h1>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div> <br> <br>";
	
	
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>&nbsp;</th>
	<th>Nombre</th>
	<th>Abreviatura</th>
	<th>Sub-Grupos</th>
	</tr>";
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		
		echo "<tr>
		<td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombre</td>
		<td>$abreviatura</td>
		<td><a href='listDetalle.php?codigo=$codigo'>Ir a SubGrupos</a>";
			
		$sqlSubGrupo="SELECT codigo,nombre, abreviatura FROM `subgrupos` where estado=1 and cod_grupo=".$codigo." order by nombre asc ";
		$respSubGrupo=mysqli_query($enlaceCon,$sqlSubGrupo);
		echo "<table style='border-spacing: 0;'>";
		while($datSubGrupo=mysqli_fetch_array($respSubGrupo)){
			$codigoSub=$datSubGrupo[0];
			$nombreSub=$datSubGrupo[1];
			echo "<tr><td>$codigoSub</td><td>$nombreSub</td></tr>";
		}
		echo "</table>";
		
		
		echo"</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>