<?php

namespace App\Http\Livewire;

use App\Models\OnlineSupplier;
use Livewire\Component;

class ManageOnlineSuppliers extends Component
{
    private $online_suppliers;

    public $search;
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $online_supplier;

    public $confirmingSupplierAdd;
    public $confirmSupplierDeletion = false;
    public $confirmingSupplierDeletion = false;

    protected $rules = [
        "online_supplier.name" => "required|string|max:255",
        "online_supplier.link" => "required|string|max:255",
        "online_supplier.contact" => "required|string|max:255",
        "online_supplier.address" => "required|string|max:255",
    ];

    public function render()
    {
        $this->online_suppliers = OnlineSupplier::when($this->search, function($query) {
            $query->where('name','like','%'.$this->search.'%');
        })->paginate(10);

        return view('livewire.manage-online-suppliers',[
            'online_suppliers' => $this->online_suppliers,
        ]);
    }

    public function confirmSupplierEdit(OnlineSupplier $online_supplierId)
    {
        $this->online_supplier = $online_supplierId;
        $this->confirmingSupplierAdd = true;
    }

    public function confirmSupplierDeletion()
    {
        $this->confirmingSupplierDeletion = true;
    }

    public function saveOnlineSupplier()
    {
        $this->validate();

        if (isset($this->online_supplier->id))
        {
            $this->online_supplier->save();
        } else {
            OnlineSupplier::create(
                [
                    'name' => $this->online_supplier['name'],
                    'link' => $this->online_supplier['link'],
                    'contact' => $this->online_supplier['contact'],
                    'address' => $this->online_supplier['address'],
                ]
            );
        }

        $this->confirmingSupplierAdd = false;
        $this->online_supplier = null;
    }

    public function deleteSupplier(OnlineSupplier $online_supplierId)
    {
        $this->online_supplier = $online_supplierId;
        $this->online_supplier->delete();
        $this->confirmingSupplierDeletion = false;
    }

    public function confirmSupplierAdd()
    {
        $this->confirmingSupplierAdd = true;
    }

}
