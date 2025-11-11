<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-chart-bar mr-2"></i> Reportes y Estadísticas
            </h1>
            <p class="text-gray-600">Análisis del sistema de reservaciones</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="<?= BASE_URL ?>/admin/reportes">
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" value="<?= $fechaInicio ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" value="<?= $fechaFin ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Estadísticas Principales -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Reservaciones</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="text-blue-600 text-5xl">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Completadas</p>
                        <p class="text-3xl font-bold text-green-600"><?= $stats['completadas'] ?? 0 ?></p>
                        <?php if ($stats['total'] > 0): ?>
                            <p class="text-xs text-gray-500 mt-1">
                                <?= round(($stats['completadas'] / $stats['total']) * 100, 1) ?>% del total
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="text-green-600 text-5xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Canceladas</p>
                        <p class="text-3xl font-bold text-red-600"><?= $stats['canceladas'] ?? 0 ?></p>
                        <?php if ($stats['total'] > 0): ?>
                            <p class="text-xs text-gray-500 mt-1">
                                <?= round(($stats['canceladas'] / $stats['total']) * 100, 1) ?>% del total
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="text-red-600 text-5xl">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ingresos Totales</p>
                        <p class="text-2xl font-bold text-green-600">$<?= number_format($stats['ingresos'] ?? 0, 2) ?></p>
                        <p class="text-xs text-gray-500 mt-1">De citas completadas</p>
                    </div>
                    <div class="text-green-600 text-5xl">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desglose por Estado -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2"></i> Desglose por Estado
                </h2>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-yellow-600 mb-2"><?= $stats['pendientes'] ?? 0 ?></div>
                        <div class="text-gray-600">
                            <i class="fas fa-clock mr-1"></i> Pendientes
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-600 mb-2"><?= $stats['confirmadas'] ?? 0 ?></div>
                        <div class="text-gray-600">
                            <i class="fas fa-check mr-1"></i> Confirmadas
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-600 mb-2"><?= $stats['no_asistio'] ?? 0 ?></div>
                        <div class="text-gray-600">
                            <i class="fas fa-user-times mr-1"></i> No Asistió
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">
                <i class="fas fa-info-circle mr-2"></i> Información del Reporte
            </h3>
            <div class="text-blue-700 space-y-2">
                <p>
                    <strong>Período:</strong> 
                    <?= date('d/m/Y', strtotime($fechaInicio)) ?> - <?= date('d/m/Y', strtotime($fechaFin)) ?>
                </p>
                <p>
                    <strong>Generado:</strong> 
                    <?= date('d/m/Y H:i:s') ?>
                </p>
                <?php if ($stats['total'] > 0): ?>
                    <p>
                        <strong>Tasa de Éxito:</strong> 
                        <?= round((($stats['completadas'] ?? 0) / $stats['total']) * 100, 1) ?>%
                    </p>
                    <p>
                        <strong>Ingreso Promedio:</strong> 
                        $<?= $stats['completadas'] > 0 ? number_format($stats['ingresos'] / $stats['completadas'], 2) : '0.00' ?> por cita
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
