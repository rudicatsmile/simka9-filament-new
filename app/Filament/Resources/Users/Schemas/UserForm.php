<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\CwspsRole;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/**
 * Skema Form untuk User (Create/Update via modal)
 */
class UserForm
{
    /**
     * Konfigurasi form untuk create/update.
     *
     * @param Schema $schema
     * @return Schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama pengguna')
                            ->helperText('Nama maksimal 255 karakter'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan email pengguna')
                            ->helperText('Email harus valid dan unik'),

                        TextInput::make('mobile')
                            ->label('No. HP')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Masukkan nomor HP')
                            ->helperText('Nomor HP pengguna (opsional)'),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->placeholder('Masukkan password')
                            // Wajib saat create, opsional saat edit; hanya kirim jika diisi
                            ->required(fn(string $context) => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText('Minimal 8 karakter; di-edit hanya jika perlu'),
                    ])
                    ->columns(2),

                Section::make('Role & Status')
                    ->schema([
                        Select::make('role_id')
                            ->label('Role')
                            ->options(
                                CwspsRole::where('status', true)
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->placeholder('Pilih role pengguna')
                            ->helperText('Role menentukan hak akses pengguna')
                            ->searchable(),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Pengguna aktif dapat mengakses sistem'),
                    ])
                    ->columns(2),
            ]);
    }
}
