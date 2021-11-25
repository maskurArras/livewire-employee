<?php

namespace App\Http\Livewire\Department;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    use WithPagination;
    // properti
    // pencarian
    public $search = '';
    public $departmentId;
    public $name;
    public $editMode = false;

    // validation rule input data
    protected $rules = [
        'name' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->departmentId = $id;
        $this->loadDepartments();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }

    public function loadDepartments()
    {
        $department = Department::find($this->departmentId);
        $this->name = $department->name;
    }

    public function updateDepartment()
    {
        // real time validation
        $validated = $this->validate([
            'name' => 'required'
        ]);

        $department = Department::find($this->departmentId);
        $department->update($validated);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully updated');
    }

    public function storeDepartment()
    {
        // real time validation
        $this->validate();
        department::create([
            'name' => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully created');
    }

    public function deleteDepartment($id)
    {
        $department = department::find($id);
        $department->delete();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
        session()->flash('department-message', 'Department successfully deleted');
    }

    public function showDepartmentModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#departmentModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $departments = Department::paginate(5);
        if (strlen($this->search) > 2) {
            $departments = Department::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.department.department-index', [
            'departments' => $departments
        ])->layout('layouts.main');
    }
}
