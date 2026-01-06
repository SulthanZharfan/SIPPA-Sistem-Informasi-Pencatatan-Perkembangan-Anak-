<?php

namespace App\Filament\Wali\Pages;

use App\Models\IndikatorPerkembangan;
use App\Models\PerkembanganKognitif as PerkembanganKognitifModel;
use App\Models\TahunAjaran;
use App\Models\Wali;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class PerkembanganKognitif extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    private const NARASI_LIMIT = 300;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Perkembangan Kognitif';

    protected static ?string $title = 'Perkembangan Kognitif Anak';

    protected static string|UnitEnum|null $navigationGroup = 'Informasi Anak';

    protected string $view = 'filament-panels::pages.page';

    public ?array $filters = [];

    public ?int $expandedId = null;

    protected ?Wali $wali = null;

    public function mount(): void
    {
        $this->wali = Wali::with(['siswas.kelas.guru'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $this->wali) {
            abort(403);
        }

        $this->filters = [
            'tahun_ajaran_id' => $this->getDefaultTahunAjaranId(),
        ];
    }

    public function content(Schema $schema): Schema
    {
        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return $schema->components([
                Section::make('Perkembangan Kognitif')
                    ->schema([
                        Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Belum ada data anak yang terhubung dengan akun wali ini.</div>')),
                    ]),
            ]);
        }

        $tahunAjaranId = $this->filters['tahun_ajaran_id'] ?? null;
        $records = $this->getKognitifRecords($siswa->id);
        $latestRecord = $records->first();
        $summary = $this->buildSummary($records, $tahunAjaranId);
        $indikatorList = $this->getOrderedIndicators($records);

        $components = [
            Section::make('Filter')
                ->schema([
                    Form::make([
                        EmbeddedSchema::make('filtersForm'),
                    ]),
                ]),
            Section::make('Ringkasan Semester')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Semester: ' . e($summary['semester'] ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Total indikator terisi: ' . e((string) ($summary['total_indikator'] ?? 0)) . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Tanggal catatan terakhir: ' . e($summary['tanggal_terakhir'] ?? '-') . '</div>')),
                            Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Guru terakhir: ' . e($summary['guru_terakhir'] ?? '-') . '</div>')),
                        ]),
                    Html::make(new HtmlString(
                        '<div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-100">' .
                            'Informasi ditampilkan dari catatan perkembangan yang diinput oleh guru melalui sistem.' .
                        '</div>'
                    )),
                ])
                ->columnSpanFull(),
        ];

        foreach ($indikatorList as $indikator) {
            $record = $records->firstWhere('indikator_id', $indikator['id']);
            $indikatorLabel = $indikator['label'];
            $tanggalLabel = $record?->created_at
                ? Carbon::parse($record->created_at)->format('d M Y')
                : '-';
            $guruLabel = $record?->guru?->nama ?? '-';
            $narasiText = $record?->narasi ?? '';
            $isExpanded = $this->expandedId === $indikator['id'];
            $canToggle = mb_strlen(trim($narasiText)) > self::NARASI_LIMIT;

            $sectionSchema = [
                Grid::make(2)
                    ->schema([
                        Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Tanggal: ' . e($tanggalLabel) . '</div>')),
                        Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Guru: ' . e($guruLabel) . '</div>')),
                    ]),
            ];

            if ($record) {
                $sectionSchema[] = Html::make($this->buildNarasiHtml($narasiText, $isExpanded));
                $sectionSchema[] = Section::make('Foto')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Dokumentasi perkembangan untuk indikator ini.</div>')),
                                Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Belum ada foto yang diunggah.</div>'))
                                    ->hidden(fn () => ! blank($record?->foto)),
                            ]),
                        Html::make(fn () => $this->buildFotoHtml($record?->foto, $indikatorLabel))
                            ->hidden(fn () => blank($record?->foto)),
                    ])
                    ->columns(1);
                if ($canToggle) {
                    $sectionSchema[] = Actions::make([
                        Action::make('toggle_' . $indikator['id'])
                            ->label($isExpanded ? 'Tutup' : 'Lihat selengkapnya')
                            ->link()
                            ->action(fn () => $this->toggleExpanded($indikator['id'])),
                    ]);
                }
            } else {
                $sectionSchema[] = Html::make(new HtmlString('<div class="text-sm text-gray-900 dark:text-gray-100">Belum ada catatan untuk indikator ini pada semester yang dipilih.</div>'));
            }

            $components[] = Section::make($indikatorLabel)
                ->schema($sectionSchema)
                ->columnSpanFull();
        }

        return $schema->components($components);
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran / Semester')
                            ->options($this->getTahunAjaranOptions())
                            ->searchable()
                            ->placeholder('Semua')
                            ->live(),
                    ]),
            ])
            ->statePath('filters');
    }

    protected function getKognitifRecords(int $siswaId)
    {
        $allowedSiswaIds = $this->getAllowedSiswaIds();

        if (! in_array($siswaId, $allowedSiswaIds, true)) {
            return collect();
        }

        return PerkembanganKognitifModel::query()
            ->with(['indikator', 'guru'])
            ->where('siswa_id', $siswaId)
            ->when($this->filters['tahun_ajaran_id'] ?? null, fn ($q, $id) => $q->where('tahun_ajaran_id', $id))
            ->orderByDesc('created_at')
            ->get();
    }

    protected function buildSummary($records, ?int $tahunAjaranId): array
    {
        $semesterLabel = $this->getSemesterLabel($tahunAjaranId);
        $latestRecord = $records->first();
        $totalIndikator = $records
            ->pluck('indikator_id')
            ->unique()
            ->filter()
            ->count();

        return [
            'semester' => $semesterLabel ?? 'Semua Semester',
            'total_indikator' => $totalIndikator,
            'tanggal_terakhir' => $latestRecord?->created_at
                ? Carbon::parse($latestRecord->created_at)->format('d M Y')
                : '-',
            'guru_terakhir' => $latestRecord?->guru?->nama ?? '-',
        ];
    }

    protected function formatIndikatorLabel($record): string
    {
        $indikator = $record?->indikator;
        $label = $indikator?->aspek ?: $indikator?->deskripsi ?: '-';

        if (str_starts_with($label, 'Berisikan Penjelasan ')) {
            $label = substr($label, strlen('Berisikan Penjelasan '));
        }

        return $label;
    }

    protected function buildNarasiHtml(?string $text, bool $expanded): HtmlString
    {
        $text = trim($text ?? '');

        if ($text === '') {
            return new HtmlString('<p class="text-gray-900 dark:text-gray-100" style="margin: 0;">Belum ada narasi perkembangan.</p>');
        }

        $displayText = $expanded ? $text : $this->truncateText($text);
        $paragraphs = preg_split("/\r?\n\r?\n/", $displayText);

        $html = collect($paragraphs)
            ->map(function (string $paragraph): string {
                $safe = nl2br(e(trim($paragraph)));

                return '<p class="text-gray-900 dark:text-gray-100" style="text-indent: 2em; margin: 0 0 1em 0; text-align: justify; line-height: 1.6;">' . $safe . '</p>';
            })
            ->implode('');

        return new HtmlString($html);
    }

    protected function truncateText(string $text): string
    {
        $clean = trim($text);

        if (mb_strlen($clean) <= self::NARASI_LIMIT) {
            return $clean;
        }

        $snippet = mb_substr($clean, 0, self::NARASI_LIMIT);
        $snippet = rtrim($snippet);

        return $snippet . '...';
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

    protected function getSemesterLabel(?int $tahunAjaranId): ?string
    {
        if (! $tahunAjaranId) {
            return null;
        }

        return TahunAjaran::query()->find($tahunAjaranId)?->label;
    }

    protected function getDefaultTahunAjaranId(): ?int
    {
        $activeId = TahunAjaran::query()
            ->where('is_active', true)
            ->value('id');

        if ($activeId) {
            return $activeId;
        }

        $siswa = $this->getSelectedSiswa();

        if (! $siswa) {
            return null;
        }

        return PerkembanganKognitifModel::query()
            ->where('siswa_id', $siswa->id)
            ->latest('created_at')
            ->value('tahun_ajaran_id');
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

    protected function buildFotoHtml(?string $path, string $indikatorLabel): HtmlString
    {
        $url = $this->getFotoUrl($path);

        if ($url === '') {
            return new HtmlString('');
        }

        $alt = 'Foto perkembangan ' . $indikatorLabel;
        $html = '<div style="display: flex; justify-content: center;">' .
            '<div style="width: 100%; max-width: 520px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px;">' .
                '<img src="' . e($url) . '" alt="' . e($alt) . '" style="display: block; width: 100%; max-height: 320px; object-fit: contain; border-radius: 8px;" />' .
            '</div>' .
        '</div>';

        return new HtmlString($html);
    }

    public function toggleExpanded(int $indikatorId): void
    {
        $this->expandedId = $this->expandedId === $indikatorId ? null : $indikatorId;
    }
}
