<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Search for wilayah.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q'); // Tom-select sends the query in 'q' parameter by default

        $wilayahs = Wilayah::query()
            ->when($query, function ($q, $query) {
                return $q->where('nama_wilayah', 'like', "%{$query}%");
            })
            ->select('wilayah_id as value', 'nama_wilayah as label') // Aliasing for TomSelect
            ->take(30) // Limit results
            ->get();

        return response()->json([
            'items' => $wilayahs,
        ]);
    }
}
