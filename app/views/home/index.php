<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>
<?php require_once ROOT_PATH . '/app/views/layouts/navbar.php'; ?>

<div class="min-h-screen flex flex-col">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">
                <i class="fas fa-calendar-check"></i> Bienvenido a ReserBot
            </h1>
            <p class="text-xl mb-8">Sistema Profesional de Reservaciones y Citas</p>
            <p class="text-lg mb-10 max-w-3xl mx-auto">
                Gestiona citas con especialistas de manera fácil y eficiente. 
                Agenda, modifica y controla tus reservaciones en tiempo real.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="<?= BASE_URL ?>/auth/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse Gratis
                </a>
                <a href="<?= BASE_URL ?>/auth/login" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Características Principales</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-blue-600 text-5xl mb-4">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Disponibilidad en Tiempo Real</h3>
                    <p class="text-gray-600">
                        Consulta horarios disponibles al instante y agenda tu cita sin conflictos.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-green-600 text-5xl mb-4">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Notificaciones Automáticas</h3>
                    <p class="text-gray-600">
                        Recibe recordatorios por email sobre tus citas programadas.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-purple-600 text-5xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Múltiples Especialistas</h3>
                    <p class="text-gray-600">
                        Accede a diversos profesionales en diferentes sucursales.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-red-600 text-5xl mb-4">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Gestión de Horarios</h3>
                    <p class="text-gray-600">
                        Los especialistas definen sus horarios, vacaciones y bloqueos fácilmente.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-yellow-600 text-5xl mb-4">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Calificaciones</h3>
                    <p class="text-gray-600">
                        Sistema de reseñas para evaluar la calidad del servicio recibido.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-indigo-600 text-5xl mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Reportes y Estadísticas</h3>
                    <p class="text-gray-600">
                        Administradores acceden a reportes detallados y métricas del sistema.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Niveles de Acceso</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-crown text-3xl text-yellow-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Superadministrador</h3>
                    </div>
                    <p class="text-gray-600">Control total del sistema, gestión de sucursales, usuarios y reportes globales.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-building text-3xl text-blue-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Admin. de Sucursal</h3>
                    </div>
                    <p class="text-gray-600">Administra especialistas, servicios y horarios de su sucursal.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-user-md text-3xl text-green-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Especialista</h3>
                    </div>
                    <p class="text-gray-600">Consulta y gestiona sus citas, define horarios y disponibilidad.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-user text-3xl text-purple-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Cliente</h3>
                    </div>
                    <p class="text-gray-600">Crea cuenta, solicita citas y recibe notificaciones y recordatorios.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-headset text-3xl text-red-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Recepcionista</h3>
                    </div>
                    <p class="text-gray-600">Agenda citas manualmente, modifica y cancela bajo autorización.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-shield-alt text-3xl text-indigo-500 mr-3"></i>
                        <h3 class="text-xl font-semibold">Seguridad</h3>
                    </div>
                    <p class="text-gray-600">Control de permisos, logs de auditoría y políticas de acceso.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">¿Listo para Comenzar?</h2>
            <p class="text-xl mb-8">Únete a ReserBot y gestiona tus citas de manera profesional</p>
            <a href="<?= BASE_URL ?>/auth/register" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                <i class="fas fa-rocket mr-2"></i> Crear Cuenta Gratuita
            </a>
        </div>
    </section>

    <?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
</div>
