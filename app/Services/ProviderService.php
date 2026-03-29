<?php

namespace App\Services;

use App\Config\Env;
use App\Integrations\XtreamUiClient;
use App\Integrations\XuiOneClient;

final class ProviderService
{
    public function __construct(
        private readonly XtreamUiClient $xtream,
        private readonly XuiOneClient $xui,
    ) {
    }

    public function provisionLine(string $username, string $password, string $planCode, int $expiresInDays): array
    {
        $provider = Env::get('IPTV_PROVIDER', 'xtream_ui');

        return match ($provider) {
            'xui_one' => $this->xui->createLine($username, $password, $planCode, $expiresInDays),
            default => $this->xtream->createLine($username, $password, $planCode, $expiresInDays),
        };
    }

    public function testConnections(): array
    {
        $xtream = $this->xtream->ping();
        $xui = $this->xui->ping();

        return [
            'xtream_ui' => [
                'status' => $xtream['status'] ?? 0,
                'ok' => ($xtream['status'] ?? 0) >= 200 && ($xtream['status'] ?? 0) < 300,
                'body' => $xtream['body'] ?? [],
            ],
            'xui_one' => [
                'status' => $xui['status'] ?? 0,
                'ok' => ($xui['status'] ?? 0) >= 200 && ($xui['status'] ?? 0) < 300,
                'body' => $xui['body'] ?? [],
            ],
        ];
    }
}
