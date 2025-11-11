<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-user-md mr-2"></i> Panel del Especialista
            </h1>
            <p class="text-gray-600">Bienvenido, <?= htmlspecialchars($especialista['profesion'] ?? 'Especialista') ?></p>
        </div>

        <!-- Citas de Hoy -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-day mr-2"></i> Citas de Hoy
            </h2>
            
            <?php if (empty($citasHoy)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                    <p class="text-gray-600 text-lg">No tienes citas programadas para hoy</p>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($citasHoy as $cita): ?>
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold mb-2 inline-block
                                        <?= $cita['estado'] === 'confirmada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= ucfirst($cita['estado']) ?>
                                    </span>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?= date('h:i A', strtotime($cita['fecha_hora'])) ?> - 
                                        <?= htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']) ?>
                                    </h3>
                                    <p class="text-gray-600 mt-2">
                                        <i class="fas fa-concierge-bell mr-2"></i>
                                        <?= htmlspecialchars($cita['servicio_nombre']) ?> 
                                        (<?= $cita['duracion_minutos'] ?> min)
                                    </p>
                                    <?php if ($cita['cliente_telefono']): ?>
                                        <p class="text-gray-600 mt-1">
                                            <i class="fas fa-phone mr-2"></i>
                                            <?= htmlspecialchars($cita['cliente_telefono']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="<?= BASE_URL ?>/reservacion/view/<?= $cita['id'] ?>" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Próximas Citas -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2"></i> Próximas Citas (7 días)
            </h2>
            
            <?php if (empty($proximasCitas)): ?>
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No hay citas confirmadas próximamente</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($proximasCitas as $cita): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('d/m/Y H:i', strtotime($cita['fecha_hora'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars($cita['cliente_nombre'] . ' ' . $cita['cliente_apellido']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars($cita['servicio_nombre']) ?>
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
