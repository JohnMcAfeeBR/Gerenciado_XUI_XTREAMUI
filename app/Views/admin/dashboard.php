<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Painel Administrativo</h2>
  <p><a href="/admin/plans">Gerenciar planos</a></p>
  <p><a href="/admin/settings">Configurar credenciais dos painéis</a></p>
  <p><a href="/admin/panel-connections">Testar conexão dos painéis</a></p>
  <form method="post" action="/logout">
    <button type="submit">Sair</button>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
