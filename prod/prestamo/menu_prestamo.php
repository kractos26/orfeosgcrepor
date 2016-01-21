<?php
   $krdOld = $krd;
   error_reporting(0);
   session_start();
   if(!$krd) $krd=$krdOsld;
   $ruta_raiz = "..";
   if(!$_SESSION['dependencia'] or !$_SESSION['tpDepeRad']) include "$ruta_raiz/rec_session.php";
   if(!$carpeta) {
      $carpeta = $carpetaOld;
      $tipo_carp = $tipoCarpOld;
   }
   $verrad = "";
   include_once "$ruta_raiz/include/db/ConnectionHandler.php";
   $db = new ConnectionHandler($ruta_raiz);	 
   
/*********************************************************************************
 *       Filename: menu_prestamo.php
 *       Modificado: 
 *          1/3/2006  IIAC  Men� del m�dulo de pr�stamos. Carga e inicializa los
 *                          formularios.  
 *********************************************************************************/

   // prestamo CustomIncludes begin
   include ("common.php");   
   // Save Page and File Name available into variables
   $sFileName = "menu_prestamo.php";
   // Variables de control   
   $opcionMenu=strip(get_param("opcionMenu"));
   if($_SESSION["usua_admin_archivo"]==1)$where=" AND d.DEPE_CODI_TERRITORIAL=".$_SESSION["depe_codi_territorial"];
   $isql = "select count(*) as CONTADOR from PRESTAMO p JOIN DEPENDENCIA d ON p.DEPE_CODI=d.DEPE_CODI where p.PRES_ESTADO=1 and p.SGD_EXP_NUMERO is not null $where";
   $rs=$db->conn->Execute($isql);
   $num_exp = $rs->fields["CONTADOR"];
?>
<html>
<head>
   <title>Archivo - Manejo de prestamos y devoluciones</title>
  <link rel="stylesheet" href="<?=$ruta_raiz."/estilos/".$_SESSION["ESTILOS_PATH"]?>/orfeo.css">
</head>
<body class="PageBODY">
   <form method="post" action="prestamo.php" name="menu"> 
      <input type="hidden" name="opcionMenu" value="1">      
      <input type="hidden" name="sFileName" value="<?=$sFileName?>"> 
      <input type="hidden"  value='<?=$krd?>' name="krd">
      <input type="hidden" value=" " name="radicado">  	          
      <script>
         // Inicializa la opci�n seleccionada
         function seleccionar(i) {
            document.menu.opcionMenu.value=i;
            document.menu.submit();
	     }
  	     var opcionM='<?=$opcionMenu?>';		 		 
	     if(opcionM!=""){ seleccionar(opcionM); }
      </script>	  	  	  
      <table width="31%" border="0" cellpadding="0" cellspacing="5" class="borde_tab" align="center">
         <tr>
            <td class="titulos4" align="center">PRESTAMO Y CONTROL DE DOCUMENTOS</td>
         </tr>
         <tr>
             <td class="listado2"><a href="javascript:seleccionar(1);" target='mainFrame' class="menu_princ"><b>1.1 Pr&eacute;stamo de Documentos</a></td>
         </tr>
         <tr>
             <td class="listado2"><a  href="javascript:seleccionar(2);" class="menu_princ">1.2 Devoluci&oacute;n de Documentos</a></td>
         </tr>
         <tr>
             <td class="listado2"><a  href="javascript:seleccionar(0);" class="menu_princ">1.3 Generaci&oacute;n de Reportes</a></td>
         </tr>
         <tr>
            <td class="listado2"><a  href="javascript:seleccionar(3);" class="menu_princ">1.4 Cancelar Solicitudes</a></td>
         </tr>
        <tr>
            <td class="titulos4" align="center">PRESTAMO Y CONTROL DE EXPEDIENTES</td>
        </tr>
        <tr>
          <td class="listado2"><a href='../expediente/prestamo/prestar.php?<?=session_name()."=".session_id()."&krd=$krd"?>' target='mainFrame' class="menu_princ"><b>&nbsp;2.1 Solicitud de Pr&eacute;stamo(<?=$num_exp?>) </a></td>
        </tr>
        <tr>
          <td class="listado2"><a href='../expediente/prestamo/devolver.php?<?=session_name()."=".session_id()."&krd=$krd"?>' target='mainFrame' class="menu_princ"><b>&nbsp;2.2 Devoluci&oacute;n </a></td>
        </tr>
        <tr>
          <td class="listado2"><a href='../expediente/consultarHistoriaPrestamo.php?<?=session_name()."=".session_id()."&krd=$krd"?>' target='mainFrame' class="menu_princ"><b>&nbsp;2.3 Generaci&oacute;n de Reportes </a></td>
        </tr>
        <tr>
          <td class="listado2"><a href='../expediente/cancelarSolicitudPrestamo.php?<?=session_name()."=".session_id()."&krd=$krd"?>' target='mainFrame' class="menu_princ"><b>&nbsp;2.4 Cancelar Solicitudes </a></td>
        </tr>
      </table>
   </form>  
</body>
</html>