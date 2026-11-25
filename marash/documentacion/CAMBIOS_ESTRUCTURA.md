# Ajustes de organización — 12/11/2026

- Se mantiene la estructura exigida por la clase.
- Se retira `recursos/` porque `vistas/plantilla.php` enlaza los archivos activos de `interfaz/css` y `interfaz/js`.
- Se mueve la construcción de SQL de guardar/editar desde `ControladorGestion.php` a `servidor/modelos/ModeloGestion.php`. El controlador conserva las validaciones.
- Se actualiza README para explicar la instalación real sin `install.php`.
- Se agrega `AVANCE_PARA_EL_PROFESOR.md`.
- Se excluye `servidor/configuracion/local.php` del ZIP: debe conservarse únicamente en el PC local.
- No se modifican tablas ni datos de MariaDB.
