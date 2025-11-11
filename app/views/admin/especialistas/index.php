<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            <i class="fas fa-user-md mr-2"></i> Gestión de Especialistas
        </h1>

        <!-- Selector de Sucursal -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="<?= BASE_URL ?>/admin/especialistas">
                <div class="flex items-end space-x-4">
                    <div class="flex-1">
                        <label class="block text-gray-700 font-semibold mb-2">Seleccionar Sucursal</label>
                        <select name="sucursal_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Seleccione una sucursal --</option>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= $sucursal['id'] ?>" <?= $selectedSucursal == $sucursal['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sucursal['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <?php if ($selectedSucursal): ?>
            <?php if (empty($especialistas)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-user-md text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">No hay especialistas en esta sucursal</p>
                </div>
            <?php else: ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($especialistas as $esp): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                            <div class="text-center mb-4">
                                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-user-md text-3xl text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    <?= htmlspecialchars($esp['nombre'] . ' ' . $esp['apellido']) ?>
                                </h3>
                                <?php if ($esp['profesion']): ?>
                                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($esp['profesion']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p>
                                    <i class="fas fa-envelope mr-2"></i>
                                    <?= htmlspecialchars($esp['email']) ?>
                                </p>
                                <?php if ($esp['telefono']): ?>
                                    <p>
                                        <i class="fas fa-phone mr-2"></i>
                                        <?= htmlspecialchars($esp['telefono']) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($esp['calificacion_promedio'] > 0): ?>
                                    <p>
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                                        <?= number_format($esp['calificacion_promedio'], 2) ?>
                                        <span class="text-xs">(<?= $esp['total_calificaciones'] ?> reseñas)</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <a href="<?= BASE_URL ?>/admin/horarios/<?= $esp['id'] ?>" 
                               class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition">
                                <i class="fas fa-calendar-alt mr-2"></i> Ver Horarios
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <i class="fas fa-info-circle text-4xl text-blue-600 mb-4"></i>
                <p class="text-blue-800">Por favor seleccione una sucursal para ver sus especialistas</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
