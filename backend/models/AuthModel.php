<?php
declare(strict_types=1);
function findActiveUser(string $email): array|false {
 $s=db()->prepare('SELECT id,email,password_hash,full_name,role FROM users WHERE email=? AND active=1 LIMIT 1');
 $s->execute([$email]);return $s->fetch();
}
function listStaff(): array {return db()->query("SELECT id,full_name,email,role,active FROM users WHERE role IN ('admin','vendedor') ORDER BY id DESC")->fetchAll();}
function createSeller(string $name,string $email,string $password): void {
 $hash=password_hash($password,PASSWORD_DEFAULT);
 $s=db()->prepare("INSERT INTO users(username,email,full_name,password_hash,role,active) VALUES(?,?,?,?,'vendedor',1)");
 $s->execute([$email,$email,$name,$hash]);
}
