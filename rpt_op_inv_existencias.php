<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha, rpt_ordenar, rpt_grupo, rpt_formato;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			rpt_ver=f.rpt_ver.value;
			rpt_fecha=f.rpt_fecha.value;
			rpt_ordenar=f.rpt_ordenar.value;
			
			var rpt_grupo=new Array();	
			
			var rpt_formato=f.rpt_formato.value;
			
			var j=0;
			for(i=0;i<=f.rpt_grupo.options.length-1;i++)
			{	if(f.rpt_grupo.options[i].selected)
				{	rpt_grupo[j]=f.rpt_grupo.options[i].value;
					j++;
				}
			}			window.open('rpt_inv_existencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_ver='+rpt_ver+'&rpt_fecha='+rpt_fecha+'&rpt_ordenar='+rpt_ordenar+'&rpt_grupo='+rpt_grupo+'&rpt_formato='+rpt_formato,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');

			return(true);
		}

		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");
	require("estilos_almacenes.inc");
$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Existencias Almacen</h1>";

echo"<form method='post' action=''>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysql_query($sql);
	echo "<option value='0'>Todos</option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='10' multiple>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto'>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</tr>";
	$fecha_rptdefault=date("d/m/Y");
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='text' value='$fecha_rptdefault' id='rpt_fecha' name='rpt_fecha'>
			</TD>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar Por:</th>";
	echo "<td><select name='rpt_ordenar' class='texto'>";
	echo "<option value='1'>Producto</option>";
	echo "<option value='2'>Grupo y Producto</option>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Formato:</th>";
	echo "<td><select name='rpt_formato' class='texto'>";
	echo "<option value='1'>Normal</option>";
	echo "<option value='2'>Para Inventario</option>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>