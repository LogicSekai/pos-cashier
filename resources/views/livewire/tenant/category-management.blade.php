@section('title', 'Category Management')

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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: bold;">Category Management</h1>
        <button wire:click="$set('showModal', true)" class="btn btn-primary">Add Category</button>
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
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Description</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Products</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">{{ $category->name }}</td>
                        <td style="padding: 1rem;">{{ $category->description ?? '-' }}</td>
                        <td style="padding: 1rem;">{{ $category->products_count }}</td>
                        <td style="padding: 1rem;">
                            <button wire:click="editCategory({{ $category->id }})" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; margin-right: 0.5rem;">Edit</button>
                            <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Are you sure?" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #6b7280;">
                            No categories found. Create your first category!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem;">
        {{ $categories->links() }}
    </div>

    @if ($showModal)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;">
            <div style="background: white; padding: 2rem; border-radius: 8px; width: 100%; max-width: 500px;">
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem;">{{ $editingCategoryId ? 'Edit' : 'Add' }} Category</h2>
                
                <form wire:submit.prevent="{{ $editingCategoryId ? 'updateCategory' : 'createCategory' }}">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Name</label>
                        <input type="text" wire:model="name" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        @error('name') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Description</label>
                        <textarea wire:model="description" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;"></textarea>
                        @error('description') <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" wire:click="$set('showModal', false)" class="btn" style="background: #6b7280; color: white;">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $editingCategoryId ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
