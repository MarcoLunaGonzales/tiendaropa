<?php
require("conexionmysqli.php");
require("estilos.inc");

//recogemos variables
$rpt_territorio=$_POST['rpt_territorio'];
$rpt_marca=$_POST['rpt_marca'];
$rpt_modelo=$_POST['rpt_modelo'];
$rpt_grupo=$_POST['rpt_grupo'];
$rpt_subgrupo=$_POST['rpt_subgrupo'];
$rpt_genero=$_POST['rpt_genero'];
$rpt_talla=$_POST['rpt_talla'];
$rpt_material=$_POST['rpt_material'];
$rpt_color=$_POST['rpt_color'];





/*if($respUpd){
		echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{*/
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='listaPrecios.php?codigo=".$codProducto."&nombre=".$nombreProducto."';
			
			</script>";
//}
	

?>