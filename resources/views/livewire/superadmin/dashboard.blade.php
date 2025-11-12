@section('title', 'Superadmin Dashboard')

@section('nav-items')
    <li><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
    <li><a href="{{ route('superadmin.tenants') }}">Tenants</a></li>
    <li><a href="{{ route('superadmin.users') }}">Users</a></li>
    <li>
        <form method="POST" action="{{ route('superadmin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: white; cursor: pointer; padding: 0.5rem 1rem;">Logout</button>
        </form>
    </li>
@endsection

<div>
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">Superadmin Dashboard</h1>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Tenants</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #1e40af; margin-top: 0.5rem;">{{ $stats['total_tenants'] }}</p>
        </div>
        
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase;">Total Users</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #16a34a; margin-top: 0.5rem;">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Quick Actions</h2>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('superadmin.tenants') }}" class="btn btn-primary">Manage Tenants</a>
            <a href="{{ route('superadmin.users') }}" class="btn btn-primary">Manage Users</a>
        </div>
    </div>
</div>

