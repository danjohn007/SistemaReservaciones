<?php 
// Ensure config is loaded
if (!defined('ROOT_PATH')) {
    require_once dirname(__DIR__, 3) . '/config/config.php';
}
?>
<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <i class="fas fa-user-plus text-5xl text-blue-600 mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-800">Crear Cuenta</h2>
                <p class="text-gray-600 mt-2">Regístrate en ReserBot</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php 
            $old = isset($_SESSION['old_data']) ? $_SESSION['old_data'] : [];
            unset($_SESSION['old_data']);
            ?>

            <form action="<?= BASE_URL ?>/auth/doRegister" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre *</label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            required
                            value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label for="apellido" class="block text-gray-700 font-semibold mb-2">Apellido *</label>
                        <input 
                            type="text" 
                            id="apellido" 
                            name="apellido" 
                            required
                            value="<?= htmlspecialchars($old['apellido'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="tu@email.com"
                    >
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                    <input 
                        type="tel" 
                        id="telefono" 
                        name="telefono" 
                        value="<?= htmlspecialchars($old['telefono'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="4421234567"
                        maxlength="10"
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="<?= PASSWORD_MIN_LENGTH ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Mínimo <?= PASSWORD_MIN_LENGTH ?> caracteres"
                    >
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">Confirmar Contraseña *</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required
                        minlength="<?= PASSWORD_MIN_LENGTH ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    ¿Ya tienes cuenta? 
                    <a href="<?= BASE_URL ?>/auth/login" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
