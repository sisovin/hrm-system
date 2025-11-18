<?php

namespace App\Filament\Hr\Pages;

use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected string $view = 'filament.hr.pages.profile';

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?int $navigationSort = 100;

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function fillForms(): void
    {
        $user = Auth::user();
        
        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
        ];
    }

    public function profileForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Information')
                    ->description('Update your account profile information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        FileUpload::make('avatar')
                            ->label('Profile Photo')
                            ->image()
                            ->avatar()
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(1024),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Update Password')
                    ->description('Ensure your account is using a long, random password to stay secure.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->confirmed()
                            ->rules(['regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/']),
                        
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required(),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();

        $user = Auth::user();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        Notification::make()
            ->title('Password updated successfully')
            ->success()
            ->send();
    }
}
