<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class PosSystem extends Component
{
    public $cart = [];
    public $search = '';
    public $payment_method = 'cash';
    public $discount = 0;
    public $tax = 0;

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Product not available or out of stock!');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] < $product->stock) {
                $this->cart[$productId]['quantity']++;
            } else {
                session()->flash('error', 'Not enough stock available!');
                return;
            }
        } else {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->stock,
            ];
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        $product = Product::find($productId);
        if ($quantity > $product->stock) {
            session()->flash('error', 'Not enough stock available!');
            return;
        }

        $this->cart[$productId]['quantity'] = $quantity;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::transaction(function () {
            $total_amount = collect($this->cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $grand_total = $total_amount - $this->discount + $this->tax;

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad(Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT),
                'total_amount' => $total_amount,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'grand_total' => $grand_total,
                'payment_method' => $this->payment_method,
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }
        });

        session()->flash('message', 'Sale completed successfully!');
        $this->reset(['cart', 'discount', 'tax']);
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->where('stock', '>', 0)
            ->limit(20)
            ->get();

        $subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $total = $subtotal - $this->discount + $this->tax;

        return view('livewire.tenant.pos-system', compact('products', 'subtotal', 'total'))
            ->extends('layouts.app')
            ->section('content');
    }
}
