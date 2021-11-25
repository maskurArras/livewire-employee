<?php

namespace App\Http\Livewire\State;

use App\Models\State;
use Livewire\Component;
use Livewire\WithPagination;

class StateIndex extends Component
{
    use WithPagination;
    // properti
    // pencarian
    public $search = '';
    public $countryId;
    public $name;
    public $editMode = false;
    public $stateId;

    // validation rule input data
    protected $rules = [
        'countryId' => 'required',
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->stateId = $id;
        $this->loadStates();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'show']);
    }

    public function loadStates()
    {
        $state = State::find($this->stateId);
        $this->countryId = $state->country_id;
        $this->name = $state->name;
    }

    public function updateState()
    {
        // real time validation
        $validated = $this->validate([
            'countryId' => 'required',
            'name' => 'required'
        ]);

        $state = State::find($this->stateId);
        $state->update($validated);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully updated');
    }

    public function storeState()
    {
        // real time validation
        $this->validate();
        State::create([
            'country_id' => $this->countryId,
            'name' => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully created');
    }

    public function deleteState($id)
    {
        $state = State::find($id);
        $state->delete();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
        session()->flash('state-message', 'State successfully deleted');
    }

    public function showStateModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#stateModal', 'actionModal' => 'hide']);
    }
    public function render()
    {
        $states = State::paginate(5);
        if (strlen($this->search) > 2) {
            $states = State::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.state.state-index', [
            'states' => $states
        ])->layout('layouts.main');
    }
}
