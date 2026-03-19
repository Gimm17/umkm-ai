<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessIncomingMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Verify WhatsApp webhook registration.
     */
    public function verifyWhatsApp(Request $request): Response
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming WhatsApp webhook.
     */
    public function handleWhatsApp(Request $request): Response
    {
        // 1. Verifikasi signature SEBELUM apapun
        $signature = $request->header('X-Hub-Signature-256');

        if (!$this->verifySignature($request->getContent(), $signature, 'whatsapp')) {
            Log::warning('Webhook WA: signature tidak valid', [
                'ip' => $request->ip(),
            ]);
            return response('Forbidden', 403);
        }

        // 2. Langsung dispatch ke queue — return 200 secepat mungkin
        ProcessIncomingMessage::dispatch('whatsapp', $request->all())
            ->onQueue('messages');

        return response('OK', 200);
    }

    /**
     * Verify Instagram webhook registration.
     */
    public function verifyInstagram(Request $request): Response
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('services.instagram.verify_token')) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming Instagram webhook.
     */
    public function handleInstagram(Request $request): Response
    {
        // 1. Verifikasi signature SEBELUM apapun
        $signature = $request->header('X-Hub-Signature-256');

        if (!$this->verifySignature($request->getContent(), $signature, 'instagram')) {
            Log::warning('Webhook IG: signature tidak valid', [
                'ip' => $request->ip(),
            ]);
            return response('Forbidden', 403);
        }

        // 2. Langsung dispatch ke queue — return 200 secepat mungkin
        ProcessIncomingMessage::dispatch('instagram', $request->all())
            ->onQueue('messages');

        return response('OK', 200);
    }

    /**
     * Verify webhook signature.
     */
    private function verifySignature(string $payload, ?string $signature, string $channel): bool
    {
        if (!$signature) {
            return false;
        }

        // Skip verification in development if WEBHOOK_SKIP_VERIFY is true
        if (!app()->isProduction() && config('services.webhook_skip_verify', false)) {
            return true;
        }

        $secret = config("services.{$channel}.app_secret");
        $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
