<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                Section::make('Input Data Buku')
                    ->description('Masukkan data buku di bawah ini')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Buku')
                            ->required()
                            // ->maxLength(255)
                            ->placeholder('Masukkan judul buku')
                            ->helperText('Judul buku maksimal 255 karakter'),

                        TextInput::make('author')
                            ->label('Penulis')
                            ->required()
                            // ->maxLength(255)
                            ->placeholder('Masukkan nama penulis')
                            ->helperText('Nama penulis maksimal 255 karakter'),

                        // Textarea::make('description')
                        //     ->label('Deskripsi')
                        //     ->required()
                        //     ->rows(4)
                        //     ->maxLength(1000)
                        //     ->placeholder('Masukkan deskripsi buku')
                        //     ->helperText('Deskripsi buku maksimal 1000 karakter'),

                        //add upload file
                        // FileUpload::make('image')
                        //     ->label('Gambar Buku')
                        //     ->required()
                        //     ->image()
                        //     ->maxSize(1024 * 10)
                        //     ->placeholder('Masukkan gambar buku')
                        //     ->helperText('Gambar buku maksimal 10MB'),

                        FileUpload::make('image')
                            ->label('File Buku')
                            ->required()
                            ->acceptedFileTypes(['application/pdf', 'image/*']) // Menerima PDF dan semua tipe gambar
                            ->maxSize(1024 * 10) // Maksimal 10MB
                            ->placeholder('Upload file PDF atau gambar')
                            ->helperText('Format yang didukung: PDF, JPG, PNG, GIF. Maksimal 10MB')
                            ->validationMessages([
                                'accepted_file_types' => 'File harus berformat PDF atau gambar (JPG, PNG, GIF)',
                                'max_size' => 'Ukuran file tidak boleh lebih dari 10MB',
                            ])
                            ->columnSpanFull()
                            ->preserveFilenames() // Menyimpan nama file asli
                            ->directory('book-files') // Menyimpan di folder terpisah
                            ->visibility('public'), // File dapat diakses publik

                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->maxLength(1000)
                            ->placeholder('Masukkan deskripsi buku')
                            ->helperText('Deskripsi buku maksimal 1000 karakter'),
                    ])


            ]);
    }
}
