<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="text-center">
        <div class="text-9xl font-bold text-blue-600 mb-4">404</div>
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Página No Encontrada</h1>
        <p class="text-xl text-gray-600 mb-8">La página que buscas no existe o ha sido movida.</p>
        <a href="<?= BASE_URL ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-home mr-2"></i> Volver al Inicio
        </a>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
