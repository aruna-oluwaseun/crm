<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class GlobalSearch extends Component
{

    public $query = '';
    public $show_results = false;
    public $count = 0;
    public $results = null;

    public function refresh()
    {
        //$this->query = '';
        $this->show_results = false;
        $this->count = 0;
        $this->results = null;
    }

    public function close()
    {
        $this->reset();
    }

    public function search()
    {
        $this->refresh();

        // Models to search against
        $models = [
            'Products'      => '\App\Models\Product',
            'Sales Orders'  => '\App\Models\SalesOrder',
            'Customers'     => '\App\Models\Customer',
            'Staff'         => '\App\Models\User',
            'Purchases'     => '\App\Models\PurchaseOrder'
        ];

        // Search these models
        $results = [];
        foreach($models as $key => $model)
        {
            // check if they have the SearchTrait first
            $traits = class_uses(new $model, false);

            // Skip if model doesn't use search trait
            if(!in_array('App\Traits\SearchTrait', $traits)) {
                continue;
            }

            $result = $model::search($this->query)->limit(30)->get();
            $this->count += $result->count();
            $results[$key] = $result->toArray();

        }

        //dd($result->get()->toArray());

        $this->show_results = true;
        return $this->results = $results;
    }

    public function render()
    {
        return view('livewire.admin.global-search');
    }
}
