<?php

namespace App\Filament\Resources\Books\Pages;

use App\Filament\Resources\Books\BookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Book')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => BookResource::form($form))
                ->modalHeading('Add New Book')
                ->modalSubmitActionLabel('Save Book')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('Book created successfully!')
                ->after(fn () => redirect()->to(BookResource::getUrl('index'))),
        ];
    }
}
