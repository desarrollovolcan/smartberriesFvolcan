<?php

include_once "../../assest/config/validarUsuarioExpo.php";

$RUTA_CONFIG_CRON_PT = dirname(__DIR__, 2) . '/data/config_cron_pt.json';
$RUTA_EJECUCION_CRON_PT = dirname(__DIR__, 2) . '/fruta/cron/alertaFoliosExiexportacion.php';

$CONFIG_ENVIO = [
    'habilitado' => true,
    'actualizado_en' => null,
    'hora' => '',
    'dias' => [],
    'correos' => '',
    'empresas' => [],
    'plantas' => [],
    'usuarios' => []
];

if (file_exists($RUTA_CONFIG_CRON_PT)) {
    $dataConfig = json_decode(file_get_contents($RUTA_CONFIG_CRON_PT), true);
    if (is_array($dataConfig)) {
        $CONFIG_ENVIO = array_merge($CONFIG_ENVIO, $dataConfig);
    }
}
$CONFIG_ENVIO['habilitado'] = isset($CONFIG_ENVIO['habilitado']) ? (bool) $CONFIG_ENVIO['habilitado'] : true;

$MENSAJE_EJECUCION = null;
$MENSAJE_EJECUCION_TIPO = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EJECUTAR_CRON_PT'])) {
    $comando = escapeshellcmd(PHP_BINARY) . ' ' . escapeshellarg($RUTA_EJECUCION_CRON_PT) . ' --force';
    $salida = [];
    $codigo = 0;
    exec($comando . ' 2>&1', $salida, $codigo);
    $MENSAJE_EJECUCION = trim(implode("\n", $salida));
    if ($MENSAJE_EJECUCION === '') {
        $MENSAJE_EJECUCION = $codigo === 0 ? 'Cron ejecutado correctamente.' : 'No fue posible ejecutar el cron.';
    }
    if ($codigo !== 0) {
        $MENSAJE_EJECUCION_TIPO = 'danger';
    }
}

$DIAS_SEMANA = [
    '1' => 'Lunes',
    '2' => 'Martes',
    '3' => 'Miércoles',
    '4' => 'Jueves',
    '5' => 'Viernes',
    '6' => 'Sábado',
    '7' => 'Domingo'
];

function obtenerProximaEjecucion(array $config): ?DateTime
{
    if (empty($config['habilitado'])) {
        return null;
    }

    $hora = trim($config['hora'] ?? '');
    $dias = $config['dias'] ?? [];
    if ($hora === '' || empty($dias)) {
        return null;
    }

    $partesHora = explode(':', $hora);
    if (count($partesHora) < 2) {
        return null;
    }

    $horaInt = max(0, min(23, (int) $partesHora[0]));
    $minutoInt = max(0, min(59, (int) $partesHora[1]));

    $now = new DateTime('now');
    for ($i = 0; $i <= 7; $i++) {
        $candidato = clone $now;
        if ($i > 0) {
            $candidato->modify('+' . $i . ' day');
        }
        $diaCandidato = $candidato->format('N');
        if (!in_array((string) $diaCandidato, $dias, true)) {
            continue;
        }
        $candidato->setTime($horaInt, $minutoInt, 0);
        if ($candidato <= $now) {
            continue;
        }
        return $candidato;
    }

    return null;
}

$proximaEjecucion = obtenerProximaEjecucion($CONFIG_ENVIO);
$timestampProxima = $proximaEjecucion ? $proximaEjecucion->getTimestamp() : null;

$diasSeleccionados = array_map(function ($dia) use ($DIAS_SEMANA) {
    return $DIAS_SEMANA[$dia] ?? $dia;
}, $CONFIG_ENVIO['dias'] ?? []);

if (isset($_GET['estado_cron_pt'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'habilitado' => $CONFIG_ENVIO['habilitado'],
        'hora' => $CONFIG_ENVIO['hora'] ?? '',
        'dias' => $diasSeleccionados,
        'proxima_ejecucion' => $proximaEjecucion ? $proximaEjecucion->format('d/m/Y H:i') : null,
        'timestamp' => $timestampProxima,
        'actualizado_en' => $CONFIG_ENVIO['actualizado_en'] ?? null,
    ]);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Cron ejecutados</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <?php include_once "../../assest/config/urlHead.php"; ?>
    <style>
        .cron-ejecucion-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e7ebf3;
        }
        .cron-ejecucion-title {
            font-weight: 700;
            color: #1f2d3d;
        }
        .cron-ejecucion-subtitle {
            font-size: 0.85rem;
            color: #6c7a89;
        }
    </style>
</head>

