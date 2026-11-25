<?php
declare(strict_types=1);
function db(): PDO {
 static $pdo=null;
 if ($pdo) return $pdo;
 $localFile=__DIR__.'/local.php';
 $local=is_file($localFile)?require $localFile:[];
 $host=getenv('MARASH_DB_HOST') ?: ($local['host']??'127.0.0.1');
 $port=getenv('MARASH_DB_PORT') ?: ($local['port']??'3306');
 $name=getenv('MARASH_DB_NAME') ?: ($local['name']??'marash');
 $user=getenv('MARASH_DB_USER') ?: ($local['user']??'root');
 $pass=getenv('MARASH_DB_PASS') ?: ($local['pass']??'');
 $pdo=new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
 return $pdo;
}
