<?php

require("conexionmysqli.php");
$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];
$contrasena = str_replace("'", "''", $contrasena);

$sql = "
    SELECT f.cod_cargo, f.cod_ciudad,f.cod_tipofuncionario
    FROM funcionarios f, usuarios_sistema u
    WHERE u.codigo_funcionario=f.codigo_funcionario AND u.codigo_funcionario='$usuario' AND u.contrasena='$contrasena' ";
$resp = mysqli_query($enlaceCon,$sql);
$num_filas = mysqli_num_rows ($resp);
//$num_filas = i($resp);
if ($num_filas != 0) {
    $dat = mysqli_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];
	$cod_tipofuncionario= $dat[2];
	 echo $cod_tipofuncionario;

    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	
	//sacamos la gestion activa
	$sqlGestion="select cod_gestion, nombre_gestion from gestiones where estado=1";
	$respGestion=mysqli_query($enlaceCon,$sqlGestion);
	$datGestion = mysqli_fetch_array($respGestion);
	$globalGestion=$datGestion[0];
	$nombreG=$datGestion[1];


	
	//almacen
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad'";	
	$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
	$dat_almacen=mysqli_fetch_array($resp_almacen);
	$global_almacen=$dat_almacen[0];

	setcookie("global_almacen",$global_almacen);
	setcookie("globalGestion", $globalGestion);
	if($cod_tipofuncionario==1){
		if($cod_cargo==1000 || $cod_cargo==1001 || $cod_cargo==1002){
			header("location:indexAlmacenReg.php");
		}
		if($cod_cargo==1016 || $cod_cargo==1017){
			header("location:indexAlmacenVentas.php");
		}
	
		if($cod_cargo==1000||$cod_cargo==1001){
			setcookie("global_admin_cargo", 1);
		}else{
			setcookie("global_admin_cargo", 0);
		}
		
    }
	if($cod_tipofuncionario==2){
		   // Verificamos si el el Funcionario esta vinculados con algunos proveeedore
		$sqlFunProv="select cod_proveedor from funcionarios_proveedores where codigo_funcionario=".$usuario;
		
		$respFunProv=mysqli_query($enlaceCon,$sqlFunProv);
		$numProv=mysqli_num_rows($respFunProv);
		echo $numProv;
		if($numProv>0){
			header("location:indexProveedor.php");
		}else{
			 echo "<link href='stilos.css' rel='stylesheet' type='text/css'>
        <form action='problemas_ingreso.php' method='post' name='formulario'>
        <h1>Sus datos de acceso son de un Usuario Externo, que no tiene vinculado ningun Proveedor.</h1>
        </form>";
		}
	}
	
		
	

} else {
    echo "<link href='stilos.css' rel='stylesheet' type='text/css'>
        <form action='problemas_ingreso.php' method='post' name='formulario'>
        <h1>Sus datos de acceso no son correctos.</h1>
        </form>";
}
?>