<?php

namespace App\Livewire\Tenant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryManagement extends Component
{
    use WithPagination;

    public $name, $description;
    public $showModal = false;
    public $editingCategoryId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function createCategory()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description', 'showModal']);
        session()->flash('message', 'Category created successfully!');
    }

    public function editCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $this->editingCategoryId = $categoryId;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showModal = true;
    }

    public function updateCategory()
    {
        $this->validate();

        $category = Category::findOrFail($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description', 'showModal', 'editingCategoryId']);
        session()->flash('message', 'Category updated successfully!');
    }

    public function deleteCategory($categoryId)
    {
        Category::findOrFail($categoryId)->delete();
        session()->flash('message', 'Category deleted successfully!');
    }

    public function render()
    {
        $categories = Category::withCount('products')->paginate(10);

        return view('livewire.tenant.category-management', compact('categories'))
            ->extends('layouts.app')
            ->section('content');
    }
}
