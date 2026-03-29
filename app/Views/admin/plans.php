<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Planos IPTV</h2>
  <?php if (!empty($error)): ?><p class="error"><?= e($error) ?></p><?php endif; ?>

  <form method="post" action="/admin/plans">
    <label>Nome</label><br>
    <input type="text" name="name" required><br>
    <label>Preço</label><br>
    <input type="number" step="0.01" name="price" required><br>
    <label>Duração (dias)</label><br>
    <input type="number" name="duration_days" required><br>
    <label>Código do plano no provedor</label><br>
    <input type="text" name="provider_plan_code" required><br>
    <button type="submit">Criar plano</button>
  </form>
</div>

<div class="card">
  <h3>Lista de Planos</h3>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nome</th><th>Preço</th><th>Dias</th><th>Código Provedor</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach (($plans ?? []) as $plan): ?>
        <tr>
          <td><?= e((string) $plan['id']) ?></td>
          <td><?= e($plan['name']) ?></td>
          <td>R$ <?= e((string) $plan['price']) ?></td>
          <td><?= e((string) $plan['duration_days']) ?></td>
          <td><?= e($plan['provider_plan_code']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
