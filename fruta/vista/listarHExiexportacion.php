<?php


include_once "../../assest/config/validarUsuarioFruta.php";

//LLAMADA ARCHIVOS NECESARIOS PARA LAS OPERACIONES
include_once '../../assest/controlador/EXIEXPORTACION_ADO.php';
include_once '../../assest/controlador/EMPRESA_ADO.php';


//INCIALIZAR LAS VARIBLES
//INICIALIZAR CONTROLADOR

$EXIEXPORTACION_ADO =  new EXIEXPORTACION_ADO();
$EMPRESA_ADO = new EMPRESA_ADO();

//INCIALIZAR VARIBALES A OCUPAR PARA LA FUNCIONALIDAD




//INICIALIZAR ARREGLOS
//INCIALIZAR VARIBALES A OCUPAR PARA LA FUNCIONALIDAD
$TOTALNETO = "";
$TOTALENVASE = "";
$TAMAÑO=0;
$CONTADOR=0;


//INICIALIZAR ARREGLOS
$ARRAYEXIEXPORTACION = "";
$ARRAYTOTALEXIEXPORTACION = "";
$ARRAYEMPRESA = "";

$LOGOEMPRESA = '';
$NOMBREEMPRESA = '';

if ($EMPRESAS) {
    $ARRAYEMPRESA = $EMPRESA_ADO->verEmpresa($EMPRESAS);
    if ($ARRAYEMPRESA) {
        $LOGOEMPRESA = $ARRAYEMPRESA[0]['LOGO_EMPRESA'];
        $NOMBREEMPRESA = $ARRAYEMPRESA[0]['NOMBRE_EMPRESA'];
    }
}

