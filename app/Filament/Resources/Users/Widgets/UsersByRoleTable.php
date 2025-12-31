<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UsersByRoleTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public string $role = 'admin';

    public function table(Table $table): Table
    {
        return UsersTable::configure($table)
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record) => UserResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return User::query()
            ->role($this->role)
            ->latest('id');
    }

    protected function getTableHeading(): ?string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'kepsek' => 'Kepala Sekolah',
            'guru' => 'Guru',
            'wali' => 'Wali Murid',
            default => 'Pengguna',
        };
    }
}
