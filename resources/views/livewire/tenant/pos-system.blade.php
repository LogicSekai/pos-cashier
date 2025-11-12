@section('title', 'POS System')

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
    <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">POS System</h1>

    @if (session()->has('message'))
        <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div style="background: #fee2e2; border: 1px solid #dc2626; color: #991b1b; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Product Search and Display -->
        <div>
            <div style="margin-bottom: 1rem;">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search products by name or SKU..." 
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; font-size: 1rem;"
                >
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @foreach($products as $product)
                    <div 
                        wire:click="addToCart({{ $product->id }})" 
                        style="background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.2s;"
                        onmouseover="this.style.transform='scale(1.05)'" 
                        onmouseout="this.style.transform='scale(1)'"
                    >
                        <h3 style="font-weight: 600; margin-bottom: 0.5rem;">{{ $product->name }}</h3>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $product->sku }}</p>
                        <p style="font-weight: bold; color: #1e40af; margin-bottom: 0.25rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p style="font-size: 0.875rem; color: #6b7280;">Stock: {{ $product->stock }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cart -->
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: fit-content;">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem;">Cart</h2>
            
            @if(empty($cart))
                <p style="text-align: center; color: #6b7280; padding: 2rem;">Cart is empty</p>
            @else
                <div style="margin-bottom: 1rem;">
                    @foreach($cart as $productId => $item)
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 0.75rem 0;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div style="flex: 1;">
                                    <p style="font-weight: 600;">{{ $item['name'] }}</p>
                                    <p style="color: #6b7280; font-size: 0.875rem;">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                                <button 
                                    wire:click="removeFromCart({{ $productId }})" 
                                    style="background: #dc2626; color: white; border: none; border-radius: 4px; padding: 0.25rem 0.5rem; cursor: pointer;"
                                >×</button>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})" class="btn btn-primary" style="padding: 0.25rem 0.5rem;">-</button>
                                <span style="padding: 0.25rem 0.75rem; background: #f3f4f6; border-radius: 4px;">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})" class="btn btn-primary" style="padding: 0.25rem 0.5rem;">+</button>
                                <span style="margin-left: auto; font-weight: 600;">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 2px solid #e5e7eb; padding-top: 1rem; margin-top: 1rem;">
                    <div style="margin-bottom: 0.5rem;">
                        <label style="display: block; margin-bottom: 0.25rem; font-weight: 500; font-size: 0.875rem;">Discount</label>
                        <input type="number" wire:model.live="discount" step="0.01" min="0" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.25rem; font-weight: 500; font-size: 0.875rem;">Tax</label>
                        <input type="number" wire:model.live="tax" step="0.01" min="0" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Subtotal:</span>
                        <span style="font-weight: 600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-top: 0.5rem; border-top: 1px solid #e5e7eb;">
                        <span style="font-size: 1.25rem; font-weight: bold;">Total:</span>
                        <span style="font-size: 1.25rem; font-weight: bold; color: #1e40af;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Payment Method</label>
                        <select wire:model="payment_method" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 4px;">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <button wire:click="checkout" class="btn btn-primary" style="width: 100%;">Complete Sale</button>
                </div>
            @endif
        </div>
    </div>
</div>
