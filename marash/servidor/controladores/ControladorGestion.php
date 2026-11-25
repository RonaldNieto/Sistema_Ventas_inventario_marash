<?php
declare(strict_types=1);
function required(string $name): string {$v=trim((string)($_POST[$name]??''));if($v==='')throw new RuntimeException('Campo obligatorio: '.$name);return $v;}
function positiveMoney(string $name): float {$v=(float)($_POST[$name]??0);if(!is_finite($v)||$v<0)throw new RuntimeException('Valor inválido: '.$name);return $v;}
function saveRecord(string $type): void {
 $map=['product'=>['products',['name','sku','category','price','cost','stock','sizes','colors','supplier','image_url']], 'client'=>['clients',['name','phone','email']], 'supplier'=>['suppliers',['name','phone','email']]];
 if(!isset($map[$type]))throw new RuntimeException('Formulario no permitido');[$table,$fields]=$map[$type];$id=(int)($_POST['id']??0);
 $vals=[];foreach($fields as $field){$vals[$field]=trim((string)($_POST[$field]??''));}
 if($vals['name']==='')throw new RuntimeException('El nombre es obligatorio.');
 if($type==='product'){
  if($vals['sku']===''||$vals['category']==='')throw new RuntimeException('SKU y categoría obligatorios.');
  foreach(['price','cost'] as $f){if(!is_numeric($vals[$f])||(float)$vals[$f]<0)throw new RuntimeException('Precio o costo inválido.');}
  if(filter_var($vals['stock'],FILTER_VALIDATE_INT)===false||(int)$vals['stock']<0)throw new RuntimeException('Stock inválido.');
  if($vals['image_url']!==''&&!filter_var($vals['image_url'],FILTER_VALIDATE_URL))throw new RuntimeException('URL de imagen inválida.');
 }
 if($type!=='product'&&$vals['email']!==''&&!filter_var($vals['email'],FILTER_VALIDATE_EMAIL))throw new RuntimeException('Correo inválido.');
 storeSaveRecord($table,$fields,$vals,$id);
}
function deleteRecord(string $type): void {
 $map=['product'=>'products','client'=>'clients','supplier'=>'suppliers'];if(!isset($map[$type]))throw new RuntimeException('Acción no permitida');
 if(($_SESSION['role']??'')!=='admin')throw new RuntimeException('Solo administradores pueden eliminar.');
 storeDelete($map[$type],(int)($_POST['id']??0));
}
function adjustStock(): void {
 $id=(int)($_POST['id']??0);$delta=filter_var($_POST['delta']??null,FILTER_VALIDATE_INT);if(!$id||$delta===false||$delta===0)throw new RuntimeException('Movimiento inválido.');
 storeAdjustStock($id,$delta,(int)$_SESSION['uid'],required('reason'));
}
function createSale(): int {
 $products=$_POST['product_id']??[];$quantities=$_POST['quantity']??[];if(!is_array($products)||!is_array($quantities)||count($products)<1||count($products)!==count($quantities))throw new RuntimeException('Agrega productos a la venta.');
 $items=[];foreach($products as $i=>$raw){$id=filter_var($raw,FILTER_VALIDATE_INT);$qty=filter_var($quantities[$i],FILTER_VALIDATE_INT);if(!$id||!$qty||$qty<1)throw new RuntimeException('Producto o cantidad inválida.');$items[$id]=($items[$id]??0)+$qty;}
 return storeCreateSale($items,(int)$_SESSION['uid'],filter_var($_POST['client_id']??'',FILTER_VALIDATE_INT)?:null,(string)($_POST['payment_method']??'Efectivo'));
}
