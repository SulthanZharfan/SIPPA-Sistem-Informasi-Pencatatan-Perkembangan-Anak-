<?php

namespace App\Filament\Guru\Resources\PerkembanganFisiks\Schemas;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PerkembanganFisikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(2)->schema([
                    Select::make('siswa_id')
                        ->label('Siswa')
                        ->relationship(
                            name: 'siswa',
                            titleAttribute: 'nama',
                            modifyQueryUsing: function (Builder $query) {
                                $guruId = Auth::user()?->guru?->id ?? 0;

                                $query->whereHas('kelas', fn (Builder $kelas) => $kelas->where('guru_id', $guruId));
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            self::syncUmurBulan($set, $get);
                        }),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('tinggi_badan')
                        ->label('Tinggi Badan (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),

                    TextInput::make('berat_badan')
                        ->label('Berat Badan (kg)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),

                    TextInput::make('lingkar_kepala')
                        ->label('Lingkar Kepala (cm)')
                        ->numeric()
                        ->step(0.1)
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    DatePicker::make('tanggal_ukur')
                        ->label('Tanggal Ukur')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            self::syncUmurBulan($set, $get);
                        }),

                    TextInput::make('umur_bulan')
                        ->label('Umur (bulan)')
                        ->numeric()
                        ->afterStateHydrated(function ($state, callable $set, callable $get) {
                            // Pastikan umur terisi ulang saat form dibuka/di-edit.
                            $set('umur_bulan', self::calculateUmurBulan($get('siswa_id'), $get('tanggal_ukur')));
                        })
                        ->readOnly()
                        ->required(),
                ]),

                FileUpload::make('foto')
                    ->label('Foto (opsional)')
                    ->directory('foto-perkembangan-fisik')
                    ->image()
                    ->maxSize(2048)
                    ->columnSpanFull(),

                Hidden::make('guru_id')
                    ->default(fn () => Auth::user()?->guru?->id),

                Hidden::make('tahun_ajaran_id')
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id),

                Hidden::make('status_persetujuan')
                    ->default('menunggu'),
            ]);
    }

    /**
     * Hitung dan set umur dalam bulan berdasarkan siswa & tanggal ukur.
     */
    protected static function syncUmurBulan(callable $set, callable $get): void
    {
        $umur = self::calculateUmurBulan($get('siswa_id'), $get('tanggal_ukur'));

        $set('umur_bulan', $umur);
    }

    /**
     * Hitung umur dalam bulan, null jika data tidak valid.
     */
    public static function calculateUmurBulan(?int $siswaId, $tanggalUkur): ?int
    {
        if (!$siswaId || !$tanggalUkur) {
            return null;
        }

        $siswa = Siswa::find($siswaId);

        if (!$siswa?->tanggal_lahir) {
            return null;
        }

        $ukur = Carbon::parse($tanggalUkur);
        $lahir = Carbon::parse($siswa->tanggal_lahir);

        if ($ukur->lt($lahir)) {
            return null;
        }

        return $lahir->diffInMonths($ukur);
    }
}
