<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-cog mr-2"></i> Configuraciones del Sistema
            </h1>
            <p class="text-gray-600">Administra todas las configuraciones del sistema</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/configuracion/save" method="POST" id="configForm">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex flex-wrap -mb-px">
                        <button type="button" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-blue-500 text-blue-600" data-tab="general">
                            <i class="fas fa-home mr-2"></i> General
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="email">
                            <i class="fas fa-envelope mr-2"></i> Email
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="whatsapp">
                            <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="contacto">
                            <i class="fas fa-phone mr-2"></i> Contacto
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="colores">
                            <i class="fas fa-palette mr-2"></i> Colores
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="paypal">
                            <i class="fab fa-paypal mr-2"></i> PayPal
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="apis">
                            <i class="fas fa-plug mr-2"></i> APIs
                        </button>
                        <button type="button" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="sistema">
                            <i class="fas fa-server mr-2"></i> Sistema
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- General Tab -->
                    <div class="tab-content" data-tab="general">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($configuraciones['general'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', str_replace('sitio_', '', $config['clave']))) ?>
                                    </label>
                                    <?php if ($config['clave'] === 'sitio_logo'): ?>
                                        <div class="mb-2">
                                            <?php if (!empty($config['valor'])): ?>
                                                <img src="<?= htmlspecialchars($config['valor']) ?>" alt="Logo" class="max-w-xs mb-2 rounded border">
                                            <?php endif; ?>
                                        </div>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="URL del logo">
                                    <?php else: ?>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Email Tab -->
                    <div class="tab-content hidden" data-tab="email">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración de Email</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($configuraciones['email'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', str_replace('email_', '', $config['clave']))) ?>
                                    </label>
                                    <?php if (strpos($config['clave'], 'password') !== false): ?>
                                        <input type="password" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php elseif ($config['clave'] === 'email_smtp_seguridad'): ?>
                                        <select name="<?= $config['clave'] ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="tls" <?= $config['valor'] === 'tls' ? 'selected' : '' ?>>TLS</option>
                                            <option value="ssl" <?= $config['valor'] === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                            <option value="" <?= empty($config['valor']) ? 'selected' : '' ?>>Ninguno</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- WhatsApp Tab -->
                    <div class="tab-content hidden" data-tab="whatsapp">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración de WhatsApp</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($configuraciones['whatsapp'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', str_replace('whatsapp_', '', $config['clave']))) ?>
                                    </label>
                                    <?php if ($config['clave'] === 'whatsapp_activado'): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-gray-700">Activar integración</span>
                                        </div>
                                    <?php else: ?>
                                        <input type="tel" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="521234567890">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Contacto Tab -->
                    <div class="tab-content hidden" data-tab="contacto">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Información de Contacto</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($configuraciones['contacto'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', $config['clave'])) ?>
                                    </label>
                                    <?php if (strpos($config['clave'], 'horario_atencion') !== false && strpos($config['clave'], 'dias') === false): ?>
                                        <input type="time" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php else: ?>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Colores Tab -->
                    <div class="tab-content hidden" data-tab="colores">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Personalización de Colores</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php foreach ($configuraciones['colores'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', str_replace('color_', '', $config['clave']))) ?>
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-16 h-10 border border-gray-300 rounded cursor-pointer">
                                        <input type="text" name="<?= $config['clave'] ?>_text" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            readonly
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-mono text-sm">
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                Los cambios de color se aplicarán después de guardar y recargar la página.
                            </p>
                        </div>
                    </div>

                    <!-- PayPal Tab -->
                    <div class="tab-content hidden" data-tab="paypal">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración de PayPal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($configuraciones['paypal'] as $config): ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', str_replace('paypal_', '', $config['clave']))) ?>
                                    </label>
                                    <?php if ($config['clave'] === 'paypal_activado'): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-gray-700">Activar pagos con PayPal</span>
                                        </div>
                                    <?php elseif ($config['clave'] === 'paypal_modo'): ?>
                                        <select name="<?= $config['clave'] ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="sandbox" <?= $config['valor'] === 'sandbox' ? 'selected' : '' ?>>Sandbox (Pruebas)</option>
                                            <option value="live" <?= $config['valor'] === 'live' ? 'selected' : '' ?>>Live (Producción)</option>
                                        </select>
                                    <?php elseif (strpos($config['clave'], 'secret') !== false): ?>
                                        <input type="password" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php else: ?>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- APIs Tab -->
                    <div class="tab-content hidden" data-tab="apis">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración de APIs</h3>
                        
                        <!-- API QR -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">
                                <i class="fas fa-qrcode mr-2"></i> API para QR Masivos
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($configuraciones['qr'] as $config): ?>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">
                                            <?= ucfirst(str_replace('_', ' ', str_replace('api_qr_', '', $config['clave']))) ?>
                                        </label>
                                        <?php if ($config['clave'] === 'api_qr_activado'): ?>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                    <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="ml-2 text-gray-700">Activar API</span>
                                            </div>
                                        <?php else: ?>
                                            <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- API Shelly -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">
                                <i class="fas fa-lightbulb mr-2"></i> API Shelly Relay
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($configuraciones['shelly'] as $config): ?>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">
                                            <?= ucfirst(str_replace('_', ' ', str_replace('api_shelly_', '', $config['clave']))) ?>
                                        </label>
                                        <?php if ($config['clave'] === 'api_shelly_activado'): ?>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                    <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="ml-2 text-gray-700">Activar integración</span>
                                            </div>
                                        <?php elseif (strpos($config['clave'], 'auth') !== false): ?>
                                            <input type="password" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php else: ?>
                                            <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- API HikVision -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">
                                <i class="fas fa-video mr-2"></i> API HikVision
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($configuraciones['hikvision'] as $config): ?>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">
                                            <?= ucfirst(str_replace('_', ' ', str_replace('api_hikvision_', '', $config['clave']))) ?>
                                        </label>
                                        <?php if ($config['clave'] === 'api_hikvision_activado'): ?>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                    <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="ml-2 text-gray-700">Activar integración</span>
                                            </div>
                                        <?php elseif (strpos($config['clave'], 'password') !== false): ?>
                                            <input type="password" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php else: ?>
                                            <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <?php endif; ?>
                                        <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sistema Tab -->
                    <div class="tab-content hidden" data-tab="sistema">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuraciones del Sistema</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php 
                            $allSystemConfigs = array_merge($configuraciones['sistema'], $configuraciones['notificaciones']);
                            foreach ($allSystemConfigs as $config): 
                            ?>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        <?= ucfirst(str_replace('_', ' ', $config['clave'])) ?>
                                    </label>
                                    <?php if (strpos($config['clave'], 'activado') !== false || strpos($config['clave'], 'permitir') !== false || $config['clave'] === 'sistema_modo_mantenimiento' || $config['clave'] === 'sistema_verificar_email' || strpos($config['clave'], 'notificaciones_') === 0): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="<?= $config['clave'] ?>" value="1" 
                                                <?= $config['valor'] == '1' ? 'checked' : '' ?>
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2 text-gray-700">Activar</span>
                                        </div>
                                    <?php elseif ($config['clave'] === 'sistema_mensaje_mantenimiento'): ?>
                                        <textarea name="<?= $config['clave'] ?>" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($config['valor']) ?></textarea>
                                    <?php elseif (strpos($config['clave'], 'tiempo_') === 0 || $config['clave'] === 'sistema_duracion_sesion'): ?>
                                        <input type="number" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php else: ?>
                                        <input type="text" name="<?= $config['clave'] ?>" value="<?= htmlspecialchars($config['valor']) ?>" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($config['descripcion']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i> Guardar Configuraciones
                </button>
                <a href="<?= BASE_URL ?>/dashboard" class="text-gray-600 hover:text-gray-800 font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Tab functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.dataset.tab;
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Add active class to clicked button
        button.classList.add('active', 'border-blue-500', 'text-blue-600');
        button.classList.remove('border-transparent', 'text-gray-500');
        
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Show selected tab content
        document.querySelector(`.tab-content[data-tab="${tabName}"]`).classList.remove('hidden');
    });
});

// Color picker sync
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    colorInput.addEventListener('input', (e) => {
        const textInput = e.target.parentElement.querySelector('input[type="text"]');
        if (textInput) {
            textInput.value = e.target.value;
        }
    });
});
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
