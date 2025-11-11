<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-concierge-bell mr-2"></i> Gestión de Servicios
            </h1>
            <a href="<?= BASE_URL ?>/admin/createServicio" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus-circle mr-2"></i> Nuevo Servicio
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Selector de Sucursal -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="<?= BASE_URL ?>/admin/servicios">
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
            <?php if (empty($servicios)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-concierge-bell text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg mb-4">No hay servicios en esta sucursal</p>
                    <a href="<?= BASE_URL ?>/admin/createServicio" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Crear el primer servicio
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($servicios as $servicio): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-semibold"><?= htmlspecialchars($servicio['nombre']) ?></div>
                                        <?php if ($servicio['descripcion']): ?>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($servicio['descripcion']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars($servicio['categoria_nombre']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        <i class="fas fa-clock mr-1"></i>
                                        <?= $servicio['duracion_minutos'] ?> min
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                                        $<?= number_format($servicio['precio'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $servicio['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $servicio['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <i class="fas fa-info-circle text-4xl text-blue-600 mb-4"></i>
                <p class="text-blue-800">Por favor seleccione una sucursal para ver sus servicios</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
