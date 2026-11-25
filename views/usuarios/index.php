<?php
if(($_SESSION['role']??'')!=='admin')throw new RuntimeException('Acceso restringido.');
$staff=listStaff();
?>
<div class="heading"><div><h1>Usuarios y vendedores</h1><small>Solo el administrador puede crear vendedores.</small></div></div>
<div class="two-col"><div class="panel"><h3>Crear vendedor</h3>
<form method="post"><?php csrf(); ?><input type="hidden" name="action" value="create_seller">
<label>Nombre completo<input name="full_name" required maxlength="150"></label>
<label>Correo electrónico<input name="email" type="email" required maxlength="160"></label>
<label>Contraseña inicial<input name="password" type="password" required minlength="8"></label>
<button class="primary" type="submit">Crear vendedor</button></form></div>
<div class="panel"><h3>Personal registrado</h3><div class="table-scroll"><table><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th></tr></thead><tbody>
<?php foreach($staff as $u): ?><tr><td><?=e($u['full_name'])?></td><td><?=e($u['email'])?></td><td><?=e($u['role'])?></td></tr><?php endforeach; ?>
</tbody></table></div></div></div>
