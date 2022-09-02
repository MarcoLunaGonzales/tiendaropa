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
	
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNavIngresos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&notaIngreso="+notaIngreso+"&global_almacen="+global_almacen+"&provBusqueda="+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function registrar_recibo()
{   location.href='registrar_recibo.php';
}

function editarIngresoTipoProv(codigoIngreso)
{   funModif(codigoIngreso,function(){
		alert("Se modicaron los Datos!");
		location.href='navegador_ingresomateriales.php';
	});
}

function editar_recibo(f)
{   var i;
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
                    location.href='editar_ingreso.php?codIngreso='+j_cod_registro+'';
                });
        }
    }
}
function anular_recibo(f)
{   var i;
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
                    location.href='anular_ingreso.php?codigo_registro='+j_cod_registro+'';
                });
        }
    }
}
        </script>
    </head>
    <body>

<?php
  $global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_agencia=$_COOKIE['global_agencia'];
echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
echo "<input type='hidden' name='global_almacen' id='global_almacen' value='$global_almacen'>";

$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago
from recibos r inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
where r.cod_ciudad=".$global_agencia." order by r.id_recibo DESC,r.cod_ciudad desc";
//echo "consulta=".$consulta;
$resp = mysqli_query($enlaceCon,$consulta);
echo "<h1>RECIBOS</h1>";

echo "<table border='1' cellspacing='0' class='textomini'><tr><th>LEYENDA:</th><th>Recibos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>RECIBOS UTILIZADOS</th><td bgcolor='#ffff99' width='10%'></td><th>RECIBOS UTILIZADOS</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

echo "<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_recibo()'>
<input type='button' value='Editar' class='boton' onclick='editar_recibo(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_recibo(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";

echo "<br><div id='divCuerpo'>";
echo "<br><center><table class='texto'>";
echo "<tr>
<th>&nbsp;</th>
<th>Recibo</th>
<th>Fecha</th>
<th>Contacto</th>
<th>Nro de Contacto</th>
<th>Descripcion</th>
<th>Monto</th>
<th>Tipo Pago</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th></th>
</tr>";
while ($dat = mysqli_fetch_array($resp)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	////////////

?>	
   <tr>
	<td><input type="checkbox" name="id_recibo" value="$id_recibo"></td>
	<td><?=$id_recibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	<td><?=$nombre_recibo;?></td>
	<td><?=$cel_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$monto_recibo;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
	
	<td><a href="formatoRecibo.php?idRecibo=<?=$id_recibo;?>" target="_BLANK">Ver Recibo</a>
	</tr>
<?php	
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_recibo()'>
<input type='button' value='Editar' class='boton' onclick='editar_recibo(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_recibo(this.form)'>
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
				<td>Nota de Ingreso</td>
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
						$sqlProv="select cod_proveedor, nombre_proveedor from proveedores order by 2";
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