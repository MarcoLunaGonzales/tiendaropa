<?php
require 'PHPMailer/send.php';



function envio_facturaanulada($idproveedor,$proveedor,$nro_correlativo,$cuf,$nitCliente,$sucursalCliente,$estado_siatCliente,$fechaCliente,$correosProveedor,$enlaceCon){
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
require_once("funciones.php");

 $email = "";//cc de correo
 // $contact_message = trim($_POST['message']);
  $mail_username="KIDSPLACE";//Correo electronico emisor
  $mail_userpassword="";// contraseña correo emisor


  // $idproveedor=$_POST['idproveedor'];
  // $proveedor=$_POST['$proveedor'];
  // $nro_correlativo=$_POST['nro_correlativo'];
  // $cuf=$_POST['cuf'];
// echo "***";
  // echo "***";
  $urlDir=obtenerValorConfiguracion($enlaceCon,46);
// echo "***";
  // $correosProveedor=obtenerCorreosListaCliente($idproveedor);
  //$correosProveedor = "davidhuarina25@gmail.com,bsullcamani@gmail.com";
  if($correosProveedor<>""){
    $mail_addAddress=$correosProveedor;
    // if($email!=""){
    //   $mail_addAddress.=",".$email;  
    // }
    //$mail_addAddress="dhuarina@farmaciasbolivia.com.bo,asd";//correo electronico destino


    // $template="enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
    $template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
    /*Inicio captura de datos enviados por $_POST para enviar el correo */
    $mail_setFromEmail=$mail_username;
    $mail_setFromName=$mail_username;
    $titulo_pedido_email="ANULACION DE FACTURA: Nro:".$nro_correlativo;
    $txt_message="Estimado Cliente:<br>\n<br>\n
      La factura Nro: ".$nro_correlativo;
    $txt_message.="<br>\n      
      Fue Anulada.<br>\n<br>\n
      Gracias por su preferencia!"; //Con CUF: ".$cuf."<br>\n

    $mail_subject=$titulo_pedido_email; //el subject del mensaje

      $datosCabecera['cuf']=$cuf;
      $datosCabecera['nombre_cliente']="<li>Cliente: ".$proveedor."</li>";
      if($idproveedor==146){
        $datosCabecera['nombre_cliente']="";
      }        
      $datosCabecera['nro_factura']=$nro_correlativo;
      $datosCabecera['nit']=$nitCliente;
      
      $datosCabecera['sucursal']=$sucursalCliente; 
      $datosCabecera['estado_siat']=$estado_siatCliente;        
      $datosCabecera['fecha']=$fechaCliente;

    $flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$datosCabecera,$urlDir);
    if($flag!=0){//se envio correctamente
      return 1;
    }else{//error al enviar el correo
      return 2;
    }
  }else{
    return 0;//sin correo
  }
}