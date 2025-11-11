<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="<?= BASE_URL ?>/admin/especialistas" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Especialistas
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                <i class="fas fa-calendar-alt mr-2"></i> Horarios de Especialista
            </h1>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-user-md text-3xl text-blue-600 mr-4"></i>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            <?= htmlspecialchars($especialista['nombre'] . ' ' . $especialista['apellido']) ?>
                        </h2>
                        <?php if ($especialista['profesion']): ?>
                            <p class="text-gray-600"><?= htmlspecialchars($especialista['profesion']) ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($especialista['sucursal_nombre']) ?></p>
                    </div>
                </div>
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

            <!-- Servicios Asignados -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-concierge-bell mr-2"></i> Servicios Asignados
                    </h3>
                </div>
                <div class="p-6">
                    <?php if (empty($servicios)): ?>
                        <p class="text-gray-600">No tiene servicios asignados</p>
                    <?php else: ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($servicios as $servicio): ?>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                    <?= htmlspecialchars($servicio['nombre']) ?>
                                    <span class="text-xs">(<?= $servicio['duracion_minutos'] ?> min)</span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Horarios -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-clock mr-2"></i> Horarios de Atención
                    </h3>
                    <button onclick="document.getElementById('addHorarioModal').classList.remove('hidden')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
                        <i class="fas fa-plus mr-1"></i> Agregar Horario
                    </button>
                </div>
                <div class="p-6">
                    <?php if (empty($horarios)): ?>
                        <p class="text-gray-600 text-center py-4">No hay horarios configurados</p>
                    <?php else: ?>
                        <div class="space-y-2">
                            <?php 
                            $diasOrden = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
                            $diasNombres = [
                                'lunes' => 'Lunes',
                                'martes' => 'Martes',
                                'miercoles' => 'Miércoles',
                                'jueves' => 'Jueves',
                                'viernes' => 'Viernes',
                                'sabado' => 'Sábado',
                                'domingo' => 'Domingo'
                            ];
                            
                            $horariosPorDia = [];
                            foreach ($horarios as $horario) {
                                $horariosPorDia[$horario['dia_semana']][] = $horario;
                            }
                            
                            foreach ($diasOrden as $dia):
                                if (isset($horariosPorDia[$dia])):
                            ?>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="font-semibold text-gray-800 mb-2">
                                        <?= $diasNombres[$dia] ?>
                                    </div>
                                    <div class="space-y-2">
                                        <?php foreach ($horariosPorDia[$dia] as $horario): ?>
                                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                <span class="text-sm text-gray-700">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    <?= date('H:i', strtotime($horario['hora_inicio'])) ?> - 
                                                    <?= date('H:i', strtotime($horario['hora_fin'])) ?>
                                                </span>
                                                <form method="POST" action="<?= BASE_URL ?>/admin/deleteHorario/<?= $horario['id'] ?>" 
                                                      onsubmit="return confirm('¿Eliminar este horario?');" class="inline">
                                                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                                    <input type="hidden" name="especialista_id" value="<?= $especialista['id'] ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Horario -->
<div id="addHorarioModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-plus-circle mr-2"></i> Agregar Horario
        </h3>
        
        <form method="POST" action="<?= BASE_URL ?>/admin/addHorario/<?= $especialista['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Día de la Semana *</label>
                <select name="dia_semana" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Seleccione --</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sabado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Hora de Inicio *</label>
                <input type="time" name="hora_inicio" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Hora de Fin *</label>
                <input type="time" name="hora_fin" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar
                </button>
                <button type="button" onclick="document.getElementById('addHorarioModal').classList.add('hidden')"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
