<?php
declare(strict_types=1);
function authLogin(): void {
 $email=mb_strtolower(trim((string)($_POST['email']??'')));$password=(string)($_POST['password']??'');
 $u=findActiveUser($email);
 if(!$u||!password_verify($password,$u['password_hash']))throw new RuntimeException('Correo o contraseña incorrectos.');
 session_regenerate_id(true);$_SESSION['uid']=(int)$u['id'];$_SESSION['name']=$u['full_name'];$_SESSION['role']=$u['role'];
}
function requireLogin(): void {if(empty($_SESSION['uid'])){header('Location: index.php?page=login');exit;}}
function requireRole(array $roles): void {requireLogin();if(!in_array($_SESSION['role']??'', $roles,true))throw new RuntimeException('No tienes permiso para esta acción.');}
function createSellerFromForm(): void {
 requireRole(['admin']);$name=trim((string)($_POST['full_name']??''));$email=mb_strtolower(trim((string)($_POST['email']??'')));$pass=(string)($_POST['password']??'');
 if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8)throw new RuntimeException('Ingresa nombre, correo válido y contraseña de mínimo 8 caracteres.');
 createSeller($name,$email,$pass);
}
