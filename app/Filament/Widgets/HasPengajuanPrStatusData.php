<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\PengajuanPr;
use Illuminate\Support\Facades\DB;

trait HasPengajuanPrStatusData
{
    protected function getData(): array
    {
        $results = PengajuanPr::query()
            ->select([
                DB::raw('COUNT(CASE WHEN (nomor_pr IS NULL OR TRIM(nomor_pr) = "") AND (proses_pr_screenshots IS NULL OR TRIM(proses_pr_screenshots) = "") THEN 1 END) as diajukan'),
                DB::raw('COUNT(CASE WHEN (nomor_pr IS NOT NULL AND TRIM(nomor_pr) != "") AND (proses_pr_screenshots IS NULL OR TRIM(proses_pr_screenshots) = "") THEN 1 END) as proses_pr'),
                DB::raw('COUNT(CASE WHEN proses_pr_screenshots IS NOT NULL AND TRIM(proses_pr_screenshots) != "" THEN 1 END) as selesai')
            ])
            ->first();

        $statusCounts = [
            'Diajukan' => $results->diajukan ?? 0,
            'Proses PR' => $results->proses_pr ?? 0,
            'Selesai' => $results->selesai ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Pengajuan PR',
                    'data' => array_values($statusCounts),
                    'backgroundColor' => [
                        '#FFCE56', // Diajukan - Yellow
                        '#36A2EB', // Proses PR - Blue
                        '#4BC0C0', // Selesai - Green
                    ],
                ],
            ],
            'labels' => array_keys($statusCounts),
        ];
    }
}
