<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Pages;

use App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\PertemuanPerkembanganFisikResource;
use App\Filament\Guru\Resources\PerkembanganFisiks\Schemas\PerkembanganFisikForm;
use App\Models\PerkembanganFisik;
use App\Models\PertemuanPerkembanganFisik;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class KelolaPerkembanganFisik extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = PertemuanPerkembanganFisikResource::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    public PertemuanPerkembanganFisik $record;

    /** State form (dipakai Filament) */
    public ?array $data = [];

    public function mount(PertemuanPerkembanganFisik $record): void
    {
        $this->record = $record;

        $this->form->fill([
            'perkembangan' => $this->generatePerkembanganState(),
        ]);
    }

    /** Schema form */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Info Pertemuan')
                    ->description('Detail jadwal pertemuan ini.')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Placeholder::make('info_kelas')
                                    ->label('Kelas')
                                    ->content(fn () => $this->record->kelas?->nama)
                                    ->columnSpan(2),

                                Placeholder::make('info_pertemuan')
                                    ->label('Pertemuan ke-')
                                    ->content(fn () => $this->record->pertemuan_ke)
                                    ->columnSpan(2),

                                Placeholder::make('info_tanggal')
                                    ->label('Tanggal')
                                    ->content(fn () => $this->record->tanggal?->format('d-m-Y'))
                                    ->columnSpan(2),

                                Placeholder::make('info_jam')
                                    ->label('Jam')
                                    ->content(fn () => $this->record->jam_mulai?->format('H:i').' - '.$this->record->jam_selesai?->format('H:i'))
                                    ->columnSpan(2),
                            ]),
                    ]),

                Section::make('Perkembangan Fisik Siswa')
                    ->description('Catat hasil pengukuran setiap siswa.')
                    ->schema([
                        Repeater::make('perkembangan')
                            ->label('Perkembangan Fisik')
                            ->columns(12)
                            ->schema([
                                Hidden::make('siswa_id'),

                                TextInput::make('nama')
                                    ->label('Nama Siswa')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(4),

                                DatePicker::make('tanggal_ukur')
                                    ->label('Tanggal Ukur')
                                    ->native(false)
                                    ->default(fn () => $this->record->tanggal)
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        $set(
                                            'umur_bulan',
                                            PerkembanganFisikForm::calculateUmurBulan(
                                                $get('siswa_id'),
                                                $state,
                                            )
                                        );
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $set(
                                            'umur_bulan',
                                            PerkembanganFisikForm::calculateUmurBulan(
                                                $get('siswa_id'),
                                                $state,
                                            )
                                        );
                                    })
                                    ->columnSpan(4),

                                TextInput::make('umur_bulan')
                                    ->label('Umur (bulan)')
                                    ->numeric()
                                    ->readOnly()
                                    ->columnSpan(4),

                                TextInput::make('tinggi_badan')
                                    ->label('Tinggi Badan (cm)')
                                    ->numeric()
                                    ->step(0.1)
                                    ->required()
                                    ->columnSpan(4),

                                TextInput::make('berat_badan')
                                    ->label('Berat Badan (kg)')
                                    ->numeric()
                                    ->step(0.1)
                                    ->required()
                                    ->columnSpan(4),

                                TextInput::make('lingkar_kepala')
                                    ->label('Lingkar Kepala (cm)')
                                    ->numeric()
                                    ->step(0.1)
                                    ->columnSpan(4),

                                FileUpload::make('foto')
                                    ->label('Foto (opsional)')
                                    ->directory('foto-perkembangan-fisik')
                                    ->image()
                                    ->maxSize(2048)
                                    ->columnSpan(12),
                            ])
                            ->addActionLabel('Tambah Perkembangan')
                            ->disableItemDeletion()
                            ->disableItemCreation()
                            ->disableItemMovement()
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([
                EmbeddedSchema::make('form'),
            ])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions())
                        ->alignment(Alignment::End)
                        ->fullWidth(false),
                ]),
        ]);
    }

    /**
     * Generate data awal: list siswa di kelas + hasil ukur kalau sudah pernah diisi
     */
    protected function generatePerkembanganState(): array
    {
        $siswas = $this->record->kelas->siswas()->orderBy('nama')->get();

        return $siswas->map(function ($siswa) {
            $existing = $this->record->perkembanganFisiks()
                ->where('siswa_id', $siswa->id)
                ->first();

            return [
                'siswa_id'       => $siswa->id,
                'nama'           => $siswa->nama,
                'tanggal_ukur'   => $existing?->tanggal_ukur ?? $this->record->tanggal,
                'umur_bulan'     => PerkembanganFisikForm::calculateUmurBulan(
                    $siswa->id,
                    $existing?->tanggal_ukur ?? $this->record->tanggal,
                ),
                'tinggi_badan'   => $existing?->tinggi_badan,
                'berat_badan'    => $existing?->berat_badan,
                'lingkar_kepala' => $existing?->lingkar_kepala,
                'foto'           => $existing?->foto,
            ];
        })->toArray();
    }

    /** Aksi simpan perkembangan fisik */
    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state['perkembangan'] ?? [] as $row) {
            if (empty($row['siswa_id'])) {
                continue;
            }

            PerkembanganFisik::updateOrCreate(
                [
                    'pertemuan_perkembangan_fisik_id' => $this->record->id,
                    'siswa_id'                        => $row['siswa_id'],
                ],
                [
                    'guru_id'          => $this->record->guru_id,
                    'tahun_ajaran_id'  => $this->record->tahun_ajaran_id,
                    'tanggal_ukur'     => $row['tanggal_ukur'] ?? $this->record->tanggal,
                    'umur_bulan'       => PerkembanganFisikForm::calculateUmurBulan(
                        $row['siswa_id'],
                        $row['tanggal_ukur'] ?? $this->record->tanggal,
                    ),
                    'tinggi_badan'     => $row['tinggi_badan'],
                    'berat_badan'      => $row['berat_badan'],
                    'lingkar_kepala'   => $row['lingkar_kepala'],
                    'foto'             => $row['foto'] ?? null,
                ]
            );
        }

        Notification::make()
            ->title('Perkembangan fisik berhasil disimpan')
            ->success()
            ->send();

        // reload agar nilai terbaru terisi kembali ke form
        $this->form->fill([
            'perkembangan' => $this->generatePerkembanganState(),
        ]);
    }

    public function getTitle(): string
    {
        return 'Kelola Perkembangan Fisik';
    }

    public function getHeading(): string
    {
        return 'Kelola Perkembangan Fisik';
    }

    public function getSubheading(): ?string
    {
        $kelas = $this->record->kelas?->nama;

        return $kelas ? 'Kelas '.$kelas.' • Pertemuan '.$this->record->pertemuan_ke : null;
    }

    /** Tombol di bawah form */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->submit('save'),

            Action::make('cancel')
                ->label('Batal')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(PertemuanPerkembanganFisikResource::getUrl()),
        ];
    }
}
