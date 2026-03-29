<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Integrations\HttpClient;
use App\Integrations\XtreamUiClient;
use App\Integrations\XuiOneClient;
use App\Repositories\PlanRepository;
use App\Services\EnvFileService;
use App\Services\PlanService;
use App\Services\ProviderService;

final class AdminController extends Controller
{
    private PlanService $plans;
    private ProviderService $provider;
    private EnvFileService $envFile;

    public function __construct()
    {
        $this->plans = new PlanService(new PlanRepository());

        $http = new HttpClient();
        $this->provider = new ProviderService(new XtreamUiClient($http), new XuiOneClient($http));
        $this->envFile = new EnvFileService();
    }

    public function dashboard(): void
    {
        $this->authorizeAdmin();
        $this->view('admin/dashboard');
    }

    public function plans(): void
    {
        $this->authorizeAdmin();
        $this->view('admin/plans', ['plans' => $this->plans->listForAdmin()]);
    }

    public function storePlan(): void
    {
        $this->authorizeAdmin();

        $name = trim($_POST['name'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $duration = (int) ($_POST['duration_days'] ?? 0);
        $providerCode = trim($_POST['provider_plan_code'] ?? '');

        if ($name === '' || $price <= 0 || $duration <= 0 || $providerCode === '') {
            $this->view('admin/plans', [
                'plans' => $this->plans->listForAdmin(),
                'error' => 'Dados inválidos para criar plano.',
            ]);
            return;
        }

        $this->plans->create($name, $price, $duration, $providerCode);
        redirect('/admin/plans');
    }

    public function panelConnections(): void
    {
        $this->authorizeAdmin();

        $result = $this->provider->testConnections();
        $this->view('admin/panel_connections', ['result' => $result]);
    }


    public function settingsForm(): void
    {
        $this->authorizeAdmin();

        $this->view('admin/settings', [
            'values' => [
                'IPTV_PROVIDER' => getenv('IPTV_PROVIDER') ?: '',
                'XTREAM_BASE_URL' => getenv('XTREAM_BASE_URL') ?: '',
                'XTREAM_API_PATH' => getenv('XTREAM_API_PATH') ?: '/api.php',
                'XTREAM_USERNAME' => getenv('XTREAM_USERNAME') ?: '',
                'XTREAM_PASSWORD' => getenv('XTREAM_PASSWORD') ?: '',
                'XUI_BASE_URL' => getenv('XUI_BASE_URL') ?: '',
                'XUI_CREATE_LINE_PATH' => getenv('XUI_CREATE_LINE_PATH') ?: '/api/lines/create',
                'XUI_PING_PATH' => getenv('XUI_PING_PATH') ?: '/api/auth/me',
                'XUI_AUTH_MODE' => getenv('XUI_AUTH_MODE') ?: 'api_key',
                'XUI_API_KEY' => getenv('XUI_API_KEY') ?: '',
                'XUI_USERNAME' => getenv('XUI_USERNAME') ?: '',
                'XUI_PASSWORD' => getenv('XUI_PASSWORD') ?: '',
            ],
        ]);
    }

    public function saveSettings(): void
    {
        $this->authorizeAdmin();

        $values = [
            'IPTV_PROVIDER' => trim($_POST['IPTV_PROVIDER'] ?? 'xtream_ui'),
            'XTREAM_BASE_URL' => trim($_POST['XTREAM_BASE_URL'] ?? ''),
            'XTREAM_API_PATH' => trim($_POST['XTREAM_API_PATH'] ?? '/api.php'),
            'XTREAM_USERNAME' => trim($_POST['XTREAM_USERNAME'] ?? ''),
            'XTREAM_PASSWORD' => trim($_POST['XTREAM_PASSWORD'] ?? ''),
            'XUI_BASE_URL' => trim($_POST['XUI_BASE_URL'] ?? ''),
            'XUI_CREATE_LINE_PATH' => trim($_POST['XUI_CREATE_LINE_PATH'] ?? '/api/lines/create'),
            'XUI_PING_PATH' => trim($_POST['XUI_PING_PATH'] ?? '/api/auth/me'),
            'XUI_AUTH_MODE' => trim($_POST['XUI_AUTH_MODE'] ?? 'api_key'),
            'XUI_API_KEY' => trim($_POST['XUI_API_KEY'] ?? ''),
            'XUI_USERNAME' => trim($_POST['XUI_USERNAME'] ?? ''),
            'XUI_PASSWORD' => trim($_POST['XUI_PASSWORD'] ?? ''),
        ];

        $this->envFile->updateMany(__DIR__ . '/../../.env', $values);

        foreach ($values as $key => $value) {
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }

        redirect('/admin/settings');
    }

    private function authorizeAdmin(): void
    {
        if (($_SESSION['role'] ?? null) !== 'admin') {
            redirect('/admin/login');
        }
    }
}
