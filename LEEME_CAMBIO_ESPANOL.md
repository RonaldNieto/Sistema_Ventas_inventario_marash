# MARASH — nombres de base de datos en español

## Resultado

La base de datos mantiene el nombre `marash`. Tiene **9 tablas reales en español**:
`usuarios`, `clientes`, `productos`, `proveedores`, `ventas`, `detalles_ventas`, `movimientos_inventario`, `pedidos`, `detalles_pedidos`.

Los campos de esas tablas también están en español. La antigua tabla `branches` se elimina porque el negocio tiene una sola tienda, **siempre que solo contenga la sucursal predeterminada Principal**.

## Por qué aparecen vistas con nombres ingleses

Se crean 9 **vistas de compatibilidad** (`users`, `products`, etc.). No son tablas duplicadas ni copias de datos: traducen temporalmente los nombres para que el código PHP actual siga trabajando sin tener que reescribir toda la aplicación. HeidiSQL las muestra separadas de las tablas. En una siguiente versión se podrán eliminar cuando se migre todo el código PHP.

## Instalación sobre tu base actual (ya tiene 10 tablas)

1. Exporta una copia de seguridad de la base `marash` desde HeidiSQL. No borres ni reinstales las tablas.
2. En HeidiSQL abre **database/MIGRACION_ESPANOL.sql**.
3. Ejecútalo **una sola vez**. Si aparece un error, detente y no vuelvas a ejecutarlo automáticamente; envía el mensaje.
4. Actualiza la carpeta del proyecto, **conservando tu `backend/config/local.php` original** (no está incluido en el ZIP por seguridad).
5. Prueba inicio de sesión, inventario, ventas y carrito antes de usar datos reales.

**NO** ejecutes `marash.sql`, `ACTUALIZACION_ROLES.sql` ni `ACTUALIZACION_TIENDA.sql` sobre esta base ya actualizada.

## Ver solo las tablas reales

```sql
USE marash;
SHOW FULL TABLES WHERE Table_type = 'BASE TABLE';
```

## Limitación

Este paquete incluye la migración SQL y mantiene el PHP existente mediante vistas de compatibilidad. No se ha podido probar contra tu instancia local de MySQL desde aquí; verifica las operaciones indicadas después de migrar.
