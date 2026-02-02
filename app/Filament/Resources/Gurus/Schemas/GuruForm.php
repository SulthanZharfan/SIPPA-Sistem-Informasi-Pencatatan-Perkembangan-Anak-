<?php

namespace App\Filament\Resources\Gurus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\User;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('user_id')
                    ->label('Akun guru')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'email',
                        modifyQueryUsing: fn ($query) => $query->whereHas('roles', fn ($roles) => $roles->whereIn('name', ['guru', 'kepsek'])),
                    )
                    ->getOptionLabelFromRecordUsing(function (User $record): string {
                        $roles = $record->roles?->pluck('name')->map(fn (string $role) => ucfirst($role))->implode(', ') ?? '';
                        $suffix = $roles !== '' ? " ($roles)" : '';

                        return $record->email . $suffix;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nama')
                    ->label('Nama Guru')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'NIP sudah digunakan.',
                    ]),

                TextInput::make('telepon')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
    }
}
