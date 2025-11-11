<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-calendar-plus mr-2"></i> Nueva Reservación
            </h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <form id="reservationForm" action="<?= BASE_URL ?>/reservacion/store" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Paso 1: Seleccionar Sucursal -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">
                            <span class="text-blue-600">1.</span> Selecciona una Sucursal *
                        </label>
                        <select id="sucursal_id" name="sucursal_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Seleccione una sucursal --</option>
                            <?php foreach ($sucursales as $sucursal): ?>
                                <option value="<?= $sucursal['id'] ?>">
                                    <?= htmlspecialchars($sucursal['nombre']) ?> - 
                                    <?= htmlspecialchars($sucursal['ciudad']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Paso 2: Seleccionar Servicio -->
                    <div id="servicios-container" class="mb-6 hidden">
                        <label class="block text-gray-700 font-bold mb-2">
                            <span class="text-blue-600">2.</span> Selecciona un Servicio *
                        </label>
                        <div id="servicios-loading" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                            <p class="text-gray-600 mt-2">Cargando servicios...</p>
                        </div>
                        <select id="servicio_id" name="servicio_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">
                            <option value="">-- Seleccione un servicio --</option>
                        </select>
                        <div id="servicio-info" class="mt-3 p-4 bg-blue-50 rounded-lg hidden">
                            <p class="text-sm text-gray-700">
                                <strong>Duración:</strong> <span id="servicio-duracion"></span> minutos<br>
                                <strong>Precio:</strong> $<span id="servicio-precio"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Paso 3: Seleccionar Especialista -->
                    <div id="especialistas-container" class="mb-6 hidden">
                        <label class="block text-gray-700 font-bold mb-2">
                            <span class="text-blue-600">3.</span> Selecciona un Especialista *
                        </label>
                        <div id="especialistas-loading" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                            <p class="text-gray-600 mt-2">Cargando especialistas...</p>
                        </div>
                        <select id="especialista_id" name="especialista_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">
                            <option value="">-- Seleccione un especialista --</option>
                        </select>
                    </div>

                    <!-- Paso 4: Seleccionar Fecha -->
                    <div id="fecha-container" class="mb-6 hidden">
                        <label class="block text-gray-700 font-bold mb-2">
                            <span class="text-blue-600">4.</span> Selecciona una Fecha *
                        </label>
                        <input type="date" id="fecha" 
                               min="<?= date('Y-m-d') ?>"
                               max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Paso 5: Seleccionar Hora -->
                    <div id="hora-container" class="mb-6 hidden">
                        <label class="block text-gray-700 font-bold mb-2">
                            <span class="text-blue-600">5.</span> Selecciona una Hora *
                        </label>
                        <div id="slots-loading" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                            <p class="text-gray-600 mt-2">Cargando horarios disponibles...</p>
                        </div>
                        <div id="slots-container" class="grid grid-cols-3 md:grid-cols-4 gap-2 hidden"></div>
                        <input type="hidden" id="fecha_hora" name="fecha_hora">
                        <div id="no-slots" class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded hidden">
                            No hay horarios disponibles para esta fecha. Por favor seleccione otra fecha.
                        </div>
                    </div>

                    <!-- Notas adicionales -->
                    <div id="notas-container" class="mb-6 hidden">
                        <label class="block text-gray-700 font-bold mb-2">
                            Notas adicionales (opcional)
                        </label>
                        <textarea name="notas" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Información adicional sobre su cita..."></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-4">
                        <button type="submit" id="submit-btn"
                                class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <i class="fas fa-check-circle mr-2"></i> Confirmar Reservación
                        </button>
                        <a href="<?= BASE_URL ?>/dashboard"
                           class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const baseUrl = BASE_URL;
    let serviciosData = [];
    let selectedSlot = null;
    
    // Cargar servicios cuando se selecciona sucursal
    document.getElementById('sucursal_id').addEventListener('change', async function() {
        const sucursalId = this.value;
        
        if (!sucursalId) {
            document.getElementById('servicios-container').classList.add('hidden');
            return;
        }
        
        document.getElementById('servicios-container').classList.remove('hidden');
        document.getElementById('servicios-loading').classList.remove('hidden');
        document.getElementById('servicio_id').classList.add('hidden');
        
        try {
            const response = await fetch(`${baseUrl}/reservacion/getServicios/${sucursalId}`);
            const data = await response.json();
            
            if (data.success) {
                serviciosData = data.servicios;
                const select = document.getElementById('servicio_id');
                select.innerHTML = '<option value="">-- Seleccione un servicio --</option>';
                
                data.servicios.forEach(servicio => {
                    const option = document.createElement('option');
                    option.value = servicio.id;
                    option.textContent = `${servicio.nombre} - $${servicio.precio}`;
                    option.dataset.duracion = servicio.duracion_minutos;
                    option.dataset.precio = servicio.precio;
                    select.appendChild(option);
                });
                
                document.getElementById('servicios-loading').classList.add('hidden');
                select.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar servicios');
        }
    });
    
    // Mostrar info del servicio y cargar especialistas
    document.getElementById('servicio_id').addEventListener('change', async function() {
        const servicioId = this.value;
        
        if (!servicioId) {
            document.getElementById('servicio-info').classList.add('hidden');
            document.getElementById('especialistas-container').classList.add('hidden');
            return;
        }
        
        const option = this.options[this.selectedIndex];
        document.getElementById('servicio-duracion').textContent = option.dataset.duracion;
        document.getElementById('servicio-precio').textContent = option.dataset.precio;
        document.getElementById('servicio-info').classList.remove('hidden');
        
        // Cargar especialistas
        const sucursalId = document.getElementById('sucursal_id').value;
        document.getElementById('especialistas-container').classList.remove('hidden');
        document.getElementById('especialistas-loading').classList.remove('hidden');
        document.getElementById('especialista_id').classList.add('hidden');
        
        try {
            const response = await fetch(`${baseUrl}/reservacion/getEspecialistas/${servicioId}?sucursal_id=${sucursalId}`);
            const data = await response.json();
            
            if (data.success) {
                const select = document.getElementById('especialista_id');
                select.innerHTML = '<option value="">-- Seleccione un especialista --</option>';
                
                data.especialistas.forEach(esp => {
                    const option = document.createElement('option');
                    option.value = esp.id;
                    option.textContent = `${esp.nombre} ${esp.apellido}`;
                    if (esp.calificacion_promedio > 0) {
                        option.textContent += ` - ⭐ ${esp.calificacion_promedio}`;
                    }
                    select.appendChild(option);
                });
                
                document.getElementById('especialistas-loading').classList.add('hidden');
                select.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar especialistas');
        }
    });
    
    // Mostrar selector de fecha
    document.getElementById('especialista_id').addEventListener('change', function() {
        if (this.value) {
            document.getElementById('fecha-container').classList.remove('hidden');
        } else {
            document.getElementById('fecha-container').classList.add('hidden');
        }
    });
    
    // Cargar slots cuando se selecciona fecha
    document.getElementById('fecha').addEventListener('change', async function() {
        const fecha = this.value;
        const especialistaId = document.getElementById('especialista_id').value;
        const servicioOption = document.getElementById('servicio_id').options[document.getElementById('servicio_id').selectedIndex];
        const duracionMinutos = servicioOption.dataset.duracion;
        
        if (!fecha || !especialistaId) return;
        
        document.getElementById('hora-container').classList.remove('hidden');
        document.getElementById('slots-loading').classList.remove('hidden');
        document.getElementById('slots-container').classList.add('hidden');
        document.getElementById('no-slots').classList.add('hidden');
        
        try {
            const response = await fetch(`${baseUrl}/reservacion/getSlots?especialista_id=${especialistaId}&fecha=${fecha}&duracion_minutos=${duracionMinutos}`);
            const data = await response.json();
            
            document.getElementById('slots-loading').classList.add('hidden');
            
            if (data.success && data.slots.length > 0) {
                const container = document.getElementById('slots-container');
                container.innerHTML = '';
                
                data.slots.forEach(slot => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'px-4 py-2 border-2 border-blue-300 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition slot-btn';
                    button.textContent = slot.hora;
                    button.dataset.fechaHora = slot.inicio;
                    button.onclick = () => selectSlot(button);
                    container.appendChild(button);
                });
                
                container.classList.remove('hidden');
                document.getElementById('notas-container').classList.remove('hidden');
            } else {
                document.getElementById('no-slots').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar horarios');
        }
    });
    
    function selectSlot(button) {
        // Deseleccionar todos
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('border-blue-300', 'text-blue-600');
        });
        
        // Seleccionar este
        button.classList.add('bg-blue-600', 'text-white');
        button.classList.remove('border-blue-300', 'text-blue-600');
        
        document.getElementById('fecha_hora').value = button.dataset.fechaHora;
        document.getElementById('submit-btn').disabled = false;
    }
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
