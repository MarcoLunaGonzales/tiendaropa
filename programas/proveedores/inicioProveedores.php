<html>
    <head>
        <title>Proveedores</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../lib/css/paneles.css"/>
        <link rel="stylesheet" type="text/css" href="../../stilos.css"/>
        <script type="text/javascript" src="../../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="../../lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
/*proceso inicial*/
$(document).ready(function() {
    //
    listadoProveedores();
    //
});
/*proceso inicial*/
function listadoProveedores() {
    cargarPnl("#pnl00","prgListaProveedores.php","");
}
//procesos
function frmAdicionar() {
	
    cargarPnl("#pnl00","frmProveedorAdicionar.php","");
}

function frmModificar() {
	
    var total=$("#idtotal").val();
	
    var tag,sel,cod,i,c=0;
	
    for(i=1;i<=total;i++) {
        tag=$("#idchk"+i);
        sel=tag.prop("checked");
		
       if(sel==true) {
            cod=tag.val(); 
			c++;
        }
    }

    if(c==1) {
        cargarPnl("#pnl00","frmProveedorEditar.php","codprov="+cod);
    } else {
		if(c>1) {
			alert("Seleccione solo un elemento para editar."+cod);
		} else {
			alert("Seleccione un elemento para editar.");        
		}
	}
}

function frmEliminar() {
    var total=$("#idtotal").val();
    var tag,sel,cods="0",c=0;
    for(var i=1;i<=total;i++) {
        tag=$("#idchk"+i);
        sel=tag.prop("checked");
        if(sel==true) {
            cods=cods+","+tag.val(); c++;
        }
    }
    if(c>0) {
        if(confirm("Esta seguro de eliminar "+c+" elemento(s) ?")) {
            eliminarProveedor(cods);
        }
    } else {
        alert("Seleccione para eliminar.");
    }
}
function adicionarProveedor() {
	 //alert("valor="+$("#nompro").val());
		if($("#nompro").val()!=""){
			if($("#tel1").val()!=""){
			var nompro = $("#nompro").val();
			var dir = $("#dir").val();
			var tel1 = $("#tel1").val();
			var tel2 = $("#tel2").val();
			var contacto = $("#contacto").val();
			var email = $("#email").val();
			var cod_ciu = $("#cod_ciu").val();
			var parms="nompro="+nompro+"&dir="+dir+"&tel1="+tel1+"&tel2="+tel2+"&contacto="+contacto+"&email="+email+"&cod_ciu="+cod_ciu+"";
			cargarPnl("#pnl00","prgProveedorAdicionar.php",parms);
		}else{
		  alert("El Campo Telefono 1 debe ser Llenado.");
		 return(false);
		}
		}else{
		  alert("El Campo Nombre debe ser Llenado.");
		 return(false);
		}
}
function modificarProveedor() {
    var codpro = $("#codpro").text();
	if($("#nompro").val()!=""){
		var nompro = $("#nompro").val();
		var dir = $("#dir").val();
		var tel1 = $("#tel1").val();
		var tel2 = $("#tel2").val();
		var contacto = $("#contacto").val();
		var email = $("#email").val();
			var cod_ciu = $("#cod_ciu").val();
		var parms="codpro="+codpro+"&nompro="+nompro+"&dir="+dir+"&tel1="+tel1+"&tel2="+tel2+"&contacto="+contacto+"&email="+email+"&cod_ciu="+cod_ciu+"";
		cargarPnl("#pnl00","prgProveedorModificar.php",parms);
	}else{
		  alert("El Campo Nombre debe ser Llenado.");
		 return(false);
	}
}
function eliminarProveedor(cods) {
    var codpro = cods;
    var parms="codpro="+codpro+"";
    cargarPnl("#pnl00","prgProveedorEliminar.php",parms);
}
        </script>
    </head>
    <body>
        <div id='pnl00'></div>
        <div id='pnldlgfrm'></div>
        <div id='pnldlggeneral'></div>
        <div id='pnldlgenespera'></div>
    </body>
</html>

<?php

?>