<body class="hold-transition light-skin fixed sidebar-mini theme-primary">
    <div class="wrapper">
        <?php include_once "../../assest/config/menuExpo.php"; ?>
        <div class="content-wrapper">
            <div class="container-full">
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="page-title">Cron ejecutados</h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Cron en ejecución</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <?php include_once "../../assest/config/verIndicadorEconomico.php"; ?>
                    </div>
                </div>

                <section class="content">
                    <div class="row">
                        <div class="col-12">
                            <div class="box cron-ejecucion-card">
                                <div class="box-body">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
                                        <div>
                                            <h4 class="cron-ejecucion-title mb-5">Cron en ejecución</h4>
                                            <span class="cron-ejecucion-subtitle">Listado de tareas activas y próximas ejecuciones.</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-pill <?php echo $CONFIG_ENVIO['habilitado'] ? 'badge-success' : 'badge-secondary'; ?> mr-10">
                                                <?php echo $CONFIG_ENVIO['habilitado'] ? 'Habilitado' : 'Deshabilitado'; ?>
                                            </span>
                                            <form method="post" class="m-0">
                                                <button type="submit" name="EJECUTAR_CRON_PT" class="btn btn-sm btn-primary">
                                                    Ejecutar cron ahora
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php if (!empty($MENSAJE_EJECUCION)) { ?>
                                        <div class="alert alert-<?php echo $MENSAJE_EJECUCION_TIPO; ?> py-5 px-10">
                                            <?php echo nl2br(htmlspecialchars($MENSAJE_EJECUCION)); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Cron</th>
                                                    <th>Próxima ejecución</th>
                                                    <th>Cuenta regresiva</th>
                                                    <th>Detalle rápido</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Cron PT</strong></td>
                                                    <td>
                                                        <?php if ($proximaEjecucion) { ?>
                                                            <span id="proxima-ejecucion">
                                                                <?php echo $proximaEjecucion->format('d/m/Y H:i'); ?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <span id="proxima-ejecucion" class="text-muted">Sin programación</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <span class="cron-countdown" data-countdown="<?php echo $timestampProxima ?? ''; ?>">
                                                            <?php echo $timestampProxima ? 'Calculando...' : 'No disponible'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div><strong>Hora:</strong> <span id="cron-hora"><?php echo $CONFIG_ENVIO['hora'] ?: 'No definida'; ?></span></div>
                                                        <div><strong>Días:</strong> <span id="cron-dias"><?php echo !empty($diasSeleccionados) ? implode(', ', $diasSeleccionados) : 'No definidos'; ?></span></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php include_once "../../assest/config/menuExtraExpo.php"; ?>
    </div>

    <script>
        function formatCountdown(ms) {
            if (ms <= 0) {
                return 'En ejecución';
            }
            const totalSeconds = Math.floor(ms / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            const parts = [];
            if (days > 0) {
                parts.push(days + 'd');
            }
            parts.push(String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0'));
            return parts.join(' ');
        }

        function actualizarCuentaRegresiva() {
            document.querySelectorAll('.cron-countdown').forEach((element) => {
                const timestamp = element.getAttribute('data-countdown');
                if (!timestamp) {
                    element.textContent = 'No disponible';
                    return;
                }
                const diff = (parseInt(timestamp, 10) * 1000) - Date.now();
                element.textContent = formatCountdown(diff);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            actualizarCuentaRegresiva();
            setInterval(actualizarCuentaRegresiva, 1000);
        });
    </script>
    <script>
        const estadoInicial = <?php echo json_encode($CONFIG_ENVIO['actualizado_en'] ?? null); ?>;
        let ultimoActualizado = estadoInicial;

        function aplicarEstadoCron(data) {
            const proxima = document.getElementById('proxima-ejecucion');
            const hora = document.getElementById('cron-hora');
            const dias = document.getElementById('cron-dias');
            const contador = document.querySelector('.cron-countdown');

            if (proxima) {
                if (data.proxima_ejecucion) {
                    proxima.textContent = data.proxima_ejecucion;
                    proxima.classList.remove('text-muted');
                } else {
                    proxima.textContent = 'Sin programación';
                    proxima.classList.add('text-muted');
                }
            }
            if (contador) {
                contador.setAttribute('data-countdown', data.timestamp || '');
            }
            if (hora) {
                hora.textContent = data.hora || 'No definida';
            }
            if (dias) {
                dias.textContent = (data.dias && data.dias.length) ? data.dias.join(', ') : 'No definidos';
            }
        }

        function verificarActualizacionCron() {
            fetch('cronEjecutados.php?estado_cron_pt=1', { cache: 'no-store' })
                .then((response) => response.json())
                .then((data) => {
                    if (data.actualizado_en !== ultimoActualizado) {
                        ultimoActualizado = data.actualizado_en;
                        aplicarEstadoCron(data);
                        actualizarCuentaRegresiva();
                    }
                })
                .catch(() => {
                    // No interrumpir la vista si no hay respuesta.
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setInterval(verificarActualizacionCron, 15000);
        });
    </script>
</body>

</html>
