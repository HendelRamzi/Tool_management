<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Users\Pages\EditUser;
use Date;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Code'))
                    ->readOnly()
                    ->visible(fn($livewire) => $livewire instanceof EditUser)
                    ->autocomplete(),
                TextInput::make('email')
                    ->label(__('Email Address'))
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->label(__('User role'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
                TextInput::make('password')
                    ->label(__('password'))
                    ->password()
                    ->columnSpanFull()
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->required(),
                Section::make(__('Personal Information'))
                    ->description(__('Enter the personal details of the user.'))
                    ->aside()
                    ->columnSpanFull()
                    ->relationship('personal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('First Name'))
                            ->required()
                            ->placeholder(__('First Name')),
                        TextInput::make('last_name')
                            ->label(__('Last Name'))
                            ->required()
                            ->placeholder(__('Last Name')),
                        DatePicker::make('birthday')
                            ->label(__('Birthday'))
                            ->placeholder(__('Select the birthday'))
                            ->maxDate(now()->subYears(18))
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        Textarea::make('address')
                            ->placeholder(__('Address'))
                            ->columnSpanFull(),
                    ]),


            ]);
    }
}
