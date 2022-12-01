<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
echo "<script language='JavaScript'>
		function pressEnter(e, f){
			tecla = (document.all) ? e.keyCode : e.which;
			if (tecla==13){
				//document.getElementById('itemNombreMaterial').focus();
				//listaMateriales(f);
				return false;
			}
		}

		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha, rpt_ordenar, rpt_grupo, rpt_formato, rpt_barcode;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			rpt_ver=f.rpt_ver.value;
			rpt_fecha=f.rpt_fecha.value;
			rpt_ordenar=f.rpt_ordenar.value;
			rpt_barcode=f.barcode.value;
			
			var rpt_grupo=new Array();	
			var rpt_marca=new Array();	
			
			var rpt_formato=f.rpt_formato.value;
			
			var j=0;
			for(i=0;i<=f.rpt_grupo.options.length-1;i++)
			{	if(f.rpt_grupo.options[i].selected)
				{	rpt_grupo[j]=f.rpt_grupo.options[i].value;
					j++;
				}
			}	
			
			var k=0;
			for(m=0;m<=f.rpt_marca.options.length-1;m++)
			{	if(f.rpt_marca.options[m].selected)
				{	rpt_marca[k]=f.rpt_marca.options[m].value;
					k++;
				}
			}
			
			window.open('rpt_inv_existencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_ver='+rpt_ver+'&rpt_fecha='+rpt_fecha+'&rpt_ordenar='+rpt_ordenar+'&rpt_grupo='+rpt_grupo+'&rpt_marca='+rpt_marca+'&rpt_formato='+rpt_formato+'&rpt_barcode='+rpt_barcode,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}

		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";


$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_usuario=$_COOKIE['global_usuario'];

$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);


if($rpt_territorio==""){
	$rpt_territorio=$globalCiudad;
}
echo "<h1>Reporte Existencias Almacen</h1>";

echo"<form method='post' action=''>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value='0'>Todos</option>";
	while($dat=mysqli_fetch_array($resp))
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
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen || $codigo_almacen==$globalAlmacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='10' multiple>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Marcas</th><td><select name='rpt_marca' class='texto' size='10' multiple>";
	$sqlMarca="select codigo, nombre from marcas where estado=1";
		if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
			$sqlMarca= $sqlMarca." and codigo in( select codigo from proveedores_marcas where cod_proveedor in
			( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario))";
		}
	}
	$sqlMarca= $sqlMarca."  order by 2";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
	while($datMarca=mysqli_fetch_array($respMarca))
	{	$codigoMarca=$datMarca[0];
		$nombreMarca=$datMarca[1];
		echo "<option value='$codigoMarca' selected>$nombreMarca</option>";
	}
	echo "</select></td></tr>";
	
	
	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto'>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2' selected>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</tr>";

	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='text' value='$fecha_rptdefault' id='rpt_fecha' name='rpt_fecha'>
			</TD>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar Por:</th>";
	echo "<td><select name='rpt_ordenar' class='texto'>";
	echo "<option value='1'>Producto</option>";
	echo "<option value='2'>Grupo y Producto</option>";
	echo "<option value='3'>Marca Grupo y Producto</option>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Formato:</th>";
	echo "<td><select name='rpt_formato' class='texto'>";
	echo "<option value='1'>Normal</option>";
	echo "<option value='2'>Para Inventario</option>";
	echo "</tr>";
	
	
	echo "<tr><th align='left'>Buscar BarCode:</th>";
	echo "<td>";
	echo "<input type='text' class='form-codigo-barras' id='barcode' name='barcode' placeholder='Ingrese el código de barras.' onkeypress='return pressEnter(event, this.form);' >";
	echo "</td></tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>