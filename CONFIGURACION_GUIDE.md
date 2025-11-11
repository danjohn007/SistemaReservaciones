# Guía del Módulo de Configuraciones

## Descripción

El módulo de configuraciones de ReserBot permite a los superadministradores personalizar y gestionar todos los aspectos del sistema desde una interfaz centralizada.

## Acceso al Módulo

1. Iniciar sesión como **Superadministrador**
2. Hacer clic en el menú **Admin** en la barra de navegación
3. Seleccionar **Configuraciones** del menú desplegable
4. Ruta directa: `/configuracion`

## Categorías de Configuración

### 1. General
Configuraciones básicas del sitio:
- **Nombre del sitio**: Nombre que aparece en el título y navegación
- **Logo del sitio**: URL o ruta del logotipo (se muestra en la barra de navegación)

### 2. Email
Configuración del servidor de correo para envío de notificaciones:
- **Email remitente**: Dirección de correo que envía los mensajes
- **Nombre remitente**: Nombre que aparece como remitente
- **SMTP Host**: Servidor SMTP (ej: smtp.gmail.com)
- **SMTP Puerto**: Puerto del servidor (típicamente 587 para TLS)
- **SMTP Usuario**: Usuario para autenticación
- **SMTP Password**: Contraseña del usuario SMTP
- **SMTP Seguridad**: Tipo de encriptación (TLS/SSL/Ninguno)

### 3. WhatsApp
Configuración del chatbot de WhatsApp:
- **Número WhatsApp**: Número del chatbot (formato internacional: 521234567890)
- **Activar WhatsApp**: Habilitar/deshabilitar integración

### 4. Contacto
Información de contacto y horarios:
- **Teléfono contacto 1**: Teléfono principal
- **Teléfono contacto 2**: Teléfono secundario
- **Horario atención inicio**: Hora de inicio (formato 24h)
- **Horario atención fin**: Hora de cierre
- **Horario atención días**: Días de atención

### 5. Colores
Personalización visual del sistema:
- **Color primario**: Color principal del tema (#hex)
- **Color secundario**: Color secundario (#hex)
- **Color acento**: Color de acento (#hex)

Los colores se aplican automáticamente en toda la interfaz al guardar.

### 6. PayPal
Configuración de pagos en línea:
- **Modo PayPal**: Sandbox (pruebas) o Live (producción)
- **Client ID**: ID del cliente PayPal
- **Secret**: Clave secreta de PayPal
- **Activar PayPal**: Habilitar/deshabilitar pagos

### 7. APIs

#### API para QR Masivos
Generación masiva de códigos QR:
- **Proveedor**: Nombre del proveedor de API (qrcode-monkey, qr-code-generator, etc.)
- **API Key**: Clave de autenticación
- **Activar API**: Habilitar/deshabilitar

#### API Shelly Relay
Control de dispositivos IoT Shelly:
- **Host**: Dirección IP o hostname del dispositivo
- **Auth**: Token de autenticación
- **Activar**: Habilitar/deshabilitar integración

#### API HikVision
Integración con cámaras y sistemas HikVision:
- **Host**: Dirección IP o hostname
- **Usuario**: Usuario de acceso
- **Password**: Contraseña
- **Activar**: Habilitar/deshabilitar integración

### 8. Sistema
Configuraciones globales del sistema:
- **Modo mantenimiento**: Activar para bloquear el acceso temporal
- **Mensaje mantenimiento**: Texto mostrado durante el mantenimiento
- **Permitir registro**: Permitir/bloquear registro de nuevos usuarios
- **Verificar email**: Requerir verificación de email al registrarse
- **Duración sesión**: Tiempo de sesión en segundos (default: 3600)
- **Notificaciones email**: Activar notificaciones por correo
- **Notificaciones SMS**: Activar notificaciones por SMS
- **Tiempo anticipación reserva**: Minutos mínimos para hacer una reserva
- **Tiempo recordatorio**: Minutos antes de la cita para enviar recordatorio
- **Permitir cancelación**: Permitir que clientes cancelen citas
- **Tiempo límite cancelación**: Minutos antes de la cita para poder cancelar

## Uso de Configuraciones en el Código

### En PHP (Controllers/Views)
```php
// Obtener una configuración
$siteName = getConfig('sitio_nombre', 'ReserBot');
$emailEnabled = getConfig('notificaciones_email', '0');

// Verificar si está activado
if (getConfig('whatsapp_activado', '0') == '1') {
    // Lógica de WhatsApp
}
```

### En el Modelo
```php
$configModel = new Configuracion();

// Obtener todas las configuraciones
$allConfigs = $configModel->getAll();

// Obtener configuraciones agrupadas
$grouped = $configModel->getGrouped();

// Obtener una configuración específica
$value = $configModel->getByClave('sitio_nombre', 'Default');

// Establecer una configuración
$configModel->set('clave', 'valor', 'descripción');

// Actualizar múltiples configuraciones
$configModel->setMultiple([
    'sitio_nombre' => 'Nuevo Nombre',
    'color_primario' => '#ff0000'
]);
```

## Características Especiales

### 1. Personalización Visual
Los colores configurados se aplican automáticamente usando variables CSS:
- `--color-primario`
- `--color-secundario`
- `--color-acento`

### 2. Cache de Configuraciones
Las configuraciones se cargan en caché en la primera llamada para optimizar el rendimiento.

### 3. Validación de Campos
- Campos de email validan formato
- Campos de contraseña se ocultan
- Campos numéricos solo aceptan números
- Selectores de color muestran preview visual

### 4. Seguridad
- Solo los superadministradores pueden acceder
- Protección CSRF en todos los formularios
- Contraseñas y tokens nunca se muestran en texto plano
- Logs de seguridad para todas las modificaciones

## Extensión del Sistema

Para agregar nuevas configuraciones:

1. **Insertar en la base de datos**:
```sql
INSERT INTO configuraciones (clave, valor, descripcion) 
VALUES ('mi_config', 'valor_default', 'Descripción de mi configuración');
```

2. **Usar en el código**:
```php
$miValor = getConfig('mi_config', 'default');
```

3. **Agregar al formulario** (opcional):
Editar `/app/views/admin/configuraciones/index.php` y agregar el campo en la pestaña apropiada.

## Troubleshooting

### Las configuraciones no se guardan
- Verificar permisos de escritura en la base de datos
- Verificar que el token CSRF es válido
- Revisar logs de PHP y MySQL

### Los colores no se aplican
- Limpiar caché del navegador
- Verificar que los valores son códigos hex válidos (#RRGGBB)
- Recargar la página después de guardar

### Error de acceso denegado
- Verificar que el usuario tiene rol 'superadmin'
- Verificar que la sesión está activa

## Respaldos

Se recomienda hacer respaldo de la tabla `configuraciones` regularmente:

```sql
mysqldump -u usuario -p base_de_datos configuraciones > configuraciones_backup.sql
```

## Soporte

Para reportar problemas o sugerir mejoras, contactar al equipo de desarrollo.
