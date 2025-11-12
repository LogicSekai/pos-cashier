<?php

namespace App\Livewire\Superadmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant;

class TenantManagement extends Component
{
    use WithPagination;

    public $name = '';
    public $domain = '';
    public $showModal = false;
    public $editingTenantId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'domain' => 'required|string|max:255|unique:domains,domain',
    ];

    public function createTenant()
    {
        $this->validate();

        $tenant = Tenant::create(['id' => $this->domain]);
        $tenant->domains()->create(['domain' => $this->domain . '.localhost']);

        $this->reset(['name', 'domain', 'showModal']);
        session()->flash('message', 'Tenant created successfully!');
    }

    public function deleteTenant($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            $tenant->delete();
            session()->flash('message', 'Tenant deleted successfully!');
        }
    }

    public function render()
    {
        $tenants = Tenant::with('domains')->paginate(10);

        return view('livewire.superadmin.tenant-management', compact('tenants'))
            ->extends('layouts.app')
            ->section('content');
    }
}
