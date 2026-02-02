<?php

namespace App\Filament\Resources\Walis\Schemas;

use App\Models\Wali;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class WaliForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('user_id')
                    ->label('Akun Wali')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'email',
                        modifyQueryUsing: fn (Builder $query) => $query->role('wali'),
                    )
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Akun wali ini sudah digunakan.',
                    ])
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->helperText('Pilih akun user yang sudah diberi role wali.'),

                Select::make('salutation')
                    ->label('Panggilan')
                    ->options(Wali::salutationOptions())
                    ->placeholder('Tanpa panggilan')
                    ->native(false)
                    ->nullable(),

                TextInput::make('nama')
                    ->label('Nama Wali')
                    ->required()
                    ->helperText('Isi nama tanpa Bapak/Ibu.')
                    ->maxLength(255),

                TextInput::make('telepon')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),

                TextInput::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->maxLength(255),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
    }
}
