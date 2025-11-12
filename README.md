# ReserBot - Sistema de Reservaciones y Citas Profesionales

Sistema completo de gestión de reservaciones y citas con múltiples niveles de acceso, desarrollado en PHP puro con arquitectura MVC.

## 🚀 Características Principales

- **Autenticación y Registro**: Sistema completo de login con roles diferenciados
- **5 Niveles de Acceso**: Superadmin, Admin de Sucursal, Especialista, Cliente y Recepcionista
- **Gestión de Reservaciones**: Agenda citas con verificación de disponibilidad en tiempo real
- **Múltiples Sucursales**: Gestión de diferentes ubicaciones
- **Especialistas y Servicios**: Catálogo completo de profesionales y servicios
- **Horarios Dinámicos**: Los especialistas definen sus horarios, vacaciones y bloqueos
- **Sistema de Calificaciones**: Evaluación de servicios y especialistas
- **Módulo de Configuraciones**: Panel completo para personalizar el sistema (sitio, emails, WhatsApp, colores, APIs)
- **Integraciones API**: PayPal, generación de QR, Shelly Relay, HikVision
- **Logs de Seguridad**: Auditoría completa de acciones en el sistema
- **Diseño Responsivo**: Interface moderna con Tailwind CSS

## 📋 Requisitos del Sistema

- **Servidor Web**: Apache 2.4+
- **PHP**: 7.4 o superior
- **MySQL**: 5.7 o superior
- **Extensiones PHP requeridas**:
  - pdo
  - pdo_mysql
  - session
  - mbstring
  - json

## 🛠️ Instalación

### 1. Clonar o Descargar el Repositorio

```bash
git clone https://github.com/danjohn007/SistemaReservaciones.git
cd SistemaReservaciones
```

### 2. Configurar el Servidor Apache

Copie todos los archivos al directorio de su servidor web (por ejemplo: `/var/www/html/reserbot` o `C:\xampp\htdocs\reserbot`).

El sistema detecta automáticamente la URL base, por lo que puede instalarse en cualquier directorio.

### 3. Crear la Base de Datos

```bash
# Acceder a MySQL
mysql -u root -p

# Ejecutar el archivo SQL
source /ruta/completa/al/database.sql
```

O desde la línea de comandos:

```bash
mysql -u root -p < database.sql
```

Esto creará:
- Base de datos: `reserbot_db`
- Usuario: `reserbot_user`
- Contraseña: `ReserBot2024!`
- Datos de ejemplo del estado de Querétaro

**Nota**: Si desea usar credenciales diferentes, edite el archivo `config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'reserbot_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar Permisos (Linux/Mac)

```bash
# Dar permisos de escritura a la carpeta de logs
chmod -R 755 logs/
chown -R www-data:www-data logs/

# Si es necesario, dar permisos al directorio completo
chmod -R 755 /var/www/html/reserbot
chown -R www-data:www-data /var/www/html/reserbot
```

### 5. Configurar Apache

Asegúrese de que el módulo `mod_rewrite` esté habilitado:

```bash
# Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# CentOS/RHEL
# El módulo suele estar habilitado por defecto
```

### 6. Verificar la Instalación

Acceda a la herramienta de prueba de conexión:

```
http://localhost/reserbot/test_connection.php
```

O si lo instaló en la raíz:

```
http://localhost/test_connection.php
```

Esta herramienta verificará:
- ✅ Configuración de URL base
- ✅ Conexión a la base de datos
- ✅ Tablas y datos de ejemplo
- ✅ Configuración de PHP
- ✅ Extensiones requeridas

### 7. Configurar el Sistema (Opcional pero Recomendado)

El sistema incluye un completo módulo de configuraciones. Para instalarlo:

```bash
# Ejecutar el script de configuraciones
mysql -u root -p < database_configuraciones.sql
```

O si su base de datos tiene un nombre diferente:

```bash
mysql -u tu_usuario -p tu_base_datos < database_configuraciones.sql
```

Luego acceda a: **Admin → Configuraciones** (solo superadmin)

Esto le permitirá configurar:
- 📝 Nombre del sitio y logotipo
- 📧 Email y servidor SMTP
- 💬 WhatsApp chatbot
- 📞 Teléfonos y horarios de atención
- 🎨 Colores del sistema
- 💳 Cuenta de PayPal
- 🔗 APIs externas (QR, Shelly Relay, HikVision)
- ⚙️ Configuraciones globales

Para más información, consulte: `CONFIGURACIONES_README.md`

Para probar el módulo: `http://localhost/reserbot/test_configuraciones.php`

## 🔐 Credenciales de Acceso

El sistema incluye usuarios de prueba con diferentes roles:

### Superadministrador
- **Email**: admin@reserbot.com
- **Contraseña**: ReserBot2024

### Administrador de Sucursal (Centro Histórico)
- **Email**: admin.centro@reserbot.com
- **Contraseña**: ReserBot2024

### Administrador de Sucursal (Juriquilla)
- **Email**: admin.juriquilla@reserbot.com
- **Contraseña**: ReserBot2024

