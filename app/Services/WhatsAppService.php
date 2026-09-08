<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $instance;
    private string $token;

    public function __construct()
    {
        $this->instance = config('services.ultramsg.instance');
        $this->token    = config('services.ultramsg.token');
    }

    public function enviar(string $celular, string $mensaje): bool
    {
        // Asegurar formato internacional peruano
        $numero = $this->formatearNumero($celular);

        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->asForm()
                ->post("https://api.ultramsg.com/{$this->instance}/messages/chat", [
                    'token' => $this->token,
                    'to'    => $numero,
                    'body'  => $mensaje,
                ]);

            $body = $response->json();

            if (isset($body['sent']) && $body['sent'] === 'true') {
                Log::info("WhatsApp enviado a {$numero}");
                return true;
            }

            Log::warning("WhatsApp no enviado a {$numero}: " . json_encode($body));
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp error: " . $e->getMessage());
            return false;
        }
    }

    private function formatearNumero(string $celular): string
    {
        // Limpiar todo lo que no sea dígito
        $limpio = preg_replace('/\D/', '', $celular);

        // Si ya tiene código de país
        if (str_starts_with($limpio, '51') && strlen($limpio) === 11) {
            return '+' . $limpio;
        }

        // Número peruano de 9 dígitos → agregar +51
        if (strlen($limpio) === 9) {
            return '+51' . $limpio;
        }

        return '+' . $limpio;
    }
}
