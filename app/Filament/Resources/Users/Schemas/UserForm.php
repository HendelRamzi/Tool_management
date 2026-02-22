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
                    ->label('Code')
                    ->readOnly()
                    ->visible(fn($livewire) => $livewire instanceof EditUser)
                    ->autocomplete(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                TextInput::make('password')
                    ->password()
                    ->columnSpanFull()
                    ->visible(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->required(),
                Section::make('Personal Information')
                    ->description('Enter the personal details of the user.')
                    ->aside()
                    ->columnSpanFull()
                    ->relationship('personal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->placeholder('First Name'),
                        TextInput::make('last_name')
                            ->required()
                            ->placeholder('Last Name'),
                        DatePicker::make('birthday')
                            ->label('Birthday')
                            ->placeholder('Select the birthday')
                            ->maxDate(now()->subYears(18))
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        Textarea::make('address')
                            ->placeholder('Address')
                            ->columnSpanFull(),
                    ]),


            ]);
    }
}
