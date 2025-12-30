<?php

namespace App\Support;

use Illuminate\Support\Str;

class LaporanNomorGenerator
{
    public static function make(string $tahunAjaranLabel, ?string $semester, string $namaAnak): string
    {
        $label = trim($tahunAjaranLabel);
        $semesterUpper = strtoupper(trim((string) $semester));

        if (! in_array($semesterUpper, ['GANJIL', 'GENAP'], true)) {
            $lower = Str::lower($label);
            if (str_contains($lower, 'ganjil')) {
                $semesterUpper = 'GANJIL';
            } elseif (str_contains($lower, 'genap')) {
                $semesterUpper = 'GENAP';
            } else {
                $semesterUpper = 'GANJIL';
            }
        }

        $ta = str_replace('/', '-', $label);
        $ta = str_ireplace(['ganjil', 'genap'], '', $ta);
        $ta = preg_replace('/[^0-9\-]/', '', $ta ?? '');
        $ta = preg_replace('/\-+/', '-', $ta ?? '');
        $ta = trim((string) $ta, '-');

        if ($ta === '') {
            $ta = '0000-0000';
        }

        $initials = collect(preg_split('/\s+/', trim($namaAnak)))
            ->filter()
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->implode('');

        $initials = strtoupper(mb_substr($initials, 0, 5));

        if ($initials === '') {
            $initials = 'ANAK';
        }

        $timestamp = now()->format('YmdHis');

        return "LPS/{$ta}/{$semesterUpper}/{$timestamp}/{$initials}";
    }
}
