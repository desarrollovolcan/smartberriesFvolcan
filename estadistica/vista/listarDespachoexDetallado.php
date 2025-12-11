<?php

include_once "../../assest/config/validarUsuarioOpera.php";


//LLAMADA ARCHIVOS NECESARIOS PARA LAS OPERACIONES
include_once '../../assest/controlador/ESPECIES_ADO.php';
include_once '../../assest/controlador/VESPECIES_ADO.php';
include_once '../../assest/controlador/PRODUCTOR_ADO.php';
include_once '../../assest/controlador/TMANEJO_ADO.php';
include_once '../../assest/controlador/TCALIBRE_ADO.php';
include_once '../../assest/controlador/TEMBALAJE_ADO.php';

include_once '../../assest/controlador/CONDUCTOR_ADO.php';
include_once '../../assest/controlador/TRANSPORTE_ADO.php';
include_once '../../assest/controlador/COMPRADOR_ADO.php';

include_once '../../assest/controlador/TPROCESO_ADO.php';
include_once '../../assest/controlador/TREEMBALAJE_ADO.php';
include_once '../../assest/controlador/PROCESO_ADO.php';
include_once '../../assest/controlador/REEMBALAJE_ADO.php';

include_once '../../assest/controlador/EEXPORTACION_ADO.php';
include_once '../../assest/controlador/EINDUSTRIAL_ADO.php';
include_once '../../assest/controlador/ERECEPCION_ADO.php';

include_once '../../assest/controlador/EXIMATERIAPRIMA_ADO.php';
include_once '../../assest/controlador/RECEPCIONMP_ADO.php';
include_once '../../assest/controlador/DESPACHOMP_ADO.php';
include_once '../../assest/controlador/EXIINDUSTRIAL_ADO.php';
include_once '../../assest/controlador/RECEPCIONIND_ADO.php';
include_once '../../assest/controlador/DESPACHOIND_ADO.php';
include_once '../../assest/controlador/EXIEXPORTACION_ADO.php';
include_once '../../assest/controlador/RECEPCIONPT_ADO.php';
include_once '../../assest/controlador/DESPACHOPT_ADO.php';
include_once '../../assest/controlador/DESPACHOEX_ADO.php';
include_once '../../assest/controlador/REPALETIZAJEEX_ADO.php';

include_once '../../assest/controlador/EMPRESA_ADO.php';
include_once '../../assest/controlador/PLANTA_ADO.php';
include_once '../../assest/controlador/TEMPORADA_ADO.php';

include_once '../../assest/controlador/ICARGA_ADO.php';
include_once '../../assest/controlador/DFINAL_ADO.php';
include_once '../../assest/controlador/RFINAL_ADO.php';
include_once '../../assest/controlador/BROKER_ADO.php';
include_once '../../assest/controlador/MERCADO_ADO.php';

include_once '../../assest/controlador/LDESTINO_ADO.php';
include_once '../../assest/controlador/ADESTINO_ADO.php';
include_once '../../assest/controlador/PDESTINO_ADO.php';


//INCIALIZAR LAS VARIBLES
//INICIALIZAR CONTROLADOR
$ESPECIES_ADO =  new ESPECIES_ADO();
$VESPECIES_ADO =  new VESPECIES_ADO();
$PRODUCTOR_ADO = new PRODUCTOR_ADO();
$TMANEJO_ADO =  new TMANEJO_ADO();
$TCALIBRE_ADO =  new TCALIBRE_ADO();
$TEMBALAJE_ADO =  new TEMBALAJE_ADO();

$CONDUCTOR_ADO =  new CONDUCTOR_ADO();
$TRANSPORTE_ADO =  new TRANSPORTE_ADO();
$COMPRADOR_ADO =  new COMPRADOR_ADO();

$TPROCESO_ADO =  new TPROCESO_ADO();
$TREEMBALAJE_ADO =  new TREEMBALAJE_ADO();
$PROCESO_ADO =  new PROCESO_ADO();
$REEMBALAJE_ADO =  new REEMBALAJE_ADO();

$EEXPORTACION_ADO =  new EEXPORTACION_ADO();
$EINDUSTRIAL_ADO =  new EINDUSTRIAL_ADO();
$ERECEPCION_ADO =  new ERECEPCION_ADO();

$EXIMATERIAPRIMA_ADO =  new EXIMATERIAPRIMA_ADO();
$RECEPCIONMP_ADO =  new RECEPCIONMP_ADO();
$DESPACHOMP_ADO =  new DESPACHOMP_ADO();
$EXIINDUSTRIAL_ADO =  new EXIINDUSTRIAL_ADO();
$RECEPCIONIND_ADO =  new RECEPCIONIND_ADO();
$DESPACHOIND_ADO =  new DESPACHOIND_ADO();
$EXIEXPORTACION_ADO =  new EXIEXPORTACION_ADO();
$RECEPCIONPT_ADO =  new RECEPCIONPT_ADO();
$DESPACHOPT_ADO =  new DESPACHOPT_ADO();
$DESPACHOEX_ADO =  new DESPACHOEX_ADO();
$REPALETIZAJEEX_ADO =  new REPALETIZAJEEX_ADO();

$EMPRESA_ADO = new EMPRESA_ADO();
$PLANTA_ADO = new PLANTA_ADO();
$TEMPORADA_ADO = new TEMPORADA_ADO();

$ICARGA_ADO =  new ICARGA_ADO();
$DFINAL_ADO =  new DFINAL_ADO();
$RFINAL_ADO =  new RFINAL_ADO();
$BROKER_ADO =  new BROKER_ADO();
$MERCADO_ADO =  new MERCADO_ADO();
$LDESTINO_ADO =  new LDESTINO_ADO();
$ADESTINO_ADO =  new ADESTINO_ADO();
$PDESTINO_ADO =  new PDESTINO_ADO();

//INCIALIZAR VARIBALES A OCUPAR PARA LA FUNCIONALIDAD
$TOTALBRUTO = 0;
$TOTALNETO = 0;
$TOTALENVASE = 0;
$FECHADESDE = "";
$FECHAHASTA = "";

$PRODUCTOR = "";
$NUMEROGUIA = "";

//INICIALIZAR ARREGLOS
$ARRAYDESPACHOPT = [];
$ARRAYDESPACHOPTTOTALES = [];
$ARRAYVEREMPRESA = [];
$ARRAYVERPRODUCTOR = [];
$ARRAYVERTRANSPORTE = [];
$ARRAYVERCONDUCTOR = [];
$ARRAYMGUIAPT = [];
$ARRAYRECEPCIONMPORIGEN1 = [];
$ARRAYRECEPCIONMPORIGEN2 = [];
$ARRAYDESPACHOEX = [];

