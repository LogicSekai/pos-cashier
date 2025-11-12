@section('title', 'Dashboard')

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
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">Tenant Dashboard</h1>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Products</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #1e40af; margin-top: 0.5rem;">{{ $stats['total_products'] }}</p>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Categories</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #16a34a; margin-top: 0.5rem;">{{ $stats['total_categories'] }}</p>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Sales</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #dc2626; margin-top: 0.5rem;">{{ $stats['total_sales'] }}</p>
        </div>

        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Revenue</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #ca8a04; margin-top: 0.5rem;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Recent Sales</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Invoice</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Cashier</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Amount</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent_sales as $sale)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem;">{{ $sale->invoice_number }}</td>
                        <td style="padding: 0.75rem;">{{ $sale->user->name }}</td>
                        <td style="padding: 0.75rem;">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                        <td style="padding: 0.75rem;">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #6b7280;">
                            No sales yet. Start selling!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Quick Actions</h2>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('tenant.pos') }}" class="btn btn-primary">Open POS</a>
            <a href="{{ route('tenant.products') }}" class="btn btn-primary">Manage Products</a>
            <a href="{{ route('tenant.sales') }}" class="btn btn-primary">View Sales Report</a>
        </div>
    </div>
</div>

