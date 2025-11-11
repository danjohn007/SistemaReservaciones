# Manual de Usuario - ReserBot

Guía completa para utilizar el Sistema de Reservaciones ReserBot.

## 📚 Índice

1. [Introducción](#introducción)
2. [Para Clientes](#para-clientes)
3. [Para Especialistas](#para-especialistas)
4. [Para Administradores](#para-administradores)
5. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## 🎯 Introducción

ReserBot es un sistema profesional de gestión de reservaciones que permite a clientes agendar citas con especialistas de manera fácil y eficiente.

### Tipos de Usuario

- **Cliente**: Agenda y gestiona sus propias citas
- **Especialista**: Visualiza y administra sus citas agendadas
- **Admin de Sucursal**: Gestiona servicios y especialistas de su sucursal
- **Superadmin**: Control total del sistema
- **Recepcionista**: Agenda citas para clientes

---

## 👥 Para Clientes

### 1. Registro e Inicio de Sesión

#### Crear una Cuenta
1. Ir a la página principal
2. Clic en "Registrarse"
3. Llenar el formulario:
   - Nombre y Apellido
   - Email (será tu usuario)
   - Teléfono (opcional pero recomendado)
   - Contraseña (mínimo 8 caracteres)
4. Clic en "Registrarse"
5. Iniciar sesión con tu email y contraseña

#### Iniciar Sesión
1. Clic en "Iniciar Sesión"
2. Ingresar email y contraseña
3. Clic en "Iniciar Sesión"

### 2. Agendar una Cita

#### Proceso Paso a Paso

**Paso 1: Nueva Reservación**
1. Desde tu Dashboard, clic en "Nueva Reservación"
2. O usar el botón en la barra de navegación

**Paso 2: Seleccionar Sucursal**
- Elige la sucursal más conveniente
- Verás ubicación y horarios de cada una

**Paso 3: Seleccionar Servicio**
- Aparecerán los servicios disponibles en esa sucursal
- Cada servicio muestra:
  - Nombre y descripción
  - Duración en minutos
  - Precio

**Paso 4: Seleccionar Especialista**
- Lista de especialistas que ofrecen ese servicio
- Puedes ver:
  - Nombre y profesión
  - Calificación promedio (si tiene reseñas)

**Paso 5: Seleccionar Fecha**
- Calendario con fechas disponibles
- Puedes reservar hasta 30 días adelante

**Paso 6: Seleccionar Hora**
- Verás slots de tiempo disponibles
- En verde: horarios libres
- Si no hay horarios, prueba otra fecha

**Paso 7: Agregar Notas (Opcional)**
- Información adicional sobre tu cita
- Razón de la consulta
- Alguna condición especial

**Paso 8: Confirmar**
- Revisar todos los detalles
- Clic en "Confirmar Reservación"
- Recibirás confirmación en pantalla

### 3. Gestionar Mis Citas

#### Ver Mis Citas
- Acceder al Dashboard
- Verás dos secciones:
  - **Próximas Citas**: Pendientes y confirmadas
  - **Historial**: Completadas y canceladas

#### Ver Detalles de una Cita
1. Clic en "Ver Detalles"
2. Información mostrada:
   - Servicio contratado
   - Fecha y hora
   - Especialista asignado
   - Ubicación de la sucursal
   - Estado de la cita
   - Precio

#### Estados de las Citas
- 🟡 **Pendiente**: Esperando confirmación del especialista
- 🟢 **Confirmada**: Cita confirmada, asistir a la hora indicada
- 🔵 **Completada**: Servicio realizado
- 🔴 **Cancelada**: Cita cancelada
- ⚫ **No Asistió**: No se presentó a la cita

#### Cancelar una Cita
1. Entrar a los detalles de la cita
2. Clic en "Cancelar Cita"
3. Confirmar la cancelación
4. Solo puedes cancelar citas pendientes o confirmadas
5. **Importante**: Cancela con anticipación para no afectar al especialista

### 4. Consejos para Clientes

✅ **Recomendaciones**:
- Llega 10 minutos antes de tu cita
- Anota la dirección de la sucursal
- Guarda el número de teléfono
- Cancela con anticipación si no puedes asistir

❌ **Evita**:
- Agendar múltiples citas para el mismo horario
- Cancelar a última hora sin razón
- No asistir sin avisar (afecta tu historial)

---

## 👨‍⚕️ Para Especialistas

### 1. Acceso al Sistema

- Iniciar sesión con credenciales proporcionadas
- Automáticamente irás a tu dashboard de especialista

### 2. Dashboard del Especialista

#### Citas de Hoy
- Lista de todas tus citas del día
- Información de cada cliente:
  - Nombre y teléfono
  - Servicio solicitado
  - Hora de la cita
  - Estado actual

#### Próximas Citas
- Calendario de los próximos 7 días
- Citas confirmadas
- Horarios ocupados

### 3. Gestionar Citas

#### Ver Detalles de una Cita
1. Clic en "Ver Detalles"
2. Información completa del cliente y servicio

#### Confirmar una Cita
- Citas nuevas llegan como "Pendiente"
- Clic en "Confirmar Cita"
- El cliente recibirá notificación

#### Completar una Cita
- Después de atender al cliente
- Clic en "Marcar Completada"
- Se registra como servicio realizado

### 4. Configurar Horarios

#### Acceder a Mis Horarios
1. Menú superior → "Mis Horarios"
2. O desde Dashboard → "Gestionar Horarios"

#### Agregar Nuevo Horario
1. Clic en "Agregar Horario"
2. Seleccionar:
   - Día de la semana
   - Hora de inicio
   - Hora de fin
3. Clic en "Guardar"

**Ejemplo**: Lunes de 9:00 a 13:00 y de 16:00 a 20:00
- Agregar dos horarios separados

#### Eliminar Horario
- Clic en el ícono de basura (🗑️)
- Confirmar eliminación

#### Bloquear Días (Vacaciones)
*Próximamente disponible*
- Podrás bloquear rangos de fechas
- Para vacaciones o días especiales

### 5. Mejores Prácticas

✅ **Recomendaciones**:
- Revisa tus citas cada mañana
- Confirma las citas lo antes posible
- Mantén tus horarios actualizados
- Avisa con anticipación si no estarás disponible

---

## 👔 Para Administradores

### 1. Panel de Administración

#### Acceso
- Menú superior → "Admin"
- Opciones disponibles según tu rol:
  - Sucursales (solo Superadmin)
  - Servicios
  - Especialistas
  - Reportes

### 2. Gestión de Sucursales

#### Ver Sucursales
- Admin → Sucursales
- Lista de todas las ubicaciones
- Estado (activa/inactiva)
- Horarios de operación

#### Crear Nueva Sucursal
1. Clic en "Nueva Sucursal"
2. Llenar información:
   - Nombre
   - Dirección completa
   - Ciudad y Estado
   - Teléfono y email
   - Horarios de apertura/cierre
3. Marcar como "Activa"
4. Guardar

#### Agregar Días No Laborables
*Desde la base de datos*
```sql
INSERT INTO dias_no_laborables (sucursal_id, fecha, descripcion) 
VALUES (1, '2024-12-25', 'Navidad');
```

### 3. Gestión de Servicios

#### Ver Servicios
1. Admin → Servicios
2. Seleccionar sucursal
3. Lista de servicios disponibles

#### Crear Nuevo Servicio
1. Clic en "Nuevo Servicio"
2. Información requerida:
   - Sucursal
   - Categoría (Medicina, Odontología, etc.)
   - Nombre del servicio
   - Descripción
   - Duración en minutos
   - Precio
3. Marcar como "Activo"
4. Guardar

**Ejemplos de Servicios**:
- Consulta General - 30 min - $350
- Limpieza Dental - 45 min - $450
- Asesoría Legal - 60 min - $800

### 4. Gestión de Especialistas

#### Ver Especialistas
1. Admin → Especialistas
2. Seleccionar sucursal
3. Lista de profesionales

#### Configurar Horarios de Especialista
1. Clic en "Ver Horarios" del especialista
2. Ver servicios asignados
3. Agregar/eliminar horarios de trabajo
4. Organizado por día de la semana

#### Asignar Servicios a Especialista
*Requiere acceso a base de datos*
```sql
INSERT INTO especialista_servicios (especialista_id, servicio_id) 
VALUES (1, 5);
```

### 5. Reportes y Estadísticas

#### Ver Dashboard de Reportes
1. Admin → Reportes
2. Métricas principales:
   - Total de reservaciones
   - Citas completadas
   - Citas canceladas
   - Ingresos totales

#### Filtrar por Fechas
1. Seleccionar fecha inicio
2. Seleccionar fecha fin
3. Clic en "Filtrar"
4. Ver estadísticas del período

#### Métricas Disponibles
- **Tasa de éxito**: % de citas completadas
- **Ingreso promedio**: Por cita completada
- **Desglose por estado**: Pendientes, confirmadas, etc.
- **Comparativas**: Entre períodos

### 6. Mejores Prácticas para Administradores

✅ **Recomendaciones**:
- Revisa reportes semanalmente
- Mantén información actualizada
- Confirma datos de especialistas
- Verifica horarios regularmente
- Monitorea cancelaciones frecuentes

❌ **Evita**:
- Eliminar sucursales con citas activas
- Desactivar servicios sin avisar
- Cambiar horarios sin coordinación

---

## ❓ Preguntas Frecuentes

### Para Todos los Usuarios

**P: ¿Olvidé mi contraseña, qué hago?**
R: Por ahora, contacta al administrador. Sistema de recuperación próximamente.

**P: ¿Puedo cambiar mi contraseña?**
R: Desde tu perfil de usuario (próximamente).

**P: ¿El sistema es seguro?**
R: Sí, usa encriptación de contraseñas y HTTPS. Tus datos están protegidos.

### Para Clientes

**P: ¿Puedo agendar múltiples citas?**
R: Sí, puedes tener varias citas pendientes.

**P: ¿Cuánto tiempo antes debo llegar?**
R: Recomendamos 10 minutos antes de tu cita.

**P: ¿Puedo cancelar el mismo día?**
R: Depende de la política, pero se recomienda cancelar con 24h de anticipación.

**P: ¿Qué pasa si no confirman mi cita?**
R: Las citas pendientes se confirman en máximo 24 horas.

**P: ¿Recibo recordatorios?**
R: Sistema de notificaciones en desarrollo.

### Para Especialistas

**P: ¿Cómo bloqueo un día completo?**
R: Actualmente a través del administrador. Función próximamente.

**P: ¿Puedo ver mi historial completo?**
R: Sí, en tu dashboard y en reportes.

**P: ¿Cómo cambio mis horarios?**
R: Menú "Mis Horarios" → Agregar/Eliminar

### Para Administradores

**P: ¿Puedo tener múltiples sucursales?**
R: Sí, sin límite.

**P: ¿Cómo exporto los reportes?**
R: Función de exportación próximamente.

**P: ¿Puedo ver logs de seguridad?**
R: Sí, desde la base de datos tabla `logs_seguridad`.

---

## 🆘 Soporte Técnico

### Contacto
- **Email**: admin@reserbot.com
- **Teléfono**: +52 442 123 4567

### Reportar Problemas
1. Describe el problema específicamente
2. Incluye capturas de pantalla si es posible
3. Menciona qué estabas haciendo cuando ocurrió

### Horario de Soporte
- Lunes a Viernes: 9:00 - 18:00
- Sábados: 9:00 - 14:00

---

## 📱 Acceso Móvil

El sistema es completamente responsivo y funciona en:
- 📱 Teléfonos móviles
- 💻 Tablets
- 🖥️ Computadoras de escritorio

Usa cualquier navegador moderno:
- Chrome
- Firefox
- Safari
- Edge

---

**¡Gracias por usar ReserBot!** 

Sistema desarrollado para facilitar la gestión de citas profesionales.