<?php
/**
 * ReserBot - Test de Conexión a Base de Datos y Configuración
 */

require_once 'config/config.php';
require_once 'config/database.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - ReserBot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">ReserBot - Test de Sistema</h1>
            
            <!-- Test de URL Base -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                    <span class="text-green-500 mr-2">✓</span> Configuración de URL Base
                </h2>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-600 mb-2"><strong>URL Base Detectada:</strong></p>
                    <p class="text-lg font-mono text-blue-600"><?php echo BASE_URL; ?></p>
                    <p class="text-sm text-gray-600 mt-4 mb-2"><strong>Ruta del Sistema:</strong></p>
                    <p class="text-sm font-mono text-gray-700"><?php echo ROOT_PATH; ?></p>
                </div>
            </div>
            
            <!-- Test de Conexión a Base de Datos -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                    <?php
                    try {
                        $db = Database::getInstance();
                        $connection = $db->getConnection();
                        $connected = true;
                        echo '<span class="text-green-500 mr-2">✓</span> Conexión a Base de Datos';
                    } catch (Exception $e) {
                        $connected = false;
                        $error_message = $e->getMessage();
                        echo '<span class="text-red-500 mr-2">✗</span> Conexión a Base de Datos';
                    }
                    ?>
                </h2>
                
                <?php if ($connected): ?>
                    <div class="bg-green-50 border border-green-200 p-4 rounded">
                        <p class="text-green-700 font-semibold mb-2">✓ Conexión exitosa a la base de datos</p>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><strong>Host:</strong> <?php echo DB_HOST; ?></p>
                            <p><strong>Base de Datos:</strong> <?php echo DB_NAME; ?></p>
                            <p><strong>Usuario:</strong> <?php echo DB_USER; ?></p>
                            <p><strong>Charset:</strong> <?php echo DB_CHARSET; ?></p>
                        </div>
                        
                        <?php
                        // Verificar tablas
                        try {
                            $stmt = $db->query("SHOW TABLES");
                            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            
                            if (count($tables) > 0) {
                                echo '<div class="mt-4 pt-4 border-t border-green-200">';
                                echo '<p class="text-green-700 font-semibold mb-2">✓ Tablas encontradas: ' . count($tables) . '</p>';
                                echo '<div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs text-gray-600">';
                                foreach ($tables as $table) {
                                    echo '<div class="bg-white px-2 py-1 rounded">' . htmlspecialchars($table) . '</div>';
                                }
                                echo '</div></div>';
                                
                                // Contar registros en tablas principales
                                $counts = [];
                                $importantTables = ['usuarios', 'sucursales', 'servicios', 'especialistas', 'reservaciones'];
                                foreach ($importantTables as $table) {
                                    if (in_array($table, $tables)) {
                                        $stmt = $db->query("SELECT COUNT(*) as total FROM $table");
                                        $result = $stmt->fetch();
                                        $counts[$table] = $result['total'];
                                    }
                                }
                                
                                if (!empty($counts)) {
                                    echo '<div class="mt-4 pt-4 border-t border-green-200">';
                                    echo '<p class="text-green-700 font-semibold mb-2">Registros en tablas principales:</p>';
                                    echo '<div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">';
                                    foreach ($counts as $table => $count) {
                                        echo '<div class="bg-white px-3 py-2 rounded border border-gray-200">';
                                        echo '<div class="text-gray-600">' . ucfirst($table) . '</div>';
                                        echo '<div class="text-lg font-bold text-blue-600">' . $count . '</div>';
                                        echo '</div>';
                                    }
                                    echo '</div></div>';
                                }
                            } else {
                                echo '<div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-yellow-700">';
                                echo '⚠ La base de datos existe pero no contiene tablas. Ejecute el archivo database.sql para crear el esquema.';
                                echo '</div>';
                            }
                        } catch (Exception $e) {
                            echo '<div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-yellow-700">';
                            echo '⚠ Error al verificar tablas: ' . htmlspecialchars($e->getMessage());
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="bg-red-50 border border-red-200 p-4 rounded">
                        <p class="text-red-700 font-semibold mb-2">✗ Error de conexión</p>
                        <p class="text-sm text-gray-700 mb-3">No se pudo conectar a la base de datos. Verifique:</p>
                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 mb-3">
                            <li>Que MySQL esté ejecutándose</li>
                            <li>Que la base de datos "<?php echo DB_NAME; ?>" exista</li>
                            <li>Que el usuario "<?php echo DB_USER; ?>" tenga permisos</li>
                            <li>Que la contraseña sea correcta</li>
                        </ul>
                        <div class="bg-white p-3 rounded text-xs font-mono text-red-600">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm text-blue-700 font-semibold mb-2">Para crear la base de datos:</p>
                            <code class="text-xs bg-white px-2 py-1 rounded block">mysql -u root -p &lt; database.sql</code>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Test de Configuración PHP -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                    <span class="text-green-500 mr-2">✓</span> Configuración PHP
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs text-gray-600 mb-1">Versión de PHP</p>
                        <p class="text-lg font-semibold text-gray-800"><?php echo PHP_VERSION; ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs text-gray-600 mb-1">Zona Horaria</p>
                        <p class="text-lg font-semibold text-gray-800"><?php echo date_default_timezone_get(); ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs text-gray-600 mb-1">Entorno</p>
                        <p class="text-lg font-semibold text-gray-800"><?php echo APP_ENV; ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-xs text-gray-600 mb-1">Versión App</p>
                        <p class="text-lg font-semibold text-gray-800"><?php echo APP_VERSION; ?></p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Extensiones PHP Requeridas:</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                        <?php
                        $extensions = ['pdo', 'pdo_mysql', 'session', 'mbstring', 'json'];
                        foreach ($extensions as $ext) {
                            $loaded = extension_loaded($ext);
                            $color = $loaded ? 'green' : 'red';
                            $icon = $loaded ? '✓' : '✗';
                            echo '<div class="bg-' . $color . '-50 border border-' . $color . '-200 px-2 py-1 rounded text-' . $color . '-700">';
                            echo $icon . ' ' . $ext;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Instrucciones -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">📋 Próximos Pasos</h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-700">
                    <li>Si la base de datos no está configurada, ejecute: <code class="bg-white px-2 py-1 rounded text-sm">mysql -u root -p &lt; database.sql</code></li>
                    <li>Acceda al sistema principal en: <a href="<?php echo BASE_URL; ?>" class="underline font-semibold"><?php echo BASE_URL; ?></a></li>
                    <li>Credenciales de prueba:
                        <ul class="list-disc list-inside ml-6 mt-2 text-sm">
                            <li>Superadmin: admin@reserbot.com / ReserBot2024</li>
                            <li>Cliente: juan.perez@email.com / ReserBot2024</li>
                        </ul>
                    </li>
                </ol>
            </div>
            
            <div class="mt-6 text-center">
                <a href="<?php echo BASE_URL; ?>" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Ir al Sistema Principal →
                </a>
            </div>
        </div>
    </div>
</body>
</html>
