<?php

namespace App\Filament\Guru\Resources\Presensis\Schemas;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                // Pilih siswa - hanya siswa dari kelas yang diajar guru yang login
                Select::make('siswa_id')
                    ->label('Siswa')
                    ->relationship(
                        name: 'siswa',
                        titleAttribute: 'nama',
                        modifyQueryUsing: function (Builder $query) {
                            $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

                            $query->whereHas('kelas', function (Builder $kelasQuery) use ($currentGuruId) {
                                $kelasQuery->where('guru_id', $currentGuruId);
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Set kelas_id otomatis mengikuti kelas siswa
                        $siswa = Siswa::find($state);
                        $set('kelas_id', $siswa?->kelas_id);
                    }),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->native(false)
                    ->required(),

                Select::make('status_kehadiran')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin'  => 'Izin',
                        'sakit' => 'Sakit',
                        'alfa'  => 'Alfa',
                    ])
                    ->required(),

                // Field otomatis (hidden)
                Hidden::make('guru_id')
                    ->default(fn () => Guru::where('user_id', Auth::id())->value('id')),

                Hidden::make('kelas_id'),

                Hidden::make('tahun_ajaran_id')
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id),
            ]);
    }
}
