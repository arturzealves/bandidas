<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateUserType extends Component
{
    public $userType;

    protected $listeners = ['postChanged' => 'updateUserType'];

    public function mount()
    {
        $this->userType = Auth::user()->user_type;
    }

    public function updateUserType()
    {
        $user = Auth::user();
        $user->user_type = $this->userType;
        $user->save();
    }

    public function render()
    {
        return view('livewire.update-user-type')
            ->with([
                'userTypes' => [
                    User::TYPE_USER,
                    User::TYPE_PROMOTER,
                    User::TYPE_ARTIST,
                ]
            ]);
    }
}
