<?php

namespace App\Http\Livewire;

use App\Models\Concern;
use Livewire\Component;
use Livewire\WithPagination;

class ManageConcern extends Component
{
    use WithPagination;

    public $confirmingConcernDeletion = false;
    public $confirmingConcernAdd = false;

    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function rules()
    {
        $rules = [
            'newConcern.name' => 'required|string|max:255',
            'newConcern.email' => 'required|email|unique:users',
            'newConcern.subject' => 'required|string|max:255',
            'newConcern.message' => 'required|string|max:255',
        ];

        return $rules;
    }

    public function render()
    {
        $concerns = Concern::when($this->search,function($query){
                    $query->where('name','like','%'.$this->search.'%')
                        ->orWhere('email','like','%'.$this->search.'%')
                        ->orWhere('subject','like','%'.$this->search.'%')
                        ->orWhere('message','like','%'.$this->search.'%');

        })
        ->orderBy('name','asc')
        ->paginate(10);

        return view('livewire.manage-concerns',[
            'concerns' => $concerns,
        ]);
    }

    public function confirmingConcernDeletion($id)
    {
        $this->confirmingConcernDeletion = $id;
    }

    public function deleteConcern(Concern $concern)
    {
        $concern->delete();

        session()->flash('message','Concern Successfully Deleted.');
        $this->confirmingConcernDeletion = false;
    }

    public function confirmDealAdd()
    {

        $this->reset(['newConcern']);
        $this->confirmingConcernAdd = true;
    }

    public function confirmConcernEdit(Concern $newConcern)
    {
        $this->newConcern = $newConcern;

        // using the same form for adding and editing
        $this->confirmingConcernAdd = true;
    }

    public function saveConcern()
    {

        $this->validate();

        if (isset($this->newConcern->id)) {
            $this->newConcern->save();
        } else {

            Concern::create([
                'name' => $this->newDeal['name'],
                'email' => $this->newDeal['email'],
                'subject' => $this->newDeal['subject'],  // divide by 100 for the percentage
                'message' => $this->newDeal['message'],
            ]);
        }


        session()->flash('message', 'Concern successfully saved.');

        $this->confirmingConcernAdd = false;
    }
}
