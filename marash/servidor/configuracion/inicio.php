<?php
declare(strict_types=1);
ini_set('display_errors','0');
ini_set('log_errors','1');
session_start();
if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));
require_once __DIR__.'/../rutas/web.php';
require_once __DIR__.'/../../vistas/funciones.php';
