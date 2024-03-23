<?php

namespace App\Filament\App\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    protected static string $view = 'filament.app.pages.tenancy.edit-team-profile';

    public static function getLabel(): string
    {
        return __('Team Profile');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('slug')
                    ->readonly()
                    ->required(),
            ]);
    }
}
