<?php

include_once "../../assest/config/validarUsuarioExpo.php";

include_once '../../assest/controlador/EMPRESA_ADO.php';
include_once '../../assest/controlador/PLANTA_ADO.php';
include_once '../../assest/controlador/USUARIO_ADO.php';

$RUTA_CONFIG_CRON_PT = dirname(__DIR__, 2) . '/data/config_cron_pt.json';

$EMPRESA_ADO = new EMPRESA_ADO();
$PLANTA_ADO = new PLANTA_ADO();
$USUARIO_ADO = new USUARIO_ADO();

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

$ARRAYEMPRESAS = $EMPRESA_ADO->listarEmpresaCBX();
$ARRAYPLANTAS = $PLANTA_ADO->listarPlantaCBX();
$ARRAYUSUARIOS = $USUARIO_ADO->listarUsuarioCBX();

$EMPRESAS_NOMBRES = [];
foreach ($ARRAYEMPRESAS as $empresa) {
    $EMPRESAS_NOMBRES[$empresa['ID_EMPRESA']] = $empresa['NOMBRE_EMPRESA'];
}

$PLANTAS_NOMBRES = [];
foreach ($ARRAYPLANTAS as $planta) {
    $PLANTAS_NOMBRES[$planta['ID_PLANTA']] = $planta['NOMBRE_PLANTA'];
}

$USUARIOS_NOMBRES = [];
foreach ($ARRAYUSUARIOS as $usuario) {
    $USUARIOS_NOMBRES[$usuario['EMAIL_USUARIO']] = $usuario['NOMBRE_USUARIO'] . ' (' . $usuario['EMAIL_USUARIO'] . ')';
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

$correosManual = array_filter(array_map('trim', explode(',', $CONFIG_ENVIO['correos'] ?? '')));
$usuariosConfigurados = $CONFIG_ENVIO['usuarios'] ?? [];

$empresasConfiguradas = array_map(function ($id) use ($EMPRESAS_NOMBRES) {
    return $EMPRESAS_NOMBRES[$id] ?? ('Empresa #' . $id);
}, $CONFIG_ENVIO['empresas'] ?? []);

$plantasConfiguradas = array_map(function ($id) use ($PLANTAS_NOMBRES) {
    return $PLANTAS_NOMBRES[$id] ?? ('Planta #' . $id);
}, $CONFIG_ENVIO['plantas'] ?? []);

$usuariosConfiguradosTexto = array_map(function ($correo) use ($USUARIOS_NOMBRES) {
    return $USUARIOS_NOMBRES[$correo] ?? $correo;
}, $usuariosConfigurados);

$diasSeleccionados = array_map(function ($dia) use ($DIAS_SEMANA) {
    return $DIAS_SEMANA[$dia] ?? $dia;
}, $CONFIG_ENVIO['dias'] ?? []);

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
        .cron-detalle-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #3f4b5b;
        }
        .cron-detalle-list li {
            margin-bottom: 4px;
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
                                        <span class="badge badge-pill <?php echo $CONFIG_ENVIO['habilitado'] ? 'badge-success' : 'badge-secondary'; ?>">
                                            <?php echo $CONFIG_ENVIO['habilitado'] ? 'Habilitado' : 'Deshabilitado'; ?>
                                        </span>
                                    </div>
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
                                                            <?php echo $proximaEjecucion->format('d/m/Y H:i'); ?>
                                                        <?php } else { ?>
                                                            <span class="text-muted">Sin programación</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <span class="cron-countdown" data-countdown="<?php echo $timestampProxima ?? ''; ?>">
                                                            <?php echo $timestampProxima ? 'Calculando...' : 'No disponible'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div><strong>Hora:</strong> <?php echo $CONFIG_ENVIO['hora'] ?: 'No definida'; ?></div>
                                                        <div><strong>Días:</strong> <?php echo !empty($diasSeleccionados) ? implode(', ', $diasSeleccionados) : 'No definidos'; ?></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="box cron-ejecucion-card">
                                <div class="box-body">
                                    <h4 class="cron-ejecucion-title mb-10">Detalles de configuración</h4>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="cron-detalle-label">Correos manuales</div>
                                            <ul class="cron-detalle-list list-unstyled mb-15">
                                                <?php if (!empty($correosManual)) { ?>
                                                    <?php foreach ($correosManual as $correo) { ?>
                                                        <li><?php echo $correo; ?></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <li class="text-muted">Sin correos manuales.</li>
                                                <?php } ?>
                                            </ul>
                                            <div class="cron-detalle-label">Usuarios</div>
                                            <ul class="cron-detalle-list list-unstyled">
                                                <?php if (!empty($usuariosConfiguradosTexto)) { ?>
                                                    <?php foreach ($usuariosConfiguradosTexto as $usuarioTexto) { ?>
                                                        <li><?php echo $usuarioTexto; ?></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <li class="text-muted">Sin usuarios seleccionados.</li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="cron-detalle-label">Empresas</div>
                                            <ul class="cron-detalle-list list-unstyled mb-15">
                                                <?php if (!empty($empresasConfiguradas)) { ?>
                                                    <?php foreach ($empresasConfiguradas as $empresaTexto) { ?>
                                                        <li><?php echo $empresaTexto; ?></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <li class="text-muted">Sin empresas configuradas.</li>
                                                <?php } ?>
                                            </ul>
                                            <div class="cron-detalle-label">Plantas</div>
                                            <ul class="cron-detalle-list list-unstyled">
                                                <?php if (!empty($plantasConfiguradas)) { ?>
                                                    <?php foreach ($plantasConfiguradas as $plantaTexto) { ?>
                                                        <li><?php echo $plantaTexto; ?></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <li class="text-muted">Sin plantas configuradas.</li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="cron-detalle-label">Última actualización</div>
                                            <p class="mb-15">
                                                <?php if (!empty($CONFIG_ENVIO['actualizado_en'])) { ?>
                                                    <?php echo date('d/m/Y H:i', $CONFIG_ENVIO['actualizado_en']); ?>
                                                <?php } else { ?>
                                                    <span class="text-muted">No registrada.</span>
                                                <?php } ?>
                                            </p>
                                            <div class="cron-detalle-label">Estado del cron</div>
                                            <p class="mb-0">
                                                <?php echo $CONFIG_ENVIO['habilitado'] ? 'Activo y programado.' : 'Deshabilitado por configuración.'; ?>
                                            </p>
                                        </div>
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
</body>

</html>
