@section('title', 'Sales Report')

@section('nav-items')
    <li><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('tenant.pos') }}">POS</a></li>
    <li><a href="{{ route('tenant.products') }}">Products</a></li>
    <li><a href="{{ route('tenant.categories') }}">Categories</a></li>
    <li><a href="{{ route('tenant.sales') }}">Sales Report</a></li>
    <li>
        <form method="POST" action="{{ route('tenant.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: white; cursor: pointer; padding: 0.5rem 1rem;">Logout</button>
        </form>
    </li>
@endsection

<div>
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">Sales Report</h1>

    <!-- Filter -->
    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Start Date</label>
                <input type="date" wire:model.live="startDate" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">End Date</label>
                <input type="date" wire:model.live="endDate" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Sales</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #1e40af; margin-top: 0.5rem;">{{ $stats['total_sales'] }}</p>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Revenue</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #16a34a; margin-top: 0.5rem;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Average Sale</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #ca8a04; margin-top: 0.5rem;">Rp {{ number_format($stats['average_sale'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Sales Table -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Invoice</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Date</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Cashier</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Items</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Total</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Payment</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">{{ $sale->invoice_number }}</td>
                        <td style="padding: 1rem;">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        <td style="padding: 1rem;">{{ $sale->user->name }}</td>
                        <td style="padding: 1rem;">{{ $sale->saleItems->count() }} items</td>
                        <td style="padding: 1rem; font-weight: 600;">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                        <td style="padding: 1rem;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                                {{ ucfirst($sale->payment_method) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">
                            No sales found for the selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $sales->links() }}
    </div>
</div>
