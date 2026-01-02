<?php
/**
 * Envío automático de folios de exportación con más de 3 días sin inspección SAG.
 * Ejecutar vía CLI o cron, opcionalmente filtrando por empresa/planta/temporada:
 *   php alertaFoliosExiexportacion.php --empresa=1 --planta=2 --temporada=5 --force
 */

$BASE_PATH = dirname(__DIR__, 2);
require_once $BASE_PATH . '/assest/config/BDCONFIG.php';
require_once $BASE_PATH . '/assest/controlador/EXIEXPORTACION_ADO.php';
require_once $BASE_PATH . '/assest/controlador/EEXPORTACION_ADO.php';
require_once $BASE_PATH . '/assest/controlador/PRODUCTOR_ADO.php';
require_once $BASE_PATH . '/assest/controlador/VESPECIES_ADO.php';
require_once $BASE_PATH . '/assest/controlador/ESPECIES_ADO.php';
require_once $BASE_PATH . '/assest/controlador/TINPSAG_ADO.php';
require_once $BASE_PATH . '/assest/controlador/INPSAG_ADO.php';
require_once $BASE_PATH . '/assest/controlador/TMANEJO_ADO.php';
require_once $BASE_PATH . '/assest/controlador/EMPRESA_ADO.php';
require_once $BASE_PATH . '/assest/controlador/PLANTA_ADO.php';
require_once $BASE_PATH . '/assest/controlador/TEMPORADA_ADO.php';

date_default_timezone_set('America/Santiago');
$CONFIG_PATH = __DIR__ . '/../../data/config_cron_pt.json';

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
        return [false, "No se pudo conectar al servidor SMTP ({$errstr})"];
    }

    if (function_exists('stream_set_timeout')) {
        stream_set_timeout($conexion, $timeout);
    }

    $leerRespuesta = function () use ($conexion) {
        $respuesta = '';
        while ($linea = fgets($conexion, 515)) {
            $respuesta .= $linea;
            if (isset($linea[3]) && $linea[3] === ' ') {
                break;
            }
        }
        return $respuesta;
    };

    $comando = function ($instruccion, $codigoEsperado) use ($conexion, $leerRespuesta) {
        fwrite($conexion, $instruccion . "\r\n");
        $respuesta = $leerRespuesta();
        if (substr($respuesta, 0, 3) !== $codigoEsperado) {
            throw new Exception("Error SMTP en '{$instruccion}': {$respuesta}");
        }
        return $respuesta;
    };

    $respuestaInicial = $leerRespuesta();
    if (substr($respuestaInicial, 0, 3) !== '220') {
        fclose($conexion);
        return [false, "El servidor SMTP no respondió correctamente: {$respuestaInicial}"];
    }

    $hostEhlo = $host ?: 'localhost';
    try {
        $comando('EHLO ' . $hostEhlo, '250');
    } catch (Exception $e) {
        $comando('HELO ' . $hostEhlo, '250');
    }

    try {
        $comando('AUTH LOGIN', '334');
        $comando(base64_encode($usuario), '334');
        $comando(base64_encode($contrasena), '235');
    } catch (Exception $e) {
        fclose($conexion);
        return [false, "Error de autenticación SMTP: " . $e->getMessage()];
    }

    try {
        $comando("MAIL FROM:<{$remitente}>", '250');
        foreach ($destinatarios as $correo) {
            $comando("RCPT TO:<{$correo}>", '250');
        }
        $comando('DATA', '354');

        $cabeceras = "Date: " . date('r') . "\r\n" .
            "Message-ID: <" . uniqid() . "@" . ($hostEhlo ?: 'localhost') . ">\r\n" .
            "From: {$remitente}\r\n" .
            "Return-Path: {$remitente}\r\n" .
            "Reply-To: {$remitente}\r\n" .
            "To: " . implode(', ', $destinatarios) . "\r\n" .
            "Subject: {$asunto}\r\n" .
            "MIME-Version: 1.0\r\n" .
            "X-Mailer: PHP/" . phpversion() . "\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n\r\n";

        $mensajeNormalizado = str_replace(["\r\n", "\n"], "\r\n", $mensaje);
        fwrite($conexion, $cabeceras . $mensajeNormalizado . "\r\n.\r\n");
        $respuestaData = $leerRespuesta();
        if (substr($respuestaData, 0, 3) !== '250') {
            throw new Exception("Error SMTP tras DATA: {$respuestaData}");
        }
        $comando('QUIT', '221');
    } catch (Exception $e) {
        fclose($conexion);
        return [false, "Error al enviar correo: " . $e->getMessage()];
    }

    fclose($conexion);
    return [true, null];
}

function obtenerTemporadaVigente($TEMPORADA_ADO, $temporadaIdManual = null)
{
    if ($temporadaIdManual) {
        return $temporadaIdManual;
    }
    $temporadas = $TEMPORADA_ADO->listarTemporadaCBX() ?: [];
    if (empty($temporadas)) {
        return null;
    }
    usort($temporadas, fn($a, $b) => ($b['ID_TEMPORADA'] ?? 0) <=> ($a['ID_TEMPORADA'] ?? 0));
    return $temporadas[0]['ID_TEMPORADA'] ?? null;
}

