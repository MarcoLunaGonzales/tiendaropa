<?php

require("conexionmysqli.php");
require("estilos.inc");

$fecha=$exafinicial;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
//verifica que no exista repeticion de datos en nuestra estructura
$sql_pre="select codigo_funcionario from funcionarios order by codigo_funcionario desc";
$resp_pre=mysqli_query($enlaceCon,$sql_pre);
$num_filas=mysqli_num_rows($resp_pre);
if($num_filas==0)
{	$codigo_funcionario=1000;
}
else
{	$dat_pre=mysqli_fetch_array($resp_pre);
	$codigo_funcionario=$dat_pre[0];
	$codigo_funcionario++;
}
//estado=1 es activo, 0 es retirado
$sql="insert into funcionarios values($codigo_funcionario,'".$_GET['cargo']."','".$_GET['paterno']."','".$_GET['materno']."','".$_GET['nombres']."',
'$fecha_real','".$_GET['direccion']."','".$_GET['telefono']."','".$_GET['celular']."','".$_GET['email']."','".$_GET['agencia']."',1,'".$_GET['tipoFuncionario']."')";
$resp=mysqli_query($enlaceCon,$sql);
echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='navegador_funcionarios.php?cod_ciudad=$agencia';
		</script>
	";
?>