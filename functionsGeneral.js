function cambiarDatosProductosTable(valor){
	$("#mensaje_input_codigo_barras").html("");
  var parametros={"codigo":valor};
      $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxSetProductoCodigoBarras.php",
        data: parametros,
        success:  function (respuesta) {
        	var resp=respuesta.split('#####');
        	if(resp[0].trim()=="0"){
               $("#mensaje_input_codigo_barras").html("No se encontró el código de barras: "+ valor);
               $("#input_codigo_barras").val("");
        	}else{
        		var existeCodigo=0;var filaEncontrado=0;
        		var numRegistro=$('input[name=materialActivo]').val();
	            for (var i = 1; i <= numRegistro; i++) {
	            	if($("#material"+i).length>0){
	            		if($("#material"+i).val()==resp[1]){
                           existeCodigo++; 
                           filaEncontrado=i;
	            		}
	            	}else{
	            	  if($("#materiales"+i).length>0){
	            		if($("#materiales"+i).val()==resp[1]){
                           existeCodigo++; 
                           filaEncontrado=i;
	            		}
	            	 }	
	            	}
	            };
	            if(existeCodigo==0){
	              if($("#ventas_codigo").length>0){
                    soloMasVentas(resp);	
	              }else{
	                soloMas(resp);		
	              }	
	              $("#mensaje_input_codigo_barras").html("Encontrado "+resp[2]+", código de barras: "+ valor);	 
	            }else{
	            	var cantidadAnterior=parseInt($("#cantidad_unitaria"+filaEncontrado).val());
	            	if($("#cantidad_unitaria"+filaEncontrado).val()==""){
                        cantidadAnterior=1; 
	            	}
	            	$("#cantidad_unitaria"+filaEncontrado).val(cantidadAnterior+1);
	            	if($("#ventas_codigo").length>0){
                      calculaMontoMaterial(filaEncontrado);
	            	}else{
	            	  cambiaCosto(document.getElementsByName('form1'),filaEncontrado);	
	            	}
	            	
	            	$("#mensaje_input_codigo_barras").html(resp[2]+" + 1 :"+ valor);	 
	            }
               $("#input_codigo_barras").val("");      
        	}
        }
      });   
 }		

function soloMasVentas(obj){
	if(num>=15){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);

		if(banderaItems0==0){
			num++;
			$('input[name=materialActivo]').val(num);
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					setMaterialesSoloVentas(obj[1],obj[2]);
				}
			}		
			ajax.send(null);
		}

	}
}
function soloMas(obj) {
	    	num++;
	    	$('input[name=materialActivo]').val(num);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterial.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					setMaterialesSolo(obj[1],obj[2],obj[3],obj[4]);
				}
			}		
			ajax.send(null);
		
	}	

function setMaterialesSolo(cod, nombreMat, cantidadPresentacion,costoItem){	
	var numRegistro=$('input[name=materialActivo]').val();
	console.log(numRegistro);
	$('#material'+numRegistro).val(cod);
	$('#cod_material'+numRegistro).html(nombreMat);
	$('#ultimoCosto'+numRegistro).val(costoItem);
	$('#divUltimoCosto'+numRegistro).html("["+costoItem+"]");
	$("#input_codigo_barras").focus();	
}

function setMaterialesSoloVentas(cod, nombreMat){
	var numRegistro=$('input[name=materialActivo]').val();
	console.log("fila:"+numRegistro);
	$('#materiales'+numRegistro).val(cod);
	$('#cod_material'+numRegistro).html(nombreMat);
	$("#input_codigo_barras").focus();
	actStock(numRegistro);
}

$(document).ready(function() {
	if($("#input_codigo_barras").length>0){
	 $("#input_codigo_barras").focus();
     $("#input_codigo_barras").after("<div id='mensaje_input_codigo_barras' class='mensaje-codigo-barras'></div>");
     $("#input_codigo_barras").keypress(function(e) {
	 if(e.which == 13) {  	
       	var valorInput=$(this).val();
        cambiarDatosProductosTable(valorInput); 
        return false;
       }
     });
	}	
});

