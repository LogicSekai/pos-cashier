<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role' => 'required|string',
    ];

    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if ($this->role) {
            $role = Role::firstOrCreate(['name' => $this->role]);
            $user->assignRole($role);
        }

        $this->reset(['name', 'email', 'password', 'role', 'showModal']);
        session()->flash('message', 'User created successfully!');
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            session()->flash('message', 'User deleted successfully!');
        }
    }

    public function render()
    {
        $users = User::with('roles')->paginate(10);

        return view('livewire.superadmin.user-management', compact('users'))
            ->extends('layouts.app')
            ->section('content');
    }
}
