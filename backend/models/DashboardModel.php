<?php
declare(strict_types=1);
function dashboardData(): array {
$k=query("SELECT (SELECT COALESCE(SUM(total),0) FROM sales WHERE DATE(created_at)=CURDATE()) AS today_total,(SELECT COALESCE(SUM(si.quantity),0) FROM sale_items si JOIN sales s ON s.id=si.sale_id WHERE DATE(s.created_at)=CURDATE()) AS today_units,(SELECT COUNT(*) FROM products WHERE stock<=10) AS low_stock,(SELECT COALESCE(SUM(total),0) FROM sales WHERE YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())) AS monthly")[0];
$recent=query('SELECT s.id,s.total,s.created_at,c.name client FROM sales s LEFT JOIN clients c ON c.id=s.client_id ORDER BY s.id DESC LIMIT 6');
$best=query('SELECT p.name,p.stock,COALESCE(SUM(si.quantity),0) qty FROM products p LEFT JOIN sale_items si ON si.product_id=p.id GROUP BY p.id ORDER BY qty DESC,p.name LIMIT 5');
return compact('k','recent','best');
}
