# MARASH — instalación local

1. Extrae la carpeta marash dentro de C:\xampp\htdocs.
2. Inicia Apache y MySQL en XAMPP.
3. Abre http://localhost/marash/index.php.
4. En una base nueva o vacía se crean automáticamente las nueve tablas, vistas de compatibilidad, datos de ejemplo y tres cuentas.

Configuración predeterminada: 127.0.0.1, puerto 3306, root sin contraseña (XAMPP estándar).
Si MySQL tiene otra configuración, copia servidor/configuracion/local.ejemplo.php como local.php y ajusta los datos.
No se sobrescriben bases existentes. Si ya existe una base antigua o incompleta, debe revisarse antes de migrarla.
HeidiSQL es opcional. No ejecutes los SQL manualmente para una instalación nueva.

## Cuentas de demostración
- Administrador: admin@marash.local / MarashAdmin2026!
- Vendedor: vendedor@marash.local / MarashVendedor2026!
- Cliente: cliente@marash.local / MarashCliente2026!

Solo para pruebas locales. No publicar con estas credenciales.
Las fotografías remotas requieren internet. Los pagos reales no están integrados.
