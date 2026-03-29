<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Área do Cliente</h2>
  <form method="post" action="/logout">
    <button type="submit">Sair</button>
  </form>
</div>

<div class="card">
  <h3>Planos disponíveis</h3>
  <table>
    <thead>
      <tr>
        <th>Nome</th><th>Preço</th><th>Duração</th><th>Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (($plans ?? []) as $plan): ?>
        <tr>
          <td><?= e($plan['name']) ?></td>
          <td>R$ <?= e((string) $plan['price']) ?></td>
          <td><?= e((string) $plan['duration_days']) ?> dias</td>
          <td>
            <form method="post" action="/client/orders">
              <input type="hidden" name="plan_id" value="<?= e((string) $plan['id']) ?>">
              <button type="submit">Comprar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h3>Meus pedidos</h3>
  <table>
    <thead>
      <tr>
        <th>ID Pedido</th><th>Plano</th><th>Valor</th><th>Status</th><th>Pagamento PIX</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (($orders ?? []) as $order): ?>
        <tr>
          <td><?= e((string) $order['id']) ?></td>
          <td><?= e($order['plan_name']) ?></td>
          <td>R$ <?= e((string) $order['amount']) ?></td>
          <td><?= e($order['status']) ?></td>
          <td>
            <?php if (($order['status'] ?? '') === 'paid'): ?>
              <strong>Pago ✅</strong>
            <?php else: ?>
              <?php if (!empty($order['pix_qr_code'])): ?>
                <div><a href="<?= e($order['pix_qr_code']) ?>" target="_blank">Abrir QR Code</a></div>
              <?php endif; ?>
              <?php if (!empty($order['pix_qr_code_text'])): ?>
                <small>PIX copia e cola:</small>
                <div><textarea rows="3" cols="40" readonly><?= e($order['pix_qr_code_text']) ?></textarea></div>
              <?php endif; ?>
              <form method="post" action="/client/orders/sync">
                <input type="hidden" name="order_id" value="<?= e((string) $order['id']) ?>">
                <button type="submit">Verificar pagamento</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
