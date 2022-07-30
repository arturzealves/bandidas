<?php

namespace App\Http\Livewire;

use App\Models\UserType;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateUserType extends Component
{
    public $userType;

    protected $listeners = ['postChanged' => 'updateUserType'];

    public function mount()
    {
        $this->userType = Auth::user()->user_type_id;
    }

    public function updateUserType()
    {
        $user = Auth::user();
        $user->user_type_id = $this->userType;
        $user->save();
    }

    public function render()
    {
        return view('livewire.update-user-type')
            ->with(['userTypes' => UserType::all()]);
    }
}
