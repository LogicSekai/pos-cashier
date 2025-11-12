<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'total_users' => User::count(),
        ];

        return view('livewire.superadmin.dashboard', compact('stats'))
            ->extends('layouts.app')
            ->section('content');
    }
}
