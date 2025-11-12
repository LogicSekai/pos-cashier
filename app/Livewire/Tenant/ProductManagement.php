<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductManagement extends Component
{
    use WithPagination;

    public $name, $description, $sku, $price, $stock, $category_id;
    public $showModal = false;
    public $editingProductId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sku' => 'required|string|unique:products,sku',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
    ];

    public function createProduct()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ]);

        $this->reset(['name', 'description', 'sku', 'price', 'stock', 'category_id', 'showModal']);
        session()->flash('message', 'Product created successfully!');
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $this->editingProductId = $productId;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->sku = $product->sku;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->showModal = true;
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $this->editingProductId,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($this->editingProductId);
        $product->update([
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ]);

        $this->reset(['name', 'description', 'sku', 'price', 'stock', 'category_id', 'showModal', 'editingProductId']);
        session()->flash('message', 'Product updated successfully!');
    }

    public function deleteProduct($productId)
    {
        Product::findOrFail($productId)->delete();
        session()->flash('message', 'Product deleted successfully!');
    }

    public function render()
    {
        $products = Product::with('category')->paginate(10);
        $categories = Category::all();

        return view('livewire.tenant.product-management', compact('products', 'categories'))
            ->extends('layouts.app')
            ->section('content');
    }
}