function obtenerFoliosAtrasados($empresaId, $plantaId, $temporadaId, $EXIEXPORTACION_ADO, $EEXPORTACION_ADO, $PRODUCTOR_ADO, $VESPECIES_ADO, $INPSAG_ADO, $cache)
{
    $resultado = [];
    $agrupados = $EXIEXPORTACION_ADO->listarExiexportacionAgrupadoPorFolioEmpresaPlantaTemporadaDisponible($empresaId, $plantaId, $temporadaId);
    foreach ($agrupados as $s) {
        $detalles = $EXIEXPORTACION_ADO->listarExiexportacionEmpresaPlantaTemporadaPorFolioDisponible($empresaId, $plantaId, $temporadaId, $s['FOLIO_AUXILIAR_EXIEXPORTACION']);
        foreach ($detalles as $r) {
            $fechaEmbalado = null;
            if (!empty($r['EMBALADO'])) {
                $fechaEmbalado = DateTime::createFromFormat('Y-m-d', $r['EMBALADO']) ?: DateTime::createFromFormat('d/m/Y', $r['EMBALADO']);
            }
            $dias = $fechaEmbalado ? $fechaEmbalado->setTime(0, 0)->diff(new DateTime('today'))->format('%a') : null;
            if (($r['ID_INPSAG'] ?? null) || !is_numeric($dias) || $dias <= 3) {
                continue;
            }

            $productor = "Sin Datos";
            if (!isset($cache['PRODUCTOR'][$r['ID_PRODUCTOR']])){
                $cache['PRODUCTOR'][$r['ID_PRODUCTOR']] = $PRODUCTOR_ADO->verProductor($r['ID_PRODUCTOR']);
            }
            if (!empty($cache['PRODUCTOR'][$r['ID_PRODUCTOR']])) {
                $productor = $cache['PRODUCTOR'][$r['ID_PRODUCTOR']][0]['NOMBRE_PRODUCTOR'];
            }

            $variedad = "Sin Datos";
            if (!isset($cache['VESPECIES'][$r['ID_VESPECIES']])) {
                $cache['VESPECIES'][$r['ID_VESPECIES']] = $VESPECIES_ADO->verVespecies($r['ID_VESPECIES']);
            }
            if (!empty($cache['VESPECIES'][$r['ID_VESPECIES']])) {
                $variedad = $cache['VESPECIES'][$r['ID_VESPECIES']][0]['NOMBRE_VESPECIES'];
            }

            $sif = "Sin Datos";
            $inpsag = $INPSAG_ADO->verInpsag3($r['ID_INPSAG']) ?: [];
            if (!empty($inpsag[0]['CORRELATIVO_INPSAG'])) {
                $sif = $inpsag[0]['CORRELATIVO_INPSAG'];
            }

            $estandar = "Sin Datos";
            if (!isset($cache['ESTANDAR'][$r['ID_ESTANDAR']])) {
                $cache['ESTANDAR'][$r['ID_ESTANDAR']] = $EEXPORTACION_ADO->verEstandar($r['ID_ESTANDAR']);
            }
            if (!empty($cache['ESTANDAR'][$r['ID_ESTANDAR']])) {
                $estandar = $cache['ESTANDAR'][$r['ID_ESTANDAR']][0]['CODIGO_ESTANDAR'];
            }

            $resultado[] = [
                'folio' => $r['FOLIO_AUXILIAR_EXIEXPORTACION'],
                'productor' => $productor,
                'variedad' => $variedad,
                'dias' => $dias,
                'embalado' => $r['EMBALADO'] ?? 'Sin Datos',
                'sif' => $sif,
                'estandar' => $estandar,
            ];
        }
    }
    return $resultado;
}

$options = getopt('', ['empresa::', 'planta::', 'temporada::', 'force::']);
if ($options === false) {
    $options = [];
}
$temporadaManual = isset($options['temporada']) ? (int) $options['temporada'] : null;
$force = array_key_exists('force', $options);

$config = [
    'hora' => '',
    'dias' => [],
    'correos' => '',
    'empresas' => [],
    'plantas' => [],
    'usuarios' => []
];
if (file_exists($CONFIG_PATH)) {
    $cfg = json_decode(file_get_contents($CONFIG_PATH), true);
    if (is_array($cfg)) {
        $config = array_merge($config, $cfg);
    }
}

$horaConfig = trim((string)($config['hora'] ?? ''));
$diaSemana = (int)date('N'); //1 lunes
$desdeInclude = defined('CRON_FOLIOS_INCLUDE_ONLY');
if (!$desdeInclude) {
    if (!$horaConfig || empty($config['dias']) || !in_array((string)$diaSemana, $config['dias'], true)) {
        echo "Configuración de hora/días no válida o día no seleccionado. Abortando.\n";
        exit(0);
    }

    $ahora = new DateTime('now');
    $objetivo = DateTime::createFromFormat('H:i', $horaConfig) ?: new DateTime('today');
    $objetivo->setDate((int)$ahora->format('Y'), (int)$ahora->format('m'), (int)$ahora->format('d'));
    if (!$force && $ahora < $objetivo) {
        echo "Aún no se alcanza la hora configurada ({$horaConfig}). Abortando.\n";
        exit(0);
    }
}

