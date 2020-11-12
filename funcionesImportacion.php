<?php

require('conexion.inc');


function insertaMarca($marca){
	$sql="select count(*), codigo from marcas where nombre='$marca' ";
	$resp=mysql_query($sql);
	$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into marcas (nombre, abreviatura, estado) values ('$marca','$marca','1')";
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta);
		$codigoDevolver=mysql_insert_id();
	}
	return ($codigoDevolver);
}

function insertaGrupo($grupo){
	if($grupo=="CONJUNTO"){$grupo="CONJUNTOS";}
	if($grupo=="MEDIA"){$grupo="MEDIAS";}
	if($grupo=="CALZA"){$grupo="CALZAS";}
	if($grupo=="CALZONCILLO"){$grupo="CALZONCILLO";}
	
	
	$sql="select count(*), codigo from grupos where nombre='$grupo' ";
	$resp=mysql_query($sql);
	$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into grupos (nombre, abreviatura, estado) values ('$grupo','$grupo','1')";
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta);
		$codigoDevolver=mysql_insert_id();
	}
	return ($codigoDevolver);
}

function insertaSubGrupo($codigoGrupo, $subgrupo){
	$sql="select count(*), codigo from subgrupos where nombre='$subgrupo' and cod_grupo='$codigoGrupo'";
	$resp=mysql_query($sql);
	$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into subgrupos (nombre, abreviatura, estado, cod_grupo) values ('$subgrupo','$subgrupo','1','$codigoGrupo')";
		$respInserta=mysql_query($sqlInserta);
		$codigoDevolver=mysql_insert_id();
	}
	return ($codigoDevolver);
}

function devuelveIdGrupo($idSubGrupo){
	$sql="select count(*), s.cod_grupo from subgrupos s where s.codigo='$idSubGrupo'";
	$resp=mysql_query($sql);
	$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$codigoDevolver=mysql_result($resp,0,1);
	}
	return($codigoDevolver);
}

function devuelveIdProducto($barCode, $nombreItem, $codMarca, $codSubGrupo, $color, $talla, $descripcionItem, $precioItem){
	$sql="select count(*), m.codigo_material from material_apoyo m where m.codigo_barras='$barCode'";
	$resp=mysql_query($sql);
	$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$codGrupo=devuelveIdGrupo($codSubGrupo);
		$codigoDevolver=crearProducto(0, $barCode, $nombreItem, $codMarca, $codGrupo, $codSubGrupo, $talla, $color, $descripcionItem, 0, $precioItem);
	}
	return ($codigoDevolver);
}

function crearProducto($idNuevo, $barCode, $nombreItem, $codMarca, $codGrupo, $codSubGrupo, $tallaItem, $colorItem, $descripcionItem, $idAnterior, $precioItem){
	$estadoItem=1;
	$lineaProveedorItem=1;
	$codTipoMaterial=1;
	$cantidadPresentacion=1;
	$codUnidad=1;
	
	if($idNuevo==0){
		$sqlMax="select max(codigo_material)+1 from material_apoyo";
		$respMax=mysql_query($sqlMax);
		$idNuevo=mysql_result($respMax,0,0);
	}
	
	if($idAnterior==0){
		$sqlMax="select max(codigo_anterior)+1 from material_apoyo";
		$respMax=mysql_query($sqlMax);
		$idAnterior=mysql_result($respMax,0,0);
	}
	
	$sqlInsertItem="insert into material_apoyo (codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial, cantidad_presentacion, observaciones, imagen, cod_unidad, cod_subgrupo, cod_marca, codigo_barras, talla, color, codigo_anterior) values 
	('$idNuevo','$nombreItem','$estadoItem','$lineaProveedorItem','$codGrupo','$codTipoMaterial','$cantidadPresentacion','','','$codUnidad','$codSubGrupo','$codMarca','$barCode','$tallaItem','$colorItem','$idAnterior')";
	$respInsertItem=mysql_query($sqlInsertItem);
	$codigoDevolver=mysql_insert_id();
	
	return($codigoDevolver);
	
}

?>