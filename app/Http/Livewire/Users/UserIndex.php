<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    // membatasi jumlah data yang di tampilkan
    use WithPagination;
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
    // simpan data user baru
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
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
    }

    // tampilkan data user baru
    public function showUserModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
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
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
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
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
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
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        $this->reset();
    }

    public function render()
    {
        // pengecekan pencarian berdasarkan username
        // $users = User::all(); tanpa paginations
        // dengan library paginate
        $users = User::paginate(5);
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.users.user-index', [
            'users' => $users
        ])
            ->layout('layouts.main');
    }
}
