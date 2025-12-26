<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Pages;

use App\Filament\Guru\Resources\PertemuanPresensis\PertemuanPresensiResource;
use App\Models\PertemuanPresensi;
use App\Models\Presensi;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\EmbeddedSchema;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;

class KelolaPresensi extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = PertemuanPresensiResource::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    public PertemuanPresensi $record;

    /** State form (dipakai Filament) */
    public ?array $data = [];

    public function mount(PertemuanPresensi $record): void
    {
        $this->record = $record;

        // Inisialisasi form dengan data siswa + status presensi
        $this->form->fill([
            'presensi' => $this->generatePresensiState(),
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
                                    ->content(fn () => $this->record->kelas->nama)
                                    ->columnSpan(2),

                                Placeholder::make('info_pertemuan')
                                    ->label('Pertemuan ke-')
                                    ->content(fn () => $this->record->pertemuan_ke)
                                    ->columnSpan(2),

                                Placeholder::make('info_tanggal')
                                    ->label('Tanggal')
                                    ->content(fn () => $this->record->tanggal->format('d-m-Y'))
                                    ->columnSpan(2),

                                Placeholder::make('info_jam')
                                    ->label('Jam')
                                    ->content(fn () => $this->record->jam_mulai->format('H:i').' - '.$this->record->jam_selesai->format('H:i'))
                                    ->columnSpan(2),

                                Placeholder::make('info_total_siswa')
                                    ->label('Total Siswa')
                                    ->content(fn () => $this->record->kelas->siswas()->count().' siswa')
                                    ->columnSpan(2),
                            ]),
                    ]),

                Section::make('Daftar Siswa')
                    ->description('Catat status kehadiran setiap siswa.')
                    ->schema([
                        Repeater::make('presensi')
                            ->label('Presensi Siswa')
                            ->columns(12)
                            ->schema([
                                Hidden::make('siswa_id'),

                                TextInput::make('nama')
                                    ->label('Nama Siswa')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(6),

                                Select::make('status_kehadiran')
                                    ->label('Status Kehadiran')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'izin'  => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alfa'  => 'Alfa',
                                    ])
                                    ->native(false)
                                    ->required()
                                    ->columnSpan(6),

                                TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Opsional')
                                    ->maxLength(255)
                                    ->columnSpan(12),
                            ])
                            ->addActionLabel('Tambah Presensi')
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
     * Generate data awal: list siswa di kelas + status kalau sudah pernah diisi
     */
    protected function generatePresensiState(): array
    {
        // ambil semua siswa di kelas pertemuan ini
        $siswas = $this->record->kelas->siswas()->orderBy('nama')->get();

        return $siswas->map(function ($siswa) {
            $existing = $this->record->presensis()
                ->where('siswa_id', $siswa->id)
                ->first();

            return [
                'siswa_id'          => $siswa->id,
                'nama'              => $siswa->nama,
                'status_kehadiran'  => $existing?->status_kehadiran,
                'keterangan'        => $existing?->keterangan,
            ];
        })->toArray();
    }

    /** Aksi simpan presensi */
    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state['presensi'] ?? [] as $row) {
            if (empty($row['siswa_id']) || empty($row['status_kehadiran'])) {
                continue;
            }

            Presensi::updateOrCreate(
                [
                    'pertemuan_presensi_id' => $this->record->id,
                    'siswa_id'              => $row['siswa_id'],
                ],
                [
                    'status_kehadiran' => $row['status_kehadiran'],
                    'guru_id'          => $this->record->guru_id,
                    'kelas_id'         => $this->record->kelas_id,
                    'tahun_ajaran_id'  => $this->record->tahun_ajaran_id,
                    'tanggal'          => $this->record->tanggal,
                    'keterangan'       => $row['keterangan'] ?? null,
                ]
            );
        }

        Notification::make()
            ->title('Presensi berhasil disimpan')
            ->success()
            ->send();

        $this->redirect(PertemuanPresensiResource::getUrl());
    }

    public function getTitle(): string
    {
        return 'Kelola Presensi Pertemuan';
    }

    public function getHeading(): string
    {
        return 'Kelola Presensi Pertemuan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelas '.$this->record->kelas->nama.' • Pertemuan '.$this->record->pertemuan_ke;
    }

    /** Tombol di bawah form (kalau mau pakai Form Actions bawaan) */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Presensi')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->submit('save'),

            Action::make('cancel')
                ->label('Batal')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(PertemuanPresensiResource::getUrl()),
        ];
    }
}
