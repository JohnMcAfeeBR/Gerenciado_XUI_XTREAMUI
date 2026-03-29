<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Teste de conexão dos painéis</h2>
  <table>
    <thead>
      <tr><th>Painel</th><th>HTTP</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php foreach (($result ?? []) as $panel => $data): ?>
      <tr>
        <td><?= e($panel) ?></td>
        <td><?= e((string) ($data['status'] ?? 0)) ?></td>
        <td><?= !empty($data['ok']) ? 'Conectado ✅' : 'Falhou ❌' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><small>Se falhar, confira URL, porta, SSL e credenciais no arquivo .env.</small></p>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
