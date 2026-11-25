<?php
declare(strict_types=1);
function reportsPageData(array $filters=[]): array {
$start=(string)($filters['start']??date('Y-m-01'));$end=(string)($filters['end']??date('Y-m-d'));
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$start)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$end)){$start=date('Y-m-01');$end=date('Y-m-d');}
$tot=query('SELECT COUNT(*) sales,COALESCE(SUM(total),0) revenue FROM sales WHERE DATE(created_at) BETWEEN ? AND ?',[$start,$end])[0];
$margin=query('SELECT COALESCE(SUM((si.unit_price-si.unit_cost)*si.quantity),0) margin,COALESCE(SUM(si.quantity),0) units FROM sale_items si JOIN sales s ON s.id=si.sale_id WHERE DATE(s.created_at) BETWEEN ? AND ?',[$start,$end])[0];
$methods=query('SELECT payment_method,COUNT(*) qty,COALESCE(SUM(total),0) amount FROM sales WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY payment_method ORDER BY amount DESC',[$start,$end]);
$categories=query('SELECT p.category,COALESCE(SUM(si.quantity),0) units,COALESCE(SUM(si.quantity*si.unit_price),0) revenue FROM sale_items si JOIN products p ON p.id=si.product_id JOIN sales s ON s.id=si.sale_id WHERE DATE(s.created_at) BETWEEN ? AND ? GROUP BY p.category ORDER BY revenue DESC',[$start,$end]);
$days=query('SELECT DATE(created_at) day,SUM(total) total FROM sales WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY DATE(created_at) ORDER BY day',[$start,$end]);
return compact('start','end','tot','margin','methods','categories','days');
}

function exportReportRows(string $start,string $end): array {
    $s=db()->prepare('SELECT DATE(created_at) day, SUM(total) total FROM sales WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY DATE(created_at) ORDER BY day');
    $s->execute([$start,$end]);
    return $s->fetchAll();
}
