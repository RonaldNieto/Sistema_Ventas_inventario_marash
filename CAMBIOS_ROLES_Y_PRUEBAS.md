# MARASH - Avance: roles y permisos

## Implementado
- Inicio de sesión por correo electrónico y contraseña.
- Correo único en tabla users (mediante migración SQL manual).
- Roles administrador, vendedor y cliente.
- Administrador crea vendedores desde Usuarios.
- Menú y acciones PHP restringidos según el rol.
- Cliente solo puede consultar catálogo de productos (sin compras en línea).

## Instalación SIN perder datos
1. Respaldar carpeta marash y exportar base de datos desde HeidiSQL.
2. Sustituir carpeta del proyecto, conservando backend/config/local.php de tu instalación.
3. Ejecutar **solo** database/ACTUALIZACION_ROLES.sql en HeidiSQL, una sola vez.
4. En HeidiSQL, asignar tu correo real al administrador: `UPDATE users SET email='TU_CORREO' WHERE role='admin';`
5. Entrar a MARASH con ese correo y tu contraseña de siempre.
6. Crear un vendedor desde Usuarios y probar el acceso.

## Pruebas pendientes en XAMPP
- Entrar como administrador y crear vendedor.
- Entrar como vendedor: ventas permitidas, usuarios y reportes restringidos.
- Registrar una cuenta cliente de prueba desde HeidiSQL (aún no hay registro público).
- Entrar como cliente y verificar que solo consulta el catálogo.
- Verificar ventas y stock sin cambiar los registros anteriores.

## Alcance
Versión académica sencilla. El rol cliente es de solo lectura; no incluye tienda virtual ni compras en línea.
