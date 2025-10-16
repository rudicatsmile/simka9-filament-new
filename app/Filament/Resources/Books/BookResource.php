<?php

namespace App\Filament\Resources\Books;

use UnitEnum;
use BackedEnum;
use App\Models\Book;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\Books\Pages\EditBook;
use App\Filament\Resources\Books\Pages\ListBooks;
use App\Filament\Resources\Books\Pages\CreateBook;
use App\Filament\Resources\Books\Schemas\BookForm;
use App\Filament\Resources\Books\Tables\BooksTable;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationLabel = 'Book';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return BookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // return BooksTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                TextColumn::make('author')
                    ->icon(Heroicon::Envelope)
                    // ->iconPosition(IconPosition::After)
                    ->searchable()
                    ->searchable(isIndividual: true)
                    ->sortable(),

                ImageColumn::make('image')
                    ->width(100),

                // ViewColumn::make('image')
                //     ->label('File')
                //     ->view('tables.columns.file-preview')
                //     ->alignCenter()
                //     ->searchable(false)
                //     ->toggleable(true),

                TextColumn::make('description')
                    ->searchable()
                    ->searchable(isIndividual: true)        //Jika baris ini diatas searchable(), maka tidak tampil
                    ->sortable()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->color('warning')
                    ->form(fn(Schema $schema) => static::form($schema))
                    ->modalHeading('Edit Book')
                    ->modalSubmitActionLabel('Save Changes')
                    ->modalCancelActionLabel('Cancel')
                    ->successNotificationTitle('Book updated successfully!')
                    ->after(fn() => redirect()->to(static::getUrl('index'))),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(
                        function (Model $record) {
                            if ($record->author === 'Emha Ainun Nadjib') {
                                Notification::make()
                                    ->title('Operation failed')
                                    ->body('You cannot delete this record')
                                    ->danger()
                                    ->send();
                                return;
                            }


                            //---
                            try {
                                //delete the image from storage
                                Storage::disk('public')->delete($record->image);
                                $record->delete();
                                //show success notification
                                Notification::make()
                                    ->title('Deleted successfully')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                //show error notification
                                Notification::make()
                                    ->title('Error')
                                    ->body('An error occurred while deleting the book.')
                                    ->danger()
                                    ->send();
                            }

                        }
                    )
                    ->modalHeading('Delete Book')
                    ->modalDescription('Are you sure you want to delete this book? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->modalCancelActionLabel('Cancel'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Books')
                        ->modalDescription('Are you sure you want to delete the selected books? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them')
                        ->modalCancelActionLabel('Cancel'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
            // 'create' => CreateBook::route('/create'), // Commented out - using modal instead
            // 'edit' => EditBook::route('/{record}/edit'), // Commented out - using modal instead
        ];
    }
}
