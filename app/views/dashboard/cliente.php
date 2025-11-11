<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-calendar-check mr-2"></i> Mis Citas
            </h1>
            <p class="text-gray-600">Gestiona tus reservaciones y citas</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Acción Rápida -->
        <div class="mb-8">
            <a href="<?= BASE_URL ?>/reservacion/create" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-plus-circle mr-2"></i> Nueva Reservación
            </a>
        </div>

        <!-- Próximas Citas -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2"></i> Próximas Citas
            </h2>
            
            <?php if (empty($proximasCitas)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">No tienes citas próximas</p>
                    <a href="<?= BASE_URL ?>/reservacion/create" class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-semibold">
                        Agenda una cita ahora
                    </a>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($proximasCitas as $cita): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            <?= $cita['estado'] === 'confirmada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= $cita['estado'] === 'confirmada' ? 'Confirmada' : 'Pendiente' ?>
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                                        <?= htmlspecialchars($cita['servicio_nombre']) ?>
                                    </h3>
                                    <div class="space-y-1 text-gray-600">
                                        <p>
                                            <i class="fas fa-user-md mr-2"></i>
                                            <strong>Especialista:</strong> 
                                            <?= htmlspecialchars($cita['especialista_nombre'] . ' ' . $cita['especialista_apellido']) ?>
                                            <?php if ($cita['profesion']): ?>
                                                <span class="text-sm">(<?= htmlspecialchars($cita['profesion']) ?>)</span>
                                            <?php endif; ?>
                                        </p>
                                        <p>
                                            <i class="fas fa-calendar mr-2"></i>
                                            <strong>Fecha:</strong> 
                                            <?= date('d/m/Y', strtotime($cita['fecha_hora'])) ?>
                                        </p>
                                        <p>
                                            <i class="fas fa-clock mr-2"></i>
                                            <strong>Hora:</strong> 
                                            <?= date('h:i A', strtotime($cita['fecha_hora'])) ?>
                                        </p>
                                        <p>
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <strong>Sucursal:</strong> 
                                            <?= htmlspecialchars($cita['sucursal_nombre']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0 md:ml-6">
                                    <a href="<?= BASE_URL ?>/reservacion/view/<?= $cita['id'] ?>" 
                                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Historial -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history mr-2"></i> Historial
            </h2>
            
            <?php if (empty($historial)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No hay historial de citas</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($historial as $cita): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('d/m/Y H:i', strtotime($cita['fecha_hora'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars($cita['servicio_nombre']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars($cita['especialista_nombre'] . ' ' . $cita['especialista_apellido']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            <?php
                                            switch($cita['estado']) {
                                                case 'completada':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'cancelada':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'no_asistio':
                                                    echo 'bg-gray-100 text-gray-800';
                                                    break;
                                                default:
                                                    echo 'bg-blue-100 text-blue-800';
                                            }
                                            ?>">
                                            <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="<?= BASE_URL ?>/reservacion/view/<?= $cita['id'] ?>" 
                                           class="text-blue-600 hover:text-blue-900">
                                            Ver
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
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
