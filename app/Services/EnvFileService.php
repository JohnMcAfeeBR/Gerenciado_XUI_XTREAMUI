<?php

namespace App\Services;

final class EnvFileService
{
    public function updateMany(string $envPath, array $values): void
    {
        $lines = is_file($envPath) ? file($envPath, FILE_IGNORE_NEW_LINES) : [];
        $lines = is_array($lines) ? $lines : [];

        $map = [];
        foreach ($values as $key => $value) {
            $map[$key] = (string) $value;
        }

        $found = [];
        foreach ($lines as $index => $line) {
            if (!str_contains($line, '=') || str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key] = explode('=', $line, 2);
            $key = trim($key);
            if (array_key_exists($key, $map)) {
                $lines[$index] = $key . '=' . $map[$key];
                $found[$key] = true;
            }
        }

        foreach ($map as $key => $value) {
            if (!isset($found[$key])) {
                $lines[] = $key . '=' . $value;
            }
        }

        file_put_contents($envPath, implode(PHP_EOL, $lines) . PHP_EOL);
    }
}
