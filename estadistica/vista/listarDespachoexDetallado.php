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
$CACHE_EXISTENCIAS = [];

function fetchCached($id, &$cache, callable $fetcher)
{
    if (!$id) {
        return [];
    }
    if (!isset($cache[$id])) {
        $cache[$id] = $fetcher($id) ?: [];
    }
    return $cache[$id];
}

function valueOrDefault($array, $key, $default = "Sin Datos")
{
    return $array ? ($array[0][$key] ?? $default) : $default;
}

function destinoNombre($tipo, $ldestinoId, $adestinoId, $pdestinoId, &$CACHE_LDESTINO, &$CACHE_ADESTINO, &$CACHE_PDESTINO, $LDESTINO_ADO, $ADESTINO_ADO, $PDESTINO_ADO)
{
    if ($tipo == "1") {
        $destino = fetchCached($ldestinoId, $CACHE_LDESTINO, fn($id) => $LDESTINO_ADO->verLdestino($id));
        return valueOrDefault($destino, "NOMBRE_LDESTINO");
    }
    if ($tipo == "2") {
        $destino = fetchCached($adestinoId, $CACHE_ADESTINO, fn($id) => $ADESTINO_ADO->verAdestino($id));
        return valueOrDefault($destino, "NOMBRE_ADESTINO");
    }
    if ($tipo == "3") {
        $destino = fetchCached($pdestinoId, $CACHE_PDESTINO, fn($id) => $PDESTINO_ADO->verPdestino($id));
        return valueOrDefault($destino, "NOMBRE_PDESTINO");
    }
    return "Sin Datos";
}

function estadoSagTexto($estado)
{
    return match ($estado) {
        "1" => "En Inspección",
        "2" => "Aprobado Origen",
        "3" => "Aprobado USLA",
        "4" => "Fumigado",
        "5" => "Rechazado",
        default => "Sin Condición"
    };
}

function embarqueDescripcion($tipo)
{
    return match ($tipo) {
        "1" => "Terrestre",
        "2" => "Aereo",
        "3" => "Maritimo",
        default => "Sin Datos"
    };
}

function detalleIcarga($registro, &$CACHE_ICARGA, &$CACHE_MERCADO, &$CACHE_RFINAL, &$CACHE_BROKER, &$CACHE_LDESTINO, &$CACHE_ADESTINO, &$CACHE_PDESTINO, $ICARGA_ADO, $MERCADO_ADO, $RFINAL_ADO, $BROKER_ADO, $LDESTINO_ADO, $ADESTINO_ADO, $PDESTINO_ADO)
{
    $icargaId = $registro['ID_ICARGA'];
    $icarga = fetchCached($icargaId, $CACHE_ICARGA, fn($id) => $ICARGA_ADO->verIcarga($id));

    if ($icarga) {
        $tipoEmbarque = $icarga[0]['TEMBARQUE_ICARGA'];
        $destino = destinoNombre($tipoEmbarque, $icarga[0]['ID_LDESTINO'], $icarga[0]['ID_ADESTINO'], $icarga[0]['ID_PDESTINO'], $CACHE_LDESTINO, $CACHE_ADESTINO, $CACHE_PDESTINO, $LDESTINO_ADO, $ADESTINO_ADO, $PDESTINO_ADO);
        $mercado = fetchCached($icarga[0]['ID_MERCADO'], $CACHE_MERCADO, fn($id) => $MERCADO_ADO->verMercado($id));
        $rfinal = fetchCached($icarga[0]['ID_RFINAL'], $CACHE_RFINAL, fn($id) => $RFINAL_ADO->verRfinal($id));
        $broker = fetchCached($icarga[0]['ID_BROKER'], $CACHE_BROKER, fn($id) => $BROKER_ADO->verBroker($id));

        return [
            'NREFERENCIA' => $icarga[0]['NREFERENCIA_ICARGA'],
            'BL' => $icarga[0]['CRT_ICARGA'],
            'ETD' => $icarga[0]['FECHAETD_ICARGA'],
            'ETA' => $icarga[0]['FECHAETA_ICARGA'],
            'ETAREAL' => $icarga[0]['FECHAETAREAL_ICARGA'],
            'CDOCUMENTAL' => $icarga[0]['FECHA_CDOCUMENTAL_ICARGA'],
            'TEMBARQUE' => embarqueDescripcion($tipoEmbarque),
            'NAVE' => $icarga[0]['NAVE_ICARGA'] ?: "No Aplica",
            'NVIAJE' => $icarga[0]['NVIAJE_ICARGA'] ?: "No Aplica",
            'DESTINO' => $destino,
            'MERCADO' => valueOrDefault($mercado, 'NOMBRE_MERCADO'),
            'RFINAL' => valueOrDefault($rfinal, 'NOMBRE_RFINAL'),
            'BROKER' => valueOrDefault($broker, 'NOMBRE_BROKER')
        ];
    }

    $tipoEmbarque = $registro['TEMBARQUE_DESPACHOEX'];
    return [
        'NREFERENCIA' => 'No Aplica',
        'BL' => 'No Aplica',
        'ETD' => $registro['FECHAETD_DESPACHOEX'],
        'ETA' => $registro['FECHAETA_DESPACHOEX'],
        'ETAREAL' => '',
        'CDOCUMENTAL' => '',
        'TEMBARQUE' => embarqueDescripcion($tipoEmbarque),
        'NAVE' => $registro['NAVE_DESPACHOEX'] ?: 'No Aplica',
        'NVIAJE' => $registro['NVIAJE_DESPACHOEX'] ?: 'No Aplica',
        'DESTINO' => destinoNombre($tipoEmbarque, $registro['ID_LDESTINO'], $registro['ID_ADESTINO'], $registro['ID_PDESTINO'], $CACHE_LDESTINO, $CACHE_ADESTINO, $CACHE_PDESTINO, $LDESTINO_ADO, $ADESTINO_ADO, $PDESTINO_ADO),
        'MERCADO' => valueOrDefault(fetchCached($registro['ID_MERCADO'], $CACHE_MERCADO, fn($id) => $MERCADO_ADO->verMercado($id)), 'NOMBRE_MERCADO'),
        'RFINAL' => valueOrDefault(fetchCached($registro['ID_RFINAL'], $CACHE_RFINAL, fn($id) => $RFINAL_ADO->verRfinal($id)), 'NOMBRE_RFINAL'),
        'BROKER' => 'No Aplica'
    ];
}


