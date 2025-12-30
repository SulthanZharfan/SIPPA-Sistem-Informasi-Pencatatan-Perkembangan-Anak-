<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;

class LaporanSemesterPdfService
{
    public function make(array $reportData)
    {
        return Pdf::loadView('pdf.laporan-semester', [
            'report' => $reportData,
        ])->setPaper('a4', 'portrait');
    }
}
