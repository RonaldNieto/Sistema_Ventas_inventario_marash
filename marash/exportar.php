<?php
declare(strict_types=1);session_start();if(empty($_SESSION['uid'])){http_response_code(403);exit('Acceso denegado');}
require_once __DIR__.'/servidor/configuracion/conexion.php';
require_once __DIR__.'/servidor/modelos/ModeloReportes.php';
$start=(string)($_GET['start']??date('Y-m-01'));$end=(string)($_GET['end']??date('Y-m-d'));
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$start)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$end)){http_response_code(400);exit('Fechas inválidas');}
$rows=exportReportRows($start,$end);
header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="marash-reporte.csv"');$out=fopen('php://output','w');fwrite($out,"\xEF\xBB\xBF");fputcsv($out,['Fecha','Ventas COP']);foreach($rows as $r)fputcsv($out,[$r['day'],$r['total']]);fclose($out);
