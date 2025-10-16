<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * Halaman list Users dengan Create via modal
 */
class ListUsers extends ListRecords
{
    /**
     * Resource terkait untuk halaman ini.
     *
     * @var string
     */
    protected static string $resource = UserResource::class;

    /**
     * Header actions (Create via modal).
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add User')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->form(fn ($form) => UserResource::form($form))
                ->modalHeading('Add New User')
                ->modalSubmitActionLabel('Save User')
                ->modalCancelActionLabel('Cancel')
                ->successNotificationTitle('User created successfully!')
                ->after(fn () => redirect()->to(UserResource::getUrl('index'))),
        ];
    }
}