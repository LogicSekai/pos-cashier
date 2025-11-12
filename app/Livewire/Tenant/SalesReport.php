<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;

class SalesReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Sale::with(['user', 'saleItems.product'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->latest();

        $sales = $query->paginate(15);
        
        $stats = [
            'total_sales' => $query->count(),
            'total_revenue' => $query->sum('grand_total'),
            'average_sale' => $query->count() > 0 ? $query->sum('grand_total') / $query->count() : 0,
        ];

        return view('livewire.tenant.sales-report', compact('sales', 'stats'))
            ->extends('layouts.app')
            ->section('content');
    }
}
