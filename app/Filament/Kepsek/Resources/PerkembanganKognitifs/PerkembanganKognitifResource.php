<?php

namespace App\Filament\Kepsek\Resources\PerkembanganKognitifs;

use App\Filament\Kepsek\Resources\PerkembanganKognitifs\Pages\ListPerkembanganKognitifs;
use App\Filament\Kepsek\Resources\PerkembanganKognitifs\Pages\ViewPerkembanganKognitif;
use App\Models\PerkembanganKognitif;
use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\PaginationMode;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class PerkembanganKognitifResource extends Resource
{
    protected static ?string $model = PerkembanganKognitif::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Perkembangan Kognitif';
    protected static ?string $pluralModelLabel = 'Perkembangan Kognitif';
    protected static string|UnitEnum|null $navigationGroup = 'Monitoring';

    public static function form(Schema $schema): Schema
    {
        // Kepsek tidak input/edit
        return $schema->schema([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make()
                ->schema([
                    Section::make('Informasi Umum')
                        ->schema([
                            TextEntry::make('siswa.nama')->label('Siswa'),
                            TextEntry::make('siswa.kelas.nama')->label('Kelas'),
                            TextEntry::make('guru.nama')->label('Guru'),
                            TextEntry::make('indikator.deskripsi')->label('Indikator'),
                            TextEntry::make('status_persetujuan')
                                ->label('Status Persetujuan')
                                ->badge()
                                ->formatStateUsing(fn (?string $state) => match ($state) {
                                    'disetujui' => 'Disetujui',
                                    'revisi' => 'Revisi',
                                    'menunggu' => 'Menunggu',
                                    default => $state ?? '-',
                                })
                                ->colors([
                                    'warning' => 'menunggu',
                                    'danger' => 'revisi',
                                    'success' => 'disetujui',
                                ]),
                            TextEntry::make('created_at')
                                ->label('Tanggal Input')
                                ->dateTime('d M Y H:i'),
                        ])
                        ->columns(2),
                ])
                ->columns(1),

            Section::make('Narasi Perkembangan')
                ->schema([
                    TextEntry::make('narasi')
                        ->label('')
                        ->markdown(false)
                        ->html()
                        ->formatStateUsing(function (?string $state): string {
                            $text = trim($state ?? '');

                            if ($text === '') {
                                return '';
                            }

                            $paragraphs = preg_split("/\r?\n\r?\n/", $text);

                            return collect($paragraphs)
                                ->map(function (string $paragraph): string {
                                    $safe = nl2br(e(trim($paragraph)));

                                    return '<p style="text-indent: 2em; margin: 0 0 1em 0;">' . $safe . '</p>';
                                })
                                ->implode('');
                        })
                        ->extraAttributes(['style' => 'text-align: justify; line-height: 1.5;']),
                ])
                ->columnSpanFull(),

            Section::make('Foto')
                ->schema([
                    ImageEntry::make('foto')
                        ->label('')
                        ->height('200px')
                        ->visibility('public'),
                ])
                ->hidden(fn ($record) => blank($record?->foto))
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50])
            ->extremePaginationLinks()
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('indikator.aspek')
                    ->label('Indikator')
                    ->getStateUsing(function ($record): string {
                        $indikator = $record?->indikator;
                        $label = $indikator?->aspek ?: $indikator?->deskripsi ?: '-';

                        if (str_starts_with($label, 'Berisikan Penjelasan ')) {
                            $label = substr($label, strlen('Berisikan Penjelasan '));
                        }

                        return $label;
                    })
                    ->limit(40)
                    ->tooltip(fn ($record) => $record?->indikator?->deskripsi),
                Tables\Columns\BadgeColumn::make('status_persetujuan')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        'menunggu' => 'Menunggu',
                        default => $state ?? '-',
                    })
                    ->colors([
                        'warning' => 'menunggu',
                        'danger' => 'revisi',
                        'success' => 'disetujui',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Actions\ViewAction::make()->label('Detail'),
                Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PerkembanganKognitif $record) => $record->status_persetujuan !== 'disetujui')
                    ->requiresConfirmation()
                    ->action(fn (PerkembanganKognitif $record) => $record->update(['status_persetujuan' => 'disetujui'])),
                Actions\Action::make('revisi')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (PerkembanganKognitif $record) => $record->status_persetujuan !== 'revisi')
                    ->requiresConfirmation()
                    ->action(fn (PerkembanganKognitif $record) => $record->update(['status_persetujuan' => 'revisi'])),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'siswa.kelas',
                'guru',
                'indikator',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPerkembanganKognitifs::route('/'),
            'view' => ViewPerkembanganKognitif::route('/{record}'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}
