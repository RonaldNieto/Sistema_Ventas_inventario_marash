-- MARASH: roles ya definidos en marash.sql.
-- No agregar email ni role: ya existen y son obligatorios.
-- Este archivo valida la estructura sin cambiar cuentas ni contraseñas.
USE marash;
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'marash'
  AND TABLE_NAME = 'users'
  AND COLUMN_NAME IN ('username','email','password_hash','role','active')
ORDER BY ORDINAL_POSITION;
SELECT id, username, email, role, active FROM users ORDER BY id;
-- Crear administrador desde PHP con password_hash(), nunca con contrasena en texto plano.