$ARRAYEMPRESAS = $EMPRESA_ADO->listarEmpresaCBX();
$EMPRESABUSCAR = "";
if (isset($_REQUEST['EMPRESA']) && $_REQUEST['EMPRESA'] != "") {
    $EMPRESABUSCAR = $_REQUEST['EMPRESA'];
}

//CACHE DE CONSULTAS
$CACHE_EMPRESA = [];
$CACHE_PLANTA = [];
$CACHE_TEMPORADA = [];
$CACHE_TRANSPORTE = [];
$CACHE_CONDUCTOR = [];
$CACHE_DFINAL = [];
$CACHE_ICARGA = [];
$CACHE_LDESTINO = [];
$CACHE_ADESTINO = [];
$CACHE_PDESTINO = [];
$CACHE_MERCADO = [];
$CACHE_RFINAL = [];
$CACHE_BROKER = [];
$CACHE_PRODUCTOR = [];
$CACHE_VESPECIES = [];
$CACHE_ESPECIES = [];
$CACHE_EEXPORTACION = [];
$CACHE_TMANEJO = [];
$CACHE_TCALIBRE = [];
$CACHE_TEMBALAJE = [];
$CACHE_RECEPCIONPT = [];
$CACHE_DESPACHOPT = [];
$CACHE_PLANTA2 = [];
$CACHE_PROCESO = [];
$CACHE_TPROCESO = [];
$CACHE_REEMBALAJE = [];
$CACHE_TREEMBALAJE = [];
$CACHE_REPALETIZAJE = [];
$CACHE_FOLIO = [];
$CACHE_RECEPCIONMPPROC = [];
$CACHE_RECEPCIONMPREEMB = [];


