<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$rpt_territorio=$_GET['rpt_territorio'];
$rpt_marca=$_GET['rpt_marca'];
$rpt_modelo=$_GET['rpt_modelo'];
$rpt_grupo=$_GET['rpt_grupo'];
$rpt_subgrupo=$_GET['rpt_subgrupo'];
$rpt_genero=$_GET['rpt_genero'];
$rpt_talla=$_GET['rpt_talla'];
$rpt_material=$_GET['rpt_material'];
$rpt_color=$_GET['rpt_color'];


echo "rpt_territorio=".$rpt_territorio."<br/>";
echo "rpt_marca=".$rpt_marca."<br/>";
echo "rpt_modelo=".$rpt_modelo."<br/>";
echo "rpt_grupo=".$rpt_grupo."<br/>";
echo "rpt_subgrupo=".$rpt_subgrupo."<br/>";
echo "rpt_genero=".$rpt_genero."<br/>";
echo "rpt_talla=".$rpt_talla."<br/>";
echo "rpt_material=".$rpt_material."<br/>";
echo "rpt_color=".$rpt_color."<br/>";

$sqlSucursal="select descripcion from ciudades where cod_ciudad=".$rpt_territorio;
$respSucursal=mysqli_query($enlaceCon,$sqlSucursal);		
while($datSucursal=mysqli_fetch_array($respSucursal)){
	$sucursal=$datSucursal['descripcion'];
}

$sqlMarca="select nombre from marcas where codigo=".$rpt_marca;
$respMarca=mysqli_query($enlaceCon,$sqlMarca);		
while($datMarca=mysqli_fetch_array($respMarca)){
	$marca=$datMarca['nombre'];
}

echo "<form  action='actualizaPreciosMaterial.php' method='post' name='form1'>";

echo "<h1>Actualizacion de Precios Sucursal $sucursal <br/> $marca</h1>";




echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Codigo</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Material</th></tr>";


$sql="select mp.codigo_material,mp.descripcion_material,mp.estado,mp.cod_grupo, g.nombre as nombreGrupo,
mp.cod_tipomaterial,mp.cantidad_presentacion,mp.observaciones,mp.imagen,
mp.cod_unidad, um.nombre as nombreUnidad,mp.peso, mp.cod_subgrupo,
sg.nombre as nombreSubgrupo, 
mp.cod_marca, mar.nombre as nombreMarca, mp.talla,tal.nombre as nombreTalla, mp.color, col.nombre as nombreColor,
mp.codigo_anterior,
mp.codigo2,mp.fecha_creacion,mp.cod_modelo, mo.nombre as nombreModelo,
mp.cod_material, ma.nombre as nombreMaterial,
mp.cod_genero, ge.nombre as nombreGenero
from material_apoyo mp
left join subgrupos sg on (mp.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
left join unidades_medida um on (mp.cod_unidad=um.codigo)
left join marcas mar on (mp.cod_marca=mar.codigo)
left join tallas tal on (mp.talla=tal.codigo)
left join colores col on (mp.color=col.codigo)
left join modelos mo on (mp.cod_modelo=mo.codigo)
left join materiales ma on (mp.cod_material=ma.codigo)
left join generos ge on (mp.cod_genero=ge.codigo)
where mp.estado=1";
// Filtro Grupo
if($rpt_grupo!="-1"){
	$sql.=" and sg.cod_grupo in(".$rpt_grupo.")";
}
// Fin Filtro Grupo
// Filtro SubGrupo
if($rpt_subgrupo!="-1"){
	$sql.=" and sg.codigo in(".$rpt_subgrupo.")";
}
// Fin Filtro SubGrupo


$sql.=" order by mo.nombre asc ,  sg.nombre asc, ge.nombre asc, col.nombre asc,mp.codigo_material asc";

echo $sql."<br/>";
$resp=mysqli_query($enlaceCon,$sql);
		
while($dat=mysqli_fetch_array($resp)){

	$codigo_material=$dat['codigo_material'];
	$descripcion_material=$dat['descripcion_material'];
	$estado=$dat['estado'];
	$cod_grupo=$dat['cod_grupo'];
	$nombreGrupo=$dat['nombreGrupo'];
	$cod_tipomaterial=$dat['cod_tipomaterial'];
	$cantidad_presentacion=$dat['cantidad_presentacion'];
	$observaciones=$dat['observaciones'];
	$imagen=$dat['imagen'];
	$cod_unidad=$dat['cod_unidad'];
	$nombreUnidad=$dat['nombreUnidad'];
	$peso=$dat['peso'];
	$cod_subgrupo=$dat['cod_subgrupo'];
	$nombreSubgrupo=$dat['nombreSubgrupo'];
	$cod_marca=$dat['cod_marca'];
	$nombreMarca=$dat['nombreMarca'];
	$talla=$dat['talla'];
	$nombreTalla=$dat['nombreTalla'];
	$color=$dat['color'];
	$nombreColor=$dat['nombreColor'];
	$codigo_anterior=$dat['codigo_anterior'];
	$codigo2=$dat['codigo2'];
	$fecha_creacion=$dat['fecha_creacion'];
	$cod_modelo=$dat['cod_modelo'];
	$nombreModelo=$dat['nombreModelo'];
	$cod_material=$dat['cod_material'];
	$nombreMaterial=$dat['nombreMaterial'];
	$cod_genero=$dat['cod_genero'];
 	$nombreGenero=$dat['nombreGenero'];

 	echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>".$codigo_material."</td>";
					
					echo "<td>".$nombreModelo."</td>";
					echo "<td>".$nombreGrupo."</td>";
					echo "<td>".$nombreSubgrupo."</td>";
					echo "<td>".$nombreMaterial."</td>";
					echo "<td>".$nombreGenero."</td>";		
					echo "<td>".$nombreColor."</td>";		
					echo "<td>".$nombreTalla."</td>";
					echo "<td>".$descripcion_material."</td>";
					
					echo "</tr>";

}

		
		
	
echo"</table></center>";


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";

?>
