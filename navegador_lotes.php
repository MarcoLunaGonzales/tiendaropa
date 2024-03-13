<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_lote.php';
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
					location.href='eliminar_lote.php?datos='+datos+'';
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
					location.href='editar_lote.php?cod_lote='+j_ciclo+'';
				}
			}
		}
    function cambiar_vista(f)
		{
			var estado;

			estado=f.estado.value;
			
		
			location.href='navegador_lotes.php?estado='+estado;
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
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<?php

	$estado=$_GET['estado'];
	$globalAgencia=$_COOKIE['global_agencia'];

    

	echo "<h3 align='center'>Listado de Lotes</h3>";

	echo "<form method='post' action=''>";
	
	$sql="select lp.cod_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,lp.codigo_material,
mp.descripcion_material, lp.cant_lote,
lp.cod_estado_lote, el.nombre_estado  as nombre_estado_lote,
lp.created_by,lp.created_date,
concat(f.paterno,' ',f.materno,' ',f.nombres) nombre_registro,
 lp.fecha_inicio_lote,lp.fecha_fin_lote 
from lotes_produccion lp 

left join estados_lote el on(lp.cod_estado_lote=el.cod_estado)
left join material_apoyo mp on(lp.codigo_material=mp.codigo_material)
left join funcionarios f on(lp.created_by=f.codigo_funcionario)
where lp.cod_lote<>0 ";
if($estado<>''){
 $sql=$sql." and lp.cod_estado_lote='".$estado."'";

}

$sql=$sql." order by  lp.nro_lote desc";	
 //echo $sql;

    
	

	
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Lotes:
	<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql2="select el.cod_estado, el.nombre_estado from estados_lote el order by cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		echo"	<option value='' selected>TODOS</option>";
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

	
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>		
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div> <br> <br>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nro Lote</th><th>Nombre Lote</th>
		<th>Obs</th><th>Producto</th><th>Cant</th><th>Estado</th>
		<th>Fecha Registro</th>
		<th>Fecha Inicio</th>
		<th>Fecha Final</th>
		<th>Procesos<br/>Construccion</th>
		<th>&nbsp;</th>
		
	
		</tr>";
	
	$indice_tabla=1;
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
		$nombre_registro=$dat['nombre_registro'];
		$created_date=$dat['created_date'];
		$fecha_registro= explode(' ',$created_date);
		$fecha_reg=$fecha_registro[0];
    $fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";

	
		$fecha_inicio_lote=$dat['fecha_inicio_lote'];
		$fecha_fin_lote=$dat['fecha_fin_lote'];

		

		echo "<tr>
		<td align='center'>";
		if($cod_estado_lote==1){
			echo "<input type='checkbox' name='codigo' id='codigo' value='$cod_lote'>";
		}
		echo "</td>

		<td>$nro_lote</td>
		<td>$nombre_lote</td>
			<td>$obs_lote</td>
		<td>$descripcion_material</td>
		<td>$cant_lote</td>
		<td>$nombre_estado_lote</td>
		<td align='center'>$nombre_registro<br/>$fecha_reg_mostrar</td>
		<td align='center'>$fecha_inicio_lote</td>
		<td align='center'>$fecha_fin_lote</td>	
		<td align='center'><a href='registroProcesosConstLote.php?codLote=$cod_lote'>
						Incluir Procesos</a></td>";
		echo"<td>";

		if($cod_estado_lote<>4){
			if($cod_estado_lote==1){
				echo"<a href='iniciar_loteproduccion.php?codLote=$cod_lote'>
						<img src='imagenes/iniciar.png' width='35'></a>
						<br/>Iniciar<br/>Produccion";
			}
	
			if($cod_estado_lote==2){
				echo"<a href='finalizar_loteproduccion.php?codLote=$cod_lote'>
						<img src='imagenes/cerrar.png' width='35'></a><br/>Finalizar<br/>Produccion";
			}
	}
		echo" </td>";
	

		echo" </tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div>";

?>

</form>