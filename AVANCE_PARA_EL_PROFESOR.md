# MARASH — Avance para revisión del profesor
**Fecha:** 12 de noviembre de 2026  
**Tipo:** Proyecto universitario — aplicación web para tienda de ropa

## Objetivo
Desarrollar una aplicación local para registrar productos, clientes, proveedores, ventas, movimientos de inventario y consultar reportes.

## Tecnologías utilizadas
HTML, CSS y JavaScript para la interfaz; PHP para la lógica del sistema; MySQL/MariaDB para almacenar la información; XAMPP (Apache), HeidiSQL y Visual Studio Code para desarrollo local.

## Organización del código
- `frontend/css`, `frontend/js`, `frontend/img`: estilos, comportamiento del navegador y espacio para imágenes locales.
- `backend/config`: conexión a base de datos y configuración.
- `backend/controllers`: recibe acciones y valida formularios.
- `backend/models`: realiza las consultas SQL.
- `backend/routes`: dirige las solicitudes.
- `views`: páginas de los módulos.
- `database`: esquema SQL de referencia.
- `index.php`: entrada principal.

## Avances implementados
- Inicio de sesión y navegación entre módulos.
- Registro y consulta de productos, clientes y proveedores.
- Ventas que actualizan el stock en la base de datos.
- Movimientos de inventario.
- Panel de indicadores y reportes por fechas.
- Corrección de reportes cuando no existen ventas en el período.
- Eliminación de archivos CSS/JS antiguos duplicados en `assets`.
- Consultas SQL de guardado trasladadas del controlador al modelo `Store.php`.

## Pruebas realizadas y pendientes
**Comprobado anteriormente en el equipo de desarrollo:** inicio de sesión, conexión con MariaDB, ventas con descuento de inventario y reportes con y sin ventas.  
**Por comprobar en esta entrega:** guardar/editar/eliminar productos, clientes y proveedores; revisar permisos; repetir el flujo completo en XAMPP después de reemplazar archivos.

## Alcance
Es un proyecto académico para funcionamiento local. Las imágenes decorativas pueden necesitar Internet. No incluye facturación electrónica ni pagos en línea.

## Próxima etapa
Completar pruebas funcionales, documentar evidencias con capturas de pantalla y preparar la sustentación.

**Nota para revisión:** no se incluyen credenciales privadas ni una copia de los datos actuales del computador. El archivo SQL de ejemplo no debe importarse sobre una base con ventas reales.
