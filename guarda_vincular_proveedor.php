<?php
require("conexionmysqli.php");
// require("estilos_administracion.inc");

	$vector=explode(",",$_POST['datos_prov']);

	$sql1="delete from funcionarios_proveedores where codigo_funcionario=".$_POST['codigo_funcionario']."";
	$resp1=mysqli_query($enlaceCon,$sql1);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++){
		$sql="insert into funcionarios_proveedores (codigo_funcionario,cod_proveedor)values(".$_POST['codigo_funcionario'].",".$vector[$i].")";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		
	}
		$cod_territorio=$_POST['cod_territorio'];
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_funcionarios.php?cod_ciudad=$cod_territorio';
			</script>";
			


	

?>