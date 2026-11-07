<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$user = App\Models\User::where('username', 'kabid.smk')->first();
Illuminate\Support\Facades\Auth::login($user);
$response = $kernel->handle(
    Illuminate\Http\Request::create('/', 'GET')
);
echo "Redirects to: " . $response->getTargetUrl() . "\n";
