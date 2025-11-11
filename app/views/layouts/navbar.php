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
