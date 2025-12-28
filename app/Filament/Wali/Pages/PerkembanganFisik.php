<?php

namespace App\Filament\Wali\Pages;

use App\Filament\Wali\Widgets\WaliPerkembanganFisikChart;
use App\Filament\Wali\Widgets\WaliPerkembanganFisikTable;
use App\Models\PerkembanganFisik as PerkembanganFisikModel;
use App\Models\Wali;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class PerkembanganFisik extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Perkembangan Fisik';

    protected static ?string $title = 'Perkembangan Fisik Anak';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament-panels::pages.page';

    protected ?Wali $wali = null;

    public function mount(): void
    {
        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $this->wali) {
            abort(403);
        }

    }

    public function content(Schema $schema): Schema
    {
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return $schema->components([
                Section::make('Perkembangan Fisik')
                    ->schema([
                        Text::make('Belum ada data anak yang terhubung dengan akun wali ini.'),
                    ]),
            ]);
        }

        $latest = $this->getLatestFisik($siswa->id, null, null);
        $rekomendasi = $this->getFisikRecommendation($latest['status_raw'] ?? null);

        return $schema->components([
            Section::make('Perkembangan Fisik')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Status Terbaru: ' . ($latest['status'] ?? '-'))
                                ->badge()
                                ->color($latest['status_color'] ?? 'gray'),
                            Text::make('Tanggal Terbaru: ' . ($latest['tanggal'] ?? '-')),
                        ]),
                    Livewire::make(WaliPerkembanganFisikChart::class, fn () => [
                        'siswaId' => $siswa->id,
                        'allowedSiswaIds' => $this->wali?->siswas?->pluck('id')->all() ?? [],
                        'startDate' => null,
                        'endDate' => null,
                    ])->key('wali-fisik-chart-' . $siswa->id . '-all')
                        ->columnSpanFull(),
                ]),

            Section::make('Usulan / Rekomendasi')
                ->schema([
                    Html::make(new HtmlString(
                        '<div style="font-size: 1rem; line-height: 1.65; color: #1f2937;">' .
                            e($rekomendasi) .
                        '</div>' .
                        '<div style="margin-top: 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #6b7280;">' .
                            'Catatan: Rekomendasi ini merupakan hasil pengolahan sistem dan digunakan sebagai bahan pertimbangan pendukung.' .
                        '</div>'
                    )),
                ])
                ->columnSpanFull(),

            Section::make('Data Fisik')
                ->schema([
                    Livewire::make(WaliPerkembanganFisikTable::class, fn () => [
                        'siswaId' => $siswa->id,
                        'allowedSiswaIds' => $this->wali?->siswas?->pluck('id')->all() ?? [],
                        'startDate' => null,
                        'endDate' => null,
                    ])->key('wali-fisik-table-' . $siswa->id . '-all'),
                ]),
        ]);
    }

    protected function getLatestFisik(int $siswaId, ?string $startDate, ?string $endDate): array
    {
        $latest = PerkembanganFisikModel::query()
            ->where('siswa_id', $siswaId)
            ->when($startDate, fn ($q) => $q->whereDate('tanggal_ukur', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('tanggal_ukur', '<=', $endDate))
            ->latest('tanggal_ukur')
            ->first();

        if (! $latest && ($startDate || $endDate)) {
            $latest = PerkembanganFisikModel::query()
                ->where('siswa_id', $siswaId)
                ->latest('tanggal_ukur')
                ->first();
        }

        $statusRaw = $latest?->status_ringkas;
        $statusLabel = match ($statusRaw) {
            'normal' => 'Normal',
            'perlu_perhatian' => 'Perlu Perhatian',
            default => $statusRaw,
        };
        $statusColor = match ($statusRaw) {
            'normal' => 'success',
            'perlu_perhatian' => 'warning',
            default => 'gray',
        };

        return [
            'status' => $statusLabel ?? '-',
            'status_raw' => $statusRaw,
            'status_color' => $statusColor,
            'tanggal' => $latest?->tanggal_ukur?->format('d M Y') ?? '-',
        ];
    }

    protected function getFisikRecommendation(?string $statusRaw): string
    {
        if (! $statusRaw) {
            return 'Belum ada data perkembangan fisik untuk ditampilkan.';
        }

        if ($statusRaw === 'normal') {
            return 'Perkembangan fisik anak berada dalam kategori normal. Disarankan untuk melanjutkan pemantauan rutin serta menjaga pola makan dan aktivitas fisik yang seimbang.';
        }

        return 'Disarankan untuk berkonsultasi dengan tenaga kesehatan (dokter anak/dokter gizi) untuk mendapatkan arahan yang sesuai. Pemantauan rutin tetap diperlukan agar perkembangan anak terpantau dengan baik.';
    }


    protected function getSelectedSiswa()
    {
        return $this->getWali()?->siswas?->sortBy('nama')->first();
    }

    protected function getWali(): ?Wali
    {
        if ($this->wali) {
            return $this->wali;
        }

        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        return $this->wali;
    }

}