//DEFINIR ARREGLOS CON LOS DATOS OBTENIDOS DE LAS FUNCIONES DE LOS CONTROLADORES
if ($EMPRESABUSCAR && $TEMPORADAS) {
    $ARRAYDESPACHOEX = $DESPACHOEX_ADO->listarDespachoexEmpresaTemporadaCBX($EMPRESABUSCAR, $TEMPORADAS);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Detallado Despacho Expo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- LLAMADA DE LOS ARCHIVOS NECESARIOS PARA DISEÑO Y FUNCIONES BASE DE LA VISTA -->
    <?php include_once "../../assest/config/urlHead.php"; ?>
    <!-- FUNCIONES BASES -->
    <script type="text/javascript">
        //REDIRECCIONAR A LA PAGINA SELECIONADA
        function irPagina(url) {
            location.href = "" + url;
        }

        function refrescar() {
            document.getElementById("form_reg_dato").submit();
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
        <?php include_once "../../assest/config/menuOpera.php"; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-full">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="page-title">Detallado </h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page">Módulo</li>
                                        <li class="breadcrumb-item" aria-current="page">Detallado</li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            <a href="#"> Detallado Despacho Expo </a>
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
                            <form method="post" id="form_reg_dato">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Empresa</label>
                                            <select class="form-control select2" name="EMPRESA" id="EMPRESA" onchange="refrescar()">
                                                <option value="">Seleccione la empresa</option>
                                                <?php foreach ($ARRAYEMPRESAS as $E) : ?>
                                                    <option value="<?php echo $E['ID_EMPRESA']; ?>" <?php if ($EMPRESABUSCAR == $E['ID_EMPRESA']) {  echo 'selected'; } ?>>
                                                        <?php echo $E['NOMBRE_EMPRESA']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="detalladodex" class="table-hover" style="width: 100%;">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Número Referencia </th>
                                                    <th>Cliente</th>
                                                    <th>Mercado </th>
                                                    <th>Contenedor </th>
                                                    <th>Tipo Despacho </th>
                                                    <th>Número Despacho </th>
                                                    <th>Fecha Despacho </th>
                                                    <th>Número Guía Despacho </th>
                                                    <th>Destino </th>
                                                    <th>Fecha Corte Documental </th>
                                                    <th>Fecha ETD </th>
                                                    <th>Fecha ETA</th>
                                                    <th>Fecha Real ETA</th>
                                                    <th>Recibidor Final</th>
                                                    <th>Tipo Embarque</th>
                                                    <th>Nave</th>
                                                    <th>Número Viaje/Vuelo</th>
                                                    <th>Puerto/Aeropuerto/Lugar Destino</th>
                                                    <th>N° Folio Original</th>
                                                    <th>N° Folio </th>
                                                    <th>Fecha Embalado </th>
                                                    <th>Condición </th>
                                                    <th>Código Estandar</th>
                                                    <th>Envase/Estandar</th>
                                                    <th>CSG</th>
                                                    <th>Productor</th>
                                                    <th>Especies</th>
                                                    <th>Variedad</th>
                                                    <th>Cantidad Envase</th>
                                                    <th>Kilos Neto</th>
                                                    <th>% Deshidratacion</th>
                                                    <th>Kilos Deshidratacion</th>
                                                    <th>Kilos Bruto</th>
                                                    <th>Número Repaletizaje </th>
                                                    <th>Fecha Repaletizaje </th>
                                                    <th>Número Proceso </th>
                                                    <th>Fecha Proceso </th>
                                                    <th>Tipo Proceso </th>
                                                    <th>Número Reembalaje </th>
                                                    <th>Fecha Reembalaje </th>
                                                    <th>Tipo Reembalaje </th>
                                                    <th>Tipo Manejo</th>
                                                    <th>Tipo Calibre </th>
                                                    <th>Tipo Embalaje </th>
                                                    <th>Stock</th>
                                                    <th>Embolsado</th>
                                                    <th>Gasificacion</th>
                                                    <th>Prefrío</th>
                                                    <th>Transporte </th>
                                                    <th>Nombre Conductor </th>
                                                    <th>Patente Camión </th>
                                                    <th>Patente Carro </th>
                                                    <th>Semana Despacho </th>
                                                    <th>Semana Guía </th>
                                                    <th>Empresa</th>
                                                    <th>Planta</th>
                                                    <th>Temporada</th>
                                                    <th>Bl/AWB</th>
                                                    <th>Número Recepción </th>
                                                    <th>Fecha Recepción </th>
                                                    <th>Tipo Recepción </th>
                                                    <th>Número Guía Recepción </th>
                                                    <th>Fecha Guía Recepción</th>
                                                    <th>Número Recepción MP</th>
                                                    <th>Fecha Recepción MP</th>
                                                    <th>Tipo Recepción MP</th>
                                                    <th>Número Guía Recepción MP</th>
                                                    <th>Fecha Guía Recepción MP </th>
                                                    <th>Planta Recepción MP</th>
                                                    <th>Termógrafo Despacho</th>
                                                    <th>Termógrafo Pallet</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ARRAYDESPACHOEX as $r) : ?>
                                                    <?php
                                                    // Transporte y conductor
                                                    $transporteId = $r['ID_TRANSPORTE'];
                                                    if ($transporteId && !isset($CACHE_TRANSPORTE[$transporteId])) {
                                                        $CACHE_TRANSPORTE[$transporteId] = $TRANSPORTE_ADO->verTransporte($transporteId);
                                                    }
                                                    $ARRAYVERTRANSPORTE = $transporteId ? $CACHE_TRANSPORTE[$transporteId] : [];
                                                    if ($ARRAYVERTRANSPORTE) {
                                                        $NOMBRETRANSPORTE = $ARRAYVERTRANSPORTE[0]['NOMBRE_TRANSPORTE'];
                                                    } else {
                                                        $NOMBRETRANSPORTE = "Sin Datos";
                                                    }

                                                    $conductorId = $r['ID_CONDUCTOR'];
                                                    if ($conductorId && !isset($CACHE_CONDUCTOR[$conductorId])) {
                                                        $CACHE_CONDUCTOR[$conductorId] = $CONDUCTOR_ADO->verConductor($conductorId);
                                                    }
                                                    $ARRAYVERCONDUCTOR = $conductorId ? $CACHE_CONDUCTOR[$conductorId] : [];
                                                    if ($ARRAYVERCONDUCTOR) {
                                                        $NOMBRECONDUCTOR = $ARRAYVERCONDUCTOR[0]['NOMBRE_CONDUCTOR'];
                                                    } else {
                                                        $NOMBRECONDUCTOR = "Sin Datos";
                                                    }

                                                    // Destino final
                                                    $dfinalId = $r['ID_DFINAL'];
                                                    if ($dfinalId && !isset($CACHE_DFINAL[$dfinalId])) {
                                                        $CACHE_DFINAL[$dfinalId] = $DFINAL_ADO->verDfinal($dfinalId);
                                                    }
                                                    $ARRAYDFINAL = $dfinalId ? $CACHE_DFINAL[$dfinalId] : [];
                                                    if ($ARRAYDFINAL) {
                                                        $DESTINO = $ARRAYDFINAL[0]['NOMBRE_DFINAL'];
                                                    } else {
                                                        $DESTINO = "Sin Datos";
                                                    }

                                                    // Empresa, planta, temporada
                                                    $empresaId = $r['ID_EMPRESA'];
                                                    if ($empresaId && !isset($CACHE_EMPRESA[$empresaId])) {
                                                        $CACHE_EMPRESA[$empresaId] = $EMPRESA_ADO->verEmpresa($empresaId);
                                                    }
                                                    $ARRAYEMPRESA = $empresaId ? $CACHE_EMPRESA[$empresaId] : [];
                                                    if ($ARRAYEMPRESA) {
                                                        $NOMBREEMPRESA = $ARRAYEMPRESA[0]['NOMBRE_EMPRESA'];
                                                    } else {
                                                        $NOMBREEMPRESA = "Sin Datos";
                                                    }

                                                    $plantaId = $r['ID_PLANTA'];
                                                    if ($plantaId && !isset($CACHE_PLANTA[$plantaId])) {
                                                        $CACHE_PLANTA[$plantaId] = $PLANTA_ADO->verPlanta($plantaId);
                                                    }
                                                    $ARRAYPLANTA = $plantaId ? $CACHE_PLANTA[$plantaId] : [];
                                                    if ($ARRAYPLANTA) {
                                                        $NOMBREPLANTA = $ARRAYPLANTA[0]['NOMBRE_PLANTA'];
                                                    } else {
                                                        $NOMBREPLANTA = "Sin Datos";
                                                    }

                                                    $temporadaId = $r['ID_TEMPORADA'];
                                                    if ($temporadaId && !isset($CACHE_TEMPORADA[$temporadaId])) {
                                                        $CACHE_TEMPORADA[$temporadaId] = $TEMPORADA_ADO->verTemporada($temporadaId);
                                                    }
                                                    $ARRAYTEMPORADA = $temporadaId ? $CACHE_TEMPORADA[$temporadaId] : [];
                                                    if ($ARRAYTEMPORADA) {
                                                        $NOMBRETEMPORADA = $ARRAYTEMPORADA[0]['NOMBRE_TEMPORADA'];
                                                    } else {
                                                        $NOMBRETEMPORADA = "Sin Datos";
                                                    }

                                                    // Termógrafo de despacho
                                                    $TERMOGRAFODESPACHOEX = $r['TERMOGRAFO_DESPACHOEX'];

                                                    // Datos de ICARGA (si existe)
                                                    $icargaId = $r["ID_ICARGA"];
                                                    if ($icargaId && !isset($CACHE_ICARGA[$icargaId])) {
                                                        $CACHE_ICARGA[$icargaId] = $ICARGA_ADO->verIcarga($icargaId);
                                                    }
                                                    $ARRAYICARGA = $icargaId ? $CACHE_ICARGA[$icargaId] : [];
                                                    if ($ARRAYICARGA) {
                                                        $NUMEROREFERENCIA   = $ARRAYICARGA[0]['NREFERENCIA_ICARGA'];
                                                        $NUMEROBL    = $ARRAYICARGA[0]['CRT_ICARGA'];
                                                        $FECHAETD           = $ARRAYICARGA[0]['FECHAETD_ICARGA'];
                                                        $FECHAETA           = $ARRAYICARGA[0]['FECHAETA_ICARGA'];
                                                        $FECHAETAREAL       = $ARRAYICARGA[0]['FECHAETAREAL_ICARGA'];
                                                        $FECHACDOCUMENTAL   = $ARRAYICARGA[0]['FECHA_CDOCUMENTAL_ICARGA'];

                                                        if ($ARRAYICARGA[0]['TEMBARQUE_ICARGA'] == "1") {
                                                            $TEMBARQUE = "Terrestre";
                                                            $NVIAJE = "No Aplica";
                                                            $NAVE   = "No Aplica";
                                                            $ldestinoId = $ARRAYICARGA[0]['ID_LDESTINO'];
                                                            if ($ldestinoId && !isset($CACHE_LDESTINO[$ldestinoId])) {
                                                                $CACHE_LDESTINO[$ldestinoId] = $LDESTINO_ADO->verLdestino($ldestinoId);
                                                            }
                                                            $ARRAYLDESTINO = $ldestinoId ? $CACHE_LDESTINO[$ldestinoId] : [];
                                                            if ($ARRAYLDESTINO) {
                                                                $NOMBREDESTINO = $ARRAYLDESTINO[0]["NOMBRE_LDESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }
                                                        if ($ARRAYICARGA[0]['TEMBARQUE_ICARGA'] == "2") {
                                                            $TEMBARQUE = "Aereo";
                                                            $NAVE = $ARRAYICARGA[0]['NAVE_ICARGA'];
                                                            $NVIAJE = $ARRAYICARGA[0]['NVIAJE_ICARGA'];
                                                            $adestinoId = $ARRAYICARGA[0]['ID_ADESTINO'];
                                                            if ($adestinoId && !isset($CACHE_ADESTINO[$adestinoId])) {
                                                                $CACHE_ADESTINO[$adestinoId] = $ADESTINO_ADO->verAdestino($adestinoId);
                                                            }
                                                            $ARRAYADESTINO = $adestinoId ? $CACHE_ADESTINO[$adestinoId] : [];
                                                            if ($ARRAYADESTINO) {
                                                                $NOMBREDESTINO = $ARRAYADESTINO[0]["NOMBRE_ADESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }
                                                        if ($ARRAYICARGA[0]['TEMBARQUE_ICARGA'] == "3") {
                                                            $TEMBARQUE = "Maritimo";
                                                            $NAVE  = $ARRAYICARGA[0]['NAVE_ICARGA'];
                                                            $NVIAJE = $ARRAYICARGA[0]['NVIAJE_ICARGA'];
                                                            $pdestinoId = $ARRAYICARGA[0]['ID_PDESTINO'];
                                                            if ($pdestinoId && !isset($CACHE_PDESTINO[$pdestinoId])) {
                                                                $CACHE_PDESTINO[$pdestinoId] = $PDESTINO_ADO->verPdestino($pdestinoId);
                                                            }
                                                            $ARRAYPDESTINO = $pdestinoId ? $CACHE_PDESTINO[$pdestinoId] : [];
                                                            if ($ARRAYPDESTINO) {
                                                                $NOMBREDESTINO = $ARRAYPDESTINO[0]["NOMBRE_PDESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }

                                                        $mercadoId = $ARRAYICARGA[0]["ID_MERCADO"];
                                                        if ($mercadoId && !isset($CACHE_MERCADO[$mercadoId])) {
                                                            $CACHE_MERCADO[$mercadoId] = $MERCADO_ADO->verMercado($mercadoId);
                                                        }
                                                        $ARRAYMERCADO = $mercadoId ? $CACHE_MERCADO[$mercadoId] : [];
                                                        if ($ARRAYMERCADO) {
                                                            $NOMBREMERCADO = $ARRAYMERCADO[0]["NOMBRE_MERCADO"];
                                                        } else {
                                                            $NOMBREMERCADO = "Sin Datos";
                                                        }

                                                        $rfinalId = $ARRAYICARGA[0]["ID_RFINAL"];
                                                        if ($rfinalId && !isset($CACHE_RFINAL[$rfinalId])) {
                                                            $CACHE_RFINAL[$rfinalId] = $RFINAL_ADO->verRfinal($rfinalId);
                                                        }
                                                        $ARRAYRFINAL = $rfinalId ? $CACHE_RFINAL[$rfinalId] : [];
                                                        if ($ARRAYRFINAL) {
                                                            $NOMBRERFINAL = $ARRAYRFINAL[0]["NOMBRE_RFINAL"];
                                                        } else {
                                                            $NOMBRERFINAL = "Sin Datos";
                                                        }

                                                        $brokerId = $ARRAYICARGA[0]["ID_BROKER"];
                                                        if ($brokerId && !isset($CACHE_BROKER[$brokerId])) {
                                                            $CACHE_BROKER[$brokerId] = $BROKER_ADO->verBroker($brokerId);
                                                        }
                                                        $ARRAYBROKER = $brokerId ? $CACHE_BROKER[$brokerId] : [];
                                                        if ($ARRAYBROKER) {
                                                            $NOMBREBROKER = $ARRAYBROKER[0]["NOMBRE_BROKER"];
                                                        } else {
                                                            $NOMBREBROKER = "Sin Datos";
                                                        }
                                                    } else {
                                                        $NUMEROREFERENCIA = "No Aplica";
                                                        $NOMBREBROKER = "No Aplica";
                                                        $NUMEROBL = "No Aplica";
                                                        $FECHAETD = $r['FECHAETD_DESPACHOEX'];
                                                        $FECHAETA = $r['FECHAETA_DESPACHOEX'];
                                                        $FECHAETAREAL = "";
                                                        $FECHACDOCUMENTAL = "";
                                                        if ($r['TEMBARQUE_DESPACHOEX'] == "1") {
                                                            $TEMBARQUE = "Terrestre";
                                                            $NVIAJE = "No Aplica";
                                                            $NAVE = "No Aplica";
                                                            $ldestinoId = $r['ID_LDESTINO'];
                                                            if ($ldestinoId && !isset($CACHE_LDESTINO[$ldestinoId])) {
                                                                $CACHE_LDESTINO[$ldestinoId] = $LDESTINO_ADO->verLdestino($ldestinoId);
                                                            }
                                                            $ARRAYLDESTINO = $ldestinoId ? $CACHE_LDESTINO[$ldestinoId] : [];
                                                            if ($ARRAYLDESTINO) {
                                                                $NOMBREDESTINO = $ARRAYLDESTINO[0]["NOMBRE_LDESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }
                                                        if ($r['TEMBARQUE_DESPACHOEX'] == "2") {
                                                            $TEMBARQUE = "Aereo";
                                                            $NAVE = $r['NAVE_DESPACHOEX'];
                                                            $NVIAJE = $r['NVIAJE_DESPACHOEX'];
                                                            $adestinoId = $r['ID_ADESTINO'];
                                                            if ($adestinoId && !isset($CACHE_ADESTINO[$adestinoId])) {
                                                                $CACHE_ADESTINO[$adestinoId] = $ADESTINO_ADO->verAdestino($adestinoId);
                                                            }
                                                            $ARRAYADESTINO = $adestinoId ? $CACHE_ADESTINO[$adestinoId] : [];
                                                            if ($ARRAYADESTINO) {
                                                                $NOMBREDESTINO = $ARRAYADESTINO[0]["NOMBRE_ADESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }
                                                        if ($r['TEMBARQUE_DESPACHOEX'] == "3") {
                                                            $TEMBARQUE = "Maritimo";
                                                            $NAVE  = $r['NAVE_DESPACHOEX'];
                                                            $NVIAJE = $r['NVIAJE_DESPACHOEX'];
                                                            $pdestinoId = $r['ID_PDESTINO'];
                                                            if ($pdestinoId && !isset($CACHE_PDESTINO[$pdestinoId])) {
                                                                $CACHE_PDESTINO[$pdestinoId] = $PDESTINO_ADO->verPdestino($pdestinoId);
                                                            }
                                                            $ARRAYPDESTINO = $pdestinoId ? $CACHE_PDESTINO[$pdestinoId] : [];
                                                            if ($ARRAYPDESTINO) {
                                                                $NOMBREDESTINO = $ARRAYPDESTINO[0]["NOMBRE_PDESTINO"];
                                                            } else {
                                                                $NOMBREDESTINO = "Sin Datos";
                                                            }
                                                        }
                                                        $mercadoId = $r["ID_MERCADO"];
                                                        if ($mercadoId && !isset($CACHE_MERCADO[$mercadoId])) {
                                                            $CACHE_MERCADO[$mercadoId] = $MERCADO_ADO->verMercado($mercadoId);
                                                        }
                                                        $ARRAYMERCADO = $mercadoId ? $CACHE_MERCADO[$mercadoId] : [];
                                                        if ($ARRAYMERCADO) {
                                                            $NOMBREMERCADO = $ARRAYMERCADO[0]["NOMBRE_MERCADO"];
                                                        } else {
                                                            $NOMBREMERCADO = "Sin Datos";
                                                        }
                                                        $rfinalId = $r["ID_RFINAL"];
                                                        if ($rfinalId && !isset($CACHE_RFINAL[$rfinalId])) {
                                                            $CACHE_RFINAL[$rfinalId] = $RFINAL_ADO->verRfinal($rfinalId);
                                                        }
                                                        $ARRAYRFINAL = $rfinalId ? $CACHE_RFINAL[$rfinalId] : [];
                                                        if ($ARRAYRFINAL) {
                                                            $NOMBRERFINAL = $ARRAYRFINAL[0]["NOMBRE_RFINAL"];
                                                        } else {
                                                            $NOMBRERFINAL = "Sin Datos";
                                                        }
                                                    }

                                                    // Existencias del despacho
                                                    $ARRAYTOMADOEX = $EXIEXPORTACION_ADO->buscarPordespachoEx($r['ID_DESPACHOEX']);
                                                    ?>

                                                    <?php foreach ($ARRAYTOMADOEX as $s) : ?>
                                                        <?php
                                                        // Estado SAG
                                                        if ($s['TESTADOSAG'] == null || $s['TESTADOSAG'] == "0") {
                                                            $ESTADOSAG = "Sin Condición";
                                                        }
                                                        if ($s['TESTADOSAG'] == "1") {
                                                            $ESTADOSAG = "En Inspección";
                                                        }
                                                        if ($s['TESTADOSAG'] == "2") {
                                                            $ESTADOSAG = "Aprobado Origen";
                                                        }
                                                        if ($s['TESTADOSAG'] == "3") {
                                                            $ESTADOSAG = "Aprobado USLA";
                                                        }
                                                        if ($s['TESTADOSAG'] == "4") {
                                                            $ESTADOSAG = "Fumigado";
                                                        }
                                                        if ($s['TESTADOSAG'] == "5") {
                                                            $ESTADOSAG = "Rechazado";
                                                        }

                                                        // Productor
                                                        $productorId = $s['ID_PRODUCTOR'];
                                                        if ($productorId && !isset($CACHE_PRODUCTOR[$productorId])) {
                                                            $CACHE_PRODUCTOR[$productorId] = $PRODUCTOR_ADO->verProductor($productorId);
                                                        }
                                                        $ARRAYVERPRODUCTORID = $productorId ? $CACHE_PRODUCTOR[$productorId] : [];
                                                        if ($ARRAYVERPRODUCTORID) {
                                                            $CSGPRODUCTOR = $ARRAYVERPRODUCTORID[0]['CSG_PRODUCTOR'];
                                                            $NOMBREPRODUCTOR = $ARRAYVERPRODUCTORID[0]['NOMBRE_PRODUCTOR'];
                                                        } else {
                                                            $CSGPRODUCTOR = "Sin Datos";
                                                            $NOMBREPRODUCTOR = "Sin Datos";
                                                        }

                                                        // Variedad / especie
                                                        $vespeciesId = $s['ID_VESPECIES'];
                                                        if ($vespeciesId && !isset($CACHE_VESPECIES[$vespeciesId])) {
                                                            $CACHE_VESPECIES[$vespeciesId] = $VESPECIES_ADO->verVespecies($vespeciesId);
                                                        }
                                                        $ARRAYVERVESPECIESID = $vespeciesId ? $CACHE_VESPECIES[$vespeciesId] : [];
                                                        if ($ARRAYVERVESPECIESID) {
                                                            $NOMBREVARIEDAD = $ARRAYVERVESPECIESID[0]['NOMBRE_VESPECIES'];
                                                            $especiesId = $ARRAYVERVESPECIESID[0]['ID_ESPECIES'];
                                                            if ($especiesId && !isset($CACHE_ESPECIES[$especiesId])) {
                                                                $CACHE_ESPECIES[$especiesId] = $ESPECIES_ADO->verEspecies($especiesId);
                                                            }
                                                            $ARRAYVERESPECIESID = $especiesId ? $CACHE_ESPECIES[$especiesId] : [];
                                                            if ($ARRAYVERESPECIESID) {
                                                                $NOMBRESPECIES = $ARRAYVERESPECIESID[0]['NOMBRE_ESPECIES'];
                                                            } else {
                                                                $NOMBRESPECIES = "Sin Datos";
                                                            }
                                                        } else {
                                                            $NOMBREVARIEDAD = "Sin Datos";
                                                            $NOMBRESPECIES = "Sin Datos";
                                                        }

                                                        // Estandar
                                                        $estandarId = $s['ID_ESTANDAR'];
                                                        if ($estandarId && !isset($CACHE_EEXPORTACION[$estandarId])) {
                                                            $CACHE_EEXPORTACION[$estandarId] = $EEXPORTACION_ADO->verEstandar($estandarId);
                                                        }
                                                        $ARRAYEVERERECEPCIONID = $estandarId ? $CACHE_EEXPORTACION[$estandarId] : [];
                                                        if ($ARRAYEVERERECEPCIONID) {
                                                            $CODIGOESTANDAR = $ARRAYEVERERECEPCIONID[0]['CODIGO_ESTANDAR'];
                                                            $NOMBREESTANDAR = $ARRAYEVERERECEPCIONID[0]['NOMBRE_ESTANDAR'];
                                                        } else {
                                                            $NOMBREESTANDAR = "Sin Datos";
                                                            $CODIGOESTANDAR = "Sin Datos";
                                                        }

                                                        // Tipo manejo / calibre / embalaje
                                                        $tmanejoId = $s['ID_TMANEJO'];
                                                        if ($tmanejoId && !isset($CACHE_TMANEJO[$tmanejoId])) {
                                                            $CACHE_TMANEJO[$tmanejoId] = $TMANEJO_ADO->verTmanejo($tmanejoId);
                                                        }
                                                        $ARRAYTMANEJO = $tmanejoId ? $CACHE_TMANEJO[$tmanejoId] : [];
                                                        if ($ARRAYTMANEJO) {
                                                            $NOMBRETMANEJO = $ARRAYTMANEJO[0]['NOMBRE_TMANEJO'];
                                                        } else {
                                                            $NOMBRETMANEJO = "Sin Datos";
                                                        }

                                                        $tcalibreId = $s['ID_TCALIBRE'];
                                                        if ($tcalibreId && !isset($CACHE_TCALIBRE[$tcalibreId])) {
                                                            $CACHE_TCALIBRE[$tcalibreId] = $TCALIBRE_ADO->verCalibre($tcalibreId);
                                                        }
                                                        $ARRAYTCALIBRE = $tcalibreId ? $CACHE_TCALIBRE[$tcalibreId] : [];
                                                        if ($ARRAYTCALIBRE) {
                                                            $NOMBRETCALIBRE = $ARRAYTCALIBRE[0]['NOMBRE_TCALIBRE'];
                                                        } else {
                                                            $NOMBRETCALIBRE = "Sin Datos";
                                                        }

                                                        $tembalajeId = $s['ID_TEMBALAJE'];
                                                        if ($tembalajeId && !isset($CACHE_TEMBALAJE[$tembalajeId])) {
                                                            $CACHE_TEMBALAJE[$tembalajeId] = $TEMBALAJE_ADO->verEmbalaje($tembalajeId);
                                                        }
                                                        $ARRAYTEMBALAJE = $tembalajeId ? $CACHE_TEMBALAJE[$tembalajeId] : [];
                                                        if ($ARRAYTEMBALAJE) {
                                                            $NOMBRETEMBALAJE = $ARRAYTEMBALAJE[0]['NOMBRE_TEMBALAJE'];
                                                        } else {
                                                            $NOMBRETEMBALAJE = "Sin Datos";
                                                        }

                                                        // Embolsado
                                                        if ($s['EMBOLSADO'] == "1") {
                                                            $EMBOLSADO = "SI";
                                                        }
                                                        if ($s['EMBOLSADO'] == "0") {
                                                            $EMBOLSADO = "NO";
                                                        }

                                                        // Stock
                                                        if ($s['STOCK'] != "") {
                                                            $STOCK = $s['STOCK'];
                                                        } else {
                                                            $STOCK = "Sin Datos";
                                                        }

                                                        // Gasificado
                                                        if ($s['GASIFICADO'] == "1") {
                                                            $GASIFICADO = "SI";
                                                        } else if ($s['GASIFICADO'] == "0") {
                                                            $GASIFICADO = "NO";
                                                        } else {
                                                            $GASIFICADO = "Sin Datos";
                                                        }

                                                        // Prefrío
                                                        if ($s['PREFRIO'] == "0") {
                                                            $PREFRIO = "NO";
                                                        } else if ($s['PREFRIO'] == "1") {
                                                            $PREFRIO = "SI";
                                                        } else {
                                                            $PREFRIO = "Sin Datos";
                                                        }

                                                        // Recepción PT / despacho interplanta
                                                        $recepcionId = $s['ID_RECEPCION'];
                                                        $despacho2Id = $s['ID_DESPACHO2'];
                                                        if ($recepcionId && !isset($CACHE_RECEPCIONPT[$recepcionId])) {
                                                            $CACHE_RECEPCIONPT[$recepcionId] = $RECEPCIONPT_ADO->verRecepcion2($recepcionId);
                                                        }
                                                        if ($despacho2Id && !isset($CACHE_DESPACHOPT[$despacho2Id])) {
                                                            $CACHE_DESPACHOPT[$despacho2Id] = $DESPACHOPT_ADO->verDespachopt($despacho2Id);
                                                        }
                                                        $ARRAYRECEPCION = $recepcionId ? $CACHE_RECEPCIONPT[$recepcionId] : [];
                                                        $ARRAYDESPACHO2 = $despacho2Id ? $CACHE_DESPACHOPT[$despacho2Id] : [];
                                                        if ($ARRAYRECEPCION) {
                                                            $NUMERORECEPCION = $ARRAYRECEPCION[0]["NUMERO_RECEPCION"];
                                                            $FECHARECEPCION = $ARRAYRECEPCION[0]["FECHA"];
                                                            $NUMEROGUIARECEPCION = $ARRAYRECEPCION[0]["NUMERO_GUIA_RECEPCION"];
                                                            $FECHAGUIARECEPCION = $ARRAYRECEPCION[0]["GUIA"];
                                                            if ($ARRAYRECEPCION[0]["TRECEPCION"] == 1) {
                                                                $TIPORECEPCION = "Desde Productor";
                                                            }
                                                            if ($ARRAYRECEPCION[0]["TRECEPCION"] == 2) {
                                                                $TIPORECEPCION = "Planta Externa";
                                                            }
                                                        } else if ($ARRAYDESPACHO2) {
                                                            $NUMERORECEPCION = $ARRAYDESPACHO2[0]["NUMERO_DESPACHO"];
                                                            $FECHARECEPCION = $ARRAYDESPACHO2[0]["FECHA"];
                                                            $NUMEROGUIARECEPCION = $ARRAYDESPACHO2[0]["NUMERO_GUIA_DESPACHO"];
                                                            $TIPORECEPCION = "Interplanta";
                                                            $FECHAGUIARECEPCION = "";
                                                            $planta2Id = $ARRAYDESPACHO2[0]['ID_PLANTA'];
                                                            if ($planta2Id && !isset($CACHE_PLANTA2[$planta2Id])) {
                                                                $CACHE_PLANTA2[$planta2Id] = $PLANTA_ADO->verPlanta($planta2Id);
                                                            }
                                                            $ARRAYPLANTA2 = $planta2Id ? $CACHE_PLANTA2[$planta2Id] : [];
                                                            if ($ARRAYPLANTA2) {
                                                                $ORIGEN = $ARRAYPLANTA2[0]['NOMBRE_PLANTA'];
                                                                $CSGCSPORIGEN = $ARRAYPLANTA2[0]['CODIGO_SAG_PLANTA'];
                                                            } else {
                                                                $ORIGEN = "Sin Datos";
                                                                $CSGCSPORIGEN = "Sin Datos";
                                                            }
                                                        } else {
                                                            $NUMERORECEPCION = "Sin Datos";
                                                            $FECHARECEPCION = "";
                                                            $NUMEROGUIARECEPCION = "Sin Datos";
                                                            $FECHAGUIARECEPCION = "";
                                                            $TIPORECEPCION = "Sin Datos";
                                                        }

                                                        // Proceso
                                                        $procesoId = $s['ID_PROCESO'];
                                                        if ($procesoId && !isset($CACHE_PROCESO[$procesoId])) {
                                                            $CACHE_PROCESO[$procesoId] = $PROCESO_ADO->verProceso2($procesoId);
                                                        }
                                                        $ARRAYPROCESO = $procesoId ? $CACHE_PROCESO[$procesoId] : [];
                                                        if ($ARRAYPROCESO) {
                                                            $NUMEROPROCESO = $ARRAYPROCESO[0]["NUMERO_PROCESO"];
                                                            $FECHAPROCESO = $ARRAYPROCESO[0]["FECHA"];
                                                            $PORCENTAJEEXPO = number_format($ARRAYPROCESO[0]["PDEXPORTACION_PROCESO"], 2);
                                                            $PORCENTAJEINDUSTRIAL = number_format($ARRAYPROCESO[0]["PDINDUSTRIAL_PROCESO"], 2);
                                                            $PORCENTAJETOTAL = number_format($ARRAYPROCESO[0]["PORCENTAJE_PROCESO"], 2);
                                                            $tprocesoId = $ARRAYPROCESO[0]["ID_TPROCESO"];
                                                            if ($tprocesoId && !isset($CACHE_TPROCESO[$tprocesoId])) {
                                                                $CACHE_TPROCESO[$tprocesoId] = $TPROCESO_ADO->verTproceso($tprocesoId);
                                                            }
                                                            $ARRAYTPROCESO = $tprocesoId ? $CACHE_TPROCESO[$tprocesoId] : [];
                                                            if ($ARRAYTPROCESO) {
                                                                $TPROCESO = $ARRAYTPROCESO[0]["NOMBRE_TPROCESO"];
                                                            } else {
                                                                $TPROCESO = "Sin datos";
                                                            }
                                                        } else {
                                                            $NUMEROPROCESO = "Sin datos";
                                                            $PORCENTAJEEXPO = "Sin datos";
                                                            $PORCENTAJEINDUSTRIAL = "Sin datos";
                                                            $PORCENTAJETOTAL = "Sin datos";
                                                            $FECHAPROCESO = "";
                                                            $TPROCESO = "Sin datos";
                                                        }

                                                        // Reembalaje
                                                        $reembalajeId = $s['ID_REEMBALAJE'];
                                                        if ($reembalajeId && !isset($CACHE_REEMBALAJE[$reembalajeId])) {
                                                            $CACHE_REEMBALAJE[$reembalajeId] = $REEMBALAJE_ADO->verReembalaje2($reembalajeId);
                                                        }
                                                        $ARRAYREEMBALAJE = $reembalajeId ? $CACHE_REEMBALAJE[$reembalajeId] : [];
                                                        if ($ARRAYREEMBALAJE) {
                                                            $NUMEROREEMBALEJE = $ARRAYREEMBALAJE[0]["ID_TREEMBALAJE"];
                                                            $FECHAREEMBALEJE = $ARRAYREEMBALAJE[0]["FECHA"];
                                                            $treembalajeId = $ARRAYREEMBALAJE[0]["ID_TREEMBALAJE"];
                                                            if ($treembalajeId && !isset($CACHE_TREEMBALAJE[$treembalajeId])) {
                                                                $CACHE_TREEMBALAJE[$treembalajeId] = $TREEMBALAJE_ADO->verTreembalaje($treembalajeId);
                                                            }
                                                            $ARRAYTREEMBALAJE = $treembalajeId ? $CACHE_TREEMBALAJE[$treembalajeId] : [];
                                                            if ($ARRAYTREEMBALAJE) {
                                                                $TREEMBALAJE = $ARRAYTREEMBALAJE[0]["NOMBRE_TREEMBALAJE"];
                                                            } else {
                                                                $TREEMBALAJE = "Sin datos";
                                                            }
                                                        } else {
                                                            $NUMEROREEMBALEJE = "Sin datos";
                                                            $FECHAREEMBALEJE = "";
                                                            $TREEMBALAJE = "Sin datos";
                                                        }

                                                        // Recepción MP origen (proceso / reembalaje)
                                                        if ($procesoId && !isset($CACHE_RECEPCIONMPPROC[$procesoId])) {
                                                            $CACHE_RECEPCIONMPPROC[$procesoId] = $PROCESO_ADO->buscarRecepcionMpExistenciaEnProceso($procesoId);
                                                        }
                                                        if ($reembalajeId && !isset($CACHE_RECEPCIONMPREEMB[$reembalajeId])) {
                                                            $CACHE_RECEPCIONMPREEMB[$reembalajeId] = $REEMBALAJE_ADO->buscarProcesoRecepcionMpExistenciaEnReembalaje($reembalajeId);
                                                        }
                                                        $ARRAYRECEPCIONMPORIGEN1 = $procesoId ? $CACHE_RECEPCIONMPPROC[$procesoId] : [];
                                                        $ARRAYRECEPCIONMPORIGEN2 = $reembalajeId ? $CACHE_RECEPCIONMPREEMB[$reembalajeId] : [];
                                                        if ($ARRAYRECEPCIONMPORIGEN1) {
                                                            $NUMERORECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["NUMERO"];
                                                            $FECHARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["FECHA"];
                                                            $NUMEROGUIARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["NUMEROGUIA"];
                                                            $FECHAGUIARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["FECHAGUIA"];
                                                            $TIPORECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["TRECEPCION"];
                                                            $ORIGENRECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["ORIGEN"];
                                                            $PLANTARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN1[0]["PLANTA"];
                                                        } else if ($ARRAYRECEPCIONMPORIGEN2) {
                                                            $NUMERORECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["NUMERO"];
                                                            $FECHARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["FECHA"];
                                                            $NUMEROGUIARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["NUMEROGUIA"];
                                                            $FECHAGUIARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["FECHAGUIA"];
                                                            $TIPORECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["TRECEPCION"];
                                                            $ORIGENRECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["ORIGEN"];
                                                            $PLANTARECEPCIONMP = $ARRAYRECEPCIONMPORIGEN2[0]["PLANTA"];
                                                        } else {
                                                            $NUMERORECEPCIONMP = "Sin Datos";
                                                            $FECHARECEPCIONMP = "";
                                                            $NUMEROGUIARECEPCIONMP = "Sin Datos";
                                                            $FECHAGUIARECEPCIONMP = "";
                                                            $TIPORECEPCIONMP = "Sin Datos";
                                                            $ORIGENRECEPCIONMP = "Sin Datos";
                                                            $PLANTARECEPCIONMP = "Sin Datos";
                                                        }

                                                        // Repaletizaje
                                                        $repaletizajeId = $s['ID_REPALETIZAJE'];
                                                        if ($repaletizajeId && !isset($CACHE_REPALETIZAJE[$repaletizajeId])) {
                                                            $CACHE_REPALETIZAJE[$repaletizajeId] = $REPALETIZAJEEX_ADO->verRepaletizaje2($repaletizajeId);
                                                        }
                                                        $ARRATREPALETIZAJE = $repaletizajeId ? $CACHE_REPALETIZAJE[$repaletizajeId] : [];
                                                        if ($ARRATREPALETIZAJE) {
                                                            $FECHAREPALETIZAJE = $ARRATREPALETIZAJE[0]["INGRESO"];
                                                            $NUMEROREPALETIZAJE = $ARRATREPALETIZAJE[0]["NUMERO_REPALETIZAJE"];
                                                        } else {
                                                            $NUMEROREPALETIZAJE = "Sin Datos";
                                                            $FECHAREPALETIZAJE = "";
                                                        }

                                                        // Termógrafo por pallet (folio)
                                                        $folioId = $s['FOLIO_AUXILIAR_EXIEXPORTACION'];
                                                        if ($folioId && !isset($CACHE_FOLIO[$folioId])) {
                                                            $CACHE_FOLIO[$folioId] = $EXIEXPORTACION_ADO->verFolio($folioId);
                                                        }
                                                        $ArrayTermografoPallet = $folioId ? $CACHE_FOLIO[$folioId] : [];
                                                        if ($ArrayTermografoPallet) {
                                                            $termografoPallet = $ArrayTermografoPallet[0]["N_TERMOGRAFO"];
                                                        } else {
                                                            $termografoPallet = "Sin Datos";
                                                        }
                                                        ?>
                                                        <tr class="text-center">
                                                            <td><?php echo $NUMEROREFERENCIA; ?></td>
                                                            <td><?php echo $NOMBREBROKER; ?></td>
                                                            <td><?php echo $NOMBREMERCADO; ?></td>
                                                            <td><?php echo $r['NUMERO_CONTENEDOR_DESPACHOEX']; ?></td>
                                                            <td><?php echo "Exportación"; ?></td>
                                                            <td><?php echo $r['NUMERO_DESPACHOEX']; ?></td>
                                                            <td><?php echo $r['FECHA']; ?></td>
                                                            <td><?php echo $r['NUMERO_GUIA_DESPACHOEX']; ?></td>
                                                            <td><?php echo $DESTINO; ?></td>
                                                            <td><?php echo $FECHACDOCUMENTAL; ?></td>
                                                            <td><?php echo $FECHAETD; ?></td>
                                                            <td><?php echo $FECHAETA; ?></td>
                                                            <td><?php echo $FECHAETAREAL; ?></td>
                                                            <td><?php echo $NOMBRERFINAL; ?></td>
                                                            <td><?php echo $TEMBARQUE; ?></td>
                                                            <td><?php echo $NAVE; ?></td>
                                                            <td><?php echo $NVIAJE; ?></td>
                                                            <td><?php echo $NOMBREDESTINO; ?></td>
                                                            <td><?php echo $s['FOLIO_EXIEXPORTACION']; ?></td>
                                                            <td><?php echo $s['FOLIO_AUXILIAR_EXIEXPORTACION']; ?></td>
                                                            <td><?php echo $s['EMBALADO']; ?></td>
                                                            <td><?php echo $ESTADOSAG; ?></td>
                                                            <td><?php echo $CODIGOESTANDAR; ?></td>
                                                            <td><?php echo $NOMBREESTANDAR; ?></td>
                                                            <td><?php echo $CSGPRODUCTOR; ?></td>
                                                            <td><?php echo $NOMBREPRODUCTOR; ?></td>
                                                            <td><?php echo $NOMBRESPECIES; ?></td>
                                                            <td><?php echo $NOMBREVARIEDAD; ?></td>
                                                            <td><?php echo $s['ENVASE']; ?></td>
                                                            <td><?php echo $s['NETO']; ?></td>
                                                            <td><?php echo $s['PORCENTAJE']; ?></td>
                                                            <td><?php echo $s['DESHIRATACION']; ?></td>
                                                            <td><?php echo $s['BRUTO']; ?></td>
                                                            <td><?php echo $NUMEROREPALETIZAJE; ?></td>
                                                            <td><?php echo $FECHAREPALETIZAJE; ?></td>
                                                            <td><?php echo $NUMEROPROCESO; ?></td>
                                                            <td><?php echo $FECHAPROCESO; ?></td>
                                                            <td><?php echo $TPROCESO; ?></td>
                                                            <td><?php echo $NUMEROREEMBALEJE; ?></td>
                                                            <td><?php echo $FECHAREEMBALEJE; ?></td>
                                                            <td><?php echo $TREEMBALAJE; ?></td>
                                                            <td><?php echo $NOMBRETMANEJO; ?></td>
                                                            <td><?php echo $NOMBRETCALIBRE; ?></td>
                                                            <td><?php echo $NOMBRETEMBALAJE; ?></td>
                                                            <td><?php echo $STOCK; ?></td>
                                                            <td><?php echo $EMBOLSADO; ?></td>
                                                            <td><?php echo $GASIFICADO; ?></td>
                                                            <td><?php echo $PREFRIO; ?></td>
                                                            <td><?php echo $NOMBRETRANSPORTE; ?></td>
                                                            <td><?php echo $NOMBRECONDUCTOR; ?></td>
                                                            <td><?php echo $r['PATENTE_CAMION']; ?></td>
                                                            <td><?php echo $r['PATENTE_CARRO']; ?></td>
                                                            <td><?php echo $r['SEMANA']; ?></td>
                                                            <td><?php echo $r['SEMANAGUIA']; ?></td>
                                                            <td><?php echo $NOMBREEMPRESA; ?></td>
                                                            <td><?php echo $NOMBREPLANTA; ?></td>
                                                            <td><?php echo $NOMBRETEMPORADA; ?></td>
                                                            <td><?php echo $NUMEROBL; ?></td>
                                                            <td><?php echo $NUMERORECEPCION; ?></td>
                                                            <td><?php echo $FECHARECEPCION; ?></td>
                                                            <td><?php echo $TIPORECEPCION; ?></td>
                                                            <td><?php echo $NUMEROGUIARECEPCION; ?></td>
                                                            <td><?php echo $FECHAGUIARECEPCION; ?></td>
                                                            <td><?php echo $NUMERORECEPCIONMP; ?></td>
                                                            <td><?php echo $FECHARECEPCIONMP; ?></td>
                                                            <td><?php echo $TIPORECEPCIONMP; ?></td>
                                                            <td><?php echo $NUMEROGUIARECEPCIONMP; ?></td>
                                                            <td><?php echo $FECHAGUIARECEPCIONMP; ?></td>
                                                            <td><?php echo $PLANTARECEPCIONMP; ?></td>
                                                            <td><?php echo $TERMOGRAFODESPACHOEX; ?></td>
                                                            <td><?php echo $termografoPallet; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
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
                                                    <button class="btn btn-default" id="TOTALENVASEV" name="TOTALENVASEV">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Neto</div>
                                                    <button class="btn btn-default" id="TOTALNETOV" name="TOTALNETOV">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Bruto</div>
                                                    <button class="btn btn-default" id="TOTALBRUTOV" name="TOTALBRUTOV">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <!-- /.box -->
                    </div>
                </section>
                <!-- /.content -->
            </div>
        </div>
        <?php include_once "../../assest/config/footer.php"; ?>
        <?php include_once "../../assest/config/menuExtraOpera.php"; ?>
    </div>
    <?php include_once "../../assest/config/urlBase.php"; ?>
</body>
</html>
