<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Em ambiente Serverless (Vercel), ajustar diretórios temporários para /tmp
if (!file_exists('/tmp/storage')) {
    @mkdir('/tmp/storage', 0777, true);
    @mkdir('/tmp/storage/framework', 0777, true);
    @mkdir('/tmp/storage/framework/views', 0777, true);
    @mkdir('/tmp/storage/framework/cache', 0777, true);
    @mkdir('/tmp/storage/framework/cache/data', 0777, true);
    @mkdir('/tmp/storage/framework/sessions', 0777, true);
    @mkdir('/tmp/storage/logs', 0777, true);
    @mkdir('/tmp/storage/app', 0777, true);
    @mkdir('/tmp/storage/app/public', 0777, true);
}

if (!file_exists('/tmp/bootstrap_cache')) {
    @mkdir('/tmp/bootstrap_cache', 0777, true);
}

// Suporte automático para Neon Postgres na Vercel (DATABASE_URL / POSTGRES_URL / STORAGE_URL)
$databaseUrl = getenv('DATABASE_URL') ?: getenv('POSTGRES_URL') ?: getenv('STORAGE_URL') ?: getenv('POSTGRES_PRISMA_URL');
if ($databaseUrl) {
    putenv('DB_CONNECTION=pgsql');
    putenv('DB_URL=' . $databaseUrl);
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_ENV['DB_URL'] = $databaseUrl;
}

// Manter arquivos em /tmp na Vercel
putenv('APP_STORAGE=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->useStoragePath('/tmp/storage');

$app->handleRequest(Request::capture());
