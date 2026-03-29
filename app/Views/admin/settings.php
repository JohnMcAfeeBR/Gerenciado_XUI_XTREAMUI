<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="card">
  <h2>Configuração de Credenciais dos Painéis</h2>
  <form method="post" action="/admin/settings">
    <label>Provedor ativo para provisionamento</label><br>
    <select name="IPTV_PROVIDER">
      <option value="xtream_ui" <?= (($values['IPTV_PROVIDER'] ?? '') === 'xtream_ui') ? 'selected' : '' ?>>xtream_ui</option>
      <option value="xui_one" <?= (($values['IPTV_PROVIDER'] ?? '') === 'xui_one') ? 'selected' : '' ?>>xui_one</option>
    </select>

    <h3>Xtream-UI</h3>
    <label>Base URL</label><br>
    <input type="text" name="XTREAM_BASE_URL" value="<?= e($values['XTREAM_BASE_URL'] ?? '') ?>"><br>
    <label>API Path</label><br>
    <input type="text" name="XTREAM_API_PATH" value="<?= e($values['XTREAM_API_PATH'] ?? '/api.php') ?>"><br>
    <label>Username</label><br>
    <input type="text" name="XTREAM_USERNAME" value="<?= e($values['XTREAM_USERNAME'] ?? '') ?>"><br>
    <label>Password</label><br>
    <input type="password" name="XTREAM_PASSWORD" value="<?= e($values['XTREAM_PASSWORD'] ?? '') ?>"><br>

    <h3>XUI One</h3>
    <label>Base URL</label><br>
    <input type="text" name="XUI_BASE_URL" value="<?= e($values['XUI_BASE_URL'] ?? '') ?>"><br>
    <label>Create Line Path</label><br>
    <input type="text" name="XUI_CREATE_LINE_PATH" value="<?= e($values['XUI_CREATE_LINE_PATH'] ?? '/api/lines/create') ?>"><br>
    <label>Ping Path</label><br>
    <input type="text" name="XUI_PING_PATH" value="<?= e($values['XUI_PING_PATH'] ?? '/api/auth/me') ?>"><br>
    <label>Auth Mode</label><br>
    <select name="XUI_AUTH_MODE">
      <option value="api_key" <?= (($values['XUI_AUTH_MODE'] ?? '') === 'api_key') ? 'selected' : '' ?>>api_key</option>
      <option value="basic" <?= (($values['XUI_AUTH_MODE'] ?? '') === 'basic') ? 'selected' : '' ?>>basic</option>
    </select><br>
    <label>API Key</label><br>
    <input type="text" name="XUI_API_KEY" value="<?= e($values['XUI_API_KEY'] ?? '') ?>"><br>
    <label>Username</label><br>
    <input type="text" name="XUI_USERNAME" value="<?= e($values['XUI_USERNAME'] ?? '') ?>"><br>
    <label>Password</label><br>
    <input type="password" name="XUI_PASSWORD" value="<?= e($values['XUI_PASSWORD'] ?? '') ?>"><br><br>

    <button type="submit">Salvar credenciais</button>
  </form>

  <p><small>Esses dados são salvos no arquivo .env do projeto.</small></p>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
