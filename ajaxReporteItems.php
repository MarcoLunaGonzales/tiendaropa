<?php
require("conexion.inc");
$codGrupo=$_GET['codGrupo'];

	echo "<select name='rpt_item' class='texto'>";
	
	$sql_item="select codigo_material, concat(descripcion_material,'-',color,' ',talla) from material_apoyo where cod_grupo='$codGrupo' and codigo_material<>0 order by descripcion_material";
	
	$resp=mysql_query($sql_item);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_item=$dat[0];
		if($tipo_item==1)
		{	$nombre_item="$dat[1] $dat[2]";
		}
		else
		{	$nombre_item=$dat[1];
		}
		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select>";

?>
