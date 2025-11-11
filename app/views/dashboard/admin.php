<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-crown mr-2"></i> Panel de Administración
            </h1>
            <p class="text-gray-600">Vista general del sistema</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Reservaciones</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="text-blue-600 text-4xl">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600"><?= $stats['pendientes'] ?? 0 ?></p>
                    </div>
                    <div class="text-yellow-600 text-4xl">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Confirmadas</p>
                        <p class="text-3xl font-bold text-green-600"><?= $stats['confirmadas'] ?? 0 ?></p>
                    </div>
                    <div class="text-green-600 text-4xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ingresos</p>
                        <p class="text-2xl font-bold text-green-600">$<?= number_format($stats['ingresos'] ?? 0, 2) ?></p>
                    </div>
                    <div class="text-green-600 text-4xl">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sucursales -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-building mr-2"></i> Sucursales
                </h2>
            </div>
            <div class="p-6">
                <?php if (empty($sucursales)): ?>
                    <p class="text-gray-600 text-center py-4">No hay sucursales registradas</p>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($sucursales as $sucursal): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <h3 class="font-bold text-lg text-gray-800 mb-2">
                                    <?= htmlspecialchars($sucursal['nombre']) ?>
                                </h3>
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    <?= htmlspecialchars($sucursal['ciudad'] . ', ' . $sucursal['estado']) ?>
                                </p>
                                <?php if ($sucursal['telefono']): ?>
                                    <p class="text-sm text-gray-600 mb-1">
                                        <i class="fas fa-phone mr-1"></i>
                                        <?= htmlspecialchars($sucursal['telefono']) ?>
                                    </p>
                                <?php endif; ?>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('H:i', strtotime($sucursal['hora_apertura'])) ?> - 
                                    <?= date('H:i', strtotime($sucursal['hora_cierre'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
