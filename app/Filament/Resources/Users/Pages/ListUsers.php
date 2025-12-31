<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Users\Widgets\UsersByRoleTable;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Livewire::make(UsersByRoleTable::class, fn () => ['role' => 'admin'])->key('users-admin'),
            Livewire::make(UsersByRoleTable::class, fn () => ['role' => 'kepsek'])->key('users-kepsek'),
            Livewire::make(UsersByRoleTable::class, fn () => ['role' => 'guru'])->key('users-guru'),
            Livewire::make(UsersByRoleTable::class, fn () => ['role' => 'wali'])->key('users-wali'),
        ]);
    }

    public function getTitle(): string
    {
        return 'Daftar Pengguna';
    }

    public function getHeading(): string
    {
        return 'Daftar Pengguna';
    }

    public function getBreadcrumb(): string
    {
        return 'Pengguna';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Pengguna'),
        ];
    }
}
