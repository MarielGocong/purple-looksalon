<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Deal;
use Livewire\WithPagination;
use App\Models\Service;
// use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class ManageDeals extends Component

{

    use WithPagination;


    public $confirmingDealDeletion = false;
    public $confirmingDealAdd = false;
    public $confirmingDealEdit = false;

    public $selectedServices = [];

    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $newDeal, $name, $description, $discount, $start_date, $end_date, $is_hidden;



    protected function rules()
    {
        $rules = [
            'newDeal.name' => 'required|string|min:1|max:255',
            'newDeal.description' => 'required|string|min:1|max:255',
            'newDeal.discount' => 'required|numeric|min:0|max:100',
            'newDeal.start_date' => 'required|date',
            'newDeal.end_date' => 'required|date|after_or_equal:newDeal.start_date',
            'newDeal.is_hidden' => 'boolean',
        ];

        return $rules;
    }


    public function render()
    {
        $deals = Deal::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        $services = Service::all(); // Fetch all services

        return view('livewire.manage-deals', [
            'deals' => $deals,
            'services' => $services, // Pass services to the view
        ]);
    }

    public function confirmDealDeletion($id)
    {
        $this->confirmingDealDeletion = $id;
    }

    public function deleteDeal(Deal $deal)
    {
        $deal->delete();

        session()->flash('message', 'Deal successfully deleted.');
        $this->confirmingDealDeletion = false;
    }


    public function confirmDealAdd()
    {

        $this->reset(['newDeal','selectedServices']);
        $this->confirmingDealAdd = true;
    }

    public function confirmDealEdit(Deal $newDeal)
    {
        $this->newDeal = $newDeal;
        $this->selectedServices = $newDeal->services->pluck('id')->toArray(); // Load associated services
        $this->confirmingDealAdd = true;
    }


    public function saveDeal()
    {
        $this->validate();

        if (isset($this->newDeal->id)) {
            $this->newDeal->save();
            $deal = $this->newDeal;
        } else {
            $deal = Deal::create([
                'name' => $this->newDeal['name'],
                'description' => $this->newDeal['description'],
                'discount' => $this->newDeal['discount'],
                'start_date' => $this->newDeal['start_date'],
                'end_date' => $this->newDeal['end_date'],
                'is_hidden' => isset($this->newDeal['is_hidden']) ? $this->newDeal['is_hidden'] : false,
            ]);
        }

        // Sync selected services
        $deal->services()->sync($this->selectedServices);

        session()->flash('message', 'Deal successfully saved.');
        $this->confirmingDealAdd = false;
    }
}
