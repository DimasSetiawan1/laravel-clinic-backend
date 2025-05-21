<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Models\Clinic;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Storage;
use Str;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('clinic_id')
                    ->label('Clinic')
                    ->options(Clinic::all()->pluck('name', 'id')),
                TextInput::make('name'),
                TextInput::make('email'),
                Select::make('role')
                    ->options([
                        'doctor' => 'Doctor',
                        'patient' => 'Patient',
                    ]),
                FileUpload::make('image')
                    ->disk('public')
                    ->preserveFilenames(true)
                    ->directory(function (callable $get) {
                        return Str::plural($get('role')); // doctors/patients
                    })
                    ->image()
                    ->previewable(true) // Wajib untuk preview
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file) => time() . '.' . $file->getClientOriginalExtension()
                    )
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, callable $get) {
                        $directory =  Str::plural($get('role'));
                        $path =  $file->storeAs(
                            $directory,
                            time() . '.' . $file->getClientOriginalExtension(),
                            'public'
                        );
                        return '/storage/' . $path;
                    })
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state === null) {
                            $set('image', null);
                        }
                    })
                    ->previewable(true),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->same('passwordConfirmation')
                    ->label(fn(string $context): string => $context === 'edit' ? 'New Password' : 'Password'),
                TextInput::make('passwordConfirmation')
                    ->password()
                    ->label('Password Confirmation')
                    ->required(fn(string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->dehydrated(false),

            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clinic.name')
                    ->label('Clinic Name')
                    ->sortable(),
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('role'),
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->getStateUsing(function ($record) {
                        $path = str_replace('/storage/', '', $record->image);
                        return $path;
                    })
            ])
            // ->modifyQueryUsing(function (Builder $query) {
            //     $query->where('role', '!=', 'admin');
            // })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
