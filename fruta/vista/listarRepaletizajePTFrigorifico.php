<?php


include_once "../../assest/config/validarUsuarioFruta.php";

//LLAMADA ARCHIVOS NECESARIOS PARA LAS OPERACIONES

include_once '../../assest/controlador/ERECEPCION_ADO.php';
include_once '../../assest/controlador/PRODUCTOR_ADO.php';
include_once '../../assest/controlador/VESPECIES_ADO.php';
include_once '../../assest/controlador/FOLIO_ADO.php';

include_once '../../assest/modelo/REPALETIZAJEEX.php';

include_once '../../assest/controlador/REPALETIZAJEEX_ADO.php';
include_once '../../assest/controlador/EXIEXPORTACION_ADO.php';
include_once '../../assest/controlador/DREPALETIZAJEEX_ADO.php';

//INICIALIZAR CONTROLADOR


$REPALETIZAJEEX_ADO =  new REPALETIZAJEEX_ADO();
$EXIEXPORTACION_ADO =  new EXIEXPORTACION_ADO();
$DREPALETIZAJEEX_ADO =  new DREPALETIZAJEEX_ADO();

$REPALETIZAJEEX = new REPALETIZAJEEX();

$ERECEPCION_ADO =  new ERECEPCION_ADO();
$PRODUCTOR_ADO =  new PRODUCTOR_ADO();
$VESPECIES_ADO =  new VESPECIES_ADO();
$FOLIO_ADO =  new FOLIO_ADO();

//INCIALIZAR VARIBALES A OCUPAR PARA LA FUNCIONALIDAD

$TOTALNETO = 0;
$TOTALENVASE = 0;
$TOTALNETO2 = 0;
$TOTALENVASE2 = 0;
$FECHADESDE = "";
$FECHAHASTA = "";
$FOLIOORIGINAL="";
$FOLIONUEVO="";
$DISABLEDT="";
$DISABLEDS="";
$MENSAJE = "";
$MENSAJEENVIO = "";

//INICIALIZAR ARREGLOS
$ARRAYREPALETIZAJEEX = "";
$ARRAYREPALETIZAJEEXTOTAL = "";

$ARRAYFOLIOREPALETIZAJE = "";
$ARRAYFOLIODREPALETIZAJE = "";


//DEFINIR ARREGLOS CON LOS DATOS OBTENIDOS DE LAS FUNCIONES DE LOS CONTROLADORES
if ($EMPRESAS  && $PLANTAS && $TEMPORADAS) {
    $ARRAYREPALETIZAJEEX = $REPALETIZAJEEX_ADO->listarRepaletizajeEmpresaPlantaTemporadaCBX($EMPRESAS, $PLANTAS, $TEMPORADAS);
}
include_once "../../assest/config/validarDatosUrl.php";
include_once "../../assest/config/datosUrLP.php";

$ARRAYUSUARIO = $USUARIO_ADO->verUsuario($_SESSION["ID_USUARIO"]);
if ($ARRAYUSUARIO) {
    $CORREOUSUARIO = trim($ARRAYUSUARIO[0]['EMAIL_USUARIO']);
    $NOMBRECOMPLETOUSUARIO = trim(
        ($ARRAYUSUARIO[0]['PNOMBRE_USUARIO'] ?? '') . ' ' .
        ($ARRAYUSUARIO[0]['SNOMBRE_USUARIO'] ?? '') . ' ' .
        ($ARRAYUSUARIO[0]['PAPELLIDO_USUARIO'] ?? '') . ' ' .
        ($ARRAYUSUARIO[0]['SAPELLIDO_USUARIO'] ?? '')
    );
    $NOMBRECOMPLETOUSUARIO = trim($NOMBRECOMPLETOUSUARIO) ?: ($_SESSION['NOMBRE_USUARIO'] ?? '');
}

function generarCodigoAutorizacion()
{
    if (function_exists('random_int')) {
        return random_int(100000, 999999);
    }

    return mt_rand(100000, 999999);
}


