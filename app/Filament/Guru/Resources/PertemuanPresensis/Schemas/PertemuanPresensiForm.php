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
                    ->columnSpanFull(),

                TextInput::make('pertemuan_ke')
                    ->label('Pertemuan ke-')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal Pertemuan')
                    ->native(false)
                    ->default(now())
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
                    ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id),
            ]);
    }
}
