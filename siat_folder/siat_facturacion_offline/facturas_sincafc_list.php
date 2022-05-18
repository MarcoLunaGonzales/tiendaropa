<script type="text/javascript">
 function activar_input_salida_almacen(index){
  var check=document.getElementById("factura_seleccionada"+index);
  if(check.checked){
    document.getElementById("factura_seleccionada_s"+index).value=1;
  }else{
    document.getElementById("factura_seleccionada_s"+index).value=0;
  }
}
 
</script>

<?php //ESTADO FINALIZADO
require("../../conexionmysqli.inc");
require("../../estilos_almacenes.inc");
?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-icon">
            <div class="card-icon bg-blanco">
              <img class="" width="40" height="40" src="../../imagenes/icon_farma.png">
            </div>
            <h4 class="card-title text-center">FACTURAS TIPO EMISION OFFLINE <br></h4>            
            <div class="row">
            </div> 
          </div>
          <form class="" action="facturas_sincafc_save.php" method="POST" onsubmit="return valida(this)" enctype="multipart/form-data">
            
          <div class="card-body">
            <div class="row">
            
            <div class="col-sm-7">
              <div class="form-group">
                  <select id="cod_motivo" name="cod_motivo" class="selectpicker form-control" data-style="btn btn-primary" data-show-subtext="true" data-live-search="true" required="true">
                  <option value="">SELECCIONE MOTIVO EVENTO</option>
                  <?php
                  $sql="select codigo,descripcion from  siat_tipos_evento where cod_estadoreferencial=1 and bandera=1";
                    $resp=mysqli_query($enlaceCon,$sql);
                    while($row=mysqli_fetch_array($resp)){ 
                   ?>
                    <option  value="<?=$row["codigo"];?>"><?=$row["descripcion"];?></option>
                  <?php } ?> 
                  </select>
              </div>
            </div>
            </div>
            <div class="table-responsive">
              
              <table id="tablePaginatorHeaderFooter" class="table table-bordered table-condensed table-striped " style="width:100%">
                <thead>                              
                  <tr>
                    <tr class='bg-info text-white'><th></th><th>&nbsp;</th><th>Sucursal</th><th>Nro. Factura</th><th>Fecha Emisión<br></th>
    				      <th>Cliente</th><th>Razon Social</th><th>NIT</th><th>Proceso</th><th>Monto</th></tr>
                  </tr>                                  
                </thead>
                <tbody>
                  <?php
                  $index=0;
                  $cod_tipoEmision=2;//tipo emision OFFLINE
                   $sql="SELECT s.cod_salida_almacenes,(select nombre_almacen from almacenes where cod_almacen=s.cod_almacen)sucursal, s.fecha, s.hora_salida, s.nro_correlativo,  
    					  (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente)cliente, s.cod_tipo_doc, razon_social, nit,s.cod_tipopago,s.monto_final,s.siat_codigotipoemision
    					  FROM salida_almacenes s
    					  WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
    						and siat_codigotipoemision=$cod_tipoEmision and siat_codigoRecepcion is null
    						order by 2,nro_correlativo";

                  // echo $sql;
                  $resp=mysqli_query($enlaceCon,$sql);
                  while($row=mysqli_fetch_array($resp)){ 
                    // echo "***";
                    $cod_salida_almacenes=$row['cod_salida_almacenes'];
                    $sucursal=$row['sucursal'];
                    $fecha=$row['fecha'];
                    $hora_salida=$row['hora_salida'];

                    $nro_correlativo=$row['nro_correlativo'];
                    $cliente=$row['cliente'];
                    // $cod_tipo_doc=$row['cod_tipo_doc'];
                    $razon_social=$row['razon_social'];
                    $nit=$row['nit'];
                    $cod_tipopago=$row['cod_tipopago'];
                    $monto_final=$row['monto_final'];
                    $cod_tipoEmision=$row['siat_codigotipoemision'];
                    
                      $index++;
                      ?>
                    <tr>

                      <td class="td-actions text-right">
                      <input type="hidden" id="factura_seleccionada_s<?=$index?>" name="factura_seleccionada_s<?=$index?>"  value="0">
                      <input type="hidden" id="cod_salida_almacenes<?=$index?>" name="cod_salida_almacenes<?=$index?>"  value="<?=$cod_salida_almacenes?>">
                        <input type="checkbox"  data-toggle="toggle" title="Seleccionar" id="factura_seleccionada<?=$index?>" name="factura_seleccionada<?=$index?>" onchange="activar_input_salida_almacen(<?=$index?>)">
                      </td>

                      <td class="text-center small"><?=$index;?></td>
                      <td class="text-left small"><?=$sucursal;?></td>
                      <td class="text-center small"><?=$nro_correlativo;?></td>
                      <td class="text-left small"><?=$fecha;?> <?=$hora_salida;?></td>
                      <td class="text-left small"><?=$cliente;?></td>
                    
                      <td class="text-center small"><?=$razon_social;?></td>
                      <td class="text-left small"><?=$nit;?></td>
                      <td class="text-right small"><?=$cod_salida_almacenes;?></td>
                      <td class="text-right small"><?=number_format($monto_final,1,'.',',');?></td>

                    </tr>
                    <?php   
                    
                  }?>
                </tbody>
              </table>
              <input type="hidden" name="contador_items" id="contador_items" value="<?=$index?>">            
            </div>
          </div>
          <div class="card-footer fixed-bottom">
              <button type="submit" class="btn btn-rose">Enviar seleccionados</button>
              <!-- <button type="button" class="btn btn-info" >Histórico</button> -->
              
          </div>
          </form>

        </div>
      </div>
    </div>  
  </div>
</div>
<!-- <div class="cargar-ajax d-none">
  <div class="div-loading text-center">
     <h4 class="text-warning font-weight-bold" id="texto_ajax_titulo">Procesando Datos</h4>
     <p class="text-white">Aguard&aacute; un momento por favor</p>  
  </div>
</div> -->


<script type="text/javascript">
    function valida(f) {
        var ok = true;
            // Swal.fire("Informativo!","PROCESANDO..", "warning");
            $(".cargar-ajax").removeClass("d-none");
            $("#texto_ajax_titulo").html("Procesando datos..");
        return ok;
    }
</script>