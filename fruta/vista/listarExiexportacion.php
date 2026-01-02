<?php

include_once "../../assest/config/validarUsuarioFruta.php";

//LLAMADA ARCHIVOS NECESARIOS PARA LAS OPERACIONES

include_once '../../assest/controlador/EXIEXPORTACION_ADO.php';
include_once '../../assest/controlador/EEXPORTACION_ADO.php';
include_once '../../assest/controlador/PRODUCTOR_ADO.php';
include_once '../../assest/controlador/VESPECIES_ADO.php';
include_once '../../assest/controlador/ESPECIES_ADO.php';
include_once '../../assest/controlador/FOLIO_ADO.php';
include_once '../../assest/controlador/FOLIO_ADO.php';
include_once '../../assest/controlador/TMANEJO_ADO.php';
include_once '../../assest/controlador/TCALIBRE_ADO.php';
include_once '../../assest/controlador/TEMBALAJE_ADO.php';
include_once '../../assest/controlador/TPROCESO_ADO.php';
include_once '../../assest/controlador/TREEMBALAJE_ADO.php';
include_once '../../assest/controlador/TCOLOR_ADO.php';
include_once '../../assest/controlador/TCATEGORIA_ADO.php';
include_once '../../assest/controlador/ICARGA_ADO.php';



include_once '../../assest/controlador/RECEPCIONPT_ADO.php';
include_once '../../assest/controlador/REPALETIZAJEEX_ADO.php';
include_once '../../assest/controlador/PROCESO_ADO.php';
include_once '../../assest/controlador/REEMBALAJE_ADO.php';
include_once '../../assest/controlador/DESPACHOPT_ADO.php';
include_once '../../assest/controlador/DESPACHOEX_ADO.php';
include_once '../../assest/controlador/TINPSAG_ADO.php';
include_once '../../assest/controlador/INPSAG_ADO.php';


//INCIALIZAR LAS VARIBLES
//INICIALIZAR CONTROLADOR

$EXIEXPORTACION_ADO =  new EXIEXPORTACION_ADO();
$EEXPORTACION_ADO =  new EEXPORTACION_ADO();

$PRODUCTOR_ADO =  new PRODUCTOR_ADO();
$VESPECIES_ADO =  new VESPECIES_ADO();
$ESPECIES_ADO =  new ESPECIES_ADO();
$FOLIO_ADO =  new FOLIO_ADO();
$TMANEJO_ADO =  new TMANEJO_ADO();
$TCALIBRE_ADO =  new TCALIBRE_ADO();
$TEMBALAJE_ADO =  new TEMBALAJE_ADO();
$TPROCESO_ADO =  new TPROCESO_ADO();
$TREEMBALAJE_ADO =  new TREEMBALAJE_ADO();
$TCOLOR_ADO =  new TCOLOR_ADO();
$TCATEGORIA_ADO =  new TCATEGORIA_ADO();
$ICARGA_ADO =  new ICARGA_ADO();




$RECEPCIONPT_ADO =  new RECEPCIONPT_ADO();
$REPALETIZAJEEX_ADO =  new REPALETIZAJEEX_ADO();
$DESPACHOPT_ADO =  new DESPACHOPT_ADO();
$DESPACHOEX_ADO =  new DESPACHOEX_ADO();
$PROCESO_ADO =  new PROCESO_ADO();
$REEMBALAJE_ADO =  new REEMBALAJE_ADO();
$TINPSAG_ADO =  new TINPSAG_ADO();
$INPSAG_ADO =  new INPSAG_ADO();


//INCIALIZAR VARIBALES A OCUPAR PARA LA FUNCIONALIDAD
$TOTALNETO = "";
$TOTALENVASE = "";
$TAMAÑO=0;
$CONTADOR=0;


//INICIALIZAR ARREGLOS
$ARRAYEXIEXPORTACION = "";
$ARRAYTOTALEXIEXPORTACION = "";
$ARRAYVEREEXPORTACIONID = "";
$ARRAYVERPRODUCTORID = "";
$ARRAYVERPVESPECIESID = "";
$ARRAYVERVESPECIESID = "";
$ARRAYVERESPECIESID = "";
$ARRAYVERFOLIOID = "";
$ARRAYEMPRESA = "";
$ARRAYPLANTA = "";
$ARRAYVERRECEPCIONPT = "";
$ARRAYDESPACHO="";
$ARRAYDESPACHO2="";
$ARRAYTINPSAG = "";
$ARRAYINPSAG = "";
$CACHEPRODUCTOR = [];
$CACHEVESPECIES = [];
$CACHEESPECIES = [];
$CACHEESTANDAR = [];
$CACHETMANEJO = [];
$CACHETCALIBRE = [];
$CACHETEMBALAJE = [];
$CACHETCATEGORIA = [];
$CACHETCOLOR = [];
$CACHEEMPRESA = [];
$CACHEPLANTA = [];
$CACHETEMPORADA = [];
$CACHEICARGA = [];

