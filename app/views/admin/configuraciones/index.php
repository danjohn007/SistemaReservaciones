<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-cogs mr-2"></i> Configuraciones del Sistema
            </h1>
            <p class="text-gray-600">Administra las configuraciones globales de ReserBot</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/admin/saveConfiguraciones" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <!-- 1. NOMBRE DEL SITIO Y LOGOTIPO -->
            <?php if (isset($configuraciones['sitio'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-globe text-blue-600 mr-2"></i>
                    Nombre del Sitio y Logotipo
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['sitio'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('sitio_', '', $clave))) ?>
                            </label>
                            <input 
                                type="text" 
                                name="config[<?= $clave ?>]" 
                                value="<?= htmlspecialchars($config['valor']) ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 2. CONFIGURACIÓN DE CORREO ELECTRÓNICO -->
            <?php if (isset($configuraciones['email'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                    Configuración de Correo Electrónico
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['email'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('email_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'password') !== false): ?>
                                <input 
                                    type="password" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="••••••••"
                                >
                            <?php elseif (strpos($clave, 'port') !== false): ?>
                                <input 
                                    type="number" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php elseif (strpos($clave, 'seguridad') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="tls" <?= $config['valor'] === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= $config['valor'] === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                    <option value="none" <?= $config['valor'] === 'none' ? 'selected' : '' ?>>Ninguno</option>
                                </select>
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 3. WHATSAPP CHATBOT -->
            <?php if (isset($configuraciones['whatsapp'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fab fa-whatsapp text-green-600 mr-2"></i>
                    WhatsApp Chatbot
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['whatsapp'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('whatsapp_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'activo') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="+52 442 123 4567"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 4. TELÉFONOS Y HORARIOS DE ATENCIÓN -->
            <?php if (isset($configuraciones['contacto'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-phone text-blue-600 mr-2"></i>
                    Teléfonos de Contacto y Horarios de Atención
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['contacto'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', $clave)) ?>
                            </label>
                            <?php if (strpos($clave, 'horario_atencion_inicio') !== false || strpos($clave, 'horario_atencion_fin') !== false): ?>
                                <input 
                                    type="time" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 5. ESTILOS Y COLORES DEL SISTEMA -->
            <?php if (isset($configuraciones['colores'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-palette text-blue-600 mr-2"></i>
                    Estilos y Colores del Sistema
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach ($configuraciones['colores'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('color_', '', $clave))) ?>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="color" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="h-10 w-16 border border-gray-300 rounded cursor-pointer"
                                >
                                <input 
                                    type="text" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    readonly
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                >
                            </div>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 6. CONFIGURACIÓN DE PAYPAL -->
            <?php if (isset($configuraciones['paypal'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fab fa-paypal text-blue-600 mr-2"></i>
                    Configuración de PayPal
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['paypal'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('paypal_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'modo') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="sandbox" <?= $config['valor'] === 'sandbox' ? 'selected' : '' ?>>Sandbox (Pruebas)</option>
                                    <option value="live" <?= $config['valor'] === 'live' ? 'selected' : '' ?>>Live (Producción)</option>
                                </select>
                            <?php elseif (strpos($clave, 'secret') !== false): ?>
                                <input 
                                    type="password" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="••••••••"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 7. API PARA CREAR QRS MASIVOS -->
            <?php if (isset($configuraciones['api_qr'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-qrcode text-blue-600 mr-2"></i>
                    API para Crear QRs Masivos
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['api_qr'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('api_qr_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'activo') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            <?php elseif (strpos($clave, 'token') !== false): ?>
                                <input 
                                    type="password" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="••••••••"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 8. API PARA DISPOSITIVOS SHELLY RELAY -->
            <?php if (isset($configuraciones['api_shelly'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-plug text-blue-600 mr-2"></i>
                    API para Dispositivos Shelly Relay
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['api_shelly'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('api_shelly_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'activo') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            <?php elseif (strpos($clave, 'token') !== false): ?>
                                <input 
                                    type="password" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="••••••••"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 9. API PARA DISPOSITIVOS HIKVISION -->
            <?php if (isset($configuraciones['api_hikvision'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-video text-blue-600 mr-2"></i>
                    API para Dispositivos HikVision
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['api_hikvision'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('api_hikvision_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'activo') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            <?php elseif (strpos($clave, 'password') !== false): ?>
                                <input 
                                    type="password" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="••••••••"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- 10. CONFIGURACIONES GLOBALES RECOMENDADAS -->
            <?php if (isset($configuraciones['sistema'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                    Configuraciones Globales del Sistema
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['sistema'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', str_replace('sistema_', '', $clave))) ?>
                            </label>
                            <?php if (strpos($clave, 'mantenimiento') !== false || strpos($clave, 'registro_publico') !== false): ?>
                                <select 
                                    name="config[<?= $clave ?>]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Sí</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>No</option>
                                </select>
                            <?php elseif (strpos($clave, 'duracion_sesion') !== false): ?>
                                <input 
                                    type="number" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    min="300"
                                    step="300"
                                >
                            <?php else: ?>
                                <input 
                                    type="text" 
                                    name="config[<?= $clave ?>]" 
                                    value="<?= htmlspecialchars($config['valor']) ?>"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            <?php endif; ?>
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- OTRAS CONFIGURACIONES -->
            <?php if (isset($configuraciones['otros']) && !empty($configuraciones['otros'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cog text-blue-600 mr-2"></i>
                    Otras Configuraciones
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($configuraciones['otros'] as $clave => $config): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= ucfirst(str_replace('_', ' ', $clave)) ?>
                            </label>
                            <input 
                                type="text" 
                                name="config[<?= $clave ?>]" 
                                value="<?= htmlspecialchars($config['valor']) ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <?php if ($config['descripcion']): ?>
                                <p class="text-xs text-gray-500 mt-1"><?= $config['descripcion'] ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4">
                <a href="<?= BASE_URL ?>/dashboard" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    <i class="fas fa-save mr-2"></i> Guardar Configuraciones
                </button>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">
                <i class="fas fa-info-circle mr-2"></i> Información Importante
            </h3>
            <ul class="list-disc list-inside space-y-2 text-blue-700 text-sm">
                <li>Los campos de contraseña/token solo se actualizan si ingresa un nuevo valor</li>
                <li>Las configuraciones de correo SMTP requieren credenciales válidas para funcionar</li>
                <li>Los cambios de color se aplicarán en la próxima actualización del sistema</li>
                <li>Asegúrese de probar las APIs antes de activarlas en producción</li>
                <li>El modo mantenimiento desactivará el acceso público al sistema</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Actualizar el valor de texto cuando cambia el color picker
document.querySelectorAll('input[type="color"]').forEach(colorPicker => {
    colorPicker.addEventListener('change', function() {
        const textInput = this.nextElementSibling;
        if (textInput && textInput.type === 'text') {
            textInput.value = this.value;
        }
    });
});
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