//DEFINIR ARREGLOS CON LOS DATOS OBTENIDOS DE LAS FUNCIONES DE LOS CONTROLADORES
if ($EMPRESABUSCAR && $TEMPORADAS) {
    $ARRAYDESPACHOEX = $DESPACHOEX_ADO->listarDespachoexEmpresaTemporadaCBX($EMPRESABUSCAR, $TEMPORADAS);
}

$DETALLESDESPACHO = [];
foreach ($ARRAYDESPACHOEX as $despacho) {
    $transporte = fetchCached($despacho['ID_TRANSPORTE'], $CACHE_TRANSPORTE, fn($id) => $TRANSPORTE_ADO->verTransporte($id));
    $conductor = fetchCached($despacho['ID_CONDUCTOR'], $CACHE_CONDUCTOR, fn($id) => $CONDUCTOR_ADO->verConductor($id));
    $dfinal = fetchCached($despacho['ID_DFINAL'], $CACHE_DFINAL, fn($id) => $DFINAL_ADO->verDfinal($id));
    $empresa = fetchCached($despacho['ID_EMPRESA'], $CACHE_EMPRESA, fn($id) => $EMPRESA_ADO->verEmpresa($id));
    $planta = fetchCached($despacho['ID_PLANTA'], $CACHE_PLANTA, fn($id) => $PLANTA_ADO->verPlanta($id));
    $temporada = fetchCached($despacho['ID_TEMPORADA'], $CACHE_TEMPORADA, fn($id) => $TEMPORADA_ADO->verTemporada($id));

    $icargaInfo = detalleIcarga($despacho, $CACHE_ICARGA, $CACHE_MERCADO, $CACHE_RFINAL, $CACHE_BROKER, $CACHE_LDESTINO, $CACHE_ADESTINO, $CACHE_PDESTINO, $ICARGA_ADO, $MERCADO_ADO, $RFINAL_ADO, $BROKER_ADO, $LDESTINO_ADO, $ADESTINO_ADO, $PDESTINO_ADO);

    $existencias = fetchCached($despacho['ID_DESPACHOEX'], $CACHE_EXISTENCIAS, fn($id) => $EXIEXPORTACION_ADO->buscarPordespachoEx($id));

    foreach ($existencias as $detalle) {
        $productor = fetchCached($detalle['ID_PRODUCTOR'], $CACHE_PRODUCTOR, fn($id) => $PRODUCTOR_ADO->verProductor($id));
        $vespecies = fetchCached($detalle['ID_VESPECIES'], $CACHE_VESPECIES, fn($id) => $VESPECIES_ADO->verVespecies($id));
        $especies = $vespecies ? fetchCached($vespecies[0]['ID_ESPECIES'], $CACHE_ESPECIES, fn($id) => $ESPECIES_ADO->verEspecies($id)) : [];
        $estandar = fetchCached($detalle['ID_ESTANDAR'], $CACHE_EEXPORTACION, fn($id) => $EEXPORTACION_ADO->verEstandar($id));
        $tmanejo = fetchCached($detalle['ID_TMANEJO'], $CACHE_TMANEJO, fn($id) => $TMANEJO_ADO->verTmanejo($id));
        $tcalibre = fetchCached($detalle['ID_TCALIBRE'], $CACHE_TCALIBRE, fn($id) => $TCALIBRE_ADO->verCalibre($id));
        $tembalaje = fetchCached($detalle['ID_TEMBALAJE'], $CACHE_TEMBALAJE, fn($id) => $TEMBALAJE_ADO->verEmbalaje($id));

        $recepcion = fetchCached($detalle['ID_RECEPCION'], $CACHE_RECEPCIONPT, fn($id) => $RECEPCIONPT_ADO->verRecepcion2($id));
        $despachoInter = fetchCached($detalle['ID_DESPACHO2'], $CACHE_DESPACHOPT, fn($id) => $DESPACHOPT_ADO->verDespachopt($id));
        $recepcionOrigen = fetchCached($despachoInter ? $despachoInter[0]['ID_PLANTA'] ?? null : null, $CACHE_PLANTA2, fn($id) => $PLANTA_ADO->verPlanta($id));

        $proceso = fetchCached($detalle['ID_PROCESO'], $CACHE_PROCESO, fn($id) => $PROCESO_ADO->verProceso2($id));
        $tproceso = $proceso ? fetchCached($proceso[0]['ID_TPROCESO'], $CACHE_TPROCESO, fn($id) => $TPROCESO_ADO->verTproceso($id)) : [];

        $reembalaje = fetchCached($detalle['ID_REEMBALAJE'], $CACHE_REEMBALAJE, fn($id) => $REEMBALAJE_ADO->verReembalaje2($id));
        $treembalaje = $reembalaje ? fetchCached($reembalaje[0]['ID_TREEMBALAJE'], $CACHE_TREEMBALAJE, fn($id) => $TREEMBALAJE_ADO->verTreembalaje($id)) : [];

        $recepcionProceso = fetchCached($detalle['ID_PROCESO'], $CACHE_RECEPCIONMPPROC, fn($id) => $PROCESO_ADO->buscarRecepcionMpExistenciaEnProceso($id));
        $recepcionReembalaje = fetchCached($detalle['ID_REEMBALAJE'], $CACHE_RECEPCIONMPREEMB, fn($id) => $REEMBALAJE_ADO->buscarProcesoRecepcionMpExistenciaEnReembalaje($id));

        $repaletizaje = fetchCached($detalle['ID_REPALETIZAJE'], $CACHE_REPALETIZAJE, fn($id) => $REPALETIZAJEEX_ADO->verRepaletizaje2($id));
        $folio = fetchCached($detalle['FOLIO_AUXILIAR_EXIEXPORTACION'], $CACHE_FOLIO, fn($id) => $EXIEXPORTACION_ADO->verFolio($id));

        $detalleRecepcion = $recepcion ?: $despachoInter;
        $origenTipo = $recepcion ? ($recepcion[0]['TRECEPCION'] == 1 ? "Desde Productor" : "Planta Externa") : ($despachoInter ? "Interplanta" : "Sin Datos");

        $recepcionMPOrigen = $recepcionProceso ?: $recepcionReembalaje;
        $recepcionMPPlanta = $recepcionMPOrigen ? $recepcionMPOrigen[0]['PLANTA'] : "Sin Datos";

        $TOTALBRUTO += floatval($detalle['BRUTO']);
        $TOTALNETO += floatval($detalle['NETO']);
        $TOTALENVASE += floatval($detalle['ENVASE']);

        $DETALLESDESPACHO[] = [
            'NUMEROREFERENCIA' => $icargaInfo['NREFERENCIA'],
            'BROKER' => $icargaInfo['BROKER'],
            'MERCADO' => $icargaInfo['MERCADO'],
            'NUMEROCONTENEDOR' => $despacho['NUMERO_CONTENEDOR_DESPACHOEX'],
            'NUMERODESPACHO' => $despacho['NUMERO_DESPACHOEX'],
            'FECHA' => $despacho['FECHA'],
            'NUMEROGUIA' => $despacho['NUMERO_GUIA_DESPACHOEX'],
            'DESTINO' => valueOrDefault($dfinal, 'NOMBRE_DFINAL'),
            'FECHACDOCUMENTAL' => $icargaInfo['CDOCUMENTAL'],
            'ETD' => $icargaInfo['ETD'],
            'ETA' => $icargaInfo['ETA'],
            'ETAREAL' => $icargaInfo['ETAREAL'],
            'RFINAL' => $icargaInfo['RFINAL'],
            'TEMBARQUE' => $icargaInfo['TEMBARQUE'],
            'NAVE' => $icargaInfo['NAVE'],
            'NVIAJE' => $icargaInfo['NVIAJE'],
            'DESTINOCARGA' => $icargaInfo['DESTINO'],
            'FOLIO' => $detalle['FOLIO_EXIEXPORTACION'],
            'FOLIOAUX' => $detalle['FOLIO_AUXILIAR_EXIEXPORTACION'],
            'EMBALADO' => $detalle['EMBALADO'],
            'ESTADOSAG' => estadoSagTexto($detalle['TESTADOSAG']),
            'CODIGOESTANDAR' => valueOrDefault($estandar, 'CODIGO_ESTANDAR'),
            'NOMBREESTANDAR' => valueOrDefault($estandar, 'NOMBRE_ESTANDAR'),
            'CSGPRODUCTOR' => valueOrDefault($productor, 'CSG_PRODUCTOR'),
            'NOMBREPRODUCTOR' => valueOrDefault($productor, 'NOMBRE_PRODUCTOR'),
            'NOMBRESPECIES' => valueOrDefault($especies, 'NOMBRE_ESPECIES'),
            'NOMBREVARIEDAD' => valueOrDefault($vespecies, 'NOMBRE_VESPECIES'),
            'ENVASE' => $detalle['ENVASE'],
            'NETO' => $detalle['NETO'],
            'PORCENTAJE' => $detalle['PORCENTAJE'],
            'DESHIDRATACION' => $detalle['DESHIRATACION'],
            'BRUTO' => $detalle['BRUTO'],
            'NUMEROREPALETIZAJE' => valueOrDefault($repaletizaje, 'NUMERO_REPALETIZAJE'),
            'FECHAREPALETIZAJE' => valueOrDefault($repaletizaje, 'INGRESO', ''),
            'NUMEROPROCESO' => valueOrDefault($proceso, 'NUMERO_PROCESO', 'Sin datos'),
            'FECHAPROCESO' => valueOrDefault($proceso, 'FECHA', ''),
            'TPROCESO' => valueOrDefault($tproceso, 'NOMBRE_TPROCESO', 'Sin datos'),
            'NUMEROREEMBALAJE' => valueOrDefault($reembalaje, 'ID_TREEMBALAJE', 'Sin datos'),
            'FECHAREEMBALAJE' => valueOrDefault($reembalaje, 'FECHA', ''),
            'TREEMBALAJE' => valueOrDefault($treembalaje, 'NOMBRE_TREEMBALAJE', 'Sin datos'),
            'NOMBRETMANEJO' => valueOrDefault($tmanejo, 'NOMBRE_TMANEJO'),
            'NOMBRETCALIBRE' => valueOrDefault($tcalibre, 'NOMBRE_TCALIBRE'),
            'NOMBRETEMBALAJE' => valueOrDefault($tembalaje, 'NOMBRE_TEMBALAJE'),
            'STOCK' => $detalle['STOCK'] !== "" ? $detalle['STOCK'] : "Sin Datos",
            'EMBOLSADO' => $detalle['EMBOLSADO'] == "1" ? "SI" : ($detalle['EMBOLSADO'] == "0" ? "NO" : "Sin Datos"),
            'GASIFICADO' => $detalle['GASIFICADO'] == "1" ? "SI" : ($detalle['GASIFICADO'] == "0" ? "NO" : "Sin Datos"),
            'PREFRIO' => $detalle['PREFRIO'] == "1" ? "SI" : ($detalle['PREFRIO'] == "0" ? "NO" : "Sin Datos"),
            'NOMBRETRANSPORTE' => valueOrDefault($transporte, 'NOMBRE_TRANSPORTE'),
            'NOMBRECONDUCTOR' => valueOrDefault($conductor, 'NOMBRE_CONDUCTOR'),
            'PATENTECAMION' => $despacho['PATENTE_CAMION'],
            'PATENTECARRO' => $despacho['PATENTE_CARRO'],
            'SEMANA' => $despacho['SEMANA'],
            'SEMANAGUIA' => $despacho['SEMANAGUIA'],
            'NOMBREEMPRESA' => valueOrDefault($empresa, 'NOMBRE_EMPRESA'),
            'NOMBREPLANTA' => valueOrDefault($planta, 'NOMBRE_PLANTA'),
            'NOMBRETEMPORADA' => valueOrDefault($temporada, 'NOMBRE_TEMPORADA'),
            'NUMEROBL' => $icargaInfo['BL'],
            'NUMERORECEPCION' => $detalleRecepcion ? ($recepcion ? $detalleRecepcion[0]['NUMERO_RECEPCION'] : $detalleRecepcion[0]['NUMERO_DESPACHO']) : "Sin Datos",
            'FECHARECEPCION' => $detalleRecepcion ? $detalleRecepcion[0]['FECHA'] : "",
            'TIPORECEPCION' => $origenTipo,
            'NUMEROGUIARECEPCION' => $detalleRecepcion ? ($recepcion ? $detalleRecepcion[0]['NUMERO_GUIA_RECEPCION'] : $detalleRecepcion[0]['NUMERO_GUIA_DESPACHO']) : "Sin Datos",
            'FECHAGUIARECEPCION' => $recepcion ? ($recepcion[0]['GUIA'] ?? "") : "",
            'NUMERORECEPCIONMP' => $recepcionMPOrigen ? $recepcionMPOrigen[0]['NUMERO'] : "Sin Datos",
            'FECHARECEPCIONMP' => $recepcionMPOrigen ? $recepcionMPOrigen[0]['FECHA'] : "",
            'TIPORECEPCIONMP' => $recepcionMPOrigen ? $recepcionMPOrigen[0]['TRECEPCION'] : "Sin Datos",
            'NUMEROGUIARECEPCIONMP' => $recepcionMPOrigen ? $recepcionMPOrigen[0]['NUMEROGUIA'] : "Sin Datos",
            'FECHAGUIARECEPCIONMP' => $recepcionMPOrigen ? $recepcionMPOrigen[0]['FECHAGUIA'] : "",
            'PLANTARECEPCIONMP' => $recepcionMPPlanta,
            'TERMOGRAFODESPACHOEX' => $despacho['TERMOGRAFO_DESPACHOEX'],
            'TERMOGRAFOPALLET' => valueOrDefault($folio, 'N_TERMOGRAFO')
        ];
    }
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
                                                <?php if (!$EMPRESABUSCAR) : ?>
                                                    <tr class="text-center">
                                                        <td colspan="69">Seleccione la empresa para cargar la información.</td>
                                                    </tr>
                                                <?php elseif (!$DETALLESDESPACHO) : ?>
                                                    <tr class="text-center">
                                                        <td colspan="69">No se encontraron datos para la empresa seleccionada.</td>
                                                    </tr>
                                                <?php else : ?>
                                                    <?php foreach ($DETALLESDESPACHO as $item) : ?>
                                                    <tr class="text-center">
                                                        <td><?php echo $item['NUMEROREFERENCIA']; ?></td>
                                                        <td><?php echo $item['BROKER']; ?></td>
                                                        <td><?php echo $item['MERCADO']; ?></td>
                                                        <td><?php echo $item['NUMEROCONTENEDOR']; ?></td>
                                                        <td><?php echo "Exportación"; ?></td>
                                                        <td><?php echo $item['NUMERODESPACHO']; ?></td>
                                                        <td><?php echo $item['FECHA']; ?></td>
                                                        <td><?php echo $item['NUMEROGUIA']; ?></td>
                                                        <td><?php echo $item['DESTINO']; ?></td>
                                                        <td><?php echo $item['FECHACDOCUMENTAL']; ?></td>
                                                        <td><?php echo $item['ETD']; ?></td>
                                                        <td><?php echo $item['ETA']; ?></td>
                                                        <td><?php echo $item['ETAREAL']; ?></td>
                                                        <td><?php echo $item['RFINAL']; ?></td>
                                                        <td><?php echo $item['TEMBARQUE']; ?></td>
                                                        <td><?php echo $item['NAVE']; ?></td>
                                                        <td><?php echo $item['NVIAJE']; ?></td>
                                                        <td><?php echo $item['DESTINOCARGA']; ?></td>
                                                        <td><?php echo $item['FOLIO']; ?></td>
                                                        <td><?php echo $item['FOLIOAUX']; ?></td>
                                                        <td><?php echo $item['EMBALADO']; ?></td>
                                                        <td><?php echo $item['ESTADOSAG']; ?></td>
                                                        <td><?php echo $item['CODIGOESTANDAR']; ?></td>
                                                        <td><?php echo $item['NOMBREESTANDAR']; ?></td>
                                                        <td><?php echo $item['CSGPRODUCTOR']; ?></td>
                                                        <td><?php echo $item['NOMBREPRODUCTOR']; ?></td>
                                                        <td><?php echo $item['NOMBRESPECIES']; ?></td>
                                                        <td><?php echo $item['NOMBREVARIEDAD']; ?></td>
                                                        <td><?php echo $item['ENVASE']; ?></td>
                                                        <td><?php echo $item['NETO']; ?></td>
                                                        <td><?php echo $item['PORCENTAJE']; ?></td>
                                                        <td><?php echo $item['DESHIDRATACION']; ?></td>
                                                        <td><?php echo $item['BRUTO']; ?></td>
                                                        <td><?php echo $item['NUMEROREPALETIZAJE']; ?></td>
                                                        <td><?php echo $item['FECHAREPALETIZAJE']; ?></td>
                                                        <td><?php echo $item['NUMEROPROCESO']; ?></td>
                                                        <td><?php echo $item['FECHAPROCESO']; ?></td>
                                                        <td><?php echo $item['TPROCESO']; ?></td>
                                                        <td><?php echo $item['NUMEROREEMBALAJE']; ?></td>
                                                        <td><?php echo $item['FECHAREEMBALAJE']; ?></td>
                                                        <td><?php echo $item['TREEMBALAJE']; ?></td>
                                                        <td><?php echo $item['NOMBRETMANEJO']; ?></td>
                                                        <td><?php echo $item['NOMBRETCALIBRE']; ?></td>
                                                        <td><?php echo $item['NOMBRETEMBALAJE']; ?></td>
                                                        <td><?php echo $item['STOCK']; ?></td>
                                                        <td><?php echo $item['EMBOLSADO']; ?></td>
                                                        <td><?php echo $item['GASIFICADO']; ?></td>
                                                        <td><?php echo $item['PREFRIO']; ?></td>
                                                        <td><?php echo $item['NOMBRETRANSPORTE']; ?></td>
                                                        <td><?php echo $item['NOMBRECONDUCTOR']; ?></td>
                                                        <td><?php echo $item['PATENTECAMION']; ?></td>
                                                        <td><?php echo $item['PATENTECARRO']; ?></td>
                                                        <td><?php echo $item['SEMANA']; ?></td>
                                                        <td><?php echo $item['SEMANAGUIA']; ?></td>
                                                        <td><?php echo $item['NOMBREEMPRESA']; ?></td>
                                                        <td><?php echo $item['NOMBREPLANTA']; ?></td>
                                                        <td><?php echo $item['NOMBRETEMPORADA']; ?></td>
                                                        <td><?php echo $item['NUMEROBL']; ?></td>
                                                        <td><?php echo $item['NUMERORECEPCION']; ?></td>
                                                        <td><?php echo $item['FECHARECEPCION']; ?></td>
                                                        <td><?php echo $item['TIPORECEPCION']; ?></td>
                                                        <td><?php echo $item['NUMEROGUIARECEPCION']; ?></td>
                                                        <td><?php echo $item['FECHAGUIARECEPCION']; ?></td>
                                                        <td><?php echo $item['NUMERORECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['FECHARECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['TIPORECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['NUMEROGUIARECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['FECHAGUIARECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['PLANTARECEPCIONMP']; ?></td>
                                                        <td><?php echo $item['TERMOGRAFODESPACHOEX']; ?></td>
                                                        <td><?php echo $item['TERMOGRAFOPALLET']; ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
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
                                                        <?php echo number_format($TOTALENVASE, 0, ',', '.'); ?>
                                                    </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Total Neto</div>
                                                    <button class="btn btn-default" id="TOTALNETOV" name="TOTALNETOV">
                                                        <?php echo number_format($TOTALNETO, 0, ',', '.'); ?>
                                                    </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Total Bruto</div>
                                                    <button class="btn btn-default" id="TOTALBRUTOV" name="TOTALBRUTOV">
                                                        <?php echo number_format($TOTALBRUTO, 0, ',', '.'); ?>
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
