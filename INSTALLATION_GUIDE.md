# Guía de Instalación Completa - ReserBot

Esta guía proporciona instrucciones paso a paso para instalar el Sistema de Reservaciones ReserBot.

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Instalación en Servidor Local](#instalación-en-servidor-local)
3. [Instalación en Servidor de Producción](#instalación-en-servidor-de-producción)
4. [Verificación de la Instalación](#verificación-de-la-instalación)
5. [Configuración Inicial](#configuración-inicial)
6. [Solución de Problemas Comunes](#solución-de-problemas-comunes)

## 🔧 Requisitos Previos

### Software Requerido
- **Apache** 2.4 o superior con mod_rewrite habilitado
- **PHP** 7.4 o superior
- **MySQL** 5.7 o superior

### Extensiones PHP Necesarias
```bash
# Verificar extensiones instaladas
php -m | grep -E "pdo|mysql|mbstring|json|session"
```

Extensiones requeridas:
- pdo
- pdo_mysql
- mbstring
- json
- session

## 🖥️ Instalación en Servidor Local

### Paso 1: Instalar XAMPP/WAMP/LAMP

#### Windows (XAMPP)
1. Descargar XAMPP desde [apachefriends.org](https://www.apachefriends.org/)
2. Instalar en `C:\xampp`
3. Iniciar Apache y MySQL desde el Panel de Control

#### Linux (LAMP)
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-mbstring

# CentOS/RHEL
sudo yum install httpd mariadb-server php php-mysqlnd php-mbstring
```

#### macOS (MAMP o Homebrew)
```bash
# Con Homebrew
brew install httpd mysql php
```

### Paso 2: Descargar el Sistema

```bash
# Opción 1: Clonar con Git
cd /var/www/html  # o C:\xampp\htdocs en Windows
git clone https://github.com/danjohn007/SistemaReservaciones.git reserbot

# Opción 2: Descargar ZIP
# Descargar y extraer en el directorio web
```

### Paso 3: Configurar la Base de Datos

```bash
# Acceder a MySQL
mysql -u root -p

# En el prompt de MySQL, ejecutar:
```

```sql
-- Crear base de datos
CREATE DATABASE reserbot_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario
CREATE USER 'reserbot_user'@'localhost' IDENTIFIED BY 'ReserBot2024!';

-- Otorgar permisos
GRANT ALL PRIVILEGES ON reserbot_db.* TO 'reserbot_user'@'localhost';
FLUSH PRIVILEGES;

-- Salir
EXIT;
```

```bash
# Importar el esquema y datos
mysql -u reserbot_user -p reserbot_db < /ruta/al/database.sql
```

### Paso 4: Configurar Permisos (Linux/macOS)

```bash
# Dar permisos al directorio
sudo chown -R www-data:www-data /var/www/html/reserbot
sudo chmod -R 755 /var/www/html/reserbot

# Permisos especiales para logs
sudo chmod -R 775 /var/www/html/reserbot/logs
```

### Paso 5: Habilitar mod_rewrite

#### Ubuntu/Debian
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Configurar AllowOverride
Editar `/etc/apache2/sites-available/000-default.conf`:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

```bash
sudo systemctl restart apache2
```

## 🌐 Instalación en Servidor de Producción

### Paso 1: Preparar el Servidor

```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar stack LAMP
sudo apt install apache2 mysql-server php php-mysql php-mbstring php-xml php-curl -y

# Habilitar módulos necesarios
sudo a2enmod rewrite ssl headers
```

### Paso 2: Configurar Virtual Host

Crear `/etc/apache2/sites-available/reserbot.conf`:

```apache
<VirtualHost *:80>
    ServerName reserbot.tudominio.com
    ServerAdmin admin@tudominio.com
    DocumentRoot /var/www/reserbot

    <Directory /var/www/reserbot>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/reserbot-error.log
    CustomLog ${APACHE_LOG_DIR}/reserbot-access.log combined
</VirtualHost>
```

```bash
# Habilitar sitio
sudo a2ensite reserbot.conf
sudo systemctl reload apache2
```

### Paso 3: Seguridad en Producción

#### Configurar config.php
```php
// Cambiar a modo producción
define('APP_ENV', 'production');

// Cambiar credenciales de base de datos
define('DB_USER', 'usuario_seguro');
define('DB_PASS', 'contraseña_fuerte_aqui');
```

#### Configurar SSL (Recomendado)
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache

# Obtener certificado SSL
sudo certbot --apache -d reserbot.tudominio.com
```

#### Proteger archivos sensibles
```bash
# Limitar acceso a archivos de configuración
sudo chmod 600 /var/www/reserbot/config/*.php
```

## ✅ Verificación de la Instalación

### 1. Test de Conexión
Acceder a: `http://localhost/reserbot/test_connection.php`

Debe mostrar:
- ✓ URL Base detectada
- ✓ Conexión a base de datos exitosa
- ✓ Tablas creadas (15 tablas)
- ✓ Datos de ejemplo cargados
- ✓ Extensiones PHP activas

### 2. Probar el Sistema
Acceder a: `http://localhost/reserbot`

Debe cargar la página principal con:
- Hero section
- Características principales
- Niveles de acceso
- Botones de Login/Registro

### 3. Probar Login
1. Hacer clic en "Iniciar Sesión"
2. Usar credenciales de prueba:
   - **Email**: admin@reserbot.com
   - **Contraseña**: ReserBot2024
3. Debe redirigir al Dashboard de Superadmin

## 🔨 Configuración Inicial

### 1. Cambiar Contraseñas Predeterminadas

```php
// Generar hash de nueva contraseña
$nueva_contraseña = password_hash('tu_nueva_contraseña', PASSWORD_DEFAULT);
// Actualizar en base de datos
```

```sql
UPDATE usuarios 
SET password_hash = 'hash_generado' 
WHERE email = 'admin@reserbot.com';
```

### 2. Configurar Zona Horaria

Editar `config/config.php`:
```php
date_default_timezone_set('America/Mexico_City');
```

### 3. Personalizar Configuraciones

```php
// Duración de sesión
define('SESSION_LIFETIME', 3600); // 1 hora

// Seguridad
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos
```

### 4. Agregar Sucursales Propias

1. Login como Superadmin
2. Ir a "Admin" → "Sucursales"
3. Clic en "Nueva Sucursal"
4. Llenar formulario y guardar

### 5. Configurar Servicios

1. Ir a "Admin" → "Servicios"
2. Seleccionar sucursal
3. Clic en "Nuevo Servicio"
4. Definir: categoría, nombre, duración, precio

### 6. Agregar Especialistas

Los especialistas deben:
1. Registrarse como usuarios (o el admin los crea)
2. Admin los vincula a una sucursal
3. Asignarles servicios
4. Configurar horarios en "Admin" → "Especialistas" → "Ver Horarios"

## 🐛 Solución de Problemas Comunes

### Error: "Error de conexión a la base de datos"

**Causa**: Credenciales incorrectas o MySQL no está ejecutándose

**Solución**:
```bash
# Verificar que MySQL esté corriendo
sudo systemctl status mysql

# Verificar credenciales en config/config.php
# Probar conexión manual:
mysql -u reserbot_user -p reserbot_db
```

### Error: "Página en blanco"

**Causa**: Errores de PHP no mostrados

**Solución**:
```php
// Activar temporalmente en config/config.php
define('APP_ENV', 'development');

// O revisar logs
tail -f logs/php_errors.log
```

### Error: "404 Not Found" en URLs amigables

**Causa**: mod_rewrite no habilitado o .htaccess no funcionando

**Solución**:
```bash
# Habilitar mod_rewrite
sudo a2enmod rewrite

# Verificar AllowOverride en configuración Apache
# Debe ser: AllowOverride All

sudo systemctl restart apache2
```

### Error: "CSRF Token Inválido"

**Causa**: Sesión expirada o cookies bloqueadas

**Solución**:
- Cerrar sesión y volver a iniciar
- Verificar que cookies estén habilitadas en el navegador
- Limpiar cache del navegador

### Las reservaciones no se guardan

**Causa**: Permisos de escritura o problema con PDO

**Solución**:
```bash
# Verificar permisos
ls -la /var/www/html/reserbot/logs

# Verificar extensión PDO
php -m | grep pdo
```

### Horarios no aparecen disponibles

**Causa**: Especialista sin horarios configurados o fecha no laboral

**Solución**:
1. Verificar que el especialista tenga horarios para ese día
2. Verificar que no sea día festivo
3. Verificar que no haya bloqueo de horario

## 📊 Monitoreo y Mantenimiento

### Logs del Sistema
```bash
# Ver logs de seguridad (desde MySQL)
SELECT * FROM logs_seguridad ORDER BY created_at DESC LIMIT 50;

# Ver logs de Apache
tail -f /var/log/apache2/reserbot-error.log
```

### Backup de Base de Datos
```bash
# Crear backup
mysqldump -u reserbot_user -p reserbot_db > backup_$(date +%Y%m%d).sql

# Restaurar backup
mysql -u reserbot_user -p reserbot_db < backup_20240101.sql
```

### Actualizaciones
```bash
# Respaldar antes de actualizar
cp -r /var/www/reserbot /var/www/reserbot_backup

# Obtener actualizaciones
cd /var/www/reserbot
git pull origin main
```

## 📞 Soporte Adicional

Para más ayuda:
- Revisar README.md principal
- Revisar issues en GitHub
- Consultar logs del sistema
- Ejecutar test_connection.php

## 🔒 Seguridad Post-Instalación

### Checklist de Seguridad

- [ ] Cambiar contraseñas predeterminadas
- [ ] Configurar modo producción (APP_ENV)
- [ ] Configurar SSL/HTTPS
- [ ] Limitar permisos de archivos
- [ ] Configurar firewall
- [ ] Habilitar logs de auditoría
- [ ] Configurar backups automáticos
- [ ] Actualizar contraseñas de DB
- [ ] Eliminar archivos de prueba en producción

---

**¡Instalación Completa!** El sistema está listo para usarse.