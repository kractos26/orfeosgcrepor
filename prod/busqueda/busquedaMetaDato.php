<!--
Ajustes de estilos
Julian Andres Ortiz Moreno
cel 3213006681
julecci@gmail.com
-->

<?php
session_start();
$verrad = "";
$ruta_raiz = "..";
define('ADODB_ASSOC_CASE', 1);
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$sqlTrad = "SELECT SGD_TRAD_CODIGO as ID,
			SGD_TRAD_DESCR as NOMB,
			SGD_TRAD_GENRADSAL as GRS
		FROM SGD_TRAD_TIPORAD 
		ORDER BY SGD_TRAD_CODIGO";
$rsTrad = $db->conn->query($sqlTrad);
if ($rsTrad && !$rsTrad->EOF) {
    $selectTrad = "<select name=\"selectTrad\" class=\"select\">
                <option value=\"0\">Tipo de radicaci&oacute;n</option>";
    while ($arr = $rsTrad->FetchRow()) {
        $selectTrad.="<option value=\"" . $arr['ID'] . "\">[" . $arr['ID'] . "] - " . $arr['NOMB'] . "</option>";
    }
    $selectTrad.="</select>";
} else {
    $selectTrad = "<p>No se encontraron tipos de radicado</p>";
}

$sqlDep = "SELECT depe_codi as ID,
			depe_nomb as NOMB
                                FROM dependencia 
		ORDER BY depe_nomb ";
$rsDep = $db->conn->query($sqlDep);
if ($rsDep && !$rsDep->EOF) {
    $selectDep = "<select name=\"selectDep\" class=\"select\">
                <option value=\"0\">Todas las dependencias</option>";
    while ($arr = $rsDep->FetchRow()) {
        $selectDep.="<option value=\"" . $arr['ID'] . "\">" . $arr['NOMB'] . "</option>";//[" . $arr['ID'] . "] - 
    }
    $selectDep.="</select>";
} else {
    $selectDep = "<p>No se encontraron dependencias</p>";
}

$sqlSerie = " SELECT SGD_SRD_DESCRIP, SGD_SRD_CODIGO FROM SGD_SRD_SERIESRD WHERE SGD_SRD_CODIGO IN (select distinct(sgd_srd_codigo) from sgd_mtd_metadatos where sgd_mtd_estado=1 and sgd_srd_codigo is not null) ORDER BY SGD_SRD_DESCRIP ";
$rsSerie = $db->conn->query($sqlSerie);
if ($rsSerie && !$rsSerie->EOF) {
    $selectSerie = "<select name=\"selectSerie\" class=\"select\">
                <option value=\"0\" selected>Serie</option>";
    while ($arr = $rsSerie->FetchRow()) {
        $selectSerie.="<option value=\"" . $arr['SGD_SRD_CODIGO'] . "\">" . $arr['SGD_SRD_DESCRIP'] . "</option>";
    }
    $selectSerie.="</select>";
} else {
    $selectSerie = "<p>No se encontraron series</p>";
}

