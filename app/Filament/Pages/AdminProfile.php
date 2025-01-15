<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class AdminProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.admin-profile';

    public $name;
    public $email;
    public $password;

    public function mount()
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save()
{
    $user = Auth::user();

    if (!$user) {
        Notification::make()
            ->title('User not found.')
            ->danger()
            ->send();

        return;
    }

    $this->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'nullable|string|min:8',
    ]);

    try {
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? bcrypt($this->password) : $user->password,
        ]);

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();
    } catch (\Exception $e) {
        Notification::make()
            ->title('An error occurred while updating the profile.')
            ->danger()
            ->send();
    }
}

}
