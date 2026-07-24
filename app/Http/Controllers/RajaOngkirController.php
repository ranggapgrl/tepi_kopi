<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function searchDestination(Request $request, RajaOngkirService $rajaOngkir)
    {
        $keyword = $request->query('keyword');
        
        if (strlen($keyword) < 3) {
            return response()->json([]);
        }

        $results = $rajaOngkir->searchDestination($keyword);
        
        return response()->json($results);
    }

    public function calculateCost(Request $request, RajaOngkirService $rajaOngkir)
    {
        $request->validate([
            'destination_id' => 'required',
            'weight'         => 'required|numeric|min:1',
            'courier'        => 'nullable|string'
        ]);

        // Default kurir kalau tidak dikirim dari frontend
        $courier = $request->courier ?? 'jne:jnt:sicepat';

        $result = $rajaOngkir->calculateCost(
            $request->destination_id, 
            $request->weight, 
            $request->courier
        );

        return response()->json($result);
    }
}