//DEFINIR ARREGLOS CON LOS DATOS OBTENIDOS DE LAS FUNCIONES DE LOS CONTROLADORES 
if ($EMPRESAS  && $PLANTAS && $TEMPORADAS) {
    $ARRAYEXIEXPORTACION = $EXIEXPORTACION_ADO->listarExiexportacionAgrupadoPorFolioEmpresaPlantaTemporadaDisponible($EMPRESAS, $PLANTAS, $TEMPORADAS);
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Existencia Producto Terminado</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <!- LLAMADA DE LOS ARCHIVOS NECESARIOS PARA DISEÑO Y FUNCIONES BASE DE LA VISTA -!>
        <?php include_once "../../assest/config/urlHead.php"; ?>
        <!- FUNCIONES BASES -!>
            <script type="text/javascript">
                //REDIRECCIONAR A LA PAGINA SELECIONADA
                function irPagina(url) {
                    location.href = "" + url;
                }
                function abrirPestana(url) {
                    var win = window.open(url, '_blank');
                    win.focus();
                }
                //FUNCION PARA ABRIR VENTANA QUE SE ENCUENTRA LA OPERACIONES DE DETALLE DE RECEPCION
                function abrirVentana(url) {
                    var opciones =
                        "'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1000, height=800'";
                    window.open(url, 'window', opciones);
                }
              
            </script>

</head>

<body class="hold-transition light-skin fixed sidebar-mini theme-primary" >
    <div class="wrapper">
        <!- LLAMADA AL MENU PRINCIPAL DE LA PAGINA-!>
            <?php include_once "../../assest/config/menuFruta.php"; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="container-full">

                    <!-- Content Header (Page header) -->
                    <div class="content-header">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h3 class="page-title">Existencia</h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">Módulo</li>
                                            <li class="breadcrumb-item" aria-current="page">Existencia</li>
                                            <li class="breadcrumb-item" aria-current="page">Disponible</li>
                                            <li class="breadcrumb-item active" aria-current="page"> <a href="#">Existencia Producto Terminado </a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                            <?php include_once "../../assest/config/verIndicadorEconomico.php"; ?>
                        </div>
                    </div>
                    <!-- Main content -->
                    <section class="content">
                        <div class="box">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="existenciapt" class="table-hover" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Folio Original</th>
                                                        <th>Folio Nuevo</th>
                                                        <th>Fecha Embalado </th>
                                                         <th>Estado </th>
                                                        <!--<th>Estado Calidad</th> 
                                                        <th>Condición </th>
                                                        <th>Días</th> -->
                                                        <th>Código Estandar</th>
                                                        <th>Envase/Estandar</th>
                                                        <th>Tipo Calibre </th>
                                                        <th>CSG</th>
                                                        <th>Productor</th>
                                                        <!-- <th>Especies</th> -->
                                                        <th>Variedad</th>
                                                        <th>Cantidad Envase</th>
                                                        <!-- <th>Total Envase</th>-->
                                                        <th>Kilos Neto</th>
                                                        <!--<th>% Deshidratacion</th>
                                                        <th>Kilos Deshidratacion</th>-->
                                                        <th>Kilos Bruto</th>
                                                        <th>Promedio Por Envase</th>
                                                        <!--<th>Número Recepción </th>
                                                        <th>Fecha Recepción </th>
                                                        <th>Tipo Recepción </th>
                                                        <th>CSG/CSP Recepción</th>
                                                        <th>Origen Recepción </th>
                                                        <th>Número Guía Recepción </th>
                                                        <th>Fecha Guía Recepción
                                                        <th>Número Repaletizaje </th>
                                                        <th>Fecha Repaletizaje </th>
                                                        <th>Número Proceso </th>
                                                        <th>Fecha Proceso </th>
                                                        <th>Tipo Proceso </th>
                                                        <th>Número Reembalaje </th>
                                                        <th>Fecha Reembalaje </th>
                                                        <th>Tipo Reembalaje </th>                                                        
                                                        <th>Número Inspección </th>
                                                        <th>Fecha Inspección </th>
                                                        <th>Tipo Inspección </th> -->
                                                        <th>Tipo Manejo</th>
                                                        <th>Condición SAG</th>
                                                        <th>Número SIF</th>
                                                        <th>Días en Existencia</th>
                                                        <!-- <th>Tipo Calibre </th>
                                                        <th>Tipo Embalaje </th>
                                                        <th>Stock</th>
                                                        <th>Embolsado</th>
                                                        <th>Gasificacion</th>
                                                        <th>Prefrío</th>
                                                        <th>Tipo Categoria </th>
                                                        <th>Tipo Color </th>     
                                                        <th>Ingreso</th>
                                                        <th>Modificación</th>
                                                        <th>Empresa</th>
                                                        <th>Planta</th>
                                                        <th>Temporada</th> -->
                                                        <th>Numero Referencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ARRAYEXIEXPORTACION as $s) : ?>

                                                        <?php $ARRAYEXISTENCIA=$EXIEXPORTACION_ADO->listarExiexportacionEmpresaPlantaTemporadaPorFolioDisponible($EMPRESAS, $PLANTAS, $TEMPORADAS,$s['FOLIO_AUXILIAR_EXIEXPORTACION'] );  ?>                                                                                                                    
                                                        <?php foreach ($ARRAYEXISTENCIA as $r) : ?>
                                                            <?php
                                                                if($r['COLOR']==1){}else{
                                                                $CONTADOR+=1;   ?>
                                                            <?php
                                                            if ($r['ESTADO'] == "0") {
                                                                $ESTADO = "Elimnado";
                                                            }
                                                            if ($r['ESTADO'] == "1") {
                                                                $ESTADO = "Ingresando";
                                                            }
                                                            if ($r['ESTADO'] == "2") {
                                                                $ESTADO = "Disponible";
                                                            }
                                                            if ($r['ESTADO'] == "3") {
                                                                $ESTADO = "En Repaletizaje";
                                                            }
                                                            if ($r['ESTADO'] == "4") {
                                                                $ESTADO = "Repaletizado";
                                                            }
                                                            if ($r['ESTADO'] == "5") {
                                                                $ESTADO = "En Reembalaje";
                                                            }
                                                            if ($r['ESTADO'] == "6") {
                                                                $ESTADO = "Reembalaje";
                                                            }
                                                            if ($r['ESTADO'] == "7") {
                                                                $ESTADO = "En Despacho";
                                                            }
                                                            if ($r['ESTADO'] == "8") {
                                                                $ESTADO = "Despachado";
                                                            }
                                                            if ($r['ESTADO'] == "9") {
                                                                $ESTADO = "En Transito";
                                                            }
                                                            if ($r['ESTADO'] == "10") {
                                                                $ESTADO = "En Inspección Sag";
                                                            }
                                                            if ($r['ESTADO'] == "11") {
                                                                $ESTADO = "Rechazado";
                                                            }
                                                            if ($r['TESTADOSAG'] == null || $r['TESTADOSAG'] == "0") {
                                                                $ESTADOSAG = "Sin Condición";
                                                            }
                                                            if ($r['TESTADOSAG'] == "1") {
                                                                $ESTADOSAG =  "En Inspección";
                                                            }
                                                            if ($r['TESTADOSAG'] == "2") {
                                                                $ESTADOSAG =  "Aprobado Origen";
                                                            }
                                                            if ($r['TESTADOSAG'] == "3") {
                                                                $ESTADOSAG =  "Aprobado USDA";
                                                            }
                                                            if ($r['TESTADOSAG'] == "4") {
                                                                $ESTADOSAG =  "Fumigado";
                                                            }
                                                            if ($r['TESTADOSAG'] == "5") {
                                                                $ESTADOSAG =  "Rechazado";
                                                            }
                                                            if($r['COLOR']=="1"){
                                                                $TRECHAZOCOLOR="badge badge-danger ";
                                                                $COLOR="Rechazado";
                                                            }else if($r['COLOR']=="2"){
                                                                $TRECHAZOCOLOR="badge badge-warning ";
                                                                $COLOR="Objetado";
                                                            }else if($r['COLOR']=="3"){
                                                                $TRECHAZOCOLOR="badge badge-success ";
                                                                $COLOR="Aprobado";
                                                            }else{
                                                                $TRECHAZOCOLOR="";
                                                                $COLOR="Sin Datos";
                                                            }                                                                                                                    
                                                            if ($r['ID_ICARGA']) {
                                                                if (!isset($CACHEICARGA[$r['ID_ICARGA']])) {
                                                                    $CACHEICARGA[$r['ID_ICARGA']] = $ICARGA_ADO->verIcarga($r['ID_ICARGA']);
                                                                }
                                                                if($CACHEICARGA[$r['ID_ICARGA']]){
                                                                    $NUMEROREFERENCIA=$CACHEICARGA[$r['ID_ICARGA']][0]["NREFERENCIA_ICARGA"];
                                                                }else{
                                                                    $NUMEROREFERENCIA =  "Sin Datos";
                                                                }
                                                            }else{
                                                                $NUMEROREFERENCIA =  "Sin Datos";
                                                            }
                                                            $ARRAYRECEPCION = $RECEPCIONPT_ADO->verRecepcion2($r['ID_RECEPCION']);
                                                            $ARRAYDESPACHO2 = $DESPACHOPT_ADO->verDespachopt($r['ID_DESPACHO2']);
                                                            if ($ARRAYRECEPCION) {
                                                                $NUMERORECEPCION = $ARRAYRECEPCION[0]["NUMERO_RECEPCION"];
                                                                $FECHARECEPCION = $ARRAYRECEPCION[0]["FECHA"];
                                                                $NUMEROGUIARECEPCION = $ARRAYRECEPCION[0]["NUMERO_GUIA_RECEPCION"];
                                                                $FECHAGUIARECEPCION = $ARRAYRECEPCION[0]["GUIA"];
                                                                if ($ARRAYRECEPCION[0]["TRECEPCION"] == 1) {
                                                                    $TIPORECEPCION = "Desde Productor";
                                                                    $ARRAYPRODUCTOR2 = $PRODUCTOR_ADO->verProductor($ARRAYRECEPCION[0]['ID_PRODUCTOR']);
                                                                    if ($ARRAYPRODUCTOR2) {
                                                                        $CSGCSPORIGEN=$ARRAYPRODUCTOR2[0]['CSG_PRODUCTOR'];
                                                                        $ORIGEN =  $ARRAYPRODUCTOR2[0]['NOMBRE_PRODUCTOR'];
                                                                    } else {
                                                                        $ORIGEN = "Sin Datos";
                                                                        $CSGCSPORIGEN="Sin Datos";
                                                                    }
                                                                }
                                                                if ($ARRAYRECEPCION[0]["TRECEPCION"] == 2) {
                                                                    $TIPORECEPCION = "Planta Externa";
                                                                    $ARRAYPLANTA2 = $PLANTA_ADO->verPlanta($ARRAYRECEPCION[0]['ID_PLANTA2']);
                                                                    if ($ARRAYPLANTA2) {
                                                                        $ORIGEN = $ARRAYPLANTA2[0]['NOMBRE_PLANTA'];
                                                                        $CSGCSPORIGEN=$ARRAYPLANTA2[0]['CODIGO_SAG_PLANTA'];
                                                                    } else {
                                                                        $ORIGEN = "Sin Datos";
                                                                        $CSGCSPORIGEN="Sin Datos";
                                                                    }
                                                                }
                                                            }else if($ARRAYDESPACHO2){
                                                                
                                                                $NUMERORECEPCION = $ARRAYDESPACHO2[0]["NUMERO_DESPACHO"];
                                                                $FECHARECEPCION = $ARRAYDESPACHO2[0]["FECHA"];                                                                
                                                                $NUMEROGUIARECEPCION = $ARRAYDESPACHO2[0]["NUMERO_GUIA_DESPACHO"];
                                                                $TIPORECEPCION = "Interplanta";
                                                                $FECHAGUIARECEPCION = "";                                                                
                                                                $ARRAYPLANTA2 = $PLANTA_ADO->verPlanta($ARRAYDESPACHO2[0]['ID_PLANTA']);
                                                                if ($ARRAYPLANTA2) {
                                                                    $ORIGEN = $ARRAYPLANTA2[0]['NOMBRE_PLANTA'];
                                                                    $CSGCSPORIGEN=$ARRAYPLANTA2[0]['CODIGO_SAG_PLANTA'];
                                                                } else {
                                                                    $ORIGEN = "Sin Datos";
                                                                    $CSGCSPORIGEN="Sin Datos";
                                                                }                                                        
                                                            } else {
                                                                $NUMERORECEPCION = "Sin Datos";
                                                                $FECHARECEPCION = "";
                                                                $NUMEROGUIARECEPCION = "Sin Datos";
                                                                $FECHAGUIARECEPCION = "";
                                                                $TIPORECEPCION = "Sin Datos";
                                                                $ORIGEN = "Sin Datos";
                                                                $CSGCSPORIGEN = "Sin Datos";
                                                            }
                                                            $ARRAYPROCESO = $PROCESO_ADO->verProceso2($r['ID_PROCESO']);
                                                            if ($ARRAYPROCESO) {
                                                                $NUMEROPROCESO = $ARRAYPROCESO[0]["NUMERO_PROCESO"];
                                                                $FECHAPROCESO = $ARRAYPROCESO[0]["FECHA"];
                                                                $ARRAYTPROCESO = $TPROCESO_ADO->verTproceso($ARRAYPROCESO[0]["ID_TPROCESO"]);
                                                                if ($ARRAYTPROCESO) {
                                                                    $TPROCESO = $ARRAYTPROCESO[0]["NOMBRE_TPROCESO"];
                                                                }
                                                            } else {
                                                                $NUMEROPROCESO = "Sin datos";
                                                                $FECHAPROCESO = "";
                                                                $TPROCESO = "Sin datos";
                                                            }
                                                            $ARRAYREEMBALAJE = $REEMBALAJE_ADO->verReembalaje2($r['ID_REEMBALAJE']);
                                                            if ($ARRAYREEMBALAJE) {
                                                                $NUMEROREEMBALEJE = $ARRAYREEMBALAJE[0]["ID_TREEMBALAJE"];
                                                                $FECHAREEMBALEJE = $ARRAYREEMBALAJE[0]["FECHA"];
                                                                $ARRAYTREEMBALAJE = $TREEMBALAJE_ADO->verTreembalaje($ARRAYREEMBALAJE[0]["ID_TREEMBALAJE"]);
                                                                if ($ARRAYTREEMBALAJE) {
                                                                    $TREEMBALAJE = $ARRAYTREEMBALAJE[0]["NOMBRE_TREEMBALAJE"];
                                                                }
                                                            } else {
                                                                $NUMEROREEMBALEJE = "Sin datos";
                                                                $FECHAREEMBALEJE = "";
                                                                $TREEMBALAJE = "Sin datos";
                                                            }

                                                            $ARRATREPALETIZAJE = $REPALETIZAJEEX_ADO->verRepaletizaje2($r['ID_REPALETIZAJE']);
                                                            if ($ARRATREPALETIZAJE) {
                                                                $FECHAREPALETIZAJE = $ARRATREPALETIZAJE[0]["INGRESO"];
                                                                $NUMEROREPALETIZAJE = $ARRATREPALETIZAJE[0]["NUMERO_REPALETIZAJE"];
                                                            } else {
                                                                $NUMEROREPALETIZAJE = "Sin Datos";
                                                                $FECHAREPALETIZAJE = "";
                                                            }
                                                            $ARRAYINPSAG = $INPSAG_ADO->verInpsag3($r['ID_INPSAG']);
                                                            if ($ARRAYINPSAG) {
                                                                $FECHAINPSAG = $ARRAYINPSAG[0]["FECHA"];                                                                
                                                                $NUMEROINPSAG = $ARRAYINPSAG[0]["NUMERO_INPSAG"]."-".$ARRAYINPSAG[0]["CORRELATIVO_INPSAG"];
                                                                $ARRAYTINPSAG=$TINPSAG_ADO->verTinpsag($ARRAYINPSAG[0]["ID_TINPSAG"]);
                                                                if($ARRAYTINPSAG){
                                                                    $NOMBRETINPSAG= $ARRAYTINPSAG[0]["NOMBRE_TINPSAG"];
                                                                }else{
                                                                    $NOMBRETINPSAG = "Sin Datos";
                                                                }
                                         
                                                            } else {
                                                                $FECHAINPSAG = "";
                                                                $NUMEROINPSAG = "Sin Datos";
                                                                $NOMBRETINPSAG = "Sin Datos";
                                                            }
                                                  
                                                            if (!isset($CACHEPRODUCTOR[$r['ID_PRODUCTOR']])) {
                                                                $CACHEPRODUCTOR[$r['ID_PRODUCTOR']] = $PRODUCTOR_ADO->verProductor($r['ID_PRODUCTOR']);
                                                            }
                                                            $ARRAYVERPRODUCTORID = $CACHEPRODUCTOR[$r['ID_PRODUCTOR']];
                                                            if ($ARRAYVERPRODUCTORID) {

                                                                $CSGPRODUCTOR = $ARRAYVERPRODUCTORID[0]['CSG_PRODUCTOR'];
                                                                $NOMBREPRODUCTOR = $ARRAYVERPRODUCTORID[0]['NOMBRE_PRODUCTOR'];
                                                            } else {
                                                                $CSGPRODUCTOR = "Sin Datos";
                                                                $NOMBREPRODUCTOR = "Sin Datos";
                                                            }
                                                            if (!isset($CACHEESTANDAR[$r['ID_ESTANDAR']])) {
                                                                $CACHEESTANDAR[$r['ID_ESTANDAR']] = $EEXPORTACION_ADO->verEstandar($r['ID_ESTANDAR']);
                                                            }
                                                            $ARRAYEVERERECEPCIONID = $CACHEESTANDAR[$r['ID_ESTANDAR']];
                                                            if ($ARRAYEVERERECEPCIONID) {
                                                                $CODIGOESTANDAR = $ARRAYEVERERECEPCIONID[0]['CODIGO_ESTANDAR'];
                                                                $NOMBREESTANDAR = $ARRAYEVERERECEPCIONID[0]['NOMBRE_ESTANDAR'];
                                                            } else {
                                                                $CODIGOESTANDAR = "Sin Datos";
                                                                $NOMBREESTANDAR = "Sin Datos";
                                                            }
                                                            if (!isset($CACHEVESPECIES[$r['ID_VESPECIES']])) {
                                                                $CACHEVESPECIES[$r['ID_VESPECIES']] = $VESPECIES_ADO->verVespecies($r['ID_VESPECIES']);
                                                            }
                                                            $ARRAYVERVESPECIESID = $CACHEVESPECIES[$r['ID_VESPECIES']];
                                                            if ($ARRAYVERVESPECIESID) {
                                                                $NOMBREVESPECIES = $ARRAYVERVESPECIESID[0]['NOMBRE_VESPECIES'];
                                                                $IDESPECIES = $ARRAYVERVESPECIESID[0]['ID_ESPECIES'];
                                                                if (!isset($CACHEESPECIES[$IDESPECIES])) {
                                                                    $CACHEESPECIES[$IDESPECIES] = $ESPECIES_ADO->verEspecies($IDESPECIES);
                                                                }
                                                                $ARRAYVERESPECIESID = $CACHEESPECIES[$IDESPECIES];
                                                                if ($ARRAYVERESPECIESID) {
                                                                    $NOMBRESPECIES = $ARRAYVERESPECIESID[0]['NOMBRE_ESPECIES'];
                                                                } else {
                                                                    $NOMBRESPECIES = "Sin Datos";
                                                                }
                                                            } else {
                                                                $NOMBREVESPECIES = "Sin Datos";
                                                                $NOMBRESPECIES = "Sin Datos";
                                                            }
                                                            if (!isset($CACHETMANEJO[$r['ID_TMANEJO']])) {
                                                                $CACHETMANEJO[$r['ID_TMANEJO']] = $TMANEJO_ADO->verTmanejo($r['ID_TMANEJO']);
                                                            }
                                                            $ARRAYTMANEJO = $CACHETMANEJO[$r['ID_TMANEJO']];
                                                            if ($ARRAYTMANEJO) {
                                                                $NOMBRETMANEJO = $ARRAYTMANEJO[0]['NOMBRE_TMANEJO'];
                                                            } else {
                                                                $NOMBRETMANEJO = "Sin Datos";
                                                            }
                                                            if (!isset($CACHETCALIBRE[$r['ID_TCALIBRE']])) {
                                                                $CACHETCALIBRE[$r['ID_TCALIBRE']] = $TCALIBRE_ADO->verCalibre($r['ID_TCALIBRE']);
                                                            }
                                                            $ARRAYTCALIBRE = $CACHETCALIBRE[$r['ID_TCALIBRE']];
                                                            if ($ARRAYTCALIBRE) {
                                                                $NOMBRETCALIBRE = $ARRAYTCALIBRE[0]['NOMBRE_TCALIBRE'];
                                                            } else {
                                                                $NOMBRETCALIBRE = "Sin Datos";
                                                            }
                                                            if (!isset($CACHETEMBALAJE[$r['ID_TEMBALAJE']])) {
                                                                $CACHETEMBALAJE[$r['ID_TEMBALAJE']] = $TEMBALAJE_ADO->verEmbalaje($r['ID_TEMBALAJE']);
                                                            }
                                                            $ARRAYTEMBALAJE = $CACHETEMBALAJE[$r['ID_TEMBALAJE']];
                                                            if ($ARRAYTEMBALAJE) {
                                                                $NOMBRETEMBALAJE = $ARRAYTEMBALAJE[0]['NOMBRE_TEMBALAJE'];
                                                            } else {
                                                                $NOMBRETEMBALAJE = "Sin Datos";
                                                            }
                                                            if (!isset($CACHETCATEGORIA[$r['ID_TCATEGORIA']])) {
                                                                $CACHETCATEGORIA[$r['ID_TCATEGORIA']] = $TCATEGORIA_ADO->verTcategoria($r['ID_TCATEGORIA']);
                                                            }
                                                            $ARRAYTCATEGORIA=$CACHETCATEGORIA[$r['ID_TCATEGORIA']];
                                                            if($ARRAYTCATEGORIA){
                                                            $NOMBRETCATEGORIA= $ARRAYTCATEGORIA[0]["NOMBRE_TCATEGORIA"];
                                                            }else{
                                                                $NOMBRETCATEGORIA = "Sin Datos";
                                                            }   
                                                            if (!isset($CACHETCOLOR[$r['ID_TCOLOR']])) {
                                                                $CACHETCOLOR[$r['ID_TCOLOR']] = $TCOLOR_ADO->verTcolor($r['ID_TCOLOR']);
                                                            }
                                                            $ARRAYTCOLOR=$CACHETCOLOR[$r['ID_TCOLOR']];
                                                            if($ARRAYTCOLOR){
                                                                $NOMBRETCOLOR= $ARRAYTCOLOR[0]["NOMBRE_TCOLOR"];
                                                            }else{
                                                                $NOMBRETCOLOR = "Sin Datos";
                                                            } 
                                                            if (!isset($CACHEEMPRESA[$r['ID_EMPRESA']])) {
                                                                $CACHEEMPRESA[$r['ID_EMPRESA']] = $EMPRESA_ADO->verEmpresa($r['ID_EMPRESA']);
                                                            }
                                                            $ARRAYEMPRESA = $CACHEEMPRESA[$r['ID_EMPRESA']];
                                                            if ($ARRAYEMPRESA) {
                                                                $NOMBREEMPRESA = $ARRAYEMPRESA[0]['NOMBRE_EMPRESA'];
                                                            } else {
                                                                $NOMBREEMPRESA = "Sin Datos";
                                                            }
                                                            if (!isset($CACHEPLANTA[$r['ID_PLANTA']])) {
                                                                $CACHEPLANTA[$r['ID_PLANTA']] = $PLANTA_ADO->verPlanta($r['ID_PLANTA']);
                                                            }
                                                            $ARRAYPLANTA = $CACHEPLANTA[$r['ID_PLANTA']];
                                                            if ($ARRAYPLANTA) {
                                                                $NOMBREPLANTA = $ARRAYPLANTA[0]['NOMBRE_PLANTA'];
                                                            } else {
                                                                $NOMBREPLANTA = "Sin Datos";
                                                            }
                                                            if (!isset($CACHETEMPORADA[$r['ID_TEMPORADA']])) {
                                                                $CACHETEMPORADA[$r['ID_TEMPORADA']] = $TEMPORADA_ADO->verTemporada($r['ID_TEMPORADA']);
                                                            }
                                                            $ARRAYTEMPORADA = $CACHETEMPORADA[$r['ID_TEMPORADA']];
                                                            if ($ARRAYTEMPORADA) {
                                                                $NOMBRETEMPORADA = $ARRAYTEMPORADA[0]['NOMBRE_TEMPORADA'];
                                                            } else {
                                                                $NOMBRETEMPORADA = "Sin Datos";
                                                            }

                                                            if ($r['STOCK'] != "") {
                                                                $STOCK = $r['STOCK'];
                                                            } else if ($r['STOCK'] == "") {
                                                                $STOCK = "Sin Datos";
                                                            } else {
                                                                $STOCK = "Sin Datos";
                                                            }
                                                            if ($r['EMBOLSADO'] == "1") {
                                                                $EMBOLSADO =  "SI";
                                                            }
                                                            if ($r['EMBOLSADO'] == "0") {
                                                                $EMBOLSADO =  "NO";
                                                            }
                                                            if ($r['GASIFICADO'] == "1") {
                                                                $GASIFICADO = "SI";
                                                            } else if ($r['GASIFICADO'] == "0") {
                                                                $GASIFICADO = "NO";
                                                            } else {
                                                                $GASIFICADO = "Sin Datos";
                                                            }
                                                            if ($r['PREFRIO'] == "0") {
                                                                $PREFRIO = "NO";
                                                            } else if ($r['PREFRIO'] == "1") {
                                                                $PREFRIO =  "SI";
                                                            } else {
                                                                $PREFRIO = "Sin Datos";
                                                            }
                                                            if ($r['EMBALADO']) {
                                                                $FECHAEMBALADO = DateTime::createFromFormat('Y-m-d', $r['EMBALADO']);
                                                                $FECHAEMBALADODOS = DateTime::createFromFormat('d/m/Y', $r['EMBALADO']);
                                                                if ($FECHAEMBALADO) {
                                                                    $FECHAEMBALADO->setTime(0, 0);
                                                                    $DIASENEXISTENCIA = $FECHAEMBALADO->diff(new DateTime('today'))->format('%a');
                                                                } elseif ($FECHAEMBALADODOS) {
                                                                    $FECHAEMBALADODOS->setTime(0, 0);
                                                                    $DIASENEXISTENCIA = $FECHAEMBALADODOS->diff(new DateTime('today'))->format('%a');
                                                                } else {
                                                                    $DIASENEXISTENCIA = "Sin Datos";
                                                                }
                                                            } else {
                                                                $DIASENEXISTENCIA = "Sin Datos";
                                                            }
                                                            $NUMEROSIF = "Sin Datos";
                                                            if ($ARRAYINPSAG && isset($ARRAYINPSAG[0]["CORRELATIVO_INPSAG"]) && strlen($ARRAYINPSAG[0]["CORRELATIVO_INPSAG"])>0) {
                                                                $NUMEROSIF = $ARRAYINPSAG[0]["CORRELATIVO_INPSAG"];
                                                            }
                                                            $RESALTARFOLIO = ($r['ID_INPSAG'] == "" || $r['ID_INPSAG'] == null) && is_numeric($DIASENEXISTENCIA) && $DIASENEXISTENCIA > 3;
                                                            $CLASERESALTADO = $RESALTARFOLIO ? "text-danger" : "";
                                                            ?>
                                                            <tr class="text-center <?php echo $CLASERESALTADO; ?>">
                                                                <td>                                                                   
                                                                    <span class="<?php echo $TRECHAZOCOLOR; ?>">
                                                                        <?php echo $r['FOLIO_EXIEXPORTACION']; ?>
                                                                    </span>
                                                                </td>
                                                                <td>                    
                                                                    <span class="<?php echo $TRECHAZOCOLOR; ?>">
                                                                        <a Onclick="abrirPestana('../../assest/documento/informeTarjasPT.php?parametro=<?php echo $r['FOLIO_AUXILIAR_EXIEXPORTACION']; ?>&&parametro1=<?php echo $r['ID_EMPRESA']; ?>&&parametro2=<?php echo $r['ID_PLANTA']; ?>&&tipo=1');">                                                                        
                                                                            <?php echo $r['FOLIO_AUXILIAR_EXIEXPORTACION']; ?>                                                                                                                                        
                                                                        </a>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo $r['EMBALADO']; ?></td>
                                                                 <td><?php echo $ESTADO; ?></td>
                                                                <!--<td><?php /*echo $COLOR; ?></td>
                                                                <td><?php echo $ESTADOSAG; ?></td>
                                                                <td><?php echo $r['DIAS']; */?></td> -->
                                                                <td><?php echo $CODIGOESTANDAR; ?></td>
                                                                <td><?php echo $NOMBREESTANDAR; ?></td>
                                                                <td><?php echo $NOMBRETCALIBRE; ?></td>
                                                                <td><?php echo $CSGPRODUCTOR; ?></td>
                                                                <td><?php echo $NOMBREPRODUCTOR; ?></td>
                                                                <!-- <td><?php /*echo $NOMBRESPECIES; */?></td> -->
                                                                <td><?php echo $NOMBREVESPECIES; ?></td>
                                                                <td><?php echo $r['ENVASE']; ?></td>
                                                                <td><?php echo $r['NETO']; ?></td>
                                                                <td><?php echo $r['BRUTO']; ?></td>
                                                                <td><?php echo number_format(($r['NETO']/$r['ENVASE']),2); ?></td>
                                                                <!--  <th><?php /*echo $s['ENVASE']; ?></th>
                                                                <td><?php echo $r['NETO']; ?></td>
                                                                <td><?php echo $r['PORCENTAJE']; ?></td>
                                                                <td><?php echo $r['DESHIRATACION']; ?></td>
                                                                <td><?php echo $r['BRUTO']; ?></td>
                                                                <td><?php echo $NUMERORECEPCION; ?></td>
                                                                <td><?php echo $FECHARECEPCION; ?></td>
                                                                <td><?php echo $TIPORECEPCION; ?></td>
                                                                <td><?php echo $CSGCSPORIGEN; ?></td>
                                                                <td><?php echo $ORIGEN; ?></td>
                                                                <td><?php echo $NUMEROGUIARECEPCION; ?></td>
                                                                <td><?php echo $FECHAGUIARECEPCION; ?></td>
                                                                <td><?php echo $NUMEROREPALETIZAJE; ?></td>
                                                                <td><?php echo $FECHAREPALETIZAJE; ?></td>
                                                                <td><?php echo $NUMEROPROCESO; ?></td>
                                                                <td><?php echo $FECHAPROCESO; ?></td>
                                                                <td><?php echo $TPROCESO; ?></td>
                                                                <td><?php echo $NUMEROREEMBALEJE; ?></td>
                                                                <td><?php echo $FECHAREEMBALEJE; ?></td>
                                                                <td><?php echo $TREEMBALAJE; ?></td>
                                                                <td><?php echo $NUMEROINPSAG; ?></td>
                                                                <td><?php echo $FECHAINPSAG; ?></td>
                                                                <td><?php echo $NOMBRETINPSAG; */ ?></td> -->
                                                                <td><?php echo $NOMBRETMANEJO; ?></td>
                                                                <td><?php echo $ESTADOSAG; ?></td>
                                                                <td><?php echo $NUMEROSIF; ?></td>
                                                                <td><?php echo $DIASENEXISTENCIA; ?></td>
                                                                <!--<td><?php /*  echo $NOMBRETCALIBRE; ?></td>
                                                                <td><?php echo $NOMBRETEMBALAJE; ?></td>
                                                                <td><?php echo $STOCK; ?></td>
                                                                <td><?php echo $EMBOLSADO; ?></td>
                                                                <td><?php echo $GASIFICADO; ?></td>
                                                                <td><?php echo $PREFRIO; ?></td>
                                                                <td><?php echo $NOMBRETCATEGORIA; ?></td>
                                                                <td><?php echo $NOMBRETCOLOR; ?></td>
                                                                <td><?php echo $r['INGRESO']; ?></td>
                                                                <td><?php echo $r['MODIFICACION']; ?></td>
                                                                <td><?php echo $NOMBREEMPRESA; ?></td>
                                                                <td><?php echo $NOMBREPLANTA; ?></td>
                                                                <td><?php echo $NOMBRETEMPORADA; */?></td> -->
                                                                <td><?php echo $NUMEROREFERENCIA; ?></td>
                                                            </tr>                                                       
                                                        <?php } endforeach; ?>        
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                            <div class="box-footer">
                                <div class="btn-toolbar mb-3" role="toolbar" aria-label="Datos generales">
                                    <div class="form-row align-items-center" role="group" aria-label="Datos">
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Envase</div>
                                                    <button class="btn   btn-default" id="TOTALENVASEV" name="TOTALENVASEV" >                                                           
                                                    </button>
                                                </div>
                                            </div>
                                        </div><!-- 
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Neto</div>
                                                    <button class="btn   btn-default" id="TOTALNETOV" name="TOTALNETOV" >                                                           
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Bruto</div>
                                                    <button class="btn   btn-default" id="TOTALBRUTOV" name="TOTALBRUTOV" >                                                           
                                                    </button>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <!-- /.box -->
                    </section>
                    <!-- /.content -->

                </div>
            </div>
            <!- LLAMADA ARCHIVO DEL DISEÑO DEL FOOTER Y MENU USUARIO -!>
                <?php include_once "../../assest/config/footer.php"; ?>
                <?php include_once "../../assest/config/menuExtraFruta.php"; ?>
    </div>
    <!- LLAMADA URL DE ARCHIVOS DE DISEÑO Y JQUERY E OTROS -!>
        <?php include_once "../../assest/config/urlBase.php"; ?>
        <script>
            // const Toast = Swal.mixin({
            //     toast: true,
            //     position: 'top',
            //     showConfirmButton: false,
            //     showConfirmButton: false
            // })
            // Toast.fire({
            //     icon: "info",
            //     title: "Informacion importante",
            //     html: "<label>Las <b>Existencia</b> que tienen la letra de color <b>Rojo</b> tiene mas de 7 dias desde su ingreso.</label>"
            // })
        </script>
</body>

</html>
