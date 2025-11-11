<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="<?= BASE_URL ?>/dashboard" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-file-alt mr-2"></i> Detalles de la Reservación
            </h1>

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

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Estado de la reservación -->
                <div class="px-8 py-6 border-b border-gray-200 
                    <?php
                    switch($reservacion['estado']) {
                        case 'confirmada':
                            echo 'bg-green-50';
                            break;
                        case 'pendiente':
                            echo 'bg-yellow-50';
                            break;
                        case 'completada':
                            echo 'bg-blue-50';
                            break;
                        case 'cancelada':
                        case 'no_asistio':
                            echo 'bg-red-50';
                            break;
                        default:
                            echo 'bg-gray-50';
                    }
                    ?>">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Estado de la cita</p>
                            <p class="text-2xl font-bold 
                                <?php
                                switch($reservacion['estado']) {
                                    case 'confirmada':
                                        echo 'text-green-700';
                                        break;
                                    case 'pendiente':
                                        echo 'text-yellow-700';
                                        break;
                                    case 'completada':
                                        echo 'text-blue-700';
                                        break;
                                    case 'cancelada':
                                    case 'no_asistio':
                                        echo 'text-red-700';
                                        break;
                                }
                                ?>">
                                <i class="fas fa-circle text-sm mr-2"></i>
                                <?= ucfirst(str_replace('_', ' ', $reservacion['estado'])) ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">ID de Reservación</p>
                            <p class="text-2xl font-bold text-gray-800">#<?= str_pad($reservacion['id'], 6, '0', STR_PAD_LEFT) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la cita -->
                <div class="px-8 py-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Información de la Cita</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Servicio</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-concierge-bell text-blue-600 mr-2"></i>
                                    <?= htmlspecialchars($reservacion['servicio_nombre']) ?>
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Fecha y Hora</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                    <?= date('d/m/Y', strtotime($reservacion['fecha_hora'])) ?>
                                </p>
                                <p class="text-lg font-semibold text-gray-800 ml-6">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    <?= date('h:i A', strtotime($reservacion['fecha_hora'])) ?>
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Duración</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-hourglass-half text-blue-600 mr-2"></i>
                                    <?= $reservacion['duracion_minutos'] ?> minutos
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Precio</p>
                                <p class="text-2xl font-bold text-green-600">
                                    $<?= number_format($reservacion['precio'], 2) ?>
                                </p>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Especialista</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-user-md text-blue-600 mr-2"></i>
                                    <?= htmlspecialchars($reservacion['especialista_nombre'] . ' ' . $reservacion['especialista_apellido']) ?>
                                </p>
                                <?php if ($reservacion['profesion']): ?>
                                    <p class="text-sm text-gray-600 ml-6">
                                        <?= htmlspecialchars($reservacion['profesion']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-1">Sucursal</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-building text-blue-600 mr-2"></i>
                                    <?= htmlspecialchars($reservacion['sucursal_nombre']) ?>
                                </p>
                                <p class="text-sm text-gray-600 ml-6">
                                    <?= htmlspecialchars($reservacion['sucursal_direccion']) ?>
                                </p>
                                <?php if ($reservacion['sucursal_telefono']): ?>
                                    <p class="text-sm text-gray-600 ml-6">
                                        <i class="fas fa-phone mr-1"></i>
                                        <?= htmlspecialchars($reservacion['sucursal_telefono']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if ($_SESSION['user_role'] !== 'cliente'): ?>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Cliente</p>
                                    <p class="text-lg font-semibold text-gray-800">
                                        <i class="fas fa-user text-blue-600 mr-2"></i>
                                        <?= htmlspecialchars($reservacion['cliente_nombre'] . ' ' . $reservacion['cliente_apellido']) ?>
                                    </p>
                                    <?php if ($reservacion['cliente_telefono']): ?>
                                        <p class="text-sm text-gray-600 ml-6">
                                            <i class="fas fa-phone mr-1"></i>
                                            <?= htmlspecialchars($reservacion['cliente_telefono']) ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="text-sm text-gray-600 ml-6">
                                        <i class="fas fa-envelope mr-1"></i>
                                        <?= htmlspecialchars($reservacion['cliente_email']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($reservacion['notas']): ?>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Notas</p>
                            <p class="text-gray-800 bg-gray-50 p-4 rounded">
                                <?= nl2br(htmlspecialchars($reservacion['notas'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Acciones -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-wrap gap-3">
                        <?php if ($_SESSION['user_role'] === 'cliente' && in_array($reservacion['estado'], ['pendiente', 'confirmada'])): ?>
                            <form method="POST" action="<?= BASE_URL ?>/reservacion/cancel/<?= $reservacion['id'] ?>" 
                                  onsubmit="return confirm('¿Está seguro de cancelar esta reservación?');" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-times-circle mr-2"></i> Cancelar Cita
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (in_array($_SESSION['user_role'], ['especialista', 'admin_sucursal', 'superadmin', 'recepcionista']) && $reservacion['estado'] === 'pendiente'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/reservacion/confirm/<?= $reservacion['id'] ?>" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i> Confirmar Cita
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if (in_array($_SESSION['user_role'], ['especialista', 'admin_sucursal', 'superadmin']) && $reservacion['estado'] === 'confirmada'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/reservacion/complete/<?= $reservacion['id'] ?>" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-check-double mr-2"></i> Marcar Completada
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>/dashboard" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition inline-block">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
