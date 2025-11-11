<nav class="bg-blue-600 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="<?= BASE_URL ?>" class="text-2xl font-bold flex items-center">
                    <i class="fas fa-calendar-check mr-2"></i>
                    ReserBot
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-sm">
                        <i class="fas fa-user mr-1"></i>
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </span>
                    <span class="text-xs bg-blue-700 px-2 py-1 rounded">
                        <?= htmlspecialchars($_SESSION['user_role']) ?>
                    </span>
                    <a href="<?= BASE_URL ?>/dashboard" class="hover:text-blue-200">
                        <i class="fas fa-dashboard mr-1"></i> Dashboard
                    </a>
                    <?php if (in_array($_SESSION['user_role'], ['superadmin', 'admin_sucursal'])): ?>
                        <div class="relative group">
                            <button class="hover:text-blue-200 flex items-center">
                                <i class="fas fa-cog mr-1"></i> Admin <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50">
                                <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                                    <a href="<?= BASE_URL ?>/admin/sucursales" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-building mr-2"></i> Sucursales
                                    </a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/admin/servicios" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-concierge-bell mr-2"></i> Servicios
                                </a>
                                <a href="<?= BASE_URL ?>/admin/especialistas" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-md mr-2"></i> Especialistas
                                </a>
                                <a href="<?= BASE_URL ?>/admin/reportes" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-chart-bar mr-2"></i> Reportes
                                </a>
                                <?php if ($_SESSION['user_role'] === 'superadmin'): ?>
                                    <a href="<?= BASE_URL ?>/configuracion" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i> Configuraciones
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['user_role'] === 'especialista'): ?>
                        <?php
                        // Get especialista ID for horarios link
                        $especialistaModel = new Especialista();
                        $especialista = $especialistaModel->findByUserId($_SESSION['user_id']);
                        if ($especialista):
                        ?>
                            <a href="<?= BASE_URL ?>/admin/horarios/<?= $especialista['id'] ?>" class="hover:text-blue-200">
                                <i class="fas fa-calendar-alt mr-1"></i> Mis Horarios
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/auth/logout" class="hover:text-blue-200">
                        <i class="fas fa-sign-out-alt mr-1"></i> Salir
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login" class="hover:text-blue-200">
                        <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                    </a>
                    <a href="<?= BASE_URL ?>/auth/register" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                        Registrarse
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
