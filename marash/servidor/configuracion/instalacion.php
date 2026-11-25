<?php
declare(strict_types=1);

// Instala únicamente en una base nueva o vacía.
function prepararBaseDatos(): void
{
    $archivo = __DIR__ . '/local.php';
    $local = is_file($archivo) ? require $archivo : [];
    $host = getenv('MARASH_DB_HOST') ?: ($local['host'] ?? '127.0.0.1');
    $port = getenv('MARASH_DB_PORT') ?: ($local['port'] ?? '3306');
    $nombre = getenv('MARASH_DB_NAME') ?: ($local['name'] ?? 'marash');
    $usuario = getenv('MARASH_DB_USER') ?: ($local['user'] ?? 'root');
    $clave = getenv('MARASH_DB_PASS') ?: ($local['pass'] ?? '');

    if ($nombre !== 'marash') {
        db();
        return;
    }

    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS marash CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE marash");

    if ((int) $pdo->query("SELECT GET_LOCK('marash_instalacion', 15)")->fetchColumn() !== 1) {
        throw new RuntimeException('Instalación en curso. Recarga la página.');
    }

    try {
        $tablas = $pdo->query("SHOW FULL TABLES")->fetchAll(PDO::FETCH_NUM);
        if (count($tablas) === 0) {
            if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)) {
                throw new RuntimeException('Abre MARASH desde localhost para instalarlo.');
            }
            $sql = file_get_contents(__DIR__ . '/../../base_datos/marash.sql');
            // El script incluido no contiene procedimientos ni puntos y coma dentro de valores.
            foreach (explode(';', $sql) as $consulta) {
                if (trim($consulta) !== '') {
                    $pdo->exec($consulta);
                }
            }
        }
        // No se modifican ni se eliminan bases previamente instaladas.
    } finally {
        $pdo->query("SELECT RELEASE_LOCK('marash_instalacion')");
    }
}
