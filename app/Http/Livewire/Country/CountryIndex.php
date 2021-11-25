<?php

namespace App\Http\Livewire\Country;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class CountryIndex extends Component
{
    use WithPagination;
    // properti
    // pencarian
    public $search = '';
    public $countryCode;
    public $name;
    public $editMode = false;
    public $countryId;

    // validation rule input data
    protected $rules = [
        'countryCode' => 'required',
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->countryId = $id;
        $this->loadCountries();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'show']);
    }

    public function loadCountries()
    {
        $country = Country::find($this->countryId);
        $this->countryCode = $country->country_code;
        $this->name = $country->name;
    }

    public function updateCountry()
    {
        // real time validation
        $validated = $this->validate([
            'countryCode' => 'required',
            'name' => 'required'
        ]);

        $country = Country::find($this->countryId);
        $country->update($validated);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully updated');
    }

    public function storeCountry()
    {
        // real time validation
        $this->validate();
        Country::create([
            'country_code' => $this->countryCode,
            'name' => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully created');
    }

    public function deleteCountry($id)
    {
        $country = Country::find($id);
        $country->delete();

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
        session()->flash('country-message', 'Country successfully deleted');
    }

    public function showCountryModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#countryModal', 'actionModal' => 'hide']);
    }


    public function render()
    {
        $countries = Country::paginate(5);
        if (strlen($this->search) > 2) {
            $countries = Country::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.country.country-index', [
            'countries' => $countries
        ])
            ->layout('layouts.main');
    }
}