//DEFINIR ARREGLOS CON LOS DATOS OBTENIDOS DE LAS FUNCIONES DE LOS CONTROLADORES
if ($EMPRESAS && $PLANTAS && $TEMPORADAS) {
    $ARRAYEXIEXPORTACION = $EXIEXPORTACION_ADO->listarExiexportacionEmpresaPlantaTemporadaDetalle($EMPRESAS, $PLANTAS, $TEMPORADAS);
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial Existencia PT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <!- LLAMADA DE LOS ARCHIVOS NECESARIOS PARA DISEÑO Y FUNCIONES BASE DE LA VISTA -!>
        <?php include_once "../../assest/config/urlHead.php"; ?>
    <style>
        .detalle-modal .modal-content {
            border: 1px solid #c7d6eb;
            box-shadow: 0 12px 30px rgba(5, 43, 92, 0.15);
            border-radius: 12px;
            overflow: hidden;
        }

        .detalle-modal .modal-header {
            background: linear-gradient(120deg, #0b559f 0%, #0c3972 100%);
            color: #f6f9ff;
            border: none;
            padding: 14px 18px;
        }

        .detalle-modal .modal-title {
            font-weight: 800;
            letter-spacing: 0.25px;
            margin: 0;
            color: #f6f9ff;
        }

        .detalle-modal .modal-subtitle {
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #d6e4f9;
            margin-bottom: 2px;
            opacity: 0.95;
        }

        .detalle-modal .close {
            color: #f2f6fb;
            opacity: 1;
            font-weight: 800;
        }

        .detalle-hero {
            margin: 0 -12px 14px;
            background: #e8f0fb;
            border-bottom: 1px solid #d7e4f5;
            padding: 0;
        }

        .detalle-hero .brand-banner {
            width: 100%;
            overflow: hidden;
            border-bottom: 1px solid #d7e4f5;
        }

        .detalle-hero .brand-banner img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .detalle-modal .modal-body {
            background: linear-gradient(180deg, #f8fbff 0%, #f2f6fb 28%, #ffffff 100%);
            padding: 12px 14px 8px;
        }

        .detalle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 10px;
            align-items: stretch;
            grid-auto-rows: 1fr;
        }

        .detalle-resumen-table {
            margin-bottom: 10px;
        }

        .detalle-resumen-table .detalle-table {
            table-layout: fixed;
        }

        .detalle-resumen-table thead th {
            background: #0c3972;
            color: #f2f6fb;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.4px;
            font-weight: 800;
        }

        .detalle-resumen-table tbody td {
            font-size: 14px;
            font-weight: 800;
            background: #f9fbff;
        }

        .detalle-card {
            background: #fff;
            border: 1px solid #d3deef;
            border-radius: 10px;
            padding: 0;
            box-shadow: 0 10px 18px rgba(12, 57, 114, 0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .detalle-card h5 {
            font-size: 12px;
            font-weight: 700;
            color: #0c3972;
            margin: 0;
            letter-spacing: 0.3px;
            padding: 10px 12px;
            background: linear-gradient(90deg, #eef3fb 0%, #dfe9f7 100%);
            border-bottom: 1px solid #d6e1f3;
        }

        .detalle-card table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            color: #1f3a56;
        }

        .detalle-card th,
        .detalle-card td {
            padding: 7px 12px;
            border-bottom: 1px solid #eef2f7;
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        .detalle-card th {
            background: #fafbfc;
            color: #4d637d;
            width: 42%;
            font-weight: 700;
        }

        .detalle-card td {
            font-weight: 700;
        }

        .detalle-table.resumen-table th,
        .detalle-table.resumen-table td {
            text-align: center;
        }

        .detalle-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            padding: 6px 10px;
            border-radius: 10px;
            background: #d8e8ff;
            color: #0b2f57;
            font-weight: 800;
            border: 1px solid #b7c9e6;
        }

        .detalle-modal .modal-footer {
            padding: 12px 14px;
            border-top: 1px solid #d3deef;
            background: #e8f0fb;
        }

        .detalle-modal .btn-primary {
            background: #0b559f;
            border-color: #0b559f;
            color: #ffffff;
            font-weight: 800;
        }

        .detalle-modal .btn-secondary {
            background: #ffffff;
            color: #0c3972;
            border-color: #0c3972;
            font-weight: 800;
        }

        .detalle-modal .btn {
            min-width: 170px;
            border-radius: 10px;
        }

        .mov-link {
            color: #0f4a7a;
            text-decoration: underline;
            font-weight: 700;
        }
    </style>
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

<body class="hold-transition light-skin fixed sidebar-mini theme-primary">
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
                                <h3 class="page-title">Producto Terminado </h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">Modulo</li>
                                            <li class="breadcrumb-item" aria-current="page">Informes</li>
                                            <li class="breadcrumb-item" aria-current="page">Producto Terminado</li>
                                            <li class="breadcrumb-item" aria-current="page">Existencia</li>
                                            <li class="breadcrumb-item active" aria-current="page"> <a href="#">Historial Existencia PT</a>
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
                                            <table id="hexistencia" class="table-hover table-bordered" style="width: 300%;">
                                                                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Trazabilidad</th>
                                                        <th>Folio Original</th>
                                                        <th>Fecha Embalado</th>
                                                        <th>Estado </th>
                                                        <th>Estado Calidad</th>
                                                        <th>Condición </th>
                                                        <th>Código Estandar</th>
                                                        <th>Envase/Estandar</th>
                                                        <th>Tipo Calibre </th>
                                                        <th>CSG</th>
                                                        <th>Productor</th>
                                                        <th>Variedad</th>
                                                        <th>Cantidad Envase</th>
                                                        <th>Kilos Neto</th>
                                                        <th>% Deshidratacion</th>
                                                        <th>Kilos Deshidratacion</th>
                                                        <th>Kilos Bruto</th>
                                                        <th>Número Recepción </th>
                                                        <th>Fecha Recepción </th>
                                                        <th>Tipo Recepción </th>
                                                        <th>CSG/CSP Recepción</th>
                                                        <th>Origen Recepción </th>
                                                        <th>Número Guía Recepción </th>
                                                        <th>Fecha Guía Recepción</th>
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
                                                        <th>Tipo Inspección </th>
                                                        <th>Número Despacho </th>
                                                        <th>Fecha Despacho </th>
                                                        <th>Número Guía Despacho </th>
                                                        <th>Tipo Despacho </th>
                                                        <th>CSG/CSP Despacho</th>
                                                        <th>Destino Despacho</th>
                                                        <th>Tipo Manejo</th>
                                                        <th>Tipo Calibre (Detalle)</th>
                                                        <th>Tipo Embalaje </th>
                                                        <th>Stock</th>
                                                        <th>Embolsado</th>
                                                        <th>Gasificacion</th>
                                                        <th>Prefrío</th>
                                                        <th>Días</th>
                                                        <th>Ingreso</th>
                                                        <th>Modificación</th>
                                                        <th>Numero Referencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ARRAYEXIEXPORTACION as $r) : ?>
                                                        <?php  $CONTADOR+=1;   ?>
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
                                                            $CONDICION = $ESTADOSAG;

                                                            if($r['COLOR']=="1"){
                                                                $TRECHAZOCOLOR="badge badge-danger ";
                                                                $COLOR="Rechazado";
                                                            }else if($r['COLOR']=="2"){
                                                                $TRECHAZOCOLOR="badge badge-warning ";
                                                                $COLOR="Levantado";
                                                            }else if($r['COLOR']=="3"){
                                                                $TRECHAZOCOLOR="badge badge-success ";
                                                                $COLOR="Aprobado";
                                                            }else{
                                                                $TRECHAZOCOLOR="";
                                                                $COLOR="Sin Datos";
                                                            }
                                                            $ESTADOCALIDAD = $COLOR;
                                                            $NUMEROREFERENCIA = $r['ICARGA_REFERENCIA'] ?: "Sin Datos";
                                                            if ($r['ID_RECEPCION']) {
                                                                $NUMERORECEPCION = $r['RECEPCION_NUMERO'] ?: "Sin Datos";
                                                                $FECHARECEPCION = $r['RECEPCION_FECHA'] ?: "";
                                                                $NUMEROGUIARECEPCION = $r['RECEPCION_GUIA_NUMERO'] ?: "Sin Datos";
                                                                $FECHAGUIARECEPCION = $r['RECEPCION_GUIA_FECHA'] ?: "";
                                                                if ($r['RECEPCION_TIPO'] == 1) {
                                                                    $TIPORECEPCION = "Desde Productor";
                                                                    $ORIGEN = $r['RECEPCION_PRODUCTOR_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPORIGEN = $r['RECEPCION_PRODUCTOR_CSG'] ?: "Sin Datos";
                                                                } elseif ($r['RECEPCION_TIPO'] == 2) {
                                                                    $TIPORECEPCION = "Planta Externa";
                                                                    $ORIGEN = $r['RECEPCION_PLANTA_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPORIGEN = $r['RECEPCION_PLANTA_CSG'] ?: "Sin Datos";
                                                                } else {
                                                                    $TIPORECEPCION = "Sin Datos";
                                                                    $ORIGEN = "Sin Datos";
                                                                    $CSGCSPORIGEN = "Sin Datos";
                                                                }
                                                            } elseif ($r['DESPACHO2_NUMERO']) {
                                                                $NUMERORECEPCION = $r['DESPACHO2_NUMERO'] ?: "Sin Datos";
                                                                $FECHARECEPCION = $r['DESPACHO2_FECHA'] ?: "";
                                                                $NUMEROGUIARECEPCION = $r['DESPACHO2_GUIA'] ?: "Sin Datos";
                                                                $TIPORECEPCION = "Interplanta";
                                                                $FECHAGUIARECEPCION = "";
                                                                $ORIGEN = $r['DESPACHO2_PLANTA_NOMBRE'] ?: "Sin Datos";
                                                                $CSGCSPORIGEN = $r['DESPACHO2_PLANTA_CSG'] ?: "Sin Datos";
                                                            } else {
                                                                $NUMERORECEPCION = "Sin Datos";
                                                                $FECHARECEPCION = "";
                                                                $NUMEROGUIARECEPCION = "Sin Datos";
                                                                $FECHAGUIARECEPCION = "";
                                                                $TIPORECEPCION = "Sin Datos";
                                                                $ORIGEN = "Sin Datos";
                                                                $CSGCSPORIGEN = "Sin Datos";
                                                            }
                                                            if ($r['ID_PROCESO']) {
                                                                $NUMEROPROCESO = $r['PROCESO_NUMERO'] ?: "Sin datos";
                                                                $FECHAPROCESO = $r['PROCESO_FECHA'] ?: "";
                                                                $TPROCESO = $r['TPROCESO_NOMBRE'] ?: "Sin datos";
                                                            } else {
                                                                $NUMEROPROCESO = "Sin datos";
                                                                $FECHAPROCESO = "";
                                                                $TPROCESO = "Sin datos";
                                                            }
                                                            if ($r['ID_REEMBALAJE']) {
                                                                $NUMEROREEMBALEJE = $r['REEMBALAJE_TIPO_ID'] ?: "Sin datos";
                                                                $FECHAREEMBALEJE = $r['REEMBALAJE_FECHA'] ?: "";
                                                                $TREEMBALAJE = $r['TREEMBALAJE_NOMBRE'] ?: "Sin datos";
                                                            } else {
                                                                $NUMEROREEMBALEJE = "Sin datos";
                                                                $FECHAREEMBALEJE = "";
                                                                $TREEMBALAJE = "Sin datos";
                                                            }
                                                            if ($r['ID_REPALETIZAJE']) {
                                                                $FECHAREPALETIZAJE = $r['REPALETIZAJE_FECHA'] ?: "";
                                                                $NUMEROREPALETIZAJE = $r['REPALETIZAJE_NUMERO'] ?: "Sin Datos";
                                                            } else {
                                                                $NUMEROREPALETIZAJE = "Sin Datos";
                                                                $FECHAREPALETIZAJE = "";
                                                            }
                                                            if ($r['ID_INPSAG']) {
                                                                $FECHAINPSAG = $r['INPSAG_FECHA'] ?: "";
                                                                if ($r['INPSAG_NUMERO']) {
                                                                    $NUMEROINPSAG = $r['INPSAG_NUMERO'] . "-" . $r['INPSAG_CORRELATIVO'];
                                                                } else {
                                                                    $NUMEROINPSAG = "Sin Datos";
                                                                }
                                                                $NOMBRETINPSAG = $r['TINPSAG_NOMBRE'] ?: "Sin Datos";
                                                            } else {
                                                                $FECHAINPSAG = "";
                                                                $NUMEROINPSAG = "Sin Datos";
                                                                $NOMBRETINPSAG = "Sin Datos";
                                                            }
                                                            if ($r['ID_DESPACHO']) {
                                                                $NUMERODESPACHO = $r['DESPACHO_NUMERO'] ?: "Sin Datos";
                                                                $FECHADESPACHO = $r['DESPACHO_FECHA'] ?: "";
                                                                $NUMEROGUIADESPACHO = $r['DESPACHO_GUIA'] ?: "Sin Datos";
                                                                if ($r['DESPACHO_TIPO'] == "1") {
                                                                    $TDESPACHO = "Interplanta";
                                                                    $DESTINO = $r['DESPACHO_PLANTA2_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPDESTINO = $r['DESPACHO_PLANTA2_CSG'] ?: "Sin Datos";
                                                                } elseif ($r['DESPACHO_TIPO'] == "2") {
                                                                    $TDESPACHO = "Devolución Productor";
                                                                    $DESTINO = $r['DESPACHO_PRODUCTOR_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPDESTINO = $r['DESPACHO_PRODUCTOR_CSG'] ?: "Sin Datos";
                                                                } elseif ($r['DESPACHO_TIPO'] == "3") {
                                                                    $TDESPACHO = "Venta";
                                                                    $DESTINO = $r['DESPACHO_COMPRADOR_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPDESTINO = "No Aplica";
                                                                } elseif ($r['DESPACHO_TIPO'] == "4") {
                                                                    $TDESPACHO = "Despacho de Decarte";
                                                                    $NUMEROGUIADESPACHO = "No Aplica";
                                                                    $CSGCSPDESTINO = "No Aplica";
                                                                    $DESTINO = $r['DESPACHO_REGALO'] ?: "Sin Datos";
                                                                } elseif ($r['DESPACHO_TIPO'] == "5") {
                                                                    $TDESPACHO = "Planta Externa";
                                                                    $DESTINO = $r['DESPACHO_PLANTA3_NOMBRE'] ?: "Sin Datos";
                                                                    $CSGCSPDESTINO = $r['DESPACHO_PLANTA3_CSG'] ?: "Sin Datos";
                                                                } else {
                                                                    $TDESPACHO = "Sin datos";
                                                                    $DESTINO = "Sin Datos";
                                                                    $CSGCSPDESTINO = "Sin Datos";
                                                                }
                                                            } elseif ($r['ID_DESPACHOEX']) {
                                                                $TDESPACHO = "Exportación";
                                                                $CSGCSPDESTINO = "No Aplica";
                                                                $NUMERODESPACHO = $r['DESPACHOEX_NUMERO'] ?: "Sin Datos";
                                                                $NUMEROGUIADESPACHO = $r['DESPACHOEX_GUIA'] ?: "Sin Datos";
                                                                $FECHADESPACHO = $r['DESPACHOEX_FECHA'] ?: "";
                                                                $DESTINO = $r['DESPACHOEX_DESTINO'] ?: "Sin Datos";
                                                            } else {
                                                                $DESTINO = "Sin datos";
                                                                $TDESPACHO = "Sin datos";
                                                                $FECHADESPACHO = "";
                                                                $NUMERODESPACHO = "Sin Datos";
                                                                $NUMEROGUIADESPACHO = "Sin Datos";
                                                                $CSGCSPDESTINO = "Sin Datos";
                                                            }
                                                            $CSGPRODUCTOR = $r['PRODUCTOR_CSG'] ?: "Sin Datos";
                                                            $NOMBREPRODUCTOR = $r['PRODUCTOR_NOMBRE'] ?: "Sin Datos";
                                                            $CODIGOESTANDAR = $r['ESTANDAR_CODIGO'] ?: "Sin Datos";
                                                            $NOMBREESTANDAR = $r['ESTANDAR_NOMBRE'] ?: "Sin Datos";
                                                            $NOMBREVESPECIES = $r['VESPECIES_NOMBRE'] ?: "Sin Datos";
                                                            $NOMBRESPECIES = $r['ESPECIES_NOMBRE'] ?: "Sin Datos";
                                                            $NOMBRETMANEJO = $r['TMANEJO_NOMBRE'] ?: "Sin Datos";
                                                            $NOMBRETCALIBRE = $r['TCALIBRE_NOMBRE'] ?: "Sin Datos";
                                                            $NOMBRETEMBALAJE = $r['TEMBALAJE_NOMBRE'] ?: "Sin Datos";

                                                            $STOCK = $r['STOCKR'] ?: "Sin Datos";
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
                                                            $ENVASE_PROMEDIO = isset($r['ENVASE_NUM']) ? (float) $r['ENVASE_NUM'] : 0;
                                                            $NETO_PROMEDIO = isset($r['NETO_NUM']) ? (float) $r['NETO_NUM'] : 0;
                                                            $PROMEDIO = ($ENVASE_PROMEDIO > 0) ? round($NETO_PROMEDIO / $ENVASE_PROMEDIO, 2) : 0;
                                                            ?>
                                                            <tr class="text-center">
    <td>
        <button type="button" class="btn btn-sm btn-outline-info btn-block" data-toggle="modal" data-target="#detalleExistenciaModal"
            data-folio="<?php echo htmlspecialchars($r['FOLIO_EXIEXPORTACION'], ENT_QUOTES, 'UTF-8'); ?>"
            data-folio-aux="<?php echo htmlspecialchars($r['FOLIO_AUXILIAR_EXIEXPORTACION'], ENT_QUOTES, 'UTF-8'); ?>"
            data-estado="<?php echo htmlspecialchars($ESTADO, ENT_QUOTES, 'UTF-8'); ?>"
            data-estado-calidad="<?php echo htmlspecialchars($ESTADOCALIDAD, ENT_QUOTES, 'UTF-8'); ?>"
            data-estandar="<?php echo htmlspecialchars($CODIGOESTANDAR . ' - ' . $NOMBREESTANDAR, ENT_QUOTES, 'UTF-8'); ?>"
            data-productor="<?php echo htmlspecialchars($NOMBREPRODUCTOR, ENT_QUOTES, 'UTF-8'); ?>"
            data-csg="<?php echo htmlspecialchars($CSGPRODUCTOR, ENT_QUOTES, 'UTF-8'); ?>"
            data-especie="<?php echo htmlspecialchars($NOMBRESPECIES, ENT_QUOTES, 'UTF-8'); ?>"
            data-variedad="<?php echo htmlspecialchars($NOMBREVESPECIES, ENT_QUOTES, 'UTF-8'); ?>"
            data-envases="<?php echo htmlspecialchars($r['ENVASE'], ENT_QUOTES, 'UTF-8'); ?>"
            data-neto="<?php echo htmlspecialchars($r['NETO'], ENT_QUOTES, 'UTF-8'); ?>"
            data-promedio="<?php echo htmlspecialchars($PROMEDIO, ENT_QUOTES, 'UTF-8'); ?>"
            data-bruto="<?php echo htmlspecialchars($r['BRUTO'], ENT_QUOTES, 'UTF-8'); ?>"
            data-tmanejo="<?php echo htmlspecialchars($NOMBRETMANEJO, ENT_QUOTES, 'UTF-8'); ?>"
            data-calibre-detalle="<?php echo htmlspecialchars($NOMBRETCALIBRE, ENT_QUOTES, 'UTF-8'); ?>"
            data-embalaje="<?php echo htmlspecialchars($NOMBRETEMBALAJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-stock="<?php echo htmlspecialchars($STOCK, ENT_QUOTES, 'UTF-8'); ?>"
            data-gasificado="<?php echo htmlspecialchars($GASIFICADO, ENT_QUOTES, 'UTF-8'); ?>"
            data-embolsado="<?php echo htmlspecialchars($EMBOLSADO, ENT_QUOTES, 'UTF-8'); ?>"
            data-prefrio="<?php echo htmlspecialchars($PREFRIO, ENT_QUOTES, 'UTF-8'); ?>"
            data-condicion="<?php echo htmlspecialchars($CONDICION, ENT_QUOTES, 'UTF-8'); ?>"
            data-tipo-recepcion="<?php echo htmlspecialchars($TIPORECEPCION, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-recepcion="<?php echo htmlspecialchars($NUMERORECEPCION, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-recepcion="<?php echo htmlspecialchars($FECHARECEPCION, ENT_QUOTES, 'UTF-8'); ?>"
            data-origen="<?php echo htmlspecialchars($ORIGEN, ENT_QUOTES, 'UTF-8'); ?>"
            data-csg-origen="<?php echo htmlspecialchars($CSGCSPORIGEN, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-guia-recepcion="<?php echo htmlspecialchars($NUMEROGUIARECEPCION, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-guia-recepcion="<?php echo htmlspecialchars($FECHAGUIARECEPCION, ENT_QUOTES, 'UTF-8'); ?>"
            data-tipo-proceso="<?php echo htmlspecialchars($TPROCESO, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-proceso="<?php echo htmlspecialchars($NUMEROPROCESO, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-proceso="<?php echo htmlspecialchars($FECHAPROCESO, ENT_QUOTES, 'UTF-8'); ?>"
            data-id-proceso="<?php echo htmlspecialchars($r['ID_PROCESO'], ENT_QUOTES, 'UTF-8'); ?>"
            data-num-repaletizaje="<?php echo htmlspecialchars($NUMEROREPALETIZAJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-repaletizaje="<?php echo htmlspecialchars($FECHAREPALETIZAJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-reembalaje="<?php echo htmlspecialchars($NUMEROREEMBALEJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-reembalaje="<?php echo htmlspecialchars($FECHAREEMBALEJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-tipo-reembalaje="<?php echo htmlspecialchars($TREEMBALAJE, ENT_QUOTES, 'UTF-8'); ?>"
            data-tipo-despacho="<?php echo htmlspecialchars($TDESPACHO, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-despacho="<?php echo htmlspecialchars($NUMERODESPACHO, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-despacho="<?php echo htmlspecialchars($FECHADESPACHO, ENT_QUOTES, 'UTF-8'); ?>"
            data-destino="<?php echo htmlspecialchars($DESTINO, ENT_QUOTES, 'UTF-8'); ?>"
            data-csg-destino="<?php echo htmlspecialchars($CSGCSPDESTINO, ENT_QUOTES, 'UTF-8'); ?>"
            data-num-inspeccion="<?php echo htmlspecialchars($NUMEROINPSAG, ENT_QUOTES, 'UTF-8'); ?>"
            data-fecha-inspeccion="<?php echo htmlspecialchars($FECHAINPSAG, ENT_QUOTES, 'UTF-8'); ?>"
            data-tipo-inspeccion="<?php echo htmlspecialchars($NOMBRETINPSAG, ENT_QUOTES, 'UTF-8'); ?>"
            data-id-inspeccion="<?php echo htmlspecialchars($r['ID_INPSAG'], ENT_QUOTES, 'UTF-8'); ?>"
            data-ingreso="<?php echo htmlspecialchars($r['INGRESO'], ENT_QUOTES, 'UTF-8'); ?>"
            data-modificacion="<?php echo htmlspecialchars($r['MODIFICACION'], ENT_QUOTES, 'UTF-8'); ?>"
            data-referencia="<?php echo htmlspecialchars($NUMEROREFERENCIA, ENT_QUOTES, 'UTF-8'); ?>"
            data-id-recepcion="<?php echo htmlspecialchars($r['ID_RECEPCION'], ENT_QUOTES, 'UTF-8'); ?>"
            data-id-despacho="<?php echo htmlspecialchars($r['ID_DESPACHO2'] ? $r['ID_DESPACHO2'] : $r['ID_DESPACHOEX'], ENT_QUOTES, 'UTF-8'); ?>">
            <i class="mdi mdi-eye"></i> Trazabilidad
        </button>
    </td>
    <td>
        <span class="<?php echo $TRECHAZOCOLOR; ?>">
            <a Onclick="abrirPestana('../../assest/documento/informeTarjasPT.php?parametro=<?php echo $r['FOLIO_AUXILIAR_EXIEXPORTACION']; ?>&&parametro1=<?php echo $r['ID_EMPRESA']; ?>&&parametro2=<?php echo $r['ID_PLANTA']; ?>&&tipo=3');">
                <?php echo $r['FOLIO_AUXILIAR_EXIEXPORTACION']; ?>
            </a>
        </span>
    </td>
                                                                <td><?php echo $r['EMBALADO']; ?></td>
                                                                <td><?php echo $ESTADO; ?></td>
                                                                <td><?php echo $CONDICION; ?></td>
                                                                <td><?php echo $ESTADOCALIDAD; ?></td>
                                                                <td><?php echo $CODIGOESTANDAR; ?></td>
                                                                <td><?php echo $NOMBREESTANDAR; ?></td>
                                                                <td><?php echo $NOMBRETCALIBRE; ?></td>
                                                                <td><?php echo $CSGPRODUCTOR; ?></td>
                                                                <td><?php echo $NOMBREPRODUCTOR; ?></td>
                                                                <td><?php echo $NOMBREVESPECIES . ' (' . $NOMBRESPECIES . ')'; ?></td>
                                                                <td><?php echo $r['ENVASE']; ?></td>
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
                                                                <td><?php echo $NOMBRETINPSAG; ?></td>
                                                                <td><?php echo $NUMERODESPACHO; ?></td>
                                                                <td><?php echo $FECHADESPACHO; ?></td>
                                                                <td><?php echo $NUMEROGUIADESPACHO; ?></td>
                                                                <td><?php echo $TDESPACHO; ?></td>
                                                                <td><?php echo $CSGCSPDESTINO; ?></td>
                                                                <td><?php echo $DESTINO; ?></td>
                                                                <td><?php echo $NOMBRETMANEJO; ?></td>
                                                                <td><?php echo $NOMBRETCALIBRE; ?></td>
                                                                <td><?php echo $NOMBRETEMBALAJE; ?></td>
                                                                <td><?php echo $STOCK; ?></td>
                                                                <td><?php echo $EMBOLSADO; ?></td>
                                                                <td><?php echo $GASIFICADO; ?></td>
                                                                <td><?php echo $PREFRIO; ?></td>
                                                                <td><?php echo $r['DIAS']; ?></td>
                                                                <td><?php echo $r['INGRESO']; ?></td>
                                                                <td><?php echo $r['MODIFICACION']; ?></td>
                                                                <td><?php echo $NUMEROREFERENCIA; ?></td>
                                                            </tr>                                                       
                                                    <?php endforeach; ?>
                                                </tbody>
                                                                                                <tfoot>
                                                    <tr class="text-center" id="filtro">
                                                        <th>Trazabilidad</th>
                                                        <th>Folio Original</th>
                                                        <th>Fecha Embalado </th>
                                                        <th>Estado </th>
                                                        <th>Estado Calidad</th>
                                                        <th>Condición </th>
                                                        <th>Código Estandar</th>
                                                        <th>Envase/Estandar</th>
                                                        <th>Tipo Calibre </th>
                                                        <th>CSG</th>
                                                        <th>Productor</th>
                                                        <th>Variedad</th>
                                                        <th>Cantidad Envase</th>
                                                        <th>Kilos Neto</th>
                                                        <th>% Deshidratacion</th>
                                                        <th>Kilos Deshidratacion</th>
                                                        <th>Kilos Bruto</th>
                                                        <th>Número Recepción </th>
                                                        <th>Fecha Recepción </th>
                                                        <th>Tipo Recepción </th>
                                                        <th>CSG/CSP Recepción</th>
                                                        <th>Origen Recepción </th>
                                                        <th>Número Guía Recepción </th>
                                                        <th>Fecha Guía Recepción</th>
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
                                                        <th>Tipo Inspección </th>
                                                        <th>Número Despacho </th>
                                                        <th>Fecha Despacho </th>
                                                        <th>Número Guía Despacho </th>
                                                        <th>Tipo Despacho </th>
                                                        <th>CSG/CSP Despacho</th>
                                                        <th>Destino Despacho</th>
                                                        <th>Tipo Manejo</th>
                                                        <th>Tipo Calibre (Detalle)</th>
                                                        <th>Tipo Embalaje </th>
                                                        <th>Stock</th>
                                                        <th>Embolsado</th>
                                                        <th>Gasificacion</th>
                                                        <th>Prefrío</th>
                                                        <th>Días</th>
                                                        <th>Ingreso</th>
                                                        <th>Modificación</th>
                                                        <th>Numero Referencia</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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
            <div class="modal fade detalle-modal" id="detalleExistenciaModal" tabindex="-1" role="dialog" aria-labelledby="detalleExistenciaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content detalle-modal">
                    <div class="modal-header">
                        <div>
                            <div class="modal-subtitle">Detalle de existencia</div>
                            <h4 class="modal-title" id="detalleExistenciaModalLabel">Historial</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if ($LOGOEMPRESA) : ?>
                            <div class="detalle-hero">
                                <div class="brand-banner">
                                    <img src="<?php echo $LOGOEMPRESA; ?>" alt="Imagen institucional" />
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="detalle-resumen-table">
                            <table class="detalle-table resumen-table">
                                <thead>
                                    <tr>
                                        <th>Folio original</th>
                                        <th>Folio nuevo</th>
                                        <th>Estado</th>
                                        <th>Condición</th>
                                        <th>Calidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-detail="folio"></td>
                                        <td data-detail="folio-aux"></td>
                                        <td><span class="detalle-badge" data-detail="estado"></span></td>
                                        <td><span class="detalle-badge" data-detail="condicion"></span></td>
                                        <td><span class="detalle-badge detalle-estado-calidad" data-detail="estado-calidad"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="detalle-grid mb-1">
                            <div class="detalle-card">
                                <h5>Identificación</h5>
                                <table class="detalle-table">
                                    <tr>
                                        <th>Estandar</th>
                                        <td data-detail="estandar"></td>
                                    </tr>
                                    <tr>
                                        <th>Especie / Variedad</th>
                                        <td data-detail="especie"></td>
                                    </tr>
                                    <tr>
                                        <th>Envases</th>
                                        <td data-detail="envases"></td>
                                    </tr>
                                    <tr>
                                        <th>Kilos</th>
                                        <td data-detail="kilos"></td>
                                    </tr>
                                    <tr>
                                        <th>Tipo calibre</th>
                                        <td data-detail="calibre-detalle"></td>
                                    </tr>
                                    <tr>
                                        <th>Embalaje</th>
                                        <td data-detail="embalaje"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="detalle-card">
                                <h5>Productor y condición</h5>
                                <table class="detalle-table">
                                    <tr>
                                        <th>Productor</th>
                                        <td data-detail="productor"></td>
                                    </tr>
                                    <tr>
                                        <th>CSG/CSP</th>
                                        <td data-detail="csg"></td>
                                    </tr>
                                    <tr>
                                        <th>Estado calidad</th>
                                        <td data-detail="estado-calidad"></td>
                                    </tr>
                                    <tr>
                                        <th>Inspección</th>
                                        <td data-detail="inspeccion"></td>
                                    </tr>
                                    <tr>
                                        <th>Embolsado</th>
                                        <td data-detail="embolsado"></td>
                                    </tr>
                                    <tr>
                                        <th>Embolsado</th>
                                        <td data-detail="embolsado"></td>
                                    </tr>
                                    <tr>
                                        <th>Condición</th>
                                        <td data-detail="condicion"></td>
                                    </tr>
                                    <tr>
                                        <th>Estado calidad</th>
                                        <td data-detail="estado-calidad"></td>
                                    </tr>
                                    <tr>
                                        <th>Inspección</th>
                                        <td data-detail="inspeccion"></td>
                                    </tr>
                                    <tr>
                                        <th>Embolsado</th>
                                        <td data-detail="embolsado"></td>
                                    </tr>
                                    <tr>
                                        <th>Gasificación</th>
                                        <td data-detail="gasificado"></td>
                                    </tr>
                                    <tr>
                                        <th>Prefrío</th>
                                        <td data-detail="prefrio"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="detalle-card">
                                <h5>Movimientos y fechas</h5>
                                <table class="detalle-table">
                                    <tr>
                                        <th>Recepción</th>
                                        <td data-detail="recepcion"></td>
                                    </tr>
                                    <tr>
                                        <th>Guía recepción</th>
                                        <td data-detail="guia-recepcion"></td>
                                    </tr>
                                    <tr>
                                        <th>Repaletizaje</th>
                                        <td data-detail="repaletizaje"></td>
                                    </tr>
                                    <tr>
                                        <th>Proceso</th>
                                        <td data-detail="proceso"></td>
                                    </tr>
                                    <tr>
                                        <th>Reembalaje</th>
                                        <td data-detail="reembalaje"></td>
                                    </tr>
                                    <tr>
                                        <th>Despacho</th>
                                        <td data-detail="despacho"></td>
                                    </tr>
                                    <tr>
                                        <th>Ingreso</th>
                                        <td data-detail="ingreso"></td>
                                    </tr>
                                    <tr>
                                        <th>Modificación</th>
                                        <td data-detail="modificacion"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="detalle-card">
                                <h5>Calibre y stock</h5>
                                <table class="detalle-table">
                                    <tr>
                                        <th>Tipo calibre</th>
                                        <td data-detail="calibre-detalle"></td>
                                    </tr>
                                    <tr>
                                        <th>Embalaje</th>
                                        <td data-detail="embalaje"></td>
                                    </tr>
                                    <tr>
                                        <th>Stock</th>
                                        <td data-detail="stock"></td>
                                    </tr>
                                    <tr>
                                        <th>Referencia</th>
                                        <td data-detail="referencia"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="exportDetallePdf()">Imprimir Trazabilidad</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

                    <?php include_once "../../assest/config/footer.php"; ?>
                <?php include_once "../../assest/config/menuExtraFruta.php"; ?>
    </div>
    <!- LLAMADA URL DE ARCHIVOS DE DISEÑO Y JQUERY E OTROS -!>
        <?php include_once "../../assest/config/urlBase.php"; ?>
    <script type="text/javascript">
        const LOGO_EMPRESA = "<?php echo htmlspecialchars($LOGOEMPRESA ?? '', ENT_QUOTES, 'UTF-8'); ?>";
        const NOMBRE_EMPRESA = "<?php echo htmlspecialchars($NOMBREEMPRESA ?? '', ENT_QUOTES, 'UTF-8'); ?>";
        let html2PdfLoader;

        function ensureHtml2Pdf() {
            if (window.html2pdf) {
                return Promise.resolve();
            }
            if (html2PdfLoader) {
                return html2PdfLoader;
            }
            html2PdfLoader = new Promise(function(resolve, reject) {
                var script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                script.onload = resolve;
                script.onerror = function() {
                    console.error('No se pudo cargar html2pdf');
                    reject();
                };
                document.head.appendChild(script);
            });
            return html2PdfLoader;
        }

        document.addEventListener('DOMContentLoaded', function() {
            function setDetailWithLink(modal, key, text, url) {
                var container = modal.find('[data-detail="' + key + '"]');
                if (!container.length) {
                    return;
                }
                if (url) {
                    var link = $('<a/>', {
                        class: 'mov-link',
                        href: url,
                        target: '_blank',
                        text: text
                    });
                    container.empty().append(link);
                } else {
                    container.text(text);
                }
            }

            $('#detalleExistenciaModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find('[data-detail="folio"]').text(button.data('folio'));
                modal.find('[data-detail="folio-aux"]').text(button.data('folio-aux'));
                modal.find('[data-detail="estado"]').text(button.data('estado'));
                modal.find('[data-detail="condicion"]').text(button.data('condicion'));
                modal.find('[data-detail="estado-calidad"]').text(button.data('estado-calidad'));
                modal.find('[data-detail="estandar"]').text(button.data('estandar'));
                modal.find('[data-detail="productor"]').text(button.data('productor'));
                modal.find('[data-detail="csg"]').text(button.data('csg'));
                modal.find('[data-detail="especie"]').text(button.data('especie') + ' / ' + button.data('variedad'));
                modal.find('[data-detail="envases"]').text(button.data('envases'));
                modal.find('[data-detail="kilos"]').text('Neto: ' + button.data('neto') + ' | Promedio: ' + button.data('promedio') + ' | Bruto: ' + button.data('bruto'));
                modal.find('[data-detail="tmanejo"]').text(button.data('tmanejo'));
                modal.find('[data-detail="embolsado"]').text(button.data('embolsado'));
                modal.find('[data-detail="gasificado"]').text(button.data('gasificado'));
                modal.find('[data-detail="prefrio"]').text(button.data('prefrio'));
                var recepcionTexto = button.data('tipo-recepcion') + ' #' + button.data('num-recepcion') + ' (' + button.data('fecha-recepcion') + ') ' + button.data('origen') + ' [' + button.data('csg-origen') + ']';
                var recepcionUrl = button.data('id-recepcion') ? '../../fruta/vista/registroRecepcionpt.php?op&id=' + encodeURIComponent(button.data('id-recepcion')) + '&a=ver' : '';
                setDetailWithLink(modal, 'recepcion', recepcionTexto, recepcionUrl);
                modal.find('[data-detail="guia-recepcion"]').text(button.data('num-guia-recepcion') + (button.data('fecha-guia-recepcion') ? ' (' + button.data('fecha-guia-recepcion') + ')' : ''));
                var repaletizajeTexto = button.data('num-repaletizaje') ? '#' + button.data('num-repaletizaje') + (button.data('fecha-repaletizaje') ? ' (' + button.data('fecha-repaletizaje') + ')' : '') : 'Sin datos';
                modal.find('[data-detail="repaletizaje"]').text(repaletizajeTexto);
                var procesoTexto = button.data('tipo-proceso') + ' #' + button.data('num-proceso') + ' (' + button.data('fecha-proceso') + ')';
                var procesoUrl = button.data('id-proceso') ? '../../fruta/vista/registroProceso.php?op&id=' + encodeURIComponent(button.data('id-proceso')) + '&a=ver' : '';
                setDetailWithLink(modal, 'proceso', procesoTexto, procesoUrl);
                var reembalajeTexto = button.data('num-reembalaje') ? button.data('tipo-reembalaje') + ' #' + button.data('num-reembalaje') + (button.data('fecha-reembalaje') ? ' (' + button.data('fecha-reembalaje') + ')' : '') : 'Sin datos';
                modal.find('[data-detail="reembalaje"]').text(reembalajeTexto);
                var despachoTexto = button.data('tipo-despacho') + ' #' + button.data('num-despacho') + ' (' + button.data('fecha-despacho') + ') ' + button.data('destino') + ' [' + button.data('csg-destino') + ']';
                var despachoUrl = button.data('id-despacho') ? '../../fruta/vista/registroDespachopt.php?op&id=' + encodeURIComponent(button.data('id-despacho')) + '&a=ver' : '';
                setDetailWithLink(modal, 'despacho', despachoTexto, despachoUrl);
                var inspeccionTexto = button.data('num-inspeccion') ? '#' + button.data('num-inspeccion') + ' (' + button.data('fecha-inspeccion') + ') ' + button.data('tipo-inspeccion') : 'Sin datos';
                var inspeccionUrl = button.data('id-inspeccion') ? '../../fruta/vista/registroInpsag.php?op&id=' + encodeURIComponent(button.data('id-inspeccion')) + '&a=ver' : '';
                setDetailWithLink(modal, 'inspeccion', inspeccionTexto, inspeccionUrl);
                modal.find('[data-detail="calibre-detalle"]').text(button.data('calibre-detalle'));
                modal.find('[data-detail="embalaje"]').text(button.data('embalaje'));
                modal.find('[data-detail="stock"]').text(button.data('stock'));
                modal.find('[data-detail="referencia"]').text(button.data('referencia'));
                modal.find('[data-detail="ingreso"]').text(button.data('ingreso'));
                modal.find('[data-detail="modificacion"]').text(button.data('modificacion'));
            });
        });

        function imprimirTarja() {
            exportDetallePdf();
        }

        function exportDetallePdf() {
            var modal = document.getElementById('detalleExistenciaModal');
            if (!modal) {
                return;
            }

            var getDetail = function(key) {
                var node = modal.querySelector('[data-detail="' + key + '"]');
                return node ? node.textContent || '' : '';
            };

            var now = new Date();
            var fecha = now.toLocaleDateString();
            var hora = now.toLocaleTimeString();
            var hero = LOGO_EMPRESA ? `<div class="hero"><img src="${LOGO_EMPRESA}" alt="Marca" /></div>` : '';

            var buildRow = function(label, value) {
                return `<tr><th>${label}</th><td>${value || 'Sin Datos'}</td></tr>`;
            };

            var html = `
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8" />
                    <title>Informe trazabilidad</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 18px; color: #0c3972; }
                        .report { border: 1px solid #d2ddec; border-radius: 12px; overflow: hidden; box-shadow: 0 12px 28px rgba(12,57,114,0.08); }
                        .hero { width: 100%; background: #e8f0fb; }
                        .hero img { width: 100%; height: 220px; object-fit: cover; display: block; }
                        .header { padding: 14px 16px; border-bottom: 1px solid #d2ddec; background: #f6f9ff; display:flex; justify-content: space-between; align-items: flex-start; }
                        .header h2 { margin: 0; color: #0b559f; }
                        .header .subtitle { font-size: 13px; color: #4f6483; }
                        .meta { text-align: right; font-size: 12px; color: #4f6483; }
                        .section { padding: 0 16px 10px; }
                        .section h3 { margin: 16px 0 6px; color: #0b559f; border-bottom: 1px solid #c7d6eb; padding-bottom: 6px; font-size: 14px; }
                        table { width: 100%; border-collapse: collapse; font-size: 12px; }
                        th { width: 32%; text-align: left; padding: 7px 8px; background: #f2f6fb; color: #0c3972; border: 1px solid #dfe6f2; }
                        td { padding: 7px 8px; border: 1px solid #dfe6f2; color: #213955; font-weight: 700; }
                        .pill-table th, .pill-table td { text-align: center; }
                        .footer { padding: 10px 16px 16px; text-align: right; color: #4f6483; font-size: 11px; }
                    </style>
                </head>
                <body>
                    <div class="report">
                        ${hero}
                        <div class="header">
                            <div>
                                <div class="subtitle">Historial de existencias de producto terminado</div>
                                <h2>${NOMBRE_EMPRESA || 'Empresa'}</h2>
                                <div class="subtitle">Detalle de trazabilidad</div>
                            </div>
                            <div class="meta">
                                <div>Fecha: ${fecha}</div>
                                <div>Hora: ${hora}</div>
                            </div>
                        </div>
                        <div class="section">
                            <h3>Identificación</h3>
                            <table class="pill-table">
                                <tr>
                                    <th>Folio original</th>
                                    <th>Folio nuevo</th>
                                    <th>Estado</th>
                                    <th>Condición</th>
                                    <th>Estado calidad</th>
                                </tr>
                                <tr>
                                    <td>${getDetail('folio')}</td>
                                    <td>${getDetail('folio-aux')}</td>
                                    <td>${getDetail('estado')}</td>
                                    <td>${getDetail('condicion')}</td>
                                    <td>${getDetail('estado-calidad')}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="section">
                            <h3>Producto y manejo</h3>
                            <table>
                                ${buildRow('Productor', getDetail('productor') + ' (' + getDetail('csg') + ')')}
                                ${buildRow('Variedad', getDetail('especie'))}
                                ${buildRow('Estandar', getDetail('estandar'))}
                                ${buildRow('Manejo', getDetail('tmanejo'))}
                                ${buildRow('Envases', getDetail('envases'))}
                                ${buildRow('Kilos', getDetail('kilos'))}
                                ${buildRow('Calibre', getDetail('calibre-detalle'))}
                                ${buildRow('Embalaje', getDetail('embalaje'))}
                                ${buildRow('Condición', getDetail('condicion'))}
                                ${buildRow('Inspección', getDetail('inspeccion'))}
                                ${buildRow('Estado calidad', getDetail('estado-calidad'))}
                            </table>
                        </div>
                        <div class="section">
                            <h3>Movimientos</h3>
                            <table>
                                ${buildRow('Recepción', getDetail('recepcion'))}
                                ${buildRow('Guía recepción', getDetail('guia-recepcion'))}
                                ${buildRow('Proceso', getDetail('proceso'))}
                                ${buildRow('Repaletizaje', getDetail('repaletizaje'))}
                                ${buildRow('Reembalaje', getDetail('reembalaje'))}
                                ${buildRow('Despacho', getDetail('despacho'))}
                            </table>
                        </div>
                        <div class="section">
                            <h3>Ubicación y fechas</h3>
                            <table>
                                ${buildRow('Stock', getDetail('stock'))}
                                ${buildRow('Referencia', getDetail('referencia'))}
                                ${buildRow('Embolsado', getDetail('embolsado'))}
                                ${buildRow('Gasificación', getDetail('gasificado'))}
                                ${buildRow('Prefrío', getDetail('prefrio'))}
                                ${buildRow('Ingreso', getDetail('ingreso'))}
                                ${buildRow('Modificación', getDetail('modificacion'))}
                            </table>
                        </div>
                        <div class="footer">Informe generado desde historial de existencias</div>
                    </div>
                    <script>
                        window.onload = function(){ window.print(); };
                    <\/script>
                </body>
                </html>
            `;

            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(html);
            printWindow.document.close();
        }
    </script>
</body>

</html>
