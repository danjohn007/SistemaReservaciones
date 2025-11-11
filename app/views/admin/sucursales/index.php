<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-building mr-2"></i> Gestión de Sucursales
                </h1>
                <p class="text-gray-600">Administra las sucursales del sistema</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/createSucursal" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus-circle mr-2"></i> Nueva Sucursal
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($sucursales)): ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg mb-4">No hay sucursales registradas</p>
                <a href="<?= BASE_URL ?>/admin/createSucursal" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Crear la primera sucursal
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($sucursales as $sucursal): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $sucursal['id'] ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-semibold"><?= htmlspecialchars($sucursal['nombre']) ?></div>
                                    <?php if ($sucursal['telefono']): ?>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-phone mr-1"></i>
                                            <?= htmlspecialchars($sucursal['telefono']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?= htmlspecialchars($sucursal['ciudad']) ?>, <?= htmlspecialchars($sucursal['estado']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    <?= date('H:i', strtotime($sucursal['hora_apertura'])) ?> - 
                                    <?= date('H:i', strtotime($sucursal['hora_cierre'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?= $sucursal['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $sucursal['activo'] ? 'Activa' : 'Inactiva' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="<?= BASE_URL ?>/admin/servicios?sucursal_id=<?= $sucursal['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-list mr-1"></i> Servicios
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/especialistas?sucursal_id=<?= $sucursal['id'] ?>" 
                                       class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-user-md mr-1"></i> Especialistas
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
