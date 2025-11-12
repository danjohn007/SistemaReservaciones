USE i45com_reserbot;

SELECT 
    id,
    nombre,
    apellido,
    email,
    rol_id,
    activo,
    email_verificado,
    bloqueado_hasta,
    ultimo_acceso
FROM usuarios 
WHERE rol_id IN (1, 2)
ORDER BY rol_id, id
LIMIT 25;
