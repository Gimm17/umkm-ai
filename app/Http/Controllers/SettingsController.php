<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\BusinessSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Get all settings grouped by category.
     */
    public function index(): JsonResponse
    {
        try {
            $settings = [
                'ai_prompt' => BusinessSetting::getGroup('ai_prompt'),
                'channel' => BusinessSetting::getGroup('channel'),
                'general' => BusinessSetting::getGroup('general'),
            ];

            // Mask sensitive values
            if (isset($settings['channel']['whatsapp_access_token'])) {
                $settings['channel']['whatsapp_access_token'] = str_repeat('*', 10);
            }
            if (isset($settings['channel']['instagram_access_token'])) {
                $settings['channel']['instagram_access_token'] = str_repeat('*', 10);
            }

            return response()->json([
                'data' => $settings,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to load settings', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal memuat pengaturan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update settings.
     */
    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        try {
            $settings = $request->validated();

            // Update each setting group
            foreach ($settings as $group => $values) {
                foreach ($values as $key => $value) {
                    // Skip masked token values
                    if ($key === 'whatsapp_access_token' || $key === 'instagram_access_token') {
                        if (str_starts_with($value, '*')) {
                            continue; // Skip if masked
                        }
                    }

                    BusinessSetting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'group' => $group,
                        ]
                    );
                }

                // Clear cache for this group
                BusinessSetting::clearGroupCache($group);
            }

            Log::info('Settings updated successfully', [
                'groups' => array_keys($settings),
            ]);

            return response()->json([
                'message' => 'Pengaturan berhasil disimpan',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to update settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Gagal menyimpan pengaturan',
                'error' => app()->isProduction() ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test WhatsApp connection.
     */
    public function testWhatsApp(): JsonResponse
    {
        try {
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $accessToken = config('services.whatsapp.access_token');
            $baseUrl = config('services.whatsapp.url');

            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->timeout(10)
                ->get("{$baseUrl}/{$phoneNumberId}");

            if ($response->successful()) {
                return response()->json([
                    'message' => 'WhatsApp connection successful',
                    'data' => [
                        'phone_number_id' => $phoneNumberId,
                        'verified' => true,
                    ],
                ], 200);
            }

            throw new \Exception("WhatsApp API error: {$response->status()}");
        } catch (\Throwable $e) {
            Log::error('WhatsApp connection test failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'WhatsApp connection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Instagram connection.
     */
    public function testInstagram(): JsonResponse
    {
        try {
            $accessToken = config('services.instagram.access_token');
            $baseUrl = config('services.instagram.url');

            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->timeout(10)
                ->get("{$baseUrl}/me");

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Instagram connection successful',
                    'data' => [
                        'instagram_id' => $response->json('id'),
                        'verified' => true,
                    ],
                ], 200);
            }

            throw new \Exception("Instagram API error: {$response->status()}");
        } catch (\Throwable $e) {
            Log::error('Instagram connection test failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Instagram connection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

