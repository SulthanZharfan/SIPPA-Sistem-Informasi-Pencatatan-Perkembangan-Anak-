<?php

namespace App\Filament\Wali\Pages;

use App\Models\RaporDigital as RaporDigitalModel;
use App\Models\Wali;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BackedEnum;
use UnitEnum;

class RaporDigital extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationLabel = 'Rapor Digital';

    protected static ?string $title = 'Rapor Digital';

    protected static string|UnitEnum|null $navigationGroup = null;

    protected string $view = 'filament-panels::pages.page';

    protected ?Wali $wali = null;

    public function mount(): void
    {
        $this->wali = Wali::with(['siswas'])
            ->where('user_id', Auth::id())
            ->first();

        if (! $this->wali) {
            abort(403);
        }

        $this->mountInteractsWithTable();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Rapor Digital')
                ->description('Rapor per semester yang sudah diterbitkan sekolah.')
                ->schema([
                    EmbeddedTable::make(),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->getRaporQuery())
            ->columns([
                TextColumn::make('tahunAjaran.label')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                TextColumn::make('periode')
                    ->label('Semester')
                    ->sortable(),
                TextColumn::make('tanggal_generate')
                    ->label('Tanggal Terbit')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Published' : 'Draft')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'tahun')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label),
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        'ganjil' => 'Ganjil',
                        'genap' => 'Genap',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $semester = $data['value'] ?? null;
                        if (! $semester) {
                            return $query;
                        }

                        $keyword = $semester === 'ganjil' ? 'Ganjil' : 'Genap';

                        return $query->where('periode', 'like', '%' . $keyword . '%');
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (RaporDigitalModel $record): void {
                        if (! $record->file_path) {
                            Notification::make()
                                ->title('File rapor belum tersedia.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $disk = config('filesystems.default');
                        if (! Storage::disk($disk)->exists($record->file_path)) {
                            Notification::make()
                                ->title('File rapor tidak ditemukan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $url = Storage::disk($disk)->url($record->file_path);
                        $this->redirect($url, navigate: false);
                    }),
            ])
            ->defaultSort('tanggal_generate', 'desc')
            ->searchPlaceholder('Cari periode');
    }

    protected function getRaporQuery(): Builder
    {
        $allowedSiswaIds = $this->wali?->siswas?->pluck('id')->all() ?? [];

        if (empty($allowedSiswaIds)) {
            return RaporDigitalModel::query()->whereRaw('1 = 0');
        }

        return RaporDigitalModel::query()
            ->with(['tahunAjaran'])
            ->whereIn('siswa_id', $allowedSiswaIds)
            ->whereNotNull('file_path');
    }
}