### Especialistas
- **Dra. Ana López (Médico)**: ana.lopez@reserbot.com / ReserBot2024
- **Dr. Roberto Hernández (Odontólogo)**: roberto.hernandez@reserbot.com / ReserBot2024
- **Lic. Patricia Ramírez (Abogada)**: patricia.ramirez@reserbot.com / ReserBot2024
- **Mtro. Fernando Silva (Contador)**: fernando.silva@reserbot.com / ReserBot2024

### Cliente
- **Juan Pérez**: juan.perez@email.com / ReserBot2024
- **Laura Sánchez**: laura.sanchez@email.com / ReserBot2024

### Recepcionista
- **Sofía Torres**: sofia.torres@reserbot.com / ReserBot2024

## 📱 Uso del Sistema

### Para Clientes

1. **Registrarse**: Crear una cuenta desde la página principal
2. **Iniciar Sesión**: Acceder con email y contraseña
3. **Nueva Reservación**:
   - Seleccionar sucursal
   - Elegir servicio
   - Seleccionar especialista
   - Elegir fecha y hora disponible
   - Confirmar reservación
4. **Gestionar Citas**: Ver, modificar o cancelar reservaciones desde el dashboard

### Para Especialistas

1. **Iniciar Sesión**: Acceder con credenciales de especialista
2. **Ver Citas**: Dashboard muestra citas del día y próximas
3. **Gestionar Citas**: Confirmar, completar o gestionar reservaciones
4. **Horarios**: Definir disponibilidad, vacaciones y bloqueos (próximamente)

### Para Administradores

1. **Panel de Control**: Vista general del sistema
2. **Gestionar Sucursales**: Crear y administrar ubicaciones
3. **Especialistas**: Dar de alta y asignar servicios
4. **Reportes**: Ver estadísticas y métricas del sistema
5. **Usuarios**: Administrar cuentas y roles

## 🏗️ Estructura del Proyecto

```
SistemaReservaciones/
├── app/
│   ├── controllers/         # Controladores MVC
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── ReservacionController.php
│   ├── models/             # Modelos de datos
│   │   ├── Usuario.php
│   │   ├── Sucursal.php
│   │   ├── Servicio.php
│   │   ├── Especialista.php
│   │   └── Reservacion.php
│   └── views/              # Vistas
│       ├── layouts/        # Plantillas reutilizables
│       ├── home/           # Página principal
│       ├── auth/           # Login y registro
│       ├── dashboard/      # Dashboards por rol
│       └── reservations/   # Gestión de reservaciones
├── config/
│   ├── config.php          # Configuración principal
│   └── database.php        # Conexión a BD
├── public/
│   ├── css/               # Estilos personalizados
│   ├── js/                # JavaScript
│   └── images/            # Imágenes
├── logs/                  # Archivos de log
├── .htaccess             # Configuración Apache
├── index.php             # Front controller
├── test_connection.php   # Test de instalación
├── database.sql          # Schema y datos de ejemplo
└── README.md            # Este archivo
```

## 🔧 Configuración Avanzada

### Cambiar Zona Horaria

Edite `config/config.php`:

```php
date_default_timezone_set('America/Mexico_City');
```

### Configuración de Sesiones

```php
define('SESSION_LIFETIME', 3600); // Duración en segundos
define('SESSION_NAME', 'reserbot_session');
```

### Seguridad

```php
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos
```

### Entorno de Desarrollo/Producción

```php
define('APP_ENV', 'development'); // o 'production'
```

## 📊 Datos de Ejemplo

El sistema incluye:
- 3 Sucursales en Querétaro (Centro Histórico, Juriquilla, Corregidora)
- 6 Categorías de servicios
- 10 Servicios diferentes
- 4 Especialistas con horarios configurados
- Reservaciones de ejemplo
- Días festivos de México

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Protección CSRF en formularios
- Validación y sanitización de inputs
- Control de intentos de login fallidos
- Bloqueo temporal de cuentas
- Logs de auditoría completos
- Sesiones seguras con cookies HTTP-only

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos

1. Verifique que MySQL esté ejecutándose
2. Confirme las credenciales en `config/config.php`
3. Asegúrese de que el usuario tenga permisos

### Página en Blanco

1. Active el modo de desarrollo en `config/config.php`
2. Revise los logs de PHP y Apache
3. Verifique permisos de archivos

### URLs no Funcionan

1. Verifique que `mod_rewrite` esté habilitado
2. Confirme que el archivo `.htaccess` exista
3. Revise la configuración de Apache (`AllowOverride All`)

## 📝 Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (puro, sin frameworks)
- **Base de Datos**: MySQL 5.7
- **Frontend**: 
  - HTML5
  - Tailwind CSS 3.x
  - JavaScript (Vanilla)
  - Font Awesome 6.x
- **Arquitectura**: MVC
- **Servidor**: Apache 2.4+

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo licencia MIT.

## 👨‍💻 Autor

Desarrollado para el sistema de gestión de reservaciones profesionales.

## 📞 Soporte

Para reportar problemas o sugerencias, por favor use el sistema de issues del repositorio.
