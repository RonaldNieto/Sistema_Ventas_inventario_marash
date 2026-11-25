<div class="heading"><div><h1>Mis pedidos</h1><small>Consulta el estado de tus compras.</small></div></div>
<div class="panel">
    <div class="table-scroll">
        <table>
            <thead><tr><th>Pedido</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach (pedidosDelCliente((int) $_SESSION['uid']) as $pedido): ?>
                    <tr>
                        <td>#<?= (int) $pedido['id'] ?></td>
                        <td><?= e($pedido['created_at']) ?></td>
                        <td><?= money($pedido['total']) ?></td>
                        <td><?= e($pedido['estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
