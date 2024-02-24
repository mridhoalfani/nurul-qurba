<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorChangePasswordForm extends Component
{
    public $current_password, $new_password, $confirm_password;
    public function changePassword()
    {
        $this->validate([
            'current_password'=>[
                'required', function($attribute, $value, $fail){
                    if(!Hash::check($value, User::find(auth('web')->id())->password)){
                        return $fail(__('The current password is incorrect'));
                    }
                }, 
            ],
            'new_password'=>'required|min:5|max:25',
            'confirm_password'=>'same:new_password'
        ],[
            'current_password.required'=>'Enter your current password',
            'new_password.required'=>'Enter new password',
            'confirm_password'=>'The confirm password must be the same new password',
        ]);
        
        $query =User::find(auth('web')->id())->update([
            'password'=>Hash::make($this->new_password)
        ]);

        if($query){
            $this->dispatch('success', ['message'=>'Your Profile info have been successfully updated.']);
            $this->current_password = $this->new_password = $this->confirm_password = null;
        }else{
            $this->dispatch('error', ['message'=>'Something wrong.']);
        }
    }

    public function render()
    {
        return view('livewire.author-change-password-form');
    }
}