function obtenerDestinatariosAutorizacion($correoSolicitante)
{
    $correosBase = ['maperez@fvolcan.cl', 'eisla@fvolcan.cl'];
    $correoSolicitante = trim((string) $correoSolicitante);

    if ($correoSolicitante !== '') {
        $correosBase = array_filter(
            $correosBase,
            fn($correo) => strcasecmp($correo, $correoSolicitante) !== 0
        );
    }

    return array_values(array_filter(array_unique($correosBase)));
}

function enviarCorreoSMTP($destinatarios, $asunto, $mensaje, $remitente, $usuario, $contrasena, $host, $puerto, $timeout = 30)
{
    $destinatarios = (array) $destinatarios;
    $contextoSSL = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
        ]
    ]);

    $conexion = @stream_socket_client("ssl://{$host}:{$puerto}", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $contextoSSL);

    if (!$conexion) {
        return [false, "No se pudo conectar al servidor de correo: {$errstr} ({$errno})"];
    }

    $remitenteCabecera = "From: {$remitente}\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/plain; charset=utf-8' . "\r\n";

    $envioOk = true;
    $error = '';
    foreach ($destinatarios as $destinatario) {
        if (!@mail($destinatario, $asunto, $mensaje, $remitenteCabecera)) {
            $envioOk = false;
            $error = "No se pudo enviar el correo a {$destinatario}.";
            break;
        }
    }

    return [$envioOk, $error];
}

function obtenerDatosCorreoRepaletizaje($repaletizaje)
{
    return [
        'numero' => $repaletizaje['NUMERO_REPALETIZAJE'] ?? '',
        'motivo' => $repaletizaje['MOTIVO_REPALETIZAJE'] ?? '',
    ];
}

