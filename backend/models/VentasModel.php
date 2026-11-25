<?php
declare(strict_types=1);
function salesPageData(): array {
$products=query('SELECT id,name,price,stock FROM products WHERE stock>0 ORDER BY name');$clients=allRows('clients');$sales=query('SELECT s.id,s.total,s.payment_method,s.created_at,c.name client FROM sales s LEFT JOIN clients c ON c.id=s.client_id ORDER BY s.id DESC LIMIT 30');
return compact('products','clients','sales');
}
