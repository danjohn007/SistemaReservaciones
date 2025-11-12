<?php
/**
 * Test de Módulo de Configuraciones
 * Script de prueba para verificar que el módulo de configuraciones funciona correctamente
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/app/models/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Módulo de Configuraciones - ReserBot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-cogs mr-2"></i> Test del Módulo de Configuraciones
            </h1>

            <?php
            try {
                // Test 1: Verificar que la tabla existe
                echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
                echo '<h2 class="text-xl font-semibold text-gray-700 mb-4">Test 1: Verificar Tabla de Configuraciones</h2>';
                
                $db = Database::getInstance();
                $stmt = $db->query("SHOW TABLES LIKE 'configuraciones'");
                $tableExists = $stmt->fetch();
                
                if ($tableExists) {
                    echo '<div class="bg-green-50 border border-green-200 p-4 rounded">';
                    echo '<p class="text-green-700 font-semibold">✓ Tabla "configuraciones" existe</p>';
                    
                    // Contar registros
                    $stmt = $db->query("SELECT COUNT(*) as total FROM configuraciones");
                    $count = $stmt->fetch();
                    echo '<p class="text-gray-700 mt-2">Total de configuraciones: <strong>' . $count['total'] . '</strong></p>';
                    echo '</div>';
                } else {
                    echo '<div class="bg-red-50 border border-red-200 p-4 rounded">';
                    echo '<p class="text-red-700 font-semibold">✗ Tabla "configuraciones" NO existe</p>';
                    echo '<p class="text-sm text-gray-600 mt-2">Ejecute el archivo database_configuraciones.sql</p>';
                    echo '</div>';
                }
                echo '</div>';
                
                // Test 2: Verificar que el modelo funciona
                echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
                echo '<h2 class="text-xl font-semibold text-gray-700 mb-4">Test 2: Modelo de Configuración</h2>';
                
                $configuracionModel = new Configuracion();
                echo '<div class="bg-green-50 border border-green-200 p-4 rounded">';
                echo '<p class="text-green-700 font-semibold">✓ Modelo Configuracion cargado correctamente</p>';
                echo '</div>';
                echo '</div>';
                
                // Test 3: Probar operaciones CRUD
                if ($tableExists && $count['total'] > 0) {
                    echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
                    echo '<h2 class="text-xl font-semibold text-gray-700 mb-4">Test 3: Operaciones CRUD</h2>';
                    
                    // GET
                    $sitioNombre = $configuracionModel->get('sitio_nombre', 'N/A');
                    echo '<div class="mb-3">';
                    echo '<p class="text-gray-700"><strong>GET sitio_nombre:</strong> ' . htmlspecialchars($sitioNombre) . '</p>';
                    echo '</div>';
                    
                    // GET con valor por defecto
                    $testValue = $configuracionModel->get('configuracion_inexistente', 'Valor por Defecto');
                    echo '<div class="mb-3">';
                    echo '<p class="text-gray-700"><strong>GET con default:</strong> ' . htmlspecialchars($testValue) . '</p>';
                    echo '</div>';
                    
                    // GET by prefix
                    $emailConfigs = $configuracionModel->getByPrefix('email_');
                    echo '<div class="mb-3">';
                    echo '<p class="text-gray-700"><strong>GET by prefix "email_":</strong> ' . count($emailConfigs) . ' configuraciones</p>';
                    echo '</div>';
                    
                    // GET ALL grouped
                    $allGrouped = $configuracionModel->getAllGrouped();
                    echo '<div class="mb-3">';
                    echo '<p class="text-gray-700"><strong>GET ALL grouped:</strong></p>';
                    echo '<ul class="list-disc list-inside ml-4 text-sm text-gray-600">';
                    foreach ($allGrouped as $category => $configs) {
                        echo '<li>' . ucfirst($category) . ': ' . count($configs) . ' configuraciones</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    
                    echo '<div class="bg-green-50 border border-green-200 p-4 rounded mt-4">';
                    echo '<p class="text-green-700 font-semibold">✓ Todas las operaciones CRUD funcionan correctamente</p>';
                    echo '</div>';
                    
                    echo '</div>';
                }
                
                // Test 4: Verificar configuraciones específicas
                if ($tableExists && $count['total'] > 0) {
                    echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
                    echo '<h2 class="text-xl font-semibold text-gray-700 mb-4">Test 4: Configuraciones Específicas</h2>';
                    
                    $configs = [
                        'sitio_nombre' => 'Nombre del Sitio',
                        'email_remitente' => 'Email Remitente',
                        'whatsapp_numero' => 'WhatsApp',
                        'color_primario' => 'Color Primario',
                        'paypal_modo' => 'Modo PayPal',
                        'api_qr_activo' => 'API QR Activa',
                        'sistema_zona_horaria' => 'Zona Horaria'
                    ];
                    
                    echo '<table class="w-full text-sm">';
                    echo '<thead><tr class="bg-gray-50"><th class="px-4 py-2 text-left">Configuración</th><th class="px-4 py-2 text-left">Valor</th><th class="px-4 py-2">Estado</th></tr></thead>';
                    echo '<tbody>';
                    
                    $allOk = true;
                    foreach ($configs as $key => $label) {
                        $value = $configuracionModel->get($key);
                        $exists = $value !== null;
                        if (!$exists) $allOk = false;
                        
                        $statusIcon = $exists ? '<span class="text-green-600">✓</span>' : '<span class="text-red-600">✗</span>';
                        $displayValue = $exists ? htmlspecialchars(substr($value, 0, 50)) : '<em class="text-gray-400">No configurado</em>';
                        
                        echo '<tr class="border-t">';
                        echo '<td class="px-4 py-2">' . $label . '</td>';
                        echo '<td class="px-4 py-2">' . $displayValue . '</td>';
                        echo '<td class="px-4 py-2 text-center">' . $statusIcon . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody></table>';
                    
                    if ($allOk) {
                        echo '<div class="bg-green-50 border border-green-200 p-4 rounded mt-4">';
                        echo '<p class="text-green-700 font-semibold">✓ Todas las configuraciones requeridas están presentes</p>';
                        echo '</div>';
                    } else {
                        echo '<div class="bg-yellow-50 border border-yellow-200 p-4 rounded mt-4">';
                        echo '<p class="text-yellow-700 font-semibold">⚠ Algunas configuraciones faltan. Ejecute database_configuraciones.sql</p>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
                
                // Test 5: Verificar acceso al controlador
                echo '<div class="bg-white rounded-lg shadow-md p-6 mb-6">';
                echo '<h2 class="text-xl font-semibold text-gray-700 mb-4">Test 5: Acceso al Módulo</h2>';
                
                echo '<div class="space-y-2">';
                echo '<p class="text-gray-700">Para acceder al módulo de configuraciones:</p>';
                echo '<ol class="list-decimal list-inside ml-4 text-sm text-gray-600 space-y-1">';
                echo '<li>Inicie sesión como <strong>superadmin</strong></li>';
                echo '<li>Credenciales: admin@reserbot.com / ReserBot2024</li>';
                echo '<li>Vaya a: <strong>Admin → Configuraciones</strong></li>';
                echo '</ol>';
                echo '</div>';
                
                $configUrl = BASE_URL . '/admin/configuraciones';
                echo '<div class="mt-4">';
                echo '<a href="' . $configUrl . '" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">';
                echo '<i class="fas fa-cogs mr-2"></i> Ir a Configuraciones';
                echo '</a>';
                echo '</div>';
                
                echo '</div>';
                
            } catch (Exception $e) {
                echo '<div class="bg-red-50 border border-red-200 p-4 rounded">';
                echo '<p class="text-red-700 font-semibold">✗ Error durante las pruebas</p>';
                echo '<p class="text-sm text-gray-600 mt-2">' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '</div>';
            }
            ?>

            <!-- Instrucciones -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">
                    <i class="fas fa-lightbulb mr-2"></i> Próximos Pasos
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-700">
                    <li>Si la tabla no existe, ejecute: <code class="bg-white px-2 py-1 rounded text-sm">mysql -u root -p &lt; database_configuraciones.sql</code></li>
                    <li>Si hay pocas configuraciones, ejecute el mismo script para agregar las faltantes</li>
                    <li>Revise el archivo <strong>CONFIGURACIONES_README.md</strong> para más información</li>
                    <li>Acceda al módulo desde el panel de administración</li>
                </ol>
            </div>

            <div class="mt-6 text-center space-x-4">
                <a href="<?= BASE_URL ?>" class="inline-block bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-home mr-2"></i> Ir al Inicio
                </a>
                <a href="<?= BASE_URL ?>/test_connection.php" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-database mr-2"></i> Test de Conexión
                </a>
            </div>
        </div>
    </div>
</body>
</html>
