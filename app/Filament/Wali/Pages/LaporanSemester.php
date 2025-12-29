<?php

namespace App\Filament\Wali\Pages;

use App\Models\IndikatorPerkembangan;
use App\Models\PerkembanganFisik as PerkembanganFisikModel;
use App\Models\PerkembanganKognitif as PerkembanganKognitifModel;
use App\Models\Presensi as PresensiModel;
use App\Models\TahunAjaran;
use App\Models\Wali;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class LaporanSemester extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan Semester';

    protected static ?string $title = 'Laporan Perkembangan Semester';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament-panels::pages.page';

    public ?array $filters = [];

    protected ?Wali $wali = null;

    public function mount(): void
    {
        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $this->wali) {
            abort(403);
        }

        $active = $this->getActiveTahunAjaran();

        $this->filters = [
            'tahun_ajaran_id' => $active?->id,
        ];
    }

    public function content(Schema $schema): Schema
    {
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return $schema->components([
                Section::make('Laporan Semester')
                    ->schema([
                        Text::make('Belum ada data anak yang terhubung dengan akun wali ini.'),
                    ]),
            ]);
        }

        $tahunAjaranId = $this->filters['tahun_ajaran_id'] ?? null;
        $tahunAjaran = $this->getTahunAjaran($tahunAjaranId);
        $startDate = null;
        $endDate = null;

        $presensiSummary = $this->getPresensiSummary($siswa->id, $tahunAjaranId, $startDate, $endDate);
        $fisikSummary = $this->getLatestFisik($siswa->id, $tahunAjaranId, $startDate, $endDate);
        $fisikRekomendasi = $this->getFisikRecommendation($fisikSummary['status_raw'] ?? null);
        $kognitifRecords = $this->getKognitifRecords($siswa->id, $tahunAjaranId, $startDate, $endDate);
        $indikatorList = $this->getOrderedIndicators($kognitifRecords);

        $components = [
            Section::make('Filter Laporan')
                ->schema([
                    Form::make([
                        EmbeddedSchema::make('filtersForm'),
                    ]),
                ]),

            Section::make('Header Laporan')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Html::make($this->buildHeaderItem('Nama Anak', $siswa->nama ?? '-')),
                            Html::make($this->buildHeaderItem('NIS', $siswa->nis ?? '-')),
                            Html::make($this->buildHeaderItem('NISN', $siswa->nisn ?? '-')),
                            Html::make($this->buildHeaderItem('Kelas', $siswa->kelas?->nama ?? '-')),
                            Html::make($this->buildHeaderItem('Nama Guru Kelas', $siswa->kelas?->guru?->nama ?? '-')),
                            Html::make($this->buildHeaderItem('Nama Wali Murid', $siswa->wali?->nama ?? '-')),
                            Html::make($this->buildHeaderItem('Tahun Ajaran', $tahunAjaran?->label ?? '-')),
                            Html::make($this->buildHeaderItem('Tanggal Cetak', Carbon::now()->format('d M Y'))),
                        ]),
                ]),

            Section::make('Rekap Presensi Semester')
                ->schema([
                    Grid::make(5)
                        ->schema([
                            Text::make('Total Hadir: ' . $presensiSummary['hadir'])
                                ->badge()
                                ->color('success'),
                            Text::make('Total Alfa: ' . $presensiSummary['alfa'])
                                ->badge()
                                ->color('danger'),
                            Text::make('Total Izin: ' . $presensiSummary['izin'])
                                ->badge()
                                ->color('warning'),
                            Text::make('Total Sakit: ' . $presensiSummary['sakit'])
                                ->badge()
                                ->color('info'),
                            Text::make('Persentase Hadir: ' . $presensiSummary['persentase_hadir']),
                        ]),
                ]),

            Section::make('Ringkasan Perkembangan Fisik Semester')
                ->schema([
                    Grid::make(1)
                        ->schema([
                            Text::make('Status Terakhir: ' . ($fisikSummary['status'] ?? '-'))
                                ->badge()
                                ->color($fisikSummary['status_color'] ?? 'gray'),
                        ]),
                    Grid::make(3)
                        ->schema([
                            Html::make($this->buildHeaderItem('Tanggal Terakhir', $fisikSummary['tanggal'] ?? '-')),
                            Html::make($this->buildHeaderItem('TB Terakhir', $fisikSummary['tb'] ?? '-')),
                            Html::make($this->buildHeaderItem('BB Terakhir', $fisikSummary['bb'] ?? '-')),
                            Html::make($this->buildHeaderItem('LK Terakhir', $fisikSummary['lk'] ?? '-')),
                        ]),
                    Html::make(new HtmlString(
                        '<div style="font-size: 1rem; line-height: 1.65; color: #1f2937;">' .
                            e($fisikRekomendasi) .
                        '</div>' .
                        '<div style="margin-top: 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #6b7280;">' .
                            'Catatan: Rekomendasi ini merupakan hasil pengolahan sistem dan digunakan sebagai bahan pertimbangan pendukung.' .
                        '</div>'
                    )),
                ]),
        ];

        $components[] = Section::make('Ringkasan Perkembangan Kognitif Semester')
            ->schema([]);

        foreach ($indikatorList as $indikator) {
            $record = $kognitifRecords->firstWhere('indikator_id', $indikator['id']);
            $indikatorLabel = $indikator['label'];
            $narasiText = $record?->narasi ?? '';

            $sectionSchema = [];
            if ($record) {
                $sectionSchema[] = Html::make($this->buildNarasiHtml($narasiText));
            } else {
                $sectionSchema[] = Text::make('Belum ada catatan pada periode ini.');
            }

            $components[] = Section::make($indikatorLabel)
                ->schema($sectionSchema)
                ->columnSpanFull();
        }

        $components[] = Section::make('Penutup')
            ->schema([
                Text::make('Laporan ini disusun secara otomatis oleh sistem berdasarkan catatan perkembangan anak yang diinput oleh guru selama satu semester dan digunakan sebagai bahan informasi bagi wali murid.'),
            ]);

        return $schema->components($components);
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran')
                            ->options($this->getTahunAjaranOptions())
                            ->searchable()
                            ->placeholder('Pilih Tahun Ajaran')
                            ->live(),
                    ]),
            ])
            ->statePath('filters');
    }

    protected function getPresensiSummary(int $siswaId, ?int $tahunAjaranId, ?string $startDate, ?string $endDate): array
    {
        $query = PresensiModel::query()
            ->where('siswa_id', $siswaId);

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $result = $query->selectRaw("
            SUM(status_kehadiran = 'hadir') as hadir_count,
            SUM(status_kehadiran = 'alfa') as alfa_count,
            SUM(status_kehadiran = 'izin') as izin_count,
            SUM(status_kehadiran = 'sakit') as sakit_count
        ")->first();

        $hadir = (int) ($result->hadir_count ?? 0);
        $alfa = (int) ($result->alfa_count ?? 0);
        $izin = (int) ($result->izin_count ?? 0);
        $sakit = (int) ($result->sakit_count ?? 0);
        $total = $hadir + $alfa + $izin + $sakit;
        $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) . '%' : '-';

        return [
            'hadir' => $hadir,
            'alfa' => $alfa,
            'izin' => $izin,
            'sakit' => $sakit,
            'persentase_hadir' => $persentase,
        ];
    }

    protected function getLatestFisik(int $siswaId, ?int $tahunAjaranId, ?string $startDate, ?string $endDate): array
    {
        $query = PerkembanganFisikModel::query()
            ->where('siswa_id', $siswaId);

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_ukur', [$startDate, $endDate]);
        }

        $latest = $query->latest('tanggal_ukur')->first();

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
            'tb' => $latest?->tinggi_badan ? ($latest->tinggi_badan . ' cm') : '-',
            'bb' => $latest?->berat_badan ? ($latest->berat_badan . ' kg') : '-',
            'lk' => $latest?->lingkar_kepala ? ($latest->lingkar_kepala . ' cm') : '-',
        ];
    }

    protected function getFisikRecommendation(?string $statusRaw): string
    {
        if (! $statusRaw) {
            return 'Belum ada data perkembangan fisik untuk semester ini.';
        }

        if ($statusRaw === 'normal') {
            return 'Perkembangan fisik anak berada dalam kategori normal. Disarankan untuk melanjutkan pemantauan rutin serta menjaga pola makan dan aktivitas fisik yang seimbang.';
        }

        return 'Perkembangan fisik anak memerlukan perhatian. Disarankan untuk berkonsultasi dengan tenaga kesehatan seperti dokter anak atau ahli gizi.';
    }

    protected function getKognitifRecords(int $siswaId, ?int $tahunAjaranId, ?string $startDate, ?string $endDate)
    {
        $allowedSiswaIds = $this->getAllowedSiswaIds();

        if (! in_array($siswaId, $allowedSiswaIds, true)) {
            return collect();
        }

        $query = PerkembanganKognitifModel::query()
            ->with(['indikator'])
            ->where('siswa_id', $siswaId);

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        return $query->orderByDesc('created_at')->get();
    }

    protected function getOrderedIndicators($records): array
    {
        $indikatorQuery = IndikatorPerkembangan::query()->get(['id', 'aspek', 'deskripsi']);
        $indikators = $indikatorQuery->isNotEmpty()
            ? $indikatorQuery
            : $records->map(fn ($record) => $record->indikator)->filter();

        $orderMap = $this->getIndicatorOrderMap();

        return $indikators
            ->map(function ($indikator) {
                $label = $indikator?->aspek ?: $indikator?->deskripsi ?: '-';

                if (str_starts_with($label, 'Berisikan Penjelasan ')) {
                    $label = substr($label, strlen('Berisikan Penjelasan '));
                }

                return [
                    'id' => $indikator?->id,
                    'label' => $label,
                ];
            })
            ->filter(fn ($item) => ! empty($item['id']))
            ->unique('id')
            ->sortBy(function ($item) use ($orderMap) {
                $label = mb_strtolower($item['label']);
                foreach ($orderMap as $keyword => $order) {
                    if (str_contains($label, $keyword)) {
                        return $order;
                    }
                }

                return 999;
            })
            ->values()
            ->all();
    }

    protected function getIndicatorOrderMap(): array
    {
        return [
            'informasi umum' => 1,
            'informasi mengenai perkembangan' => 1,
            'dasar-dasar literasi' => 2,
            'literasi' => 2,
            'matematika' => 2,
            'sains' => 2,
            'rekayasa' => 2,
            'teknologi' => 2,
            'seni' => 2,
            'jati diri' => 3,
            'nilai agama' => 4,
            'budi pekerti' => 4,
        ];
    }

    protected function buildNarasiHtml(?string $text): HtmlString
    {
        $text = trim($text ?? '');

        if ($text === '') {
            return new HtmlString('<p style="margin: 0;">Belum ada catatan pada periode ini.</p>');
        }

        $paragraphs = preg_split("/\r?\n\r?\n/", $text);

        $html = collect($paragraphs)
            ->map(function (string $paragraph): string {
                $safe = nl2br(e(trim($paragraph)));

                return '<p style="text-indent: 2em; margin: 0 0 1em 0; text-align: justify; line-height: 1.6;">' . $safe . '</p>';
            })
            ->implode('');

        return new HtmlString($html);
    }

    protected function buildHeaderItem(string $label, string $value): HtmlString
    {
        return new HtmlString(
            '<div>' .
                '<div style="font-size: 0.875rem; color: #6b7280;">' . e($label) . '</div>' .
                '<div style="font-size: 1rem; color: #111827; font-weight: 500;">' . e($value) . '</div>' .
            '</div>'
        );
    }

    protected function getActiveTahunAjaran(): ?TahunAjaran
    {
        return TahunAjaran::query()
            ->where('is_active', true)
            ->first();
    }

    protected function getTahunAjaran(int|string|null $tahunAjaranId): ?TahunAjaran
    {
        if (! $tahunAjaranId) {
            return $this->getActiveTahunAjaran();
        }

        return TahunAjaran::query()->find($tahunAjaranId);
    }

    protected function getDefaultTahunAjaranId(): ?int
    {
        return $this->getActiveTahunAjaran()?->id;
    }

    protected function getTahunAjaranOptions(): array
    {
        return TahunAjaran::query()
            ->orderByDesc('tahun')
            ->orderByDesc('semester')
            ->get()
            ->mapWithKeys(fn ($ta) => [$ta->id => $ta->label])
            ->all();
    }

    protected function getAllowedSiswaIds(): array
    {
        return $this->getWali()?->siswas?->pluck('id')->all() ?? [];
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
