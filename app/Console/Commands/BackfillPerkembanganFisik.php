<?php

namespace App\Console\Commands;

use App\Models\PerkembanganFisik;
use App\Services\StandarFisikCalculator;
use Illuminate\Console\Command;

class BackfillPerkembanganFisik extends Command
{
    protected $signature = 'sippa:backfill-fisik';

    protected $description = 'Hitung ulang standar_id, kategori_tb/bb/lk, dan status_ringkas untuk seluruh data perkembangan fisik';

    public function handle(): int
    {
        $calculator = app(StandarFisikCalculator::class);

        $query = PerkembanganFisik::query()
            ->with('siswa'); // untuk menghindari N+1 saat kalkulasi

        $total = $query->count();
        $this->info("Total rows to backfill: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->chunkById(100, function ($items) use ($calculator, $bar) {
            foreach ($items as $pf) {
                $result = $calculator->calculate($pf);

                $pf->update($result);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Backfill selesai.');

        return self::SUCCESS;
    }
}
