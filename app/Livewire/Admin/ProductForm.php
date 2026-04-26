<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductParameterValue;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public string $activeTab = 'basic';

    // Basic
    public string $modelNumber = '';
    public string $categoryId  = '';
    public string $slug        = '';
    public bool   $slugLocked  = false;

    // Content (translations)
    public string $nameUz        = '';
    public string $nameRu        = '';
    public string $nameEn        = '';
    public string $descriptionUz = '';
    public string $descriptionRu = '';
    public string $descriptionEn = '';

    // Parameters
    public array $selectedValues = [];

    // Photo
    public $photo;
    public ?string $existingPhoto = null;

    public function mount(?Product $product = null): void
    {
        if ($product && $product->exists) {
            $this->productId = $product->id;
            $this->modelNumber = $product->model_number;
            $this->categoryId  = (string) $product->category_id;
            $this->slug        = $product->slug;
            $this->slugLocked  = true;
            $this->existingPhoto = $product->photo ?? null;

            foreach ($product->translations as $t) {
                $this->{'name' . ucfirst($t->lang)}        = $t->name ?? '';
                $this->{'description' . ucfirst($t->lang)} = $t->description ?? '';
            }

            $this->selectedValues = $product->parameterValues->pluck('id')->map(fn($id) => (string)$id)->toArray();
        }
    }

    public function updatedModelNumber(): void
    {
        if (!$this->slugLocked) {
            $this->slug = Str::slug($this->modelNumber);
        }
    }

    public function updatedNameUz(): void
    {
        if (!$this->slugLocked && !$this->modelNumber) {
            $this->slug = Str::slug($this->nameUz);
        }
    }

    public function updatedCategoryId(): void
    {
        $this->selectedValues = [];
    }

    public function unlockSlug(): void
    {
        $this->slugLocked = false;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function save(): void
    {
        $this->validate([
            'categoryId'     => ['required', 'exists:categories,id'],
            'modelNumber'    => ['required', 'string', 'max:100'],
            'slug'           => [
                'required', 'string', 'max:150', 'regex:/^[a-z0-9-]+$/',
                $this->productId
                    ? "unique:products,slug,{$this->productId}"
                    : 'unique:products,slug',
            ],
            'nameUz'         => ['required', 'string', 'max:255'],
            'nameRu'         => ['required', 'string', 'max:255'],
            'nameEn'         => ['required', 'string', 'max:255'],
            'descriptionUz'  => ['nullable', 'string'],
            'descriptionRu'  => ['nullable', 'string'],
            'descriptionEn'  => ['nullable', 'string'],
            'photo'          => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = $this->existingPhoto;
        if ($this->photo) {
            $photoPath = $this->photo->store('products', 'public');
        }

        $data = [
            'category_id'  => $this->categoryId,
            'model_number' => $this->modelNumber,
            'slug'         => $this->slug,
            'photo'        => $photoPath,
        ];

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
        } else {
            $product = Product::create($data);
        }

        foreach (['uz', 'ru', 'en'] as $lang) {
            $product->translations()->updateOrCreate(['lang' => $lang], [
                'name'        => $this->{'name' . ucfirst($lang)},
                'description' => $this->{'description' . ucfirst($lang)},
            ]);
        }

        // Sync parameter values
        ProductParameterValue::where('product_id', $product->id)->delete();
        foreach ($this->selectedValues as $valueId) {
            ProductParameterValue::create([
                'product_id'         => $product->id,
                'parameter_value_id' => (int) $valueId,
            ]);
        }

        session()->flash('success', "«{$this->modelNumber}» " . ($this->productId ? 'yangilandi' : "qo'shildi"));
        $this->redirect(route('admin.products.index'), navigate: true);
    }

    #[Computed]
    public function categories()
    {
        return Category::with('translations')->get();
    }

    #[Computed]
    public function parameters()
    {
        if (!$this->categoryId) return collect();

        return Category::find($this->categoryId)
            ?->parameters()
            ->with(['translations', 'values.translations'])
            ->get() ?? collect();
    }

    #[Computed]
    public function slugExists(): bool
    {
        if (!$this->slug) return false;

        return Product::where('slug', $this->slug)
            ->when($this->productId, fn($q) => $q->where('id', '!=', $this->productId))
            ->exists();
    }

    public function render()
    {
        return view('livewire.admin.product-form');
    }
}
