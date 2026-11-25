<?php
declare(strict_types=1);
function inventoryPageData(): array {
$products=allRows('products');$movements=query('SELECT m.created_at,m.quantity,m.reason,p.name FROM stock_movements m JOIN products p ON p.id=m.product_id ORDER BY m.id DESC LIMIT 12');
return compact('products','movements');
}