$sqlTpDoc = "SELECT SGD_TPR_DESCRIP, SGD_TPR_CODIGO FROM SGD_TPR_TPDCUMENTO  WHERE SGD_TPR_CODIGO IN (select distinct(sgd_tpr_codigo) from sgd_mtd_metadatos where sgd_mtd_estado=1 and sgd_tpr_codigo is not null) ORDER BY SGD_TPR_DESCRIP ";
$rsTpDoc = $db->conn->query($sqlTpDoc);
if ($rsTpDoc && !$rsTpDoc->EOF) {
    $selectTpDoc = "<select name=\"selectTpDoc\" class=\"select\">
                <option value=\"\">Seleccione Tipo documental</option>";
    while ($arr = $rsTpDoc->FetchRow()) {
        $selectTpDoc.="<option value=\"" . $arr['SGD_TPR_CODIGO'] . "\">" . $arr['SGD_TPR_DESCRIP'] . "</option>";
    }
    $selectTpDoc.="</select>";
} else {
    $selectTpDoc = "<p>No se encontraron tipos documentales</p>";
}
?>
<html>
    <head>
        <title>Consulta Metadato</title>
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta http-equiv="cache-control" content="no-cache">
        <link rel="stylesheet" href="Site.css" type="text/css">
        <link rel="stylesheet" href="<?=$ruta_raiz."/estilos/".$_SESSION["ESTILOS_PATH"]?>/orfeo.css">
        <link rel="stylesheet" href="<?=$ruta_raiz."/estilos/tabber.css"?>" type="text/css" media="screen">
        <script type="text/javascript" src="../jquery/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="../jquery/sorttable.js"></script>
        <script type="text/javascript" src="../jquery/calendarDateInput.js">

            /***********************************************
             * Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
             * Script featured on and available at http://www.dynamicdrive.com
             * Keep this notice intact for use.
             ***********************************************/

        </script>
        <script>
            $(window).load(function(){
                checkTpMeta();
            });
            $(document).ready(function(){
                $('input[name="rbtn_tipometa"]').change(function(){
                    checkTpMeta();
                });
                $('select[name="selectSerie"]').change(function(){
                    $.post("BusqMetaAux.php", { Serie: $('select[name="selectSerie"]').val()},
                    function(data) {
                        $("#selectSbSerie").show().empty().html(data);
                        $("#selectSbSerie2").show();
                    });
                });
                $('input[name="btnSearch"]').click(function(){
                    $.post("BusqMetaAux.php", { 
                        Busq:true,
                        TipoBusq:$('input[name="rbtn_tipometa"]:checked').val(),
                        SerieB:$('select[name="selectSerie"]').val(),
                        SubSerie:$('select[name="selectSbSerie"]').val(),
                        TpDoc:$('select[name="selectTpDoc"]').val(),
                        Num:$("#txtBusqDoc").val(),
                        Desde:this.form.Desde.value,
                        Hasta:this.form.Hasta.value,
                        Meta:$("#txtMetadato").val(),
                        Trad:$('select[name="selectTrad"]').val(),
                        Dep:$('select[name="selectDep"]').val()
                    },
                    function(data) {
                        $("#resultTable").show().empty().html(data);
                    });
                });
            });
            function checkTpMeta(){
                $("#resultTable").hide();
                if($('input[name="rbtn_tipometa"]:checked').val()=="T"){
                    $('select[name="selectSerie"]').val(0);
                    $('select[name="selectSerie"]').hide();
                    $("#selectSbSerie").hide();
                    $("#selectSbSerie2").hide();
                    $('select[name="selectTpDoc"]').show();
                    $("#BusqDoc").empty().html("Radicado");
                    $("#TipoMetaDato").empty().html(" Tipo Documental ");
                    $("#TRad").show();
                    $("#DepGen").show();
                }else{
                    $('select[name="selectTpDoc"]').val(null);
                    $('select[name="selectTpDoc"]').hide();          
                    $('select[name="selectSerie"]').show();
                    $("#TRad").hide();
                    $("#DepGen").hide();
                    $("#BusqDoc").empty().html("Expediente");
                    $("#TipoMetaDato").empty().html(" Serie ");
                }
            }
            function limpiar()
            {
                $('form[name="Search"]').each (function(){
                    this.reset();
                });
                checkTpMeta();
                
            }
            
            function pasarDatos(numExp)
            {
                opener.document.getElementById('numeroExpediente').value=numExp;
                window.close();
            }

        </script>
    </head>
    <body class="PageBODY" >
        <form  name="Search"  action='<?= $_SERVER['PHP_SELF'] ?>?<?= $encabezado ?>' method=post>
            <table  border=0 cellpadding=0 cellspacing=2 width="60%" class='borde_tab'>
                <tbody>
                    <tr>
                        <td  class="titulos4" colspan="13">B&Uacute;SQUEDAS POR:</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="titulos5">
                             <table border=0 width=69% cellpadding="0" cellspacing="0">
                            <tr>
                                <td>		
                                    <div id="tab1" class="tabberlive" border="1">
                                            <ul class="tabbernav">
                                            <li >
                                            <a class="vinculos" href="../busqueda/busquedaPiloto.php?<?= $phpsession ?>&krd=<?= $krd ?>&<? echo "&fechah=$fechah&primera=1&ent=2&s_Listado=VerListado"; ?>">
                                                    GENERAL
                                                    </a>
                                            </li>
                                            </ul>
                                    </div>
                                </td>
                                 <td>		
                                    <div id="tab1" class="tabberlive" border="1">
                                            <ul class="tabbernav">
                                            <li >
                                                <a class="vinculos" href="../busqueda/busquedaHist.php?<?= session_name() . "=" . session_id() . "&fechah=$fechah&krd=$krd" ?>">
                                                HISTORICO
                                                    </a>
                                            </li>
                                            </ul>
                                    </div>
                                </td>
                                <td>		
                                    <div id="tab1" class="tabberlive" border="1">
                                            <ul class="tabbernav">
                                            <li >
                                           <a class="vinculos" href="../busqueda/busquedaExp.php?<?= $phpsession ?>&krd=<?= $krd ?>&<? ECHO "&fechah=$fechah&primera=1&ent=2"; ?>">

                                                Expedientes
                                               </a>
                                            </li>
                                            </ul>
                                    </div>
                                </td>
                                <td>		
                                    <div id="tab1" class="tabberlive" border="1">
                                            <ul class="tabbernav">
                                            <li >
                                                <a class="vinculos" href="../expediente/consultaTransferenciaExp.php?<?= $phpsession ?>&krd=<?= $krd ?>&<? ECHO "&fechah=$fechah&primera=1&ent=2"; ?>">
                                                TRANSFERENCIA
                                               </a>
                                            </li>
                                            </ul>
                                    </div>
                                </td>
                                <td>		
                                    <div id="tab1" class="tabberlive" border="1">
                                            <ul class="tabbernav">
                                            <li >
                                                <a class="vinculos" href="../busqueda/busquedaMetaDato.php?<?= $phpsession ?>&krd=<?= $krd ?>&<? ECHO "&fechah=$fechah&primera=1&ent=2"; ?>"> 

                                                METADATO
                                               </a>
                                            </li>
                                            </ul>
                                    </div>
                                </td>
                                <!--<td width="13%" valign="bottom" class="" ><a class="vinculos" href="../busqueda/busquedaUsuActu.php?<?= session_name() . "=" . session_id() . "&fechah=$fechah&krd=$krd" ?>"><img src='../imagenes/usuarios.gif' alt='' border=0 width="110" height="25" ></a></td>-->
                                <td width="35%" valign="bottom" class="" >&nbsp;</td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" >
                            <input type="hidden" name="FormName" value="Search"><input type="hidden" name="FormAction" value="search">
                            <table width="100%" class='borde_tab' align='center' cellpadding='0' cellspacing='2'>
                                <tbody>
                                    <tr>
                                        <td class="titulos5">Tipo de MetaDato</td>
                                        <td class="listado5">
                                            <input type="radio" name="rbtn_tipometa" value="S"> Subserie.
                                            <input type="radio" name="rbtn_tipometa" checked value="T">Tipo Documental.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="titulos5" id="TipoMetaDato">Tipo Documental</td>
                                        <td class="listado5">
                                            <?php echo $selectSerie; ?>
                                            <?php echo $selectTpDoc; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="titulos5" id="selectSbSerie2">Sub serie</td>
                                        <td class="listado5" id="selectSbSerie"></td>
                                    </tr>
                                    <tr>
                                        <td class="titulos5" id="BusqDoc">Radicado</td>
                                        <td class="listado5">
                                            <input class="tex_area" type="text" name="txtBusqDoc" id="txtBusqDoc" maxlength="" size="" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="titulos5">Metadato</td>
                                        <td class="listado5">
                                            <input class="tex_area" type="text" name="txtMetadato" id="txtMetadato" maxlength="" size="" >
                                        </td>
                                    </tr>
                                    <tr id="TRad"> 
                                        <td class="titulos5">Tipo de radicado</td>
                                        <td class="listado5"> <?php echo $selectTrad; ?> </td>
                                    </tr>
                                    <tr id="DepGen"> 
                                        <td class="titulos5">Dependencia generadora</td>
                                        <td class="listado5"> <?php echo $selectDep; ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="titulos5">Desde Fecha (yyyy/mm/dd)</td>
                                        <td class="listado5">
                                            <script>DateInput('Desde', true, 'DD-MM-YYYY')</script>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="titulos5">Hasta Fecha (yyyy/mm/dd)</td>
                                        <td class="listado5">
                                            <script>DateInput('Hasta', true, 'DD-MM-YYYY')</script>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td class="titulos5"></td>
                                        <td class="listado5"></td>
                                    </tr>
                                    <tr> 
                                        <td colspan="3" class="listado5">
                                <center><input class="botones" value="Limpiar" onclick="limpiar();" type="button">
                                    <input class="botones" value="B&uacute;squeda" type="button" name="btnSearch"></center>
                        </td>
                    </tr>
                </tbody> 
            </table>
        </td>
    </tr>
</tbody>
</table>
<table  border=0 cellpadding=0 cellspacing=2 width="auto" class='borde_tab' style="display:none;" id="resultTable">
    <tbody>
        <tr>
            <td valign="top">
                <table width="auto" class="FormTABLE">
                    <tbody>
                        <tr>
                            <td colspan="5" class="info"><b>Total Registros Encontrados: <div id="rsCount"></div></b></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>

            </td>
        </tr>
    </tbody>
</table>
</form>
</body>
</html>