if ($_POST) {
    $IDREPALETIZAJE = $_REQUEST['ID'] ?? null;
    $CODIGOVERIFICACION = $_REQUEST['CODIGO_ELIMINAR'] ?? '';
    $CODIGOAPERTURA = $_REQUEST['CODIGO_ABRIR'] ?? '';

    $detalleRepaletizaje = $IDREPALETIZAJE ? $REPALETIZAJEEX_ADO->verRepaletizaje2($IDREPALETIZAJE) : [];
    $datosRepaletizaje = $detalleRepaletizaje ? $detalleRepaletizaje[0] : [];
    $datosCorreo = obtenerDatosCorreoRepaletizaje($datosRepaletizaje);

    if (isset($_REQUEST['SOLICITARELIMINAR'])) {
        $foliosEntrada = $IDREPALETIZAJE ? $EXIEXPORTACION_ADO->buscarPorRepaletizajeAgrupado($IDREPALETIZAJE) : [];
        $foliosSalida = $IDREPALETIZAJE ? $DREPALETIZAJEEX_ADO->buscarDrepaletizaje($IDREPALETIZAJE) : [];

        if ($foliosEntrada || $foliosSalida) {
            $MENSAJE = "No es posible eliminar el repaletizaje porque existen registros de entrada o salida asociados.";
        } else {
            $codigoAutorizacion = generarCodigoAutorizacion();
            $_SESSION['REPALETIZAJE_ELIMINAR_CODIGO'] = $codigoAutorizacion;
            $_SESSION['REPALETIZAJE_ELIMINAR_ID'] = $IDREPALETIZAJE;
            $_SESSION['REPALETIZAJE_ELIMINAR_TIEMPO'] = time();

            $destinatarios = obtenerDestinatariosAutorizacion($CORREOUSUARIO);
            $asunto = 'Autorización eliminación repaletizaje #' . $datosCorreo['numero'];
            $mensajeCorreo = "Se solicitó eliminar un repaletizaje." . "\r\n\r\n" .
                "Número de repaletizaje: " . $datosCorreo['numero'] . "\r\n" .
                "Motivo: " . $datosCorreo['motivo'] . "\r\n" .
                "Solicitado por: " . $NOMBRECOMPLETOUSUARIO . "\r\n" .
                "Código de autorización: " . $codigoAutorizacion . "\r\n\r\n" .
                "El código tiene validez de 15 minutos.";

            $remitente = 'informevolcan@gocreative.cl';
            $usuarioSMTP = 'informevolcan@gocreative.cl';
            $contrasenaSMTP = 'bOaKXtke6.#5#v[q';
            $hostSMTP = 'mail.gocreative.cl';
            $puertoSMTP = 465;

            [$envioOk, $errorEnvio] = enviarCorreoSMTP($destinatarios, $asunto, $mensajeCorreo, $remitente, $usuarioSMTP, $contrasenaSMTP, $hostSMTP, $puertoSMTP);
            $MENSAJEENVIO = $envioOk ? "Código de autorización enviado correctamente a Maria de los Ángeles y Erwin Isla." : ($errorEnvio ?: "No fue posible enviar el correo de autorización.");
        }
    }

    if (isset($_REQUEST['CONFIRMAR_ELIMINAR'])) {
        $codigoSesion = $_SESSION['REPALETIZAJE_ELIMINAR_CODIGO'] ?? null;
        $idSesion = $_SESSION['REPALETIZAJE_ELIMINAR_ID'] ?? null;
        $tiempoSesion = $_SESSION['REPALETIZAJE_ELIMINAR_TIEMPO'] ?? 0;

        $foliosEntrada = $IDREPALETIZAJE ? $EXIEXPORTACION_ADO->buscarPorRepaletizajeAgrupado($IDREPALETIZAJE) : [];
        $foliosSalida = $IDREPALETIZAJE ? $DREPALETIZAJEEX_ADO->buscarDrepaletizaje($IDREPALETIZAJE) : [];

        if ($foliosEntrada || $foliosSalida) {
            $MENSAJE = "El repaletizaje tiene registros asociados y no puede eliminarse.";
        } elseif (!$codigoSesion || !$idSesion || $idSesion != $IDREPALETIZAJE) {
            $MENSAJE = "No hay una solicitud de eliminación vigente para este repaletizaje.";
        } elseif ((time() - $tiempoSesion) > 900) {
            $MENSAJE = "El código de autorización ha expirado.";
        } elseif (!$CODIGOVERIFICACION || $CODIGOVERIFICACION != $codigoSesion) {
            $MENSAJE = "El código ingresado no es válido.";
        } else {
            $REPALETIZAJEEX->__SET('ID_REPALETIZAJE', $IDREPALETIZAJE);
            $REPALETIZAJEEX_ADO->deshabilitar($REPALETIZAJEEX);

            $destinatarios = obtenerDestinatariosAutorizacion($CORREOUSUARIO);
            $asunto = 'Confirmación eliminación repaletizaje #' . $datosCorreo['numero'];
            $mensajeCorreo = "Se confirmó la eliminación del repaletizaje." . "\r\n\r\n" .
                "Número de repaletizaje: " . $datosCorreo['numero'] . "\r\n" .
                "Motivo: " . $datosCorreo['motivo'] . "\r\n" .
                "Confirmado por: " . $NOMBRECOMPLETOUSUARIO . "\r\n\r\n" .
                "El estado de registro fue desactivado.";

            $remitente = 'informevolcan@gocreative.cl';
            $usuarioSMTP = 'informevolcan@gocreative.cl';
            $contrasenaSMTP = 'bOaKXtke6.#5#v[q';
            $hostSMTP = 'mail.gocreative.cl';
            $puertoSMTP = 465;

            [$envioOk, $errorEnvio] = enviarCorreoSMTP($destinatarios, $asunto, $mensajeCorreo, $remitente, $usuarioSMTP, $contrasenaSMTP, $hostSMTP, $puertoSMTP);
            $MENSAJEENVIO = $envioOk ? "Repaletizaje eliminado (estado de registro desactivado)." : ($errorEnvio ?: "El repaletizaje se eliminó, pero hubo un problema al enviar la notificación.");
            unset($_SESSION['REPALETIZAJE_ELIMINAR_CODIGO'], $_SESSION['REPALETIZAJE_ELIMINAR_ID'], $_SESSION['REPALETIZAJE_ELIMINAR_TIEMPO']);
        }
    }

    if (isset($_REQUEST['SOLICITAR_ABRIR'])) {
        $codigoAutorizacion = generarCodigoAutorizacion();
        $_SESSION['REPALETIZAJE_ABRIR_CODIGO'] = $codigoAutorizacion;
        $_SESSION['REPALETIZAJE_ABRIR_ID'] = $IDREPALETIZAJE;
        $_SESSION['REPALETIZAJE_ABRIR_TIEMPO'] = time();

        $destinatarios = obtenerDestinatariosAutorizacion($CORREOUSUARIO);
        $asunto = 'Autorización apertura repaletizaje #' . $datosCorreo['numero'];
        $mensajeCorreo = "Se solicitó abrir un repaletizaje cerrado." . "\r\n\r\n" .
            "Número de repaletizaje: " . $datosCorreo['numero'] . "\r\n" .
            "Motivo: " . $datosCorreo['motivo'] . "\r\n" .
            "Solicitado por: " . $NOMBRECOMPLETOUSUARIO . "\r\n" .
            "Código de autorización: " . $codigoAutorizacion . "\r\n\r\n" .
            "El código tiene validez de 15 minutos.";

        $remitente = 'informevolcan@gocreative.cl';
        $usuarioSMTP = 'informevolcan@gocreative.cl';
        $contrasenaSMTP = 'bOaKXtke6.#5#v[q';
        $hostSMTP = 'mail.gocreative.cl';
        $puertoSMTP = 465;

        [$envioOk, $errorEnvio] = enviarCorreoSMTP($destinatarios, $asunto, $mensajeCorreo, $remitente, $usuarioSMTP, $contrasenaSMTP, $hostSMTP, $puertoSMTP);
        $MENSAJEENVIO = $envioOk ? "Código de autorización enviado correctamente a Maria de los Ángeles y Erwin Isla." : ($errorEnvio ?: "No fue posible enviar el correo de autorización.");
    }

    if (isset($_REQUEST['CONFIRMAR_ABRIR'])) {
        $codigoSesion = $_SESSION['REPALETIZAJE_ABRIR_CODIGO'] ?? null;
        $idSesion = $_SESSION['REPALETIZAJE_ABRIR_ID'] ?? null;
        $tiempoSesion = $_SESSION['REPALETIZAJE_ABRIR_TIEMPO'] ?? 0;

        if (!$codigoSesion || !$idSesion || $idSesion != $IDREPALETIZAJE) {
            $MENSAJE = "No hay una solicitud de apertura vigente para este repaletizaje.";
        } elseif ((time() - $tiempoSesion) > 900) {
            $MENSAJE = "El código de autorización ha expirado.";
        } elseif (!$CODIGOAPERTURA || $CODIGOAPERTURA != $codigoSesion) {
            $MENSAJE = "El código ingresado no es válido.";
        } else {
            $REPALETIZAJEEX->__SET('ID_REPALETIZAJE', $IDREPALETIZAJE);
            $REPALETIZAJEEX_ADO->abierto($REPALETIZAJEEX);

            $destinatarios = obtenerDestinatariosAutorizacion($CORREOUSUARIO);
            $asunto = 'Confirmación apertura repaletizaje #' . $datosCorreo['numero'];
            $mensajeCorreo = "Se confirmó la apertura del repaletizaje." . "\r\n\r\n" .
                "Número de repaletizaje: " . $datosCorreo['numero'] . "\r\n" .
                "Motivo: " . $datosCorreo['motivo'] . "\r\n" .
                "Confirmado por: " . $NOMBRECOMPLETOUSUARIO . "\r\n\r\n" .
                "El estado fue marcado como abierto.";

            $remitente = 'informevolcan@gocreative.cl';
            $usuarioSMTP = 'informevolcan@gocreative.cl';
            $contrasenaSMTP = 'bOaKXtke6.#5#v[q';
            $hostSMTP = 'mail.gocreative.cl';
            $puertoSMTP = 465;

            [$envioOk, $errorEnvio] = enviarCorreoSMTP($destinatarios, $asunto, $mensajeCorreo, $remitente, $usuarioSMTP, $contrasenaSMTP, $hostSMTP, $puertoSMTP);
            $MENSAJEENVIO = $envioOk ? "Repaletizaje abierto correctamente." : ($errorEnvio ?: "Repaletizaje abierto, pero hubo un problema al enviar la notificación.");
            unset($_SESSION['REPALETIZAJE_ABRIR_CODIGO'], $_SESSION['REPALETIZAJE_ABRIR_ID'], $_SESSION['REPALETIZAJE_ABRIR_TIEMPO']);
        }
    }
}




