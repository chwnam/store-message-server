<?php

namespace Changwoo\StoreMessageServer;

function sendJson(bool $success, mixed $data, int $status = 200): never
{
    http_response_code($status);

    header('Content-Type: application/json; charset=UTF-8');

    $encoded = json_encode(
        [
            'success' => $success,
            'data'    => $data,
        ],
        JSON_UNESCAPED_UNICODE
    );

    die($encoded);
}

function sendJsonError(string $code, string $message, int $status = 400): never
{
    $payload = [
        [
            'code'    => $code,
            'message' => $message,
        ],
    ];

    sendJson(false, $payload, $status);
}

function sendJsonSuccess(mixed $data, int $status = 200): never
{
    sendJson(true, $data, $status);
}

function sanitizeKey(string $input): string
{
    return preg_replace('/[^a-z0-9_\-]/', '', $input);
}
