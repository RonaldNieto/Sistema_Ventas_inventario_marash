# Ajustes de organización — 12/11/2026

- Se mantiene la estructura exigida por la clase.
- Se retira `assets/` porque `views/layout.php` enlaza los archivos activos de `frontend/css` y `frontend/js`.
- Se mueve la construcción de SQL de guardar/editar desde `StoreController.php` a `backend/models/Store.php`. El controlador conserva las validaciones.
- Se actualiza README para explicar la instalación real sin `install.php`.
- Se agrega `AVANCE_PARA_EL_PROFESOR.md`.
- Se excluye `backend/config/local.php` del ZIP: debe conservarse únicamente en el PC local.
- No se modifican tablas ni datos de MariaDB.
