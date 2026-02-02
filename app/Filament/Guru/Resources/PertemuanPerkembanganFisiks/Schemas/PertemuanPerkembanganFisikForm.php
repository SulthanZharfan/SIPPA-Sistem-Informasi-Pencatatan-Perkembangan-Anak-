<?php

namespace App\Filament\Guru\Resources\PertemuanPerkembanganFisiks\Schemas;

use App\Models\Guru;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PertemuanPerkembanganFisikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pertemuan')
                    ->schema([
                        Select::make('kelas_id')
                            ->relationship(
                                name: 'kelas',
                                titleAttribute: 'nama',
                                modifyQueryUsing: function (Builder $query) {
                                    $currentGuruId = Guru::where('user_id', Auth::id())->value('id');

                                    $query->where('guru_id', $currentGuruId);
                                }
                            )
                            ->label('Kelas')
                            ->preload()
                            ->searchable()
                            ->live()
                            ->required(),

                        Select::make('tahun_ajaran_id')
                            ->relationship('tahunAjaran', 'tahun')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->label)
                            ->label('Tahun Ajaran')
                            ->default(fn () => TahunAjaran::where('is_active', 1)->first()?->id)
                            ->preload()
                            ->searchable()
                            ->live()
                            ->required(),

                        TextInput::make('pertemuan_ke')
                            ->label('Pertemuan Ke-')
                            ->numeric()
                            ->minValue(1)
                            ->rules(function (Get $get, $record) {
                                $kelasId = $get('kelas_id');
                                $tahunAjaranId = $get('tahun_ajaran_id');

                                if (! $kelasId || ! $tahunAjaranId) {
                                    return [];
                                }

                                return [
                                    Rule::unique('pertemuan_perkembangan_fisiks', 'pertemuan_ke')
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
                            ->rules(function (Get $get, $record) {
                                $kelasId = $get('kelas_id');
                                $tahunAjaranId = $get('tahun_ajaran_id');

                                if (! $kelasId || ! $tahunAjaranId) {
                                    return [];
                                }

                                return [
                                    Rule::unique('pertemuan_perkembangan_fisiks', 'tanggal')
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

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'   => 'Pending',
                                'approved'  => 'Disetujui',
                                'rejected'  => 'Ditolak',
                            ])
                            ->default('pending')
                            ->disabled()    // guru tidak bisa ubah manual
                            ->dehydrated(), // tapi nilai tetap tersimpan

                        // otomatis set guru_id sesuai user login
                        Hidden::make('guru_id')
                            ->default(fn () => Guru::where('user_id', Auth::id())->value('id')),
                    ])
                    ->columns(2),
            ]);
    }
}
