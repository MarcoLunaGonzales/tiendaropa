<?php
require("conexionmysqli.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
		
function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
		
function funOk(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmConfirmarCodigoIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introducir el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function funModif(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmModificarIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Modificar Tipo de Ingreso y Proveedor",inf1,function(){
            var cad1=$("select[id=combotipoingreso]").val();
            var cad2=$("select[id=comboproveedor]").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
				//alert ("combotipoingreso="+cad1+"&comboproveedor="+cad2+"&codigo="+codReg);
                $.get("programas/ingresos/guardarModifTipoProveIngreso.php","combotipoingreso="+cad1+"&comboproveedor="+cad2+"&codigo="+codReg, function(inf2) {
                    dlgEsp.setVisible(false);
                    funOkConfirm();
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Debe seleccionar datos.</div>",function(){},function(){});
            }
        },function(){});
    });
}


function ajaxBuscarIngresos(f){
	
	var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
	
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	
	notaIngreso=document.getElementById("notaIngreso").value;
	
	global_almacen=document.getElementById("global_almacen").value;
	
	provBusqueda=document.getElementById("provBusqueda").value;
	tipo=document.getElementById("tipo").value;
	estado=document.getElementById("estado").value;
	
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	

	ajax.open("GET", "ajaxNavIngresos.php?tipo="+tipo+"&estado="+estado+"&fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&notaIngreso="+notaIngreso+"&global_almacen="+global_almacen+"&provBusqueda="+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function enviar_nav(f)
{   
			var estado,tipo;
			estado=f.estado.value;
			tipo=f.tipo.value;
			location.href='registrar_ingresomateriales.php?tipo='+tipo+'&estado='+estado;
}

function editarIngresoTipoProv(codigoIngreso)
{   funModif(codigoIngreso,function(){
		alert("Se modicaron los Datos!");
		location.href='navegador_ingresomateriales.php';
	});
}

function editar_ingreso(f)
{   

	var estado,tipo;
			estado=f.estado.value;
			tipo=f.tipo.value;
			

	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {      //location.href='editar_ingresomateriales.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                funOk(j_cod_registro,function(){
                	//location.href='registrar_ingresomateriales.php?tipo='+tipo+'&estado='+estado;
                    location.href='editar_ingreso.php?codIngreso='+j_cod_registro+'&tipo='+tipo+'&estado='+estado;
                });
        }
    }
}
function anular_ingreso(f)
{   

var estado,tipo;
			estado=f.estado.value;
			tipo=f.tipo.value;
	var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){

                    location.href='anular_ingreso.php?codigo_registro='+j_cod_registro+'&tipo='+tipo+'&estado='+estado;
                });
        }
    }
}

function cambiar_vista(f)
		{
			var estado,tipo;

			estado=f.estado.value;
			tipo=f.tipo.value;

			
		
			location.href='navegador_ingresomateriales.php?estado='+estado+'&tipo='+tipo;
		}
        </script>
    </head>
    <body>

<?php
  $global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$estado=$_GET['estado'];
$tipo=$_GET['tipo'];
//echo "tipo=".$tipo."<br/> estado=".$estado;

echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
echo "<input type='hidden' name='global_almacen' id='global_almacen' value='$global_almacen'>";
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";



echo "<h1>Ingreso de Productos</h1>";

echo "<table align='center' class='texto'><tr><th>Ver Ingresos:
	<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql2="select es.cod_estado, es.nombre_estado from estados es order by es.cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		echo"	<option value='-1' selected>TODOS</option>";
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
echo "<center><table border='0' cellspacing='0' class='textomini'>
<tr><td bgcolor='#ff8080' width='10%'></td><th>INGRESOS ANULADOS</th></tr>
<tr><td bgcolor='#ffff99' width='10%'></td><th>INGRESOS CON MOVIMIENTO</th></tr>
<tr><td bgcolor='' width='10%'></td><th>INGRESOS SIN MOVIMIENTO</th></tr></table></center>";

echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";

echo "<br><div id='divCuerpo'>";
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Factura o Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th>
<th>Registro</th>
<th>Ult. Edicion</th>
<th>&nbsp;</th><th>&nbsp;</th><th>Estado</th><th>Nro PreIngreso</th></tr>";
$consulta = "SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso,
 i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,p.nombre_proveedor,
	i.nro_factura_proveedor, i.created_by,i.created_date, i.modified_by,
	 i.modified_date,es.nombre_estado  
	FROM ingreso_almacenes i
	left join tipos_ingreso ti  on (i.cod_tipoingreso=ti.cod_tipoingreso )
	left join proveedores p  on (i.cod_proveedor=p.cod_proveedor)	 
	left join estados es on (i.ingreso_anulado=es.cod_estado)
	WHERE i.cod_tipo=".$tipo."";
if($estado<>'-1'){
 $consulta =$consulta." and i.ingreso_anulado='".$estado."'";

}
$consulta =$consulta." AND i.cod_almacen='".$global_almacen."' order by i.nro_correlativo DESC limit 0, 70";

$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];
	$nroFacturaProveedor=$dat[9];
	
		$created_by=$dat[10];
	$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
	$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
	$usuReg =" ";
	while($datRegUsu=mysqli_fetch_array($respRegUsu)){
		$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
	}
	$created_date=$dat[11];
	$modified_by=$dat[12];
	$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
	$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
	$usuMod ="";
	while($datModUsu=mysqli_fetch_array($respModUsu)){
		$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
	}
	$modified_date=$dat[13];
	$nombre_estado=$dat['nombre_estado'];
	
	$sqlAux=" select IFNULL(codigo_ingreso,0),nro_correlativo from  preingreso_almacenes  where codigo_ingreso=$codigo";
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);


    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select * from salida_almacenes s, salida_detalle_almacenes sd, ingreso_almacenes i
		where s.cod_salida_almacenes=sd.cod_salida_almacen  and sd.cod_ingreso_almacen=i.cod_ingreso_almacen and s.salida_anulada=0 and i.cod_ingreso_almacen='$codigo'";
	//echo $sql_verifica_movimiento;
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento > 0) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($anulado == 2) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if ($num_filas_movimiento == 0 and $anulado == 1) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>$nroFacturaProveedor</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td>
	<td>&nbsp;$usuReg<br>$created_date</td>";
	if(empty($usuMod)){
	echo "<td>&nbsp</td>";
	}else{
		echo "<td>&nbsp;$usuMod<br>$modified_date</td>";
    }				
	 
	 echo "	<td align='center'>
		<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/detalles.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
	</td>
	<td align='center'>
		<a href='#' onclick='javascript:editarIngresoTipoProv($codigo)' > 
			<img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Proveedor'>
		</a>
		<td>".$nombre_estado."</td>";
	if($datAux[0]>0){
		echo"<td align='center'>$datAux[1]</td>";
	}else{
		echo"<td align='center'></td>";
	}

	echo"</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()this.form'>
<input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'>
<input type='button' value='Anular Ingreso' name='adicionar' class='boton2' onclick='anular_ingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ingresos</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Factura o Nota de Ingreso</td>
				<td>
				<input type='text' name='notaIngreso' id="notaIngreso" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Proveedor:</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlProv="select cod_proveedor, nombre_proveedor from proveedores where cod_tipo in($tipo,0) order by 2";
						$respProv=mysqli_query($enlaceCon,$sqlProv);
						while($datProv=mysqli_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarIngresos(this.form)">
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenBuscar();">
			
		</center>
	</div>
</div>

        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
    </body>
</html>