?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Agrupado Repaletizaje</title>
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
             
                //FUNCION PARA ABRIR VENTANA QUE SE ENCUENTRA LA OPERACIONES DE DETALLE DE RECEPCION
                function abrirVentana(url) {
                    var opciones =
                        "'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1000, height=800'";
                    window.open(url, 'window', opciones);
                }

                function abrirPestana(url) {
                    var win = window.open(url, '_blank');
                    win.focus();
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
                                <h3 class="page-title">Frigorifico</h3>
                                <div class="d-inline-block align-items-center">
                                    <nav>
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                            <li class="breadcrumb-item" aria-current="page">Módulo</li>
                                            <li class="breadcrumb-item" aria-current="page">Frigorifico</li>
                                            <li class="breadcrumb-item" aria-current="page">Repaletizaje</li>
                                            <li class="breadcrumb-item active" aria-current="page"> <a href="#"> Agrupado Repaletizaje </a> </li>
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
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="repaletizajept" class="table-hover " style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Número </th>
                                                        <th>Estado </th>
                                                        <th class="text-center">Operaciónes </th>
                                                        <th>Folio Original </th>
                                                        <th>Folio Nuevo </th>
                                                        <th>Cantidad Envase </th>
                                                        <th>Kilo Neto Entrada</th>
                                                        <th>Kilo Neto Salida</th>
                                                        <th>Motivo </th>
                                                        <th>Inspección</th>
                                                        <th>Fecha Ingreso </th>
                                                        <th>Fecha Modificación </th>
                                                        <th>Empresa</th>
                                                        <th>Planta</th>
                                                        <th>Temporada</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ARRAYREPALETIZAJEEX as $r) : ?>

                                                        <?php
                                                        $ARRAYTOMADO = $EXIEXPORTACION_ADO->buscarPorRepaletizaje2($r['ID_REPALETIZAJE']);
                                                        if (empty($ARRAYTOMADO)) {
                                                            $DISABLEDT = "disabled"; 
                                                        }else{
                                                            $DISABLEDT = ""; 
                                                        }
                                                        $ARRAYFOLIOREPALETIZAJE = $EXIEXPORTACION_ADO->buscarPorRepaletizajeAgrupado($r['ID_REPALETIZAJE']);
                                                        if ($ARRAYFOLIOREPALETIZAJE) {
                                                            foreach ($ARRAYFOLIOREPALETIZAJE as $dr) :
                                                                $FOLIOORIGINAL = $FOLIOORIGINAL . $dr['FOLIO_AUXILIAR_EXIEXPORTACION'] . ", ";
                                                            endforeach;
                                                        } else {
                                                            $FOLIOORIGINAL = "";
                                                        }
                                                        $ARRAYFOLIODREPALETIZAJE = $DREPALETIZAJEEX_ADO->buscarDrepaletizajeAgrupadoFolio($r['ID_REPALETIZAJE']);
                                                        if ($ARRAYFOLIODREPALETIZAJE) {
                                                            foreach ($ARRAYFOLIODREPALETIZAJE as $dr) :
                                                                $FOLIONUEVO = $FOLIONUEVO . $dr['FOLIO_NUEVO_DREPALETIZAJE'] . ", ";
                                                            endforeach;
                                                        } else {
                                                            $FOLIONUEVO = "";
                                                        }

                                                        $ARRAYEMPRESA = $EMPRESA_ADO->verEmpresa($r['ID_EMPRESA']);
                                                        if ($ARRAYEMPRESA) {
                                                            $NOMBREEMPRESA = $ARRAYEMPRESA[0]['NOMBRE_EMPRESA'];
                                                        } else {
                                                            $NOMBREEMPRESA = "Sin Datos";
                                                        }
                                                        $ARRAYPLANTA = $PLANTA_ADO->verPlanta($r['ID_PLANTA']);
                                                        if ($ARRAYPLANTA) {
                                                            $NOMBREPLANTA = $ARRAYPLANTA[0]['NOMBRE_PLANTA'];
                                                        } else {
                                                            $NOMBREPLANTA = "Sin Datos";
                                                        }
                                                        $ARRAYTEMPORADA = $TEMPORADA_ADO->verTemporada($r['ID_TEMPORADA']);
                                                        if ($ARRAYTEMPORADA) {
                                                            $NOMBRETEMPORADA = $ARRAYTEMPORADA[0]['NOMBRE_TEMPORADA'];
                                                        } else {
                                                            $NOMBRETEMPORADA = "Sin Datos";
                                                        }
                                                        if($r['SINPSAG']==1){
                                                            $SINPSAG="Si";
                                                            $DISABLEDS = ""; 
                                                        }else if($r['SINPSAG']==0){     
                                                            $SINPSAG="No";      
                                                            $DISABLEDS = "disabled";                                            
                                                        }else{  
                                                            $SINPSAG="Sin Datos";       
                                                        }
                                                        ?>
                                                        <tr class="text-center">
                                                            <td><?php echo $r['NUMERO_REPALETIZAJE']; ?> </td>
                                                            <td>
                                                                <?php if ($r['ESTADO'] == "0") { ?>
                                                                    <button type="button" class="btn btn-block btn-danger">Cerrado</button>
                                                                <?php  }  ?>
                                                                <?php if ($r['ESTADO'] == "1") { ?>
                                                                    <button type="button" class="btn btn-block btn-success">Abierto</button>
                                                                <?php  }  ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <form method="post" id="form1">
                                                                    <div class="list-icons d-inline-flex">
                                                                        <div class="list-icons-item dropdown">
                                                                            <button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                <i class="glyphicon glyphicon-cog"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <button class="dropdown-menu" aria-labelledby="dropdownMenuButton"></button>
                                                                                <input type="hidden" class="form-control" placeholder="ID" id="ID" name="ID" value="<?php echo $r['ID_REPALETIZAJE']; ?>" />
                                                                                <input type="hidden" class="form-control" placeholder="URL" id="URL" name="URL" value="registroRepaletizajePTFrigorifico" />
                                                                                <input type="hidden" class="form-control" placeholder="URL" id="URLO" name="URLO" value="listarRepaletizajePTFrigorifico" />
                                                                                <?php if ($r['ESTADO'] == "0") { ?>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Ver">
                                                                                        <button type="submit" class="btn btn-info btn-block " id="VERURL" name="VERURL">
                                                                                            <i class="ti-eye"></i> Ver
                                                                                        </button>
                                                                                    </span>
                                                                                <?php } ?>
                                                                                <?php if ($r['ESTADO'] == "1") { ?>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Editar">
                                                                                        <button type="submit" class="btn  btn-warning btn-block" id="EDITARURL" name="EDITARURL">
                                                                                            <i class="ti-pencil-alt"></i> Editar
                                                                                        </button>
                                                                                    </span>
                                                                                <?php } ?>
                                                                                <?php if ($r['ESTADO'] == "0") { ?>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Abrir repaletizaje">
                                                                                        <button type="submit" class="btn btn-primary btn-block" id="SOLICITAR_ABRIR" name="SOLICITAR_ABRIR">
                                                                                            <i class="fa fa-unlock"></i> Solicitar abrir
                                                                                        </button>
                                                                                    </span>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Confirmar apertura">
                                                                                        <input type="text" class="form-control mb-1" id="CODIGO_ABRIR" name="CODIGO_ABRIR" placeholder="Código autorización">
                                                                                        <button type="submit" class="btn btn-success btn-block" id="CONFIRMAR_ABRIR" name="CONFIRMAR_ABRIR">
                                                                                            <i class="fa fa-check"></i> Confirmar abrir
                                                                                        </button>
                                                                                    </span>
                                                                                <?php } ?>
                                                                                <?php if ($r['ESTADO'] == "1") { ?>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Solicitar eliminar">
                                                                                        <button type="submit" class="btn btn-danger btn-block" id="SOLICITARELIMINAR" name="SOLICITARELIMINAR">
                                                                                            <i class="fa fa-envelope"></i> Solicitar eliminar
                                                                                        </button>
                                                                                    </span>
                                                                                    <span href="#" class="dropdown-item" data-toggle="tooltip" title="Confirmar eliminación">
                                                                                        <input type="text" class="form-control mb-1" id="CODIGO_ELIMINAR" name="CODIGO_ELIMINAR" placeholder="Código autorización">
                                                                                        <button type="submit" class="btn btn-outline-danger btn-block" id="CONFIRMAR_ELIMINAR" name="CONFIRMAR_ELIMINAR">
                                                                                            <i class="fa fa-trash"></i> Confirmar eliminar
                                                                                        </button>
                                                                                    </span>
                                                                                <?php } ?>
                                                                                <hr>
                                                                                <span href="#" class="dropdown-item" data-toggle="tooltip" title="Informe">
                                                                                    <button type="button" class="btn  btn-danger  btn-block" id="defecto" name="informe" title="Informe" <?php if ($r['ESTADO'] == "1") { echo "disabled"; } ?> Onclick="abrirPestana('../../assest/documento/informeRepaletizajePT.php?parametro=<?php echo $r['ID_REPALETIZAJE']; ?>&&usuario=<?php echo $IDUSUARIOS; ?>'); ">
                                                                                        <i class="fa fa-file-pdf-o"></i> Informe
                                                                                    </button>
                                                                                </span>
                                                                                <span href="#" class="dropdown-item" data-toggle="tooltip" title="Tarja">
                                                                                    <button type="button" class="btn  btn-danger  btn-block" id="defecto" name="informe" title="Informe"  Onclick="abrirPestana('../../assest/documento/informeTarjasRepaletizajePT.php?parametro=<?php echo $r['ID_REPALETIZAJE']; ?>'); ">
                                                                                        <i class="fa fa-file-pdf-o"></i> Tarja
                                                                                    </button>
                                                                                </span>
                                                                                <span href="#" class="dropdown-item" data-toggle="tooltip" title="CSV">
                                                                                    <button type="button" class="btn  btn-success btn-block" id="defecto" name="tarjas" title="Archivo Plano" <?php echo $DISABLEDS; ?> <?php echo $DISABLEDT; ?> Onclick="abrirPestana('../../assest/csv/CsvRepa.php?parametro=<?php echo $r['ID_REPALETIZAJE']; ?>&&usuario=<?php echo $IDUSUARIOS; ?>'); ">
                                                                                        <i class="fa fa-file-excel-o"></i> Archivo Plano
                                                                                    </button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </td>
                                                            <td><?php echo $FOLIOORIGINAL; ?> </td>
                                                            <td><?php echo $FOLIONUEVO; ?> </td>
                                                            <td><?php echo $r['CANTIDAD_ENVASE_REPALETIZAJE']; ?> </td>
                                                            <td><?php echo $r['NETOO']; ?> </td>
                                                            <td><?php echo $r['NETOR']; ?> </td>
                                                            <td><?php echo $r['MOTIVO_REPALETIZAJE']; ?> </td>
                                                            <td><?php echo $SINPSAG; ?> </td>
                                                            <td><?php echo $r['INGRESO']; ?> </td>
                                                            <td><?php echo $r['MODIFICACION']; ?> </td>
                                                            <td><?php echo $NOMBREEMPRESA; ?></td>
                                                            <td><?php echo $NOMBREPLANTA; ?></td>
                                                            <td><?php echo $NOMBRETEMPORADA; ?></td>
                                                            <?php 
                                                                $FOLIONUEVO="";
                                                                $FOLIOORIGINAL="";                                                            
                                                            ?>
                                                        </tr>
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
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Total Neto</div>
                                                    <button class="btn   btn-default" id="TOTALNETOV" name="TOTALNETOV" >                                                           
                                                    </button>
                                                </div>
                                            </div>
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
                <?php include_once "../../assest/config/footer.php"; ?>
                <?php include_once "../../assest/config/menuExtraFruta.php"; ?>
        </div>
        <!- LLAMADA URL DE ARCHIVOS DE DISEÑO Y JQUERY E OTROS -!>
            <?php include_once "../../assest/config/urlBase.php"; ?>
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                showConfirmButton: true
            })

            Toast.fire({
                icon: "info",
                title: "Informacion importante",
                html: "<label>Los <b>repaletizajes</b> tienen que estar <b>Cerrados</b> para que los folios nuevos estén disponible para inspeccionar.</label>"
            })
        </script>
</body>

</html>