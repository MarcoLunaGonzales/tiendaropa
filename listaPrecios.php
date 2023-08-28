<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];

echo "<form enctype='multipart/form-data' action='guarda_reemplazar_imagen.php' method='post' name='form1'>";

echo "<h1>Lista de Precios</h1><h2>$nombreProducto</h2>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Sucursal</th><th>&nbsp;</th><th>Precio</th><th>Cantidad Inicio</th><th>Cantidad Final</th>		
		</tr>";
		$sqlListPrecios="select p.codigo_material,p.cod_precio,gp.nombre ,gp.abreviatura ,p.precio,p.cod_ciudad,c.nombre_ciudad,c.descripcion,
		p.cant_inicio,p.cant_final, p.created_by, 
		concat(f.nombres,' ',f.paterno,' ',f.materno) as creado_por, p.created_date,
		p.modified_by, 
		concat(f1.nombres,' ',f1.paterno,' ',f1.materno) as modificado_por,
		p.modified_date
		from precios p
		left join grupos_precio gp on (p.cod_precio=gp.codigo)
		left join ciudades c on (p.cod_ciudad=c.cod_ciudad)
		left join funcionarios f on (p.created_by=f.codigo_funcionario)
		left join funcionarios f1 on (p.modified_by=f1.codigo_funcionario)
		where p.codigo_material='".$codigo."' order by c.nombre_ciudad asc,p.cod_precio asc";
		$respListPrecios=mysqli_query($enlaceCon,$sqlListPrecios);
		$indice_tabla=1;
		while($datListPrecios=mysqli_fetch_array($respListPrecios)){

			$cod_precio=$datListPrecios['cod_precio'];
			$nombre=$datListPrecios['nombre'];
			$abreviatura=$datListPrecios['abreviatura'];
			$precio=$datListPrecios['precio'];
			$cod_ciudad=$datListPrecios['cod_ciudad'];
			$nombre_ciudad=$datListPrecios['nombre_ciudad'];
			$descripcion_ciudad=$datListPrecios['descripcion'];
			$cant_inicio=$datListPrecios['cant_inicio'];
			$cant_final=$datListPrecios['cant_final'];


			echo "<tr><td>".$indice_tabla."</td><td>".$descripcion_ciudad."</td><td>".$nombre."</td><td>".redondear2($precio)."</td>";
			echo " <td>".$cant_inicio."</td><td>".$cant_final."</td></tr>";
		$indice_tabla++;

		}

echo"</table></center>";


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>
