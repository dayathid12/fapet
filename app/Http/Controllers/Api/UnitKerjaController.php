<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    /**
     * Search for unit kerja.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $unitKerjas = UnitKerja::query()
            ->when($query, function ($q, $query) {
                return $q->where('nama_unit_kerja', 'like', "%{$query}%");
            })
            ->take(30)
            ->get();

        $items = $unitKerjas->map(function ($unit) {
            return [
                'value' => $unit->getKey(), // Use getKey() to be safe
                'label' => $unit->nama_unit_kerja
            ];
        });

        return response()->json([
            'items' => $items,
        ]);
    }
}
