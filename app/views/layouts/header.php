<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?><?= getConfig('sitio_nombre', 'ReserBot') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
    <?php
    // Apply custom colors if set
    $colorPrimario = getConfig('color_primario', '#2563eb');
    $colorSecundario = getConfig('color_secundario', '#3b82f6');
    $colorAcento = getConfig('color_acento', '#60a5fa');
    ?>
    <style>
        :root {
            --color-primario: <?= $colorPrimario ?>;
            --color-secundario: <?= $colorSecundario ?>;
            --color-acento: <?= $colorAcento ?>;
        }
        .bg-primary { background-color: var(--color-primario) !important; }
        .text-primary { color: var(--color-primario) !important; }
        .border-primary { border-color: var(--color-primario) !important; }
    </style>
</head>
<body class="bg-gray-50">
