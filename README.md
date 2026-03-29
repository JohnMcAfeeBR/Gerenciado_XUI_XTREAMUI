# IPTV Sales Platform (PHP + MySQL)

Projeto inicial de uma plataforma completa para venda de listas IPTV com:

- **Painel Administrativo**
- **Área do Cliente**
- Integração planejada com **XUI One** e **Xtream-UI**

> Este repositório entrega um **MVP estruturado** e pronto para evoluir para produção.

## Stack

- PHP 8.2+
- MySQL 8+
- HTML/CSS/JS (sem framework front-end, para facilitar customização)

## Estrutura

```txt
app/
  Config/
  Controllers/
  Core/
  Integrations/
  Repositories/
  Services/
  Views/
config/
database/
public/
```

## Funcionalidades já incluídas (MVP)

- Registro e login de clientes.
- Login administrativo (seed inicial).
- Gestão de planos IPTV no painel admin.
- Listagem de planos para clientes.
- Registro de pedidos com criação de cobrança PIX via FastDePix.
- Base de integração HTTP para APIs externas (Xtream-UI/XUI One).
- Estrutura de segurança inicial (hash de senha e prepared statements).

## Como rodar

1. Copie variáveis de ambiente:

```bash
cp .env.example .env
```

2. Crie o banco e tabelas:

```bash
mysql -u root -p < database/schema.sql
```

3. (Opcional) Popular com dados iniciais:

```bash
mysql -u root -p < database/seed.sql
```

4. Suba servidor local:

```bash
php -S localhost:8080 -t public
```

5. Acesse:

- Home: `http://localhost:8080`
- Admin: `http://localhost:8080/admin/login`
- Cliente: `http://localhost:8080/login`

## Credenciais padrão (seed)

- Admin
  - Email: `admin@iptv.local`
  - Senha: `Admin@123`


## Configuração pronta para uso (Xtream-UI + XUI One)

1. Configure `.env` com os dados reais dos dois painéis:
   - `XTREAM_BASE_URL`, `XTREAM_USERNAME`, `XTREAM_PASSWORD`
   - `XUI_BASE_URL`, `XUI_AUTH_MODE`, `XUI_API_KEY` (ou `XUI_USERNAME`/`XUI_PASSWORD`)
2. Escolha qual provedor vai provisionar novos pedidos em `IPTV_PROVIDER`.
3. Acesse `GET /admin/settings` para cadastrar credenciais reais via painel admin (grava no `.env`).
4. Acesse `GET /admin/panel-connections` para testar conectividade dos dois painéis.
5. Crie planos no admin usando o `provider_plan_code` exatamente como o ID/código do pacote no painel selecionado.

### Compatibilidade de autenticação

- Xtream-UI: autenticação por `username/password` no payload.
- XUI One: `XUI_AUTH_MODE=api_key` (header `X-API-KEY`) ou `XUI_AUTH_MODE=basic` (Authorization Basic).

## Próximos passos sugeridos

1. Pagamentos (PIX/cartão) com webhooks.
2. Provisionamento automático de linha no painel escolhido.
3. Renovação automática + notificações (WhatsApp/Email).
4. Antifraude, auditoria e rate-limit.
5. Migração para arquitetura com filas (RabbitMQ/Redis) para alto volume.
6. Tokens de API criptografados em repouso.

## Integrações alvo

- Xtream-UI API: https://xtream-ui.org/api-xtreamui-xtreamcode/
- XUI One API docs: https://worldofiptvcom.github.io/xui-one-api-docs/#/Authentication/apiBase

### Observação importante

Os endpoints mudam por versão/painel. Antes de ativar em produção, valide os endpoints reais no seu ambiente e implemente testes de integração com respostas reais.