$empresaFiltroLista = array_map('intval', $config['empresas'] ?? []);
$plantaFiltroLista = array_map('intval', $config['plantas'] ?? []);

$destinatariosManual = array_filter(array_map('trim', explode(',', $config['correos'] ?? '')));
$destinatariosUsuarios = array_filter(array_map('trim', $config['usuarios'] ?? []));
$destinatarios = array_values(array_unique(array_merge($destinatariosManual, $destinatariosUsuarios)));
if (empty($destinatarios)) {
    echo "Sin destinatarios configurados.\n";
    exit(0);
}

$EXIEXPORTACION_ADO = new EXIEXPORTACION_ADO();
$EEXPORTACION_ADO = new EEXPORTACION_ADO();
$PRODUCTOR_ADO = new PRODUCTOR_ADO();
$VESPECIES_ADO = new VESPECIES_ADO();
$INPSAG_ADO = new INPSAG_ADO();
$EMPRESA_ADO = new EMPRESA_ADO();
$PLANTA_ADO = new PLANTA_ADO();
$TEMPORADA_ADO = new TEMPORADA_ADO();

$temporadaId = obtenerTemporadaVigente($TEMPORADA_ADO, $temporadaManual);
if (!$temporadaId) {
    echo "No se encontró una temporada vigente.\n";
    exit(1);
}

$lockFile = __DIR__ . '/alerta_folios_exiexportacion.lock';
$hoy = date('Y-m-d');
if (!$force && !$desdeInclude && file_exists($lockFile) && trim(@file_get_contents($lockFile)) === $hoy . ' ' . $horaConfig) {
    echo "Alerta ya enviada hoy a la hora configurada. Use --force para reenviar.\n";
    exit(0);
}

$empresas = $EMPRESA_ADO->listarEmpresaCBX() ?: [];
$plantas = $PLANTA_ADO->listarPlantaCBX() ?: [];
$enviosRealizados = 0;

foreach ($empresas as $empresa) {
    if (!empty($empresaFiltroLista) && !in_array((int)$empresa['ID_EMPRESA'], $empresaFiltroLista, true)) {
        continue;
    }
    foreach ($plantas as $planta) {
        if (!empty($plantaFiltroLista) && !in_array((int)$planta['ID_PLANTA'], $plantaFiltroLista, true)) {
            continue;
        }

        $cache = ['PRODUCTOR' => [], 'VESPECIES' => [], 'ESTANDAR' => []];
        $folios = obtenerFoliosAtrasados(
            $empresa['ID_EMPRESA'],
            $planta['ID_PLANTA'],
            $temporadaId,
            $EXIEXPORTACION_ADO,
            $EEXPORTACION_ADO,
            $PRODUCTOR_ADO,
            $VESPECIES_ADO,
            $INPSAG_ADO,
            $cache
        );

        if (empty($folios)) {
            continue;
        }

        $lineas = array_map(function ($item) {
            return "Folio: {$item['folio']} | Productor: {$item['productor']} | Variedad: {$item['variedad']} | Días: {$item['dias']} | Embalado: {$item['embalado']} | SIF: {$item['sif']} | Estandar: {$item['estandar']}";
        }, $folios);

        $mensaje = "Empresa: {$empresa['NOMBRE_EMPRESA']}\r\nPlanta: {$planta['NOMBRE_PLANTA']}\r\nTemporada ID: {$temporadaId}\r\n\r\n";
        $mensaje .= "Folios con más de 3 días sin inspección SAG:\r\n\r\n" . implode("\r\n", $lineas);

        $asunto = "Alerta folios sin inspección - {$empresa['NOMBRE_EMPRESA']} / {$planta['NOMBRE_PLANTA']}";
        [$ok, $error] = enviarCorreoSMTP($destinatarios, $asunto, $mensaje, 'informes@volcanfoods.cl', 'informes@volcanfoods.cl', '1z=EWfu0026k', 'mail.volcanfoods.cl', 465);
        if ($ok) {
            $enviosRealizados++;
            echo "Enviado: {$empresa['NOMBRE_EMPRESA']} / {$planta['NOMBRE_PLANTA']} (" . count($folios) . " folios)\n";
        } else {
            echo "Error enviando {$empresa['NOMBRE_EMPRESA']} / {$planta['NOMBRE_PLANTA']}: {$error}\n";
        }
    }
}

if ($enviosRealizados > 0 && !$desdeInclude) {
    @file_put_contents($lockFile, $hoy . ' ' . $horaConfig);
}

echo "Proceso finalizado. Envios: {$enviosRealizados}\n";
