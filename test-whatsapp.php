<?php

use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$token = config('services.whatsapp.token');
$phoneId = config('services.whatsapp.phone_id');

$testNumber = '5219995066872'; // Agregando el 1 para ver si Meta lo acepta

echo "Intentando enviar a: $testNumber\n";

$response = Http::withToken($token)
    ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
        'messaging_product' => 'whatsapp',
        'to' => $testNumber,
        'type' => 'text',
        'text' => [
            'body' => 'Hola, este es un mensaje de prueba directo de Laravel.',
        ],
    ]);

echo "\nRespuesta de Meta:\n";
echo $response->body() . "\n";
