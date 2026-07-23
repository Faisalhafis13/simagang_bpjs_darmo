<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$user = App\Models\User::with('role')->first();
if ($user) {
    echo "id=" . $user->id . "\n";
    echo "name=" . $user->name . "\n";
    echo "email=" . $user->email . "\n";
    echo "role_id=" . $user->role_id . "\n";
    echo "role_name=" . optional($user->role)->name . "\n";
} else {
    echo "no-users\n";
}
