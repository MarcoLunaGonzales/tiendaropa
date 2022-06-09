<?php
        require("conexionmysqli.php");
		require("estilos_almacenes.inc");
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
                function enviar_nav(cod_ciudad)
                {       location.href='registro_funcionarios.php?cod_ciudad='+cod_ciudad;
                }
                function eliminar_nav(f, cod_ciudad)
                {
                        var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j==0)
                        {       alert('Debe seleccionar al menos un funcionario para proceder a su eliminaci�n.');
                        }
                        else
                        {
                                if(confirm('Esta seguro de eliminar los datos ya que con ello se perdera toda la informacion historica del funcionario.'))
                                {
                                        location.href='eliminar_funcionario.php?datos='+datos+'&cod_ciudad='+cod_ciudad;
                                }
                                else
                                {
                                        return(false);
                                }
                        }
                }
				function editar_nav(f, cod_ciudad)
                {
                        var i;
                        var j=0;
                        var j_contacto;
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       j_contacto=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
                        if(j>1)
                        {       alert('Debe seleccionar solamente un funcionario para editar sus datos.');
                        }
                        else
                        {
                                if(j==0)
                                {
                                        alert('Debe seleccionar un funcionario para editar sus datos.');
                                }
                                else
                                {
                                        location.href='editar_funcionarios.php?j_funcionario='+j_contacto+'&cod_ciudad='+cod_ciudad;
                                }
                        }
                }
                function cambiar_vista(sel_vista, f)
                {
                        var modo_vista;
                        modo_vista=sel_vista.value;
                        location.href='navegador_funcionarios.php?cod_ciudad=$cod_ciudad&vista='+modo_vista+'';
                }
                </script>";

		
		$cod_ciudad=$_GET['cod_ciudad'];
		
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_ciudad";
                $resp_cab=mysqli_query($enlaceCon,$sql_cab);
                $dat_cab=mysqli_fetch_array($resp_cab);
                $nombre_ciudad=$dat_cab[0];
        echo "<form method='post' action=''>";
        //esta parte saca el ciclo activo
        $sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, f.celular,f.email,
		ci.descripcion,f.estado, f.cod_tipofuncionario,tf.nombre_tipofuncionario
        from funcionarios f, cargos c, ciudades ci, tipos_funcionarios tf
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' and f.estado='1' 
		and f.cod_tipofuncionario=tf.cod_tipofuncionario order by c.cargo,f.paterno";

		$resp=mysqli_query($enlaceCon,$sql);
        echo "<h1>Registro de Funcionarios<br>Territorio $nombre_ciudad</h1>";
        
		echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Funcionarios Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";

        echo "<center><table class='texto'>";
		echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Tipo</th><th>Cargo</th><th>Nombre</th>
				<th>E-mail</th><th>Celular</th><th>Alta en sistema</th>
				<th>Dar Alta</th><th>Restablecer<br> Clave</th><th>Proveedor</th></tr>";
        $indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
    {
		$codigo=$dat[0];
		$cargo=$dat[1];
		$paterno=$dat[2];
		$materno=$dat[3];
		$nombre=$dat[4];
		$nombre_f="$paterno $materno $nombre";
		$fecha_nac=$dat[5];
		$direccion=$dat[6];
		$telf=$dat[7];
		$cel=$dat[8];
		$email=$dat[9];
		$ciudad=$dat[10];
		$estado=$dat[11];
		$cod_tipofuncionario=$dat[12];
		$nombre_tipofuncionario=$dat[13];

		$sql_alta_sistema="select * from usuarios_sistema where codigo_funcionario='$codigo'";
		$resp_alta_sistema=mysqli_query($enlaceCon,$sql_alta_sistema);
		$filas_alta=mysqli_num_rows($resp_alta_sistema);
		if($estado==0)
		{	$alta_sistema="<img src='imagenes/no2.png' width='40'>";
				$dar_alta="-";
				$restablecer="-";
				$agenciasFuncionario="-";
		}
		if($estado==1)
		{	if($filas_alta==0)
				{
						//$alta_sistema="<img src='imagenes/no.png' width='40'>";  
						$alta_sistema="NO";  
						$dar_alta="<a href='alta_funcionario_sistema.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>
						<img src='imagenes/accesoSistema4.png'  width='35'></a>";
				}
				else
				{
						//$alta_sistema="<img src='imagenes/si.png' width='40'>";
						$alta_sistema="SI";  
						$dar_alta="-";
						$restablecer="<a href='restablecer_contrasena.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>
						<img src='imagenes/reestablecerPass.png' width='35'></a>";
				}
		}

	   

		echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td>
			<td align='center'>
			<input type='checkbox' name='cod_contacto' value='$codigo'></td>
			<td>&nbsp;$nombre_tipofuncionario</td><td>&nbsp;$cargo</td><td>$nombre_f</td>
			<td align='left'>&nbsp;$email</td><td align='left'>&nbsp;$cel</td>
			<td align='center'>$alta_sistema</td>
			<td align='center'>$dar_alta</td>
			<td align='center'>$restablecer</td>";
			if ($cod_tipofuncionario<>1){
				
			echo "<td align='center'>
			<center><a href='vincular_proveedor.php?codigo_funcionario=$codigo&cod_territorio=$cod_ciudad'>
			<img src='imagenes/proveedor8.png' width='30'></a></center>";			
			echo "<table border='0'  >";
			
				$sqlFunProv=" select fp.cod_proveedor, p.nombre_proveedor from funcionarios_proveedores  fp 
							inner join proveedores p on (fp.cod_proveedor=p.cod_proveedor)
							where codigo_funcionario='".$codigo."'";
			    $respFunProv=mysqli_query($enlaceCon,$sqlFunProv);
				while($dat=mysqli_fetch_array($respFunProv)){
						$cod_proveedor=$dat['cod_proveedor'];
						$nombre_proveedor=$dat['nombre_proveedor'];
					echo "<tr><td>".$nombre_proveedor."</td>";
					///////////
					echo "<td>
					<table class='texto'>
					<tr>
					<td><img src='imagenes/etiqueta3.png' width='20'></td>";

				echo "<td>";
		$sqlProvMarcas=" select pm.codigo, m.nombre, m.abreviatura from proveedores_marcas pm			
							inner join marcas m on(pm.codigo=m.codigo) where pm.cod_proveedor='".$cod_proveedor."' order by m.nombre asc";

			    $respProvMarcas=mysqli_query($enlaceCon,$sqlProvMarcas);
				while($datProvMarcas=mysqli_fetch_array($respProvMarcas)){
						$cod_marca=$datProvMarcas['codigo'];
						$nombre_marca=$datProvMarcas['nombre'];
						$abrev_marca=$datProvMarcas['abreviatura'];
					echo "$cod_marca - $abrev_marca - $nombre_marca<br>";	
				}	
			echo"</td></tr></table>";
			echo "</td>";
					////////
					echo "</tr>";	
				}	
			echo"</table>";
			}else{
				echo "<td align='center'>&nbsp;</td>";
			}
		echo "</tr>";
		$indice_tabla++;
	}
		
		echo "</table></center><br>";
		
        echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav($cod_ciudad)'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form, $cod_ciudad)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form, $cod_ciudad)'>
		</div>";
		
        echo "</form>";
?>

