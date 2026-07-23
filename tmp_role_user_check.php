<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/back-office/role-user', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_HOST' => 'localhost']);
$response = $kernel->handle($request);
echo $response->getContent();
$kernel->terminate($request, $response);
