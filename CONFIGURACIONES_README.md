# Módulo de Configuraciones - ReserBot

Este documento explica cómo implementar el nuevo módulo de configuraciones y resolver el problema de acceso al sistema.

## Problema Identificado

El archivo `test_connection.php` valida las conexiones correctamente, pero hay una **discrepancia en los nombres de base de datos**:

- **config.php** usa: `i45com_reserbot`
- **database.sql** crea: `reserbot_db`

### Solución al Problema de Login/Register

**Opción 1: Ajustar la base de datos a la configuración actual**

Si ya tiene datos en `i45com_reserbot`, no necesita hacer nada más. Solo asegúrese de ejecutar el script de configuraciones.

**Opción 2: Crear la base de datos con el nombre correcto**

Si está empezando desde cero:

```bash
# Opción A: Modificar database.sql antes de ejecutarlo
sed -i 's/reserbot_db/i45com_reserbot/g' database.sql
mysql -u root -p < database.sql
```

O

```bash
# Opción B: Crear manualmente
mysql -u root -p
```

```sql
CREATE DATABASE IF NOT EXISTS i45com_reserbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE i45com_reserbot;
-- Luego ejecutar el contenido de database.sql
```

## Implementación del Módulo de Configuraciones

### Paso 1: Ejecutar el Script SQL

El archivo `database_configuraciones.sql` contiene todas las configuraciones requeridas.

```bash
mysql -u i45com_reserbot -p i45com_reserbot < database_configuraciones.sql
```

O desde MySQL:

```bash
mysql -u i45com_reserbot -p
```

```sql
USE i45com_reserbot;
source /ruta/al/database_configuraciones.sql;
```

### Paso 2: Verificar la Instalación

Ejecute la siguiente consulta para verificar que las configuraciones se insertaron correctamente:

```sql
SELECT COUNT(*) as total FROM configuraciones;
-- Debería mostrar aproximadamente 50 configuraciones
```

### Paso 3: Acceder al Módulo

1. Inicie sesión como **superadmin**:
   - Email: `admin@reserbot.com`
   - Password: `ReserBot2024`

2. Vaya a **Admin → Configuraciones** en el menú superior

3. Configure cada sección según sus necesidades

## Configuraciones Disponibles

### 1. Nombre del Sitio y Logotipo
- Nombre del sitio
- URL del logotipo

### 2. Configuración de Correo Electrónico
- Correo remitente
- Nombre del remitente
- Servidor SMTP (host, puerto, usuario, contraseña)
- Tipo de seguridad (TLS/SSL)

### 3. WhatsApp Chatbot
- Número de WhatsApp
- Activar/desactivar chatbot

### 4. Teléfonos de Contacto y Horarios
- Teléfono principal y secundario
- Horarios de atención (inicio, fin, días)

### 5. Estilos y Colores del Sistema
- Color primario
- Color secundario
- Color de acento
- Color de texto
- Color de fondo

### 6. Configuración de PayPal
- Modo (sandbox/live)
- Client ID
- Secret
- Email de la cuenta

### 7. API para Crear QRs Masivos
- Proveedor de API
- URL del endpoint
- Token de autenticación
- Estado (activo/inactivo)

### 8. API para Dispositivos Shelly Relay
- URL base
- Token de autenticación
- Estado (activo/inactivo)

### 9. API para Dispositivos HikVision
- URL base
- Usuario
- Contraseña
- Estado (activo/inactivo)

### 10. Configuraciones Globales del Sistema
- Zona horaria
- Idioma
- Formato de fecha y hora
- Moneda
- Duración de sesión
- Modo mantenimiento
- Registro público habilitado/deshabilitado

## Uso Programático de las Configuraciones

### Obtener una configuración

```php
$configuracionModel = new Configuracion();
$sitioNombre = $configuracionModel->get('sitio_nombre', 'ReserBot');
$whatsappNumero = $configuracionModel->get('whatsapp_numero');
```

### Establecer una configuración

```php
$configuracionModel = new Configuracion();
$configuracionModel->set('sitio_nombre', 'Mi Nuevo Nombre');
$configuracionModel->set('whatsapp_numero', '+52 442 123 4567');
```

### Obtener configuraciones por prefijo

```php
$configuracionModel = new Configuracion();
$emailConfigs = $configuracionModel->getByPrefix('email_');
// Devuelve: ['email_remitente' => 'noreply@...', 'email_smtp_host' => 'smtp...', ...]
```

### Actualizar múltiples configuraciones

```php
$configuracionModel = new Configuracion();
$configs = [
    'sitio_nombre' => 'Nuevo Nombre',
    'whatsapp_numero' => '+52 442 999 8888'
];
$configuracionModel->setMultiple($configs);
```

## Verificación de Funcionamiento

### 1. Verificar que el Login funciona

```bash
# Acceder a:
http://tu-dominio.com/auth/login
```

Si aparece un error 404 o página en blanco:

1. Verifique que `.htaccess` está configurado correctamente
2. Verifique que `mod_rewrite` está habilitado en Apache
3. Revise los logs de PHP y Apache:

```bash
tail -f /var/log/apache2/error.log
tail -f logs/php_errors.log
```

### 2. Verificar que el Registro funciona

```bash
# Acceder a:
http://tu-dominio.com/auth/register
```

### 3. Verificar las Configuraciones

Una vez logueado como superadmin:

```bash
# Acceder a:
http://tu-dominio.com/admin/configuraciones
```

## Solución de Problemas Comunes

### Error: "Vista no encontrada"

- Verifique que los archivos existen en `app/views/admin/configuraciones/`
- Verifique permisos de lectura: `chmod 644 app/views/admin/configuraciones/index.php`

### Error: "Class Configuracion not found"

- Verifique que existe `app/models/Configuracion.php`
- Verifique el autoloader en `index.php`

### Error: "Table 'configuraciones' doesn't exist"

- Ejecute el script SQL: `database_configuraciones.sql`
- Verifique que la tabla se creó: `SHOW TABLES LIKE 'configuraciones';`

### No aparece el menú de Configuraciones

- Debe estar logueado como **superadmin**
- Verifique `$_SESSION['user_role'] === 'superadmin'`

## Seguridad

### Campos Sensibles

Los siguientes campos son tratados como sensibles y no se sanitizan (para preservar caracteres especiales):

- Contraseñas (`*password*`)
- Secrets (`*secret*`)
- Tokens (`*token*`)

### Protección CSRF

Todas las operaciones de guardado están protegidas con tokens CSRF.

### Logs de Auditoría

Cada actualización de configuraciones se registra en `logs_seguridad`:

```sql
SELECT * FROM logs_seguridad WHERE accion = 'configuraciones_actualizadas' ORDER BY created_at DESC;
```

## Mantenimiento

### Limpiar Caché de Configuraciones

```php
Configuracion::clearCache();
```

### Backup de Configuraciones

```bash
mysqldump -u i45com_reserbot -p i45com_reserbot configuraciones > configuraciones_backup.sql
```

### Restaurar Configuraciones

```bash
mysql -u i45com_reserbot -p i45com_reserbot < configuraciones_backup.sql
```

## Soporte

Para más información sobre el sistema ReserBot, consulte:
- `README.md` - Documentación general
- `INSTALLATION_GUIDE.md` - Guía de instalación
- `USER_GUIDE.md` - Guía de usuario

---

**Versión del Módulo:** 1.0.0  
**Fecha:** Noviembre 2024  
**Autor:** Sistema ReserBot
