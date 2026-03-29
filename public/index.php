<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Core/helpers.php';
require_once __DIR__ . '/../app/Config/Env.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Repositories/UserRepository.php';
require_once __DIR__ . '/../app/Repositories/PlanRepository.php';
require_once __DIR__ . '/../app/Repositories/OrderRepository.php';
require_once __DIR__ . '/../app/Services/AuthService.php';
require_once __DIR__ . '/../app/Services/PlanService.php';
require_once __DIR__ . '/../app/Services/OrderService.php';
require_once __DIR__ . '/../app/Integrations/HttpClient.php';
require_once __DIR__ . '/../app/Integrations/XtreamUiClient.php';
require_once __DIR__ . '/../app/Integrations/XuiOneClient.php';
require_once __DIR__ . '/../app/Integrations/FastDePixClient.php';
require_once __DIR__ . '/../app/Services/ProviderService.php';
require_once __DIR__ . '/../app/Services/PaymentService.php';
require_once __DIR__ . '/../app/Services/EnvFileService.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Controllers/ClientController.php';

$router = new App\Core\Router();

$home = new App\Controllers\HomeController();
$auth = new App\Controllers\AuthController();
$admin = new App\Controllers\AdminController();
$client = new App\Controllers\ClientController();

$router->get('/', [$home, 'index']);

$router->get('/register', [$auth, 'registerForm']);
$router->post('/register', [$auth, 'register']);
$router->get('/login', [$auth, 'loginForm']);
$router->post('/login', [$auth, 'login']);
$router->post('/logout', [$auth, 'logout']);

$router->get('/admin/login', [$auth, 'adminLoginForm']);
$router->post('/admin/login', [$auth, 'adminLogin']);
$router->get('/admin/dashboard', [$admin, 'dashboard']);
$router->get('/admin/plans', [$admin, 'plans']);
$router->post('/admin/plans', [$admin, 'storePlan']);
$router->get('/admin/settings', [$admin, 'settingsForm']);
$router->post('/admin/settings', [$admin, 'saveSettings']);
$router->get('/admin/panel-connections', [$admin, 'panelConnections']);

$router->get('/client/dashboard', [$client, 'dashboard']);
$router->post('/client/orders', [$client, 'createOrder']);
$router->post('/client/orders/sync', [$client, 'syncOrderPayment']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
