<?php
function getEnvValue($key)
{
    $envPath = __DIR__ . '/../.env';

    if (!file_exists($envPath)) {
        return null;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        list($envKey, $envValue) = explode('=', $line, 2);

        if (trim($envKey) === $key) {
            return trim($envValue);
        }
    }

    return null;
}
?>