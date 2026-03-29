<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Integrations\FastDePixClient;
use App\Integrations\HttpClient;
use App\Integrations\XtreamUiClient;
use App\Integrations\XuiOneClient;
use App\Repositories\OrderRepository;
use App\Repositories\PlanRepository;
use App\Repositories\UserRepository;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\PlanService;
use App\Services\ProviderService;

final class ClientController extends Controller
{
    private PlanService $plans;
    private OrderService $orders;

    public function __construct()
    {
        $planRepo = new PlanRepository();
        $this->plans = new PlanService($planRepo);

        $http = new HttpClient();
        $provider = new ProviderService(new XtreamUiClient($http), new XuiOneClient($http));
        $payments = new PaymentService(new FastDePixClient($http));

        $this->orders = new OrderService(
            new OrderRepository(),
            $planRepo,
            new UserRepository(),
            $payments,
            $provider
        );
    }

    public function dashboard(): void
    {
        $this->authorizeClient();
        $this->view('client/dashboard', [
            'plans' => $this->plans->listForClient(),
            'orders' => $this->orders->listByUser((int) $_SESSION['user_id']),
        ]);
    }

    public function createOrder(): void
    {
        $this->authorizeClient();

        $planId = (int) ($_POST['plan_id'] ?? 0);
        if ($planId <= 0) {
            redirect('/client/dashboard');
        }

        $this->orders->createOrder((int) $_SESSION['user_id'], $planId);
        redirect('/client/dashboard');
    }

    public function syncOrderPayment(): void
    {
        $this->authorizeClient();

        $orderId = (int) ($_POST['order_id'] ?? 0);
        if ($orderId > 0) {
            $this->orders->syncPaymentStatus($orderId, (int) $_SESSION['user_id']);
        }

        redirect('/client/dashboard');
    }

    private function authorizeClient(): void
    {
        if (($_SESSION['role'] ?? null) !== 'client') {
            redirect('/login');
        }
    }
}
