<?php

namespace App\Http\Livewire\City;

use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;

class CityIndex extends Component
{
    use WithPagination;
    // properti
    // pencarian
    public $search = '';
    public $stateId;
    public $name;
    public $editMode = false;
    public $cityId;

    // validation rule input data
    protected $rules = [
        'stateId' => 'required',
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->cityId = $id;
        $this->loadCities();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'show']);
    }

    public function loadCities()
    {
        $city = City::find($this->cityId);
        $this->stateId = $city->state_id;
        $this->name = $city->name;
    }

    public function updateCity()
    {
        // real time validation
        $validated = $this->validate([
            'stateId' => 'required',
            'name' => 'required'
        ]);

        $city = City::find($this->cityId);
        $city->update($validated);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
        session()->flash('city-message', 'City successfully updated');
    }

    public function storeCity()
    {
        // real time validation
        $this->validate();
        City::create([
            'state_id' => $this->stateId,
            'name' => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
        session()->flash('city-message', 'City successfully created');
    }

    public function deleteCity($id)
    {
        $city = City::find($id);
        $city->delete();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
        session()->flash('city-message', 'City successfully deleted');
    }

    public function showCityModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#cityModal', 'actionModal' => 'hide']);
    }
    public function render()
    {
        $cities = City::paginate(5);
        if (strlen($this->search) > 2) {
            $cities = City::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.city.city-index', [
            'cities' => $cities
        ])->layout('layouts.main');
    }
}
