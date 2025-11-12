<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_sales' => Sale::count(),
            'total_revenue' => Sale::sum('grand_total'),
        ];

        $recent_sales = Sale::with('user')->latest()->take(5)->get();

        return view('livewire.tenant.dashboard', compact('stats', 'recent_sales'))
            ->extends('layouts.app')
            ->section('content');
    }
}
