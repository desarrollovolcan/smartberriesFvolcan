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
    'hora' => '',
    'dias' => [],
    'correos' => '',
    'empresas' => [],
    'plantas' => [],
    'usuarios' => []
];
$ARRAYEMPRESAS = $EMPRESA_ADO->listarEmpresaCBX();
$ARRAYPLANTAS = $PLANTA_ADO->listarPlantaCBX();
$ARRAYUSUARIOS = $USUARIO_ADO->listarUsuarioCBX();
$MENSAJE_CONFIG = "";

if (file_exists($RUTA_CONFIG_CRON_PT)) {
    $dataConfig = json_decode(file_get_contents($RUTA_CONFIG_CRON_PT), true);
    if (is_array($dataConfig)) {
        $CONFIG_ENVIO = array_merge($CONFIG_ENVIO, $dataConfig);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CONFIG_CRON_PT'])) {
    $configRecibida = json_decode($_POST['CONFIG_CRON_PT'], true);
    if (is_array($configRecibida)) {
        $configLimpia = [
            'hora' => $configRecibida['hora'] ?? '',
            'dias' => isset($configRecibida['dias']) && is_array($configRecibida['dias']) ? array_values(array_unique($configRecibida['dias'])) : [],
            'correos' => $configRecibida['correos'] ?? '',
            'empresas' => isset($configRecibida['empresas']) && is_array($configRecibida['empresas']) ? array_values(array_unique($configRecibida['empresas'])) : [],
            'plantas' => isset($configRecibida['plantas']) && is_array($configRecibida['plantas']) ? array_values(array_unique($configRecibida['plantas'])) : [],
            'usuarios' => isset($configRecibida['usuarios']) && is_array($configRecibida['usuarios']) ? array_values(array_unique($configRecibida['usuarios'])) : [],
        ];
        if (!is_dir(dirname($RUTA_CONFIG_CRON_PT))) {
            @mkdir(dirname($RUTA_CONFIG_CRON_PT), 0777, true);
        }
        file_put_contents($RUTA_CONFIG_CRON_PT, json_encode($configLimpia, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $CONFIG_ENVIO = array_merge($CONFIG_ENVIO, $configLimpia);
        $MENSAJE_CONFIG = "Configuración guardada correctamente para el envío automático.";

        if (!empty($_POST['TEST_ENVIO_CRON'])) {
            $destinatariosManual = array_filter(array_map('trim', explode(',', $configLimpia['correos'] ?? '')));
            $destinatariosUsuarios = $configLimpia['usuarios'] ?? [];
            $destinatariosPrueba = array_values(array_unique(array_merge($destinatariosManual, $destinatariosUsuarios)));

            if (!empty($destinatariosPrueba)) {
                define('CRON_FOLIOS_INCLUDE_ONLY', true);
                require_once "../../fruta/cron/alertaFoliosExiexportacion.php";
                $mensajePrueba = "Prueba de configuración Cron PT. Hora: {$configLimpia['hora']}, Días: " . implode(',', $configLimpia['dias']) . ".";
                [$ok, $error] = enviarCorreoSMTP($destinatariosPrueba, "Prueba envío Cron PT", $mensajePrueba, 'informes@volcanfoods.cl', 'informes@volcanfoods.cl', '1z=EWfu0026k', 'mail.volcanfoods.cl', 465);
                if ($ok) {
                    $MENSAJE_CONFIG = "Configuración guardada y correo de prueba enviado.";
                } else {
                    $MENSAJE_CONFIG = "Configuración guardada, pero el envío de prueba falló: {$error}";
                }
            } else {
                $MENSAJE_CONFIG = "Configuración guardada. No se enviaron pruebas por falta de destinatarios.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Cron PT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <?php include_once "../../assest/config/urlHead.php"; ?>
</head>

<body class="hold-transition light-skin fixed sidebar-mini theme-primary">
    <div class="wrapper">
        <?php include_once "../../assest/config/menuExpo.php"; ?>
        <div class="content-wrapper">
            <div class="container-full">
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="mr-auto">
                            <h3 class="page-title">Cron PT</h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Configuración Cron PT</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <?php include_once "../../assest/config/verIndicadorEconomico.php"; ?>
                    </div>
                </div>

                <section class="content">
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col-xs-12">
                            <div class="box">
                                <div class="box-header with-border bg-success d-flex justify-content-between align-items-center">
                                    <h4 class="box-title mb-0">Configuración Cron PT</h4>
                                    <small class="text-muted">Horario, días, destinos y alcance</small>
                                </div>
                                <div class="box-body">
                                    <?php if (!empty($MENSAJE_CONFIG)) { ?>
                                        <div class="alert alert-success py-5 px-10">
                                            <?php echo $MENSAJE_CONFIG; ?>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group mb-10">
                                                <label class="font-weight-600">Hora de envío</label>
                                                <input type="time" class="form-control" id="HORA_ENVIO" name="HORA_ENVIO">
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="font-weight-600">Días de envío</label>
                                                <div class="d-flex flex-wrap">
                                                    <?php
                                                    $diasSemana = [
                                                        '1' => 'Lunes',
                                                        '2' => 'Martes',
                                                        '3' => 'Miércoles',
                                                        '4' => 'Jueves',
                                                        '5' => 'Viernes',
                                                        '6' => 'Sábado',
                                                        '7' => 'Domingo'
                                                    ];
                                                    foreach ($diasSemana as $diaClave => $diaNombre) {
                                                        echo '<div class="mr-10 mb-5 custom-control custom-checkbox">';
                                                        echo '<input type="checkbox" class="custom-control-input" id="DIA_' . $diaClave . '" value="' . $diaClave . '">';
                                                        echo '<label class="custom-control-label" for="DIA_' . $diaClave . '">' . $diaNombre . '</label>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="font-weight-600">Correos (separados por coma)</label>
                                                <textarea class="form-control" id="CORREOS_DESTINO" name="CORREOS_DESTINO" rows="4" placeholder="correo1@dominio.cl, correo2@dominio.cl"></textarea>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="font-weight-600">Usuarios</label>
                                                <select class="form-control select2" id="USUARIOS_DESTINO" multiple>
                                                    <?php foreach ($ARRAYUSUARIOS as $usuario) { ?>
                                                        <option value="<?php echo $usuario['EMAIL_USUARIO']; ?>">
                                                            <?php echo $usuario['NOMBRE_USUARIO'] . ' - ' . $usuario['EMAIL_USUARIO']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="font-weight-600">Empresas</label>
                                                <select class="form-control select2" id="EMPRESAS_DESTINO" multiple>
                                                    <?php foreach ($ARRAYEMPRESAS as $empresa) { ?>
                                                        <option value="<?php echo $empresa['ID_EMPRESA']; ?>">
                                                            <?php echo $empresa['NOMBRE_EMPRESA']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <small class="text-muted">Selecciona las empresas incluidas.</small>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label class="font-weight-600">Plantas</label>
                                                <select class="form-control select2" id="PLANTAS_DESTINO" multiple>
                                                    <?php foreach ($ARRAYPLANTAS as $planta) { ?>
                                                        <option value="<?php echo $planta['ID_PLANTA']; ?>">
                                                            <?php echo $planta['NOMBRE_PLANTA']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <small class="text-muted">Selecciona las plantas incluidas.</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-10">
                                        <div id="alerta-config" class="text-success" style="display:none;"></div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm" id="GUARDAR_CONFIG_ENVIO">
                                                <i class="ti-save"></i> Guardar configuración
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" id="PROBAR_ENVIO_CONFIG">
                                                <i class="ti-email"></i> Probar envío
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php include_once "../../assest/config/footer.php"; ?>
        <?php include_once "../../assest/config/menuExtraExpo.php"; ?>
        <form id="form-config-cron-pt" method="POST" style="display:none;">
            <input type="hidden" name="CONFIG_CRON_PT" id="CONFIG_CRON_PT">
            <input type="hidden" name="TEST_ENVIO_CRON" id="TEST_ENVIO_CRON">
        </form>
    </div>
    <?php include_once "../../assest/config/urlBase.php"; ?>
    <script>
        (function () {
            const keyConfig = 'config_envio_cron_pt';
            const servidorConfig = <?php echo json_encode($CONFIG_ENVIO, JSON_UNESCAPED_UNICODE); ?>;
            if (!localStorage.getItem(keyConfig)) {
                localStorage.setItem(keyConfig, JSON.stringify(servidorConfig));
            }
            const horaInput = document.getElementById('HORA_ENVIO');
            const correosInput = document.getElementById('CORREOS_DESTINO');
            const empresasSelect = document.getElementById('EMPRESAS_DESTINO');
            const plantasSelect = document.getElementById('PLANTAS_DESTINO');
            const usuariosSelect = document.getElementById('USUARIOS_DESTINO');
            const guardarBtn = document.getElementById('GUARDAR_CONFIG_ENVIO');
            const probarBtn = document.getElementById('PROBAR_ENVIO_CONFIG');
            const alerta = document.getElementById('alerta-config');
            const formCron = document.getElementById('form-config-cron-pt');
            const inputCron = document.getElementById('CONFIG_CRON_PT');
            const inputTest = document.getElementById('TEST_ENVIO_CRON');
            const diasCheckboxes = Array.from(document.querySelectorAll('[id^="DIA_"]'));

            const cargarConfig = () => {
                try {
                    const datos = JSON.parse(localStorage.getItem(keyConfig) || '{}');
                    if (datos.hora) {
                        horaInput.value = datos.hora;
                    }
                    if (Array.isArray(datos.dias)) {
                        diasCheckboxes.forEach(cb => { cb.checked = datos.dias.includes(cb.value); });
                    }
                    if (datos.correos) {
                        correosInput.value = datos.correos;
                    }
                    if (Array.isArray(datos.empresas) && empresasSelect) {
                        $(empresasSelect).val(datos.empresas).trigger('change');
                    }
                    if (Array.isArray(datos.plantas) && plantasSelect) {
                        $(plantasSelect).val(datos.plantas).trigger('change');
                    }
                    if (Array.isArray(datos.usuarios) && usuariosSelect) {
                        $(usuariosSelect).val(datos.usuarios).trigger('change');
                    }
                } catch (e) { }
            };

            const guardarConfig = (probar = false) => {
                const hora = (horaInput.value || '').trim();
                const dias = diasCheckboxes.filter(cb => cb.checked).map(cb => cb.value);
                const correos = (correosInput.value || '').split(',').map(c => c.trim()).filter(c => c.length > 0);
                const empresas = empresasSelect ? Array.from(empresasSelect.selectedOptions).map(o => o.value) : [];
                const plantas = plantasSelect ? Array.from(plantasSelect.selectedOptions).map(o => o.value) : [];
                const usuarios = usuariosSelect ? Array.from(usuariosSelect.selectedOptions).map(o => o.value) : [];

                alerta.style.display = 'none';
                alerta.classList.remove('text-danger', 'text-success');

                if (!hora) {
                    alerta.style.display = 'block';
                    alerta.classList.add('text-danger');
                    alerta.textContent = 'Debe definir una hora de envío.';
                    return;
                }
                if (dias.length === 0) {
                    alerta.style.display = 'block';
                    alerta.classList.add('text-danger');
                    alerta.textContent = 'Debe seleccionar al menos un día.';
                    return;
                }
                const formatoCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const invalidos = correos.filter(c => !formatoCorreo.test(c));
                if (invalidos.length > 0) {
                    alerta.style.display = 'block';
                    alerta.classList.add('text-danger');
                    alerta.textContent = 'Correo inválido: ' + invalidos.join(', ');
                    return;
                }

                if (empresas.length === 0) {
                    alerta.style.display = 'block';
                    alerta.classList.add('text-danger');
                    alerta.textContent = 'Debe seleccionar al menos una empresa.';
                    return;
                }
                if (plantas.length === 0) {
                    alerta.style.display = 'block';
                    alerta.classList.add('text-danger');
                    alerta.textContent = 'Debe seleccionar al menos una planta.';
                    return;
                }
                const datos = { hora, dias, correos: correos.join(', '), empresas, plantas, usuarios };
                localStorage.setItem(keyConfig, JSON.stringify(datos));
                if (formCron && inputCron) {
                    inputCron.value = JSON.stringify(datos);
                    if (inputTest) {
                        inputTest.value = probar ? '1' : '';
                    }
                    formCron.submit();
                }
                alerta.style.display = 'block';
                alerta.classList.add('text-success');
                alerta.textContent = 'Configuración guardada para uso automático.';
            };

            if (guardarBtn) {
                guardarBtn.addEventListener('click', () => guardarConfig(false));
            }
            if (probarBtn) {
                probarBtn.addEventListener('click', () => guardarConfig(true));
            }
            cargarConfig();
        })();
    </script>
</body>

</html>
