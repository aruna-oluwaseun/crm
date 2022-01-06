<?php

namespace App\Http\Livewire\Admin;

use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddProductAttribute extends Component
{
    public $product_id = null; // the product attribute are being added to
    public $attributes = null;
    public $products = null;
    public $selected_product = null;
    public $product_attributes = null;

    protected $listeners = [
        'set:product-id' => 'setProductId',
        'set:selected-product' => 'setSelectedProduct'
    ];

    public function mount()
    {
        $this->attributes = Attribute::all();
        $this->products = Product::active()->isAvailableOnline()->get();
    }

    public function setProductId( $product_id )
    {
        $this->product_id = $product_id;
        $this->dispatchBrowserEvent('product-id-added', ['productId' => $this->product_id]);
    }

    public function setSelectedProduct( $product_id )
    {
        $this->selected_product = $product_id;
        $product = Product::with(['attributes'])->find($product_id);

        if($product && $product->attributes->count())
        {
            $this->product_attributes = $product->attributes;
        }
        else
        {
            $this->product_attributes = null;
        }

        $this->dispatchBrowserEvent('product-updated', ['attributes' => $this->product_attributes,'title' => isset($product->title) ? $product->title : '']);
    }

    public function render()
    {
        return view('livewire.admin.add-product-attribute');
    }
}
