<?php
require_once('lib/importar_excel/php-excel-reader/excel_reader2.php');
require_once('lib/importar_excel/SpreadsheetReader.php');
require("funcionesImportacion.php");


function borrarIngresoAlmacen($codigo){
  $consulta="DELETE FROM ingreso_ingreso_almacenes where cod_ingreso_almacen=$codigo";
  $sql_inserta = mysql_query($consulta);
  $consulta="DELETE FROM ingreso_detalle_almacenes where cod_ingreso_almacen=$codigo";
  $sql_inserta = mysql_query($consulta);
}

function verificarFecha($x) {
    if (date('Y-m-d', strtotime($x)) == $x) {
      return 1;
    }else{
      return 0;
     } 
}
function verificarHora($x) {
    if (date('H:m:s', strtotime($x)) == $x) {
      return true;
    } else {
      return false;
    }
}



$cod_estadoreferencial="1";   
$message="";
$index=0;
$totalFilasCorrectas=0;
$filasErroneas=0;
$filasErroneasCampos=0;
$filasErroneasFechas=0;
$filaArchivo=0;
$listaFilasFechas=[];
$listaFilasCampos=[];


$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
$sqlInserts=[];  $lista_documento=[];
  if(in_array($_FILES["documentos_excel"]["type"],$allowedFileType)){
        $targetPath = 'subidas/'.$_FILES['documentos_excel']['name'];
        move_uploaded_file($_FILES['documentos_excel']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);       
        $sheetCount = count($Reader->sheets());
        for($i=0;$i<$sheetCount;$i++){         
        $Reader->ChangeSheet($i);
        $validacionFila=1;
           foreach ($Reader as $Row){ 
             if ($filaArchivo>0){
                $index++;
    
                $marca=""; 
                $cod_subgrupo="";  
                $articulo="";  
                $descripcion="";   
                $color="";   
                $talla="";   
                $cantidad="";    
                $code="";    
                $barcode="";   
                $precioCosto="";   
                $precio="";    
                $field1="";    
                $material="";   
                $field2="";    
                $saldo="";  


                if(isset($Row[0])) {
                    $marca=$Row[0];
                }
                if(isset($Row[1])) {
                    $cod_subgrupo=$Row[1];
                }
                if(isset($Row[2])) {
                    $articulo=$Row[2];
                }
                if(isset($Row[3])) {
                    $descripcion=$Row[3];
                }
                if(isset($Row[4])) {
                    $color=$Row[4];
                }
                if(isset($Row[5])) {
                    $talla=$Row[5];
                }
                if(isset($Row[6])) {
                    $cantidad=$Row[6];
                }
                if(isset($Row[7])) {
                    $code=$Row[7];
                }
                if(isset($Row[8])) {
                    $barcode=$Row[8];
                }
                if(isset($Row[9])) {
                    $precioCosto=$Row[9];
                }
                if(isset($Row[10])) {
                    $precio=$Row[10];
                }
                if(isset($Row[11])) {
                    $field1=$Row[11];
                }
                if(isset($Row[12])) {
                    $material=$Row[12];
                }
                if(isset($Row[13])) {
                    $field2=$Row[13];
                }
                if(isset($Row[14])) {
                    $saldo=$Row[14];
                }
                
                $cod_material=devuelveIdProducto($barcode, $articulo, $marca, $cod_subgrupo, $color, $talla, $descripcion, $precioCosto);
                $fechaVencimiento='1900-01-01';
                $precioUnitario=$precioCosto;     
                $costo=$precioCosto;
                $lote=0;
      

                              
                if (!empty($cod_subgrupo) || !empty($marca)) {
                    if($cod_subgrupo=="" && $marca==""){

                    }else{
                      $lista_documento[$index]=$index;
                      $totalFilasCorrectas++; 
                	    $sql="INSERT INTO ingreso_detalle_almacenes (cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
                    	VALUES('$codigo','$cod_material','$cantidad','$cantidad','$lote','$fechaVencimiento','$precioUnitario','$precioUnitario','$costo','$costo','$costo','$costo')";
                    $sqlInserts[$index]=$sql;                      
                    }
                }else{
                  if($cod_subgrupo=="" && $marca==""){

                  }else{
                     $listaFilasCampos[$filasErroneasCampos]=$index;
                     $filasErroneasCampos++;
                     $filasErroneas++;
                  }  
                }
              } //fin de if  
                $filaArchivo++;
           }//fin foreach
        
         }//fin for

         //eliminarArchivo
         //unlink($targetPath);
  }
  else
  { 
        $type = "error";
        $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
  }
if($filasErroneas>0){
  $htmlInforme='';
  /*$htmlInforme='Errores sin formato: <b>'.$filasErroneasCampos.'</b> <a href="#colapseFormato" class="btn btn-default btn-sm" data-toggle="collapse">Ver más...</a>'.
  '<div id="colapseFormato" class="collapse small">'.
         'Filas:['.implode(",",$listaFilasCampos).']'.
       '</div>'.
  '<br>Errores de fecha: <b>'.$filasErroneasFechas.'</b><a href="#colapseFechas" class="btn btn-default btn-sm" data-toggle="collapse">Ver más...</a>'.
  '<div id="colapseFechas" class="collapse small">'.
         'Filas:['.implode(",",$listaFilasFechas).']'.
       '</div>'. 
  '<br><i class="material-icons text-danger">clear</i> Filas con errores: <b>'.$filasErroneas.'</b>'.    
  '<br><i class="material-icons text-success">check</i> Filas Correctas: <b>'.$totalFilasCorrectas.'</b>'.
  '<br>Total Filas: <b>'.$index.'</b>';
  showAlertSuccessErrorFilasLibreta("../".$urlOficial,$htmlInforme);  */

  $mensaje='Se encontraron algunos errores';
  borrarIngresoAlmacen($codigo);
}else{
  if($index>0){ // para registrar solo si hay filas en el archivo
    if(count($lista_documento) > count(array_unique($lista_documento))){
       $htmlInforme='';
       //$htmlInforme='<b>Filas repetidas: El numero de Referencia / Documento se repite en algunas filas</b>';
       //showAlertSuccessErrorFilasLibreta("../".$urlOficial,$htmlInforme);
       $mensaje='Filas Repetidas';
       borrarIngresoAlmacen($codigo);  
    }else{
      for ($ins=0; $ins<count($sqlInserts) ; $ins++) { 
        $sql_inserta2 = mysql_query($sqlInserts[$ins]);
      }
      if($sql_inserta2==1){
        $mensaje='Los datos fueron insertados correctamente.';
      	//showAlertSuccessError(true,"../".$urlOficial);	
      }else{
        $mensaje='EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.';
        borrarIngresoAlmacen($codigo);
	     //showAlertSuccessError(false,"../".$urlOficial);
      }
    }
  }else{
    $mensaje='El archivo está vacío';
    borrarIngresoAlmacen($codigo);
  }
}

//}


?>