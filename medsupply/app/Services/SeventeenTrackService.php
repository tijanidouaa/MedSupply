<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SeventeenTrackService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.17track.key');
    }

    // Enregistrer un numéro de tracking
    public function registerTracking($trackingNumber)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            '17token' => $this->apiKey,
        ])->post('https://api.17track.net/track/v2.2/register', [
            [
                'number' => $trackingNumber,
            ]
        ]);

        return $response->json();
    }

    // Récupérer le statut du colis
    public function getTracking($trackingNumber)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            '17token' => $this->apiKey,
        ])->post('https://api.17track.net/track/v2.2/gettrackinfo', [
            [
                'number' => $trackingNumber,
            ]
        ]);

        return $response->json();
    }
}