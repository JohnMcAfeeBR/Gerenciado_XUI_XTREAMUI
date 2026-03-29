<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Registro de Cliente</h2>
  <?php if (!empty($error)): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
  <form method="post" action="/register">
    <label>Nome</label><br>
    <input type="text" name="name" required><br>
    <label>Email</label><br>
    <input type="email" name="email" required><br>
    <label>Senha</label><br>
    <input type="password" name="password" required><br>
    <button type="submit">Registrar</button>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
