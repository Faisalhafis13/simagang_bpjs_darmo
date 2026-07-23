<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = App\Models\User::find(3);
if (! $user) {
    echo "no-auth-user\n";
    exit(1);
}
$app['auth']->login($user);
$request = Illuminate\Http\Request::create('/back-office/role-user/data', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_HOST' => 'localhost']);
$request->setUserResolver(fn() => $app['auth']->user());
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);
echo $response->getContent();
$kernel->terminate($request, $response);
