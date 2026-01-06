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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class PerkembanganFisik extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Perkembangan Fisik';

    protected static ?string $title = 'Perkembangan Fisik Anak';

    protected static string|UnitEnum|null $navigationGroup = 'Informasi Anak';

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

        $latestRecord = $this->getLatestFisikRecord($siswa->id, null, null);
        $latest = $this->formatLatestFisik($latestRecord, $siswa->id, null, null);
        $rekomendasi = $this->getFisikRecommendation($latest['status_raw'] ?? null);

        return $schema->components([
            Section::make('Perkembangan Fisik')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Text::make('Status Terbaru: ' . ($latest['status'] ?? '-'))
                                ->badge()
                                ->color($latest['status_color'] ?? 'gray'),
                            Html::make(new HtmlString(
                                '<div class="text-sm text-gray-900 dark:text-gray-100">Tanggal Terbaru: ' .
                                    e($latest['tanggal'] ?? '-') .
                                '</div>'
                            )),
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
                        '<div class="text-base leading-relaxed text-gray-900 dark:text-gray-100">' .
                            e($rekomendasi) .
                        '</div>' .
                        '<div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-100">' .
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

            Section::make('Foto')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Dokumentasi perkembangan fisik untuk data terbaru.</div>')),
                            Text::make('Belum ada foto yang diunggah.')
                                ->hidden(fn () => ! blank($latestRecord?->foto)),
                        ]),
                    Html::make(fn () => $this->buildFotoHtml($latestRecord?->foto, 'Foto perkembangan fisik'))
                        ->hidden(fn () => blank($latestRecord?->foto)),
                ])
                ->columnSpanFull(),
        ]);
    }

    protected function getLatestFisik(int $siswaId, ?string $startDate, ?string $endDate): array
    {
        $latest = $this->getLatestFisikRecord($siswaId, $startDate, $endDate);

        return $this->formatLatestFisik($latest, $siswaId, $startDate, $endDate);
    }

    protected function getLatestFisikRecord(int $siswaId, ?string $startDate, ?string $endDate): ?PerkembanganFisikModel
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

        return $latest;
    }

    protected function formatLatestFisik(?PerkembanganFisikModel $latest, int $siswaId, ?string $startDate, ?string $endDate): array
    {
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

    protected function getFotoUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        $diskName = config('filesystems.default', 'local');
        $disk = Storage::disk($diskName);

        if ($disk->providesTemporaryUrls()) {
            return $disk->temporaryUrl($path, now()->addMinutes(30));
        }

        return $disk->url($path);
    }

    protected function buildFotoHtml(?string $path, string $label): HtmlString
    {
        $url = $this->getFotoUrl($path);

        if ($url === '') {
            return new HtmlString('');
        }

        $html = '<div style="display: flex; justify-content: center;">' .
            '<div style="width: 100%; max-width: 520px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px;">' .
                '<img src="' . e($url) . '" alt="' . e($label) . '" style="display: block; width: 100%; max-height: 320px; object-fit: contain; border-radius: 8px;" />' .
            '</div>' .
        '</div>';

        return new HtmlString($html);
    }

}
