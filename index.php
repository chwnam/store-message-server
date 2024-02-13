<?php

declare(strict_types=1);

/**
 * Store Message Server
 *
 * 메시지 저장 API 엔드포인트.
 * README.md 참고.
 */

use Dotenv\Dotenv;

use function Changwoo\StoreMessageServer\sanitizeKey;
use function Changwoo\StoreMessageServer\sendJsonError;
use function Changwoo\StoreMessageServer\sendJsonSuccess;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['STORE_ROOT', 'API_KEY']);

$apiKey    = $_ENV['API_KEY'];
$storeRoot = rtrim(realpath($_ENV['STORE_ROOT']), '\\/');

if (!file_exists($storeRoot) || !is_dir($storeRoot)) {
    die('STORE_ROOT 는 디렉토리가 아닙니다.');
} elseif (!is_writable($storeRoot) || !is_executable($storeRoot)) {
    die('STORE_ROOT 에 접근할 충분한 권한이 없습니다.');
}

// Method check.
$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ('POST' !== $method) {
    sendJsonError('error', "'$method' method is not supported.");
}

// Simple authorization.
$headerApiKey = $_SERVER['HTTP_X_SMS_API_KEY'] ?? '';
if ($headerApiKey !== $apiKey) {
    sendJsonError('error', 'API key mismatch', 403);
}

// Message extraction.
$message = trim(htmlspecialchars($_POST['message'] ?? ''));
if (empty($message)) {
    sendJsonError('error', 'Empty message is not allowed');
}

// Tag process.
$tag = sanitizeKey($_POST['tag'] ?? '');
$dir = $tag ? ($storeRoot . DIRECTORY_SEPARATOR . $tag) : $storeRoot;
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

// Storing.
$path = $dir . DIRECTORY_SEPARATOR . ((string)time()) . '.txt';
if (false === file_put_contents($path, $message)) {
    $error = error_get_last();
    if ($error) {
        $message = sprintf('%s:%s %s', $error['file'], $error['line'], $error['message']);
    } else {
        $message = 'Unknown error occurred.';
    }
    sendJsonError('error', $message);
}

sendJsonSuccess('OK');
