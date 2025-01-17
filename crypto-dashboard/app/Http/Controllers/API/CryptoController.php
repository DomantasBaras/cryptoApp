<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CryptoController extends Controller
{
    public function index()
    {
        // Cache the response for 1 minute to reduce API calls and improve performance
        return Cache::remember('crypto_assets', 60, function () {
            try {
                $response = Http::get(config('services.coincap.url') . '/assets');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return response()->json([
                    'error' => 'External API error'
                ], 503);
                
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Service temporarily unavailable'
                ], 503);
            }
        });
    }
}