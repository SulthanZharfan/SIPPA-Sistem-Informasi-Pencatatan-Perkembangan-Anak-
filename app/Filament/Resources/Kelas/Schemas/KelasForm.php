<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Database\Eloquent\Builder;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('nama')
                    ->label('Nama Kelas')
                    ->placeholder('Misal: A1, B2, TK A')
                    ->required()
                    ->maxLength(255),

                TextInput::make('tingkat')
                    ->label('Tingkat Kelas')
                    ->placeholder('Misal: Kelompok A, Kelompok B')
                    ->required()
                    ->maxLength(255),

                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'tahun')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label)
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('guru_id')
                    ->label('Guru')
                    ->relationship('guru', 'nama')
                    ->getOptionLabelFromRecordUsing(function (Guru $record): string {
                        $kelas = $record->kelas()->latest('id')->first();
                        if (! $kelas) {
                            return $record->nama;
                        }

                        return $record->nama . ' (Sudah pegang kelas: ' . $kelas->nama . ')';
                    })
                    ->rules([
                        function (): \Closure {
                            return function (string $attribute, $value, \Closure $fail): void {
                                if (blank($value)) {
                                    return;
                                }

                                $alreadyAssigned = Kelas::query()
                                    ->where('guru_id', $value)
                                    ->when(request()->route('record'), fn (Builder $q, $recordId) => $q->where('id', '!=', $recordId))
                                    ->exists();

                                if ($alreadyAssigned) {
                                    $fail('Guru ini sudah terdaftar pada kelas lain.');
                                }
                            };
                        },
                    ])
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
