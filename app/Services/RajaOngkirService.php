<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('rajaongkir.base_url');
        $this->apiKey  = config('rajaongkir.api_key');
    }

    /**
     * Cari kota/kecamatan/kelurahan untuk autocomplete alamat.
     * Dipakai baik di checkout (alamat customer) maupun saat set origin toko.
     */
    public function searchDestination(string $keyword, int $limit = 10)
    {
        $cacheKey = 'rajaongkir:search:' . strtolower($keyword) . ":$limit";

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($keyword, $limit) {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $keyword,
                    'limit'  => $limit,
                    'offset' => 0,
                ]);

            return $response->successful() ? $response->json('data', []) : [];
        });
    }

    /**
     * Hitung ongkos kirim.
     * $courier contoh: 'jne:jnt:sicepat:pos' (gabung pakai titik dua)
     */
    public function calculateCost(int|string $destinationId, int $weightGram, string $courier = 'jne:jnt:sicepat', string $sort = 'lowest')
    {
        $response = Http::asForm()
            ->withHeaders(['key' => $this->apiKey])
            ->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin'      => config('rajaongkir.origin_id'),
                'destination' => $destinationId,
                'weight'      => $weightGram,
                'courier'     => $courier,
                'price'       => $sort,
            ]);

        if (! $response->successful()) {
            return ['success' => false, 'message' => $response->json('meta.message', 'Gagal menghitung ongkir'), 'data' => []];
        }

        return ['success' => true, 'data' => $response->json('data', [])];
    }

    /**
     * Lacak status pengiriman berdasarkan nomor resi.
     */
    public function trackWaybill(string $awb, string $courier)
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->post("{$this->baseUrl}/track/waybill", [
                'awb'     => $awb,
                'courier' => $courier,
            ]);

        if (! $response->successful()) {
            return ['success' => false, 'message' => $response->json('meta.message', 'Gagal melacak resi')];
        }

        return ['success' => true, 'data' => $response->json('data')];
    }
}