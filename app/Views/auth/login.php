<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Login Cliente</h2>
  <?php if (!empty($error)): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
  <form method="post" action="/login">
    <label>Email</label><br>
    <input type="email" name="email" required><br>
    <label>Senha</label><br>
    <input type="password" name="password" required><br>
    <button type="submit">Entrar</button>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
