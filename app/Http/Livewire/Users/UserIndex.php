<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserIndex extends Component
{
    // properti variable
    // mengkosongkan pencarian
    public $search = '';
    // modal new user
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $userId;
    public $editMode = false;

    protected $rules = [
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email',
        'password' => 'required',

    ];

    public function storeUser()
    {
        // form validation
        $this->validate();

        // membuat User baru
        User::create([
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        // pesan
        session()->flash('user-message', 'User successfully created');
        $this->reset();
        $this->dispatchBrowserEvent('closeModal');
    }

    // edit user
    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // cari user/find
        $this->userId = $id;

        // memasukkan user/load
        $this->loadUser();

        // tampilkan user/show
        $this->dispatchBrowserEvent('showModal');
    }

    // memasukkan/ load user
    public function loadUser()
    {
        $user = User::find($this->userId);
        $this->username = $user->username;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
    }

    // update user
    public function updateUser()
    {
        $validated = $this->validate([
            'username' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::find($this->userId);
        $user->update($validated);
        // pesan
        session()->flash('user-message', 'User successfully updated');
        $this->reset();
        $this->dispatchBrowserEvent('closeModal');
    }

    // delete user
    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();

        // pesan
        session()->flash('user-message', 'User successfully deleted');
    }

    // close dan reset
    public function closeModal()
    {
        $this->dispatchBrowserEvent('closeModal');
        $this->reset();
    }

    public function render()
    {
        // pengecekan pencarian berdasarkan username
        $users = User::all();
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->get();
        }
        return view('livewire.users.user-index', [
            'users' => $users
        ])
            ->layout('layouts.main');
    }
}
