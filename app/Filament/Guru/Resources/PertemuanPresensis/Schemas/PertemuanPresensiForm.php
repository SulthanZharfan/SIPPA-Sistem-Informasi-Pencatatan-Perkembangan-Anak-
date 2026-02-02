<?php

namespace App\Filament\Guru\Resources\PertemuanPresensis\Schemas;

use App\Models\Guru;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PertemuanPresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                // Kelas: hanya kelas yang diampu guru login
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship(
                        name: 'kelas',
                        titleAttribute: 'nama',
                        modifyQueryUsing: function (Builder $query) {
                            $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

                            $query->where('guru_id', $currentGuruId);
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('pertemuan_ke')
                    ->label('Pertemuan ke-')
                    ->numeric()
                    ->minValue(1)
                    ->rules(function (Get $get, $record) {
                        $kelasId = $get('kelas_id');
                        $tahunAjaranId = $get('tahun_ajaran_id');

                        if (! $kelasId || ! $tahunAjaranId) {
                            return [];
                        }

                        return [
                            Rule::unique('pertemuan_presensis', 'pertemuan_ke')
                                ->where('kelas_id', $kelasId)
                                ->where('tahun_ajaran_id', $tahunAjaranId)
                                ->ignore($record),
                        ];
                    })
                    ->validationMessages([
                        'unique' => 'Pertemuan ke- ini sudah ada pada tanggal lain di kelas dan tahun ajaran yang sama.',
                    ])
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal Pertemuan')
                    ->native(false)
                    ->default(now())
                    ->rules(function (Get $get, $record) {
                        $kelasId = $get('kelas_id');
                        $tahunAjaranId = $get('tahun_ajaran_id');

                        if (! $kelasId || ! $tahunAjaranId) {
                            return [];
                        }

                        return [
                            Rule::unique('pertemuan_presensis', 'tanggal')
                                ->where('kelas_id', $kelasId)
                                ->where('tahun_ajaran_id', $tahunAjaranId)
                                ->ignore($record),
                        ];
                    })
                    ->validationMessages([
                        'unique' => 'Tanggal pertemuan ini sudah ada pada kelas dan tahun ajaran yang sama.',
                    ])
                    ->required(),

                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required(),

                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->required(),

                // Field otomatis (tidak diisi guru)
                Hidden::make('guru_id')
                    ->default(fn () => Guru::where('user_id', Auth::id())->value('id')),

                Hidden::make('tahun_ajaran_id')
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id)
                    ->dehydrated()
                    ->live(),
            ]);
    }
}
