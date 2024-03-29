<?php

namespace App\Http\Livewire\Employee;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;
    // properti
    // pencarian
    public $search = '';
    // data table
    public $employeeId;
    public $lastName;
    public $firstName;
    public $middleName;
    public $address;
    public $departmentId;
    public $countryId;
    public $stateId;
    public $cityId;
    public $zipCode;
    public $birthDate;
    public $dateHired;
    public $selectedDepartmentId = null;

    public $editMode = false;

    // validation rule input data
    protected $rules = [
        'lastName' => 'required',
        'firstName' => 'required',
        'middleName' => 'required',
        'address' => 'required',
        'departmentId' => 'required',
        'countryId' => 'required',
        'stateId' => 'required',
        'cityId' => 'required',
        'zipCode' => 'required',
        'birthDate' => 'required',
        'dateHired' => 'required',
    ];

    public function showEditModal($id)
    {
        $this->reset();
        $this->employeeId = $id;
        $this->loadEmployee();
        $this->editMode = true;
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'show']);
    }

    public function loadEmployee()
    {
        $employee = Employee::find($this->employeeId);
        $this->firstName = $employee->first_name;
        $this->lastName = $employee->last_name;
        $this->middleName = $employee->middle_name;
        $this->address = $employee->address;
        $this->countryId = $employee->country_id;
        $this->stateId = $employee->state_id;
        $this->cityId = $employee->city_id;
        $this->departmentId = $employee->department_id;
        $this->zipCode = $employee->zip_code;
        $this->birthDate = $employee->birthdate;
        $this->dateHired = $employee->date_hired;
    }

    public function updateEmployee()
    {
        // real time validation
        $validated = $this->validate();

        $employee = Employee::find($this->employeeId);
        $employee->update([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'address' => $this->address,
            'department_id' => $this->departmentId,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'zip_code' => $this->zipCode,
            'birthdate' => $this->birthDate,
            'date_hired' => $this->dateHired
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
        session()->flash('employee-message', 'Employee successfully updated');
    }

    public function storeEmployee()
    {
        // real time validation
        $this->validate();
        Employee::create([
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'address' => $this->address,
            'department_id' => $this->departmentId,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'zip_code' => $this->zipCode,
            'birthdate' => $this->birthDate,
            'date_hired' => $this->dateHired

        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
        session()->flash('employee-message', 'Employee successfully created');
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
        session()->flash('employee-message', 'Employee successfully deleted');
    }

    public function showEmployeeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'show']);
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#employeeModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $employees = Employee::paginate(5);
        if (strlen($this->search) > 2) {
            if ($this->selectedDepartmentId) {
                $employees = Employee::where('first_name', 'like', "%{$this->search}%")
                    ->where('department_id', $this->selectedDepartmentId)
                    ->paginate(5);
            } else {
                $employees = Employee::where('first_name', 'like', "%{$this->search}%")->paginate(5);
            }
            $employees = Employee::where('first_name', 'like', "%{$this->search}%")->paginate(5);
        } elseif ($this->selectedDepartmentId) {
            $employees = Employee::where('department_id', $this->selectedDepartmentId)->paginate(5);
        }
        return view('livewire.employee.employee-index', [
            'employees' => $employees
        ])->layout('layouts.main');
    }
}
