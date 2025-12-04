<?php

namespace App\Filament\Resources\Walis\Schemas;

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
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->helperText('Pilih akun user yang sudah diberi role wali.'),

                TextInput::make('nama')
                    ->label('Nama Wali')
                    ->required()
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
