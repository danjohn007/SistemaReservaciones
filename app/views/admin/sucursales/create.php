<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <a href="<?= BASE_URL ?>/admin/sucursales" class="text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Sucursales
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                <i class="fas fa-plus-circle mr-2"></i> Nueva Sucursal
            </h1>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST" action="<?= BASE_URL ?>/admin/createSucursal">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Nombre de la Sucursal *</label>
                        <input type="text" name="nombre" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Dirección *</label>
                        <input type="text" name="direccion" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Ciudad *</label>
                            <input type="text" name="ciudad" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Estado *</label>
                            <input type="text" name="estado" required value="Querétaro"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Código Postal</label>
                            <input type="text" name="codigo_postal" maxlength="10"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                            <input type="tel" name="telefono" maxlength="20"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Hora de Apertura *</label>
                            <input type="time" name="hora_apertura" required value="08:00"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Hora de Cierre *</label>
                            <input type="time" name="hora_cierre" required value="20:00"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="activo" value="1" checked
                                   class="mr-2 h-4 w-4 text-blue-600">
                            <span class="text-gray-700">Sucursal activa</span>
                        </label>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            <i class="fas fa-save mr-2"></i> Guardar Sucursal
                        </button>
                        <a href="<?= BASE_URL ?>/admin/sucursales" 
                           class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
