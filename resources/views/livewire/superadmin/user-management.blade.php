@section('title', 'User Management')

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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold;">User Management</h1>
        <button wire:click="$set('showModal', true)" class="btn btn-primary">Create User</button>
    </div>

    @if (session()->has('message'))
        <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('message') }}
        </div>
    @endif

    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Name</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Roles</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Created At</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">{{ $user->name }}</td>
                        <td style="padding: 1rem;">{{ $user->email }}</td>
                        <td style="padding: 1rem;">
                            @foreach($user->roles as $role)
                                <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td style="padding: 1rem;">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td style="padding: 1rem;">
                            <button 
                                wire:click="deleteUser({{ $user->id }})" 
                                wire:confirm="Are you sure you want to delete this user?"
                                class="btn btn-danger"
                                style="padding: 0.25rem 0.75rem; font-size: 0.875rem;"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">
                            No users found. Create your first user!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $users->links() }}
    </div>

    @if ($showModal)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;">
            <div style="background: white; padding: 2rem; border-radius: 8px; width: 100%; max-width: 500px;">
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">Create New User</h2>
                
                <form wire:submit.prevent="createUser">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Name</label>
                        <input type="text" wire:model="name" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        @error('name') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                        <input type="email" wire:model="email" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        @error('email') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password</label>
                        <input type="password" wire:model="password" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        @error('password') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Role</label>
                        <input type="text" wire:model="role" placeholder="admin, manager, etc." style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        @error('role') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" wire:click="$set('showModal', false)" class="btn" style="background: #6b7280; color: white;">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

