<?php
declare(strict_types=1);
function allRows(string $table): array {if(!in_array($table,['products','clients','suppliers','sales','branches'],true))throw new InvalidArgumentException('Tabla inválida');return db()->query("SELECT * FROM `$table` ORDER BY id DESC")->fetchAll();}
function oneRow(string $table,int $id): ?array {if(!in_array($table,['products','clients','suppliers'],true))throw new InvalidArgumentException('Tabla inválida');$s=db()->prepare("SELECT * FROM `$table` WHERE id=?");$s->execute([$id]);return $s->fetch()?:null;}
function countRows(string $table): int {if(!in_array($table,['products','clients','sales','suppliers'],true))throw new InvalidArgumentException('Tabla inválida');return (int)db()->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();}

// Guarda productos, clientes o proveedores. El controlador valida los datos;
// el modelo se encarga de construir y ejecutar la consulta SQL.
function storeSaveRecord(string $table,array $fields,array $vals,int $id): void {
    $allowed=['products','clients','suppliers'];
    if(!in_array($table,$allowed,true))throw new InvalidArgumentException('Tabla inválida');
    $columns=['products'=>['name','sku','category','price','cost','stock','sizes','colors','supplier','image_url'],
              'clients'=>['name','phone','email'], 'suppliers'=>['name','phone','email']];
    if($fields!==$columns[$table])throw new InvalidArgumentException('Campos inválidos');
    if($id>0){
        $set=implode(',',array_map(fn($f)=>"`$f`=?",$fields));
        storeExecute("UPDATE `$table` SET $set WHERE id=?",[...array_values($vals),$id]);
    }else{
        $names=implode(',',array_map(fn($f)=>"`$f`",$fields));
        $marks=implode(',',array_fill(0,count($fields),'?'));
        storeExecute("INSERT INTO `$table` ($names) VALUES ($marks)",array_values($vals));
    }
}

function storeExecute(string $sql,array $params): void {
    $s=db()->prepare($sql);
    $s->execute($params);
}
function storeDelete(string $table,int $id): void {
    if(!in_array($table,['products','clients','suppliers'],true))throw new InvalidArgumentException('Tabla inválida');
    db()->prepare("DELETE FROM `$table` WHERE id=?")->execute([$id]);
}
function storeAdjustStock(int $id,int $delta,int $userId,string $reason): void {
 $pdo=db();$pdo->beginTransaction();try{
  $s=$pdo->prepare('SELECT stock FROM products WHERE id=? FOR UPDATE');$s->execute([$id]);$current=$s->fetchColumn();if($current===false||((int)$current+$delta)<0)throw new RuntimeException('No hay existencias suficientes.');
  $pdo->prepare('UPDATE products SET stock=stock+? WHERE id=?')->execute([$delta,$id]);
  $pdo->prepare('INSERT INTO stock_movements(product_id,user_id,quantity,reason) VALUES (?,?,?,?)')->execute([$id,$userId,$delta,$reason]);$pdo->commit();
 }catch(Throwable $e){$pdo->rollBack();throw $e;}
}

function storeCreateSale(array $items,int $userId,?int $client,string $method): int {
 $pdo=db();$pdo->beginTransaction();try{
  $total=0;$details=[];
  foreach($items as $id=>$qty){$s=$pdo->prepare('SELECT id,name,price,cost,stock FROM products WHERE id=? FOR UPDATE');$s->execute([$id]);$p=$s->fetch();if(!$p||$p['stock']<$qty)throw new RuntimeException('Stock insuficiente para el producto #'.$id);$details[]=[$p,$qty];$total+=(float)$p['price']*$qty;}
  if($client){$s=$pdo->prepare('SELECT id FROM clients WHERE id=?');$s->execute([$client]);if(!$s->fetchColumn())throw new RuntimeException('Cliente inválido.');}
  if(!in_array($method,['Efectivo','Tarjeta','Transferencia'],true))throw new RuntimeException('Medio de pago inválido.');
  $s=$pdo->prepare('INSERT INTO sales(client_id,user_id,payment_method,total) VALUES (?,?,?,?)');$s->execute([$client,$userId,$method,$total]);$saleId=(int)$pdo->lastInsertId();
  foreach($details as [$p,$qty]){
   $pdo->prepare('INSERT INTO sale_items(sale_id,product_id,quantity,unit_price,unit_cost) VALUES (?,?,?,?,?)')->execute([$saleId,$p['id'],$qty,$p['price'],$p['cost']]);
   $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=?')->execute([$qty,$p['id']]);
   $pdo->prepare('INSERT INTO stock_movements(product_id,user_id,quantity,reason) VALUES (?,?,?,?)')->execute([$p['id'],$userId,-$qty,'Venta #'.$saleId]);
  }$pdo->commit();return $saleId;
 }catch(Throwable $e){$pdo->rollBack();throw $e;}
}

function query(string $sql,array $params=[]): array {
    $statement=db()->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}
