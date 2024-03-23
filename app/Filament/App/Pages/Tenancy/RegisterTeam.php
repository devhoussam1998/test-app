<?php

namespace App\Filament\App\Pages\Tenancy;

use App\Models\Team;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;

class RegisterTeam extends RegisterTenant
{
    protected static string $view = 'filament.app.pages.tenancy.register-team';

    public static function getLabel(): string
    {
        return __('Register Team');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->prefixIcon('heroicon-o-user-group')
                    ->placeholder('SpaceX')
                    ->regex('/^[a-zA-Z\s]*$/')
                    ->maxLength(255)
                    ->required()
                    ->validationAttribute(__('name'))
                    ->autocomplete('name')
                    ->autofocus()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state, Livewire $livewire, TextInput $component) {
                        $set('slug', Str::slug($state));

                        $livewire->validateOnly($component->getStatePath());
                    }),

                TextInput::make('slug')
                    ->label(__('Slug'))
                    ->prefixIcon('heroicon-o-globe-alt')
                    ->placeholder('spacex')
                    ->readonly()
                    ->unique(Team::class, 'slug')
                    ->maxLength(255)
                    ->required()
                    ->validationAttribute(__('slug'))
                    ->autocomplete()
                    ->live()
                    ->afterStateUpdated(fn (Livewire $livewire, TextInput $component) => $livewire->validateOnly($component->getStatePath())),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        $team->members()->attach(auth()->user());

        $notificationBody = __('The <span class="font-semibold text-primary-500 dark:text-primary-400">:team</span> team has been created successfully.', ['team' => $team->name]);

        Notification::make()
            ->success()
            ->title(__('Created Successfully'))
            ->body($notificationBody)
            ->send();

        return $team;
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('Register Team'))
            ->size(ActionSize::Large)
            ->color('primary')
            ->icon('heroicon-o-user-plus')
            ->submit('register');
    }
}
