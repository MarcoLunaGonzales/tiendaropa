<?php
require "../../conexionmysqli.php";
require "../funciones_siat.php";

$fechaHoraActual=date('Y-m-d\TH:i:s.v', time());

$nroAbiertos=obtenerCantidadPuntosVenta(1);
$nroCerrados=obtenerCantidadPuntosVenta(0);

?>
<script type="text/javascript">
  function cerrarPuntoVenta(ciudad){
   Swal.fire({
        title: '¿Esta seguro de cerrar?',
        text: "Se procederá con el cierre de la sucursal",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Si, Cerrar',
        cancelButtonText: 'No',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
            window.location.href='cerrar_punto.php?cod_ciudad='+ciudad;                           
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
}
function abrirPuntoVenta(ciudad){
   Swal.fire({
        title: '¿Esta seguro de abrir?',
        text: "Se procederá con el apertura de la sucursal",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: 'Si, Cerrar',
        cancelButtonText: 'No',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
            Swal.fire('Procesando...','Espere estamos procesando. Gracias! :)','warning');
            window.location.href='abrir_punto.php?cod_ciudad='+ciudad;                           
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
}

</script>
<div class="content">
  <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <form id="form1" class="form-horizontal" action="configSave.php" method="post">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">receipt</i>
                  </div>
                  <h4 class="card-title">Puntos de Venta - Sucursales(SIAT)</h4>
                  <hr>
                  <h5 class="text-dark">Sucursales Abiertas <b class="text-muted">[<?=$nroAbiertos?>]</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sucursales Cerradas <b class="text-danger">[<?=$nroCerrados?>]</b></h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-condensed small">
                      <thead class="fondo-boton">
                        <tr class="bg-primary text-white">
                          <th align="center">Codigo</th>
                          <th align="center">Sucursal</th>
                          <th align="center">Dirección</th>
                          <th align="center">Codigo Impuestos</th>
                          <th align="center">Codigo Punto Venta</th>
                          <th align="center">Estado</th>
                          <th width="20%" align="center">Opción</th>
                        </tr>
                      </thead>
                      <tbody>
                           <?php
                        $sql="SELECT c.cod_ciudad,c.nombre_ciudad,c.direccion,c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta  from ciudades c where c.cod_impuestos>=0 order by c.cod_ciudad;";
                        $resp=mysqli_query($enlaceCon,$sql);
                        while($dat=mysqli_fetch_array($resp)){
                          $codigo=$dat[0];
                          $descripcion=$dat[1];
                          $direccion=$dat[2];
                          $cod_impuestos=$dat[3];
                          $codigoPuntoVenta=$dat[4];

                          if($codigoPuntoVenta>0){
                            $estadoList="<a href='#' class='btn btn-sm btn-success'>Sucursal Abierta!</a>";
                            $botonPuntoVenta='<a href="#" onclick="cerrarPuntoVenta('.$codigo.');return false;" class=" btn btn-sm btn-default" title="CERRAR PUNTO VENTA"><i class="material-icons">door_back</i> CERRAR</a>';
                          }else{
                            $estadoList="<a href='#' class='btn btn-sm btn-danger'>Sucursal Cerrada!</a>";
                            $botonPuntoVenta='<ahref="#" onclick="abrirPuntoVenta('.$codigo.');return false;" class=" btn btn-sm btn-warning" title="ABRIR PUNTO VENTA"><i class="material-icons">meeting_room</i> ABRIR</a>';
                          }


                          
                          ?>
                          <tr>
                            <td class="text-left"><?=$codigo?></td>
                            <td class="text-left"><?=$descripcion?></td>
                            <td class="text-left"><?=$direccion?></td>
                            <td class="text-left"><?=$cod_impuestos?></td>                            
                            <td class="text-left"><?=$codigoPuntoVenta?></td>
                            <td class="text-left"><?=$estadoList?></td>
                            <td>
                             <?=$botonPuntoVenta?>
                            </td>
                          </tr>  
                        <?php 
                      } ?>

                        
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer">
                    <!-- <a href="#" onclick="sincronizarParametros();return false;" class="btn btn-primary"><i class="material-icons">sync_alt</i> Sincronizar con Impuestos</a> -->
                </div>
              </div>
              
               </form>
            </div>
          </div>  
        </div>
    </div>

