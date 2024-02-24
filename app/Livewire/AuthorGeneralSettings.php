<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class AuthorGeneralSettings extends Component
{

    public $settings;
    public $blog_name, $blog_email, $blog_description;
    public function mount(){
        $this->settings = Setting::find(1);
        $this->blog_name = $this->settings->blog_name;
        $this->blog_email = $this->settings->blog_email;
        $this->blog_description = $this->settings->blog_description;
    }

    public function updateGeneralSettings(){
        $this->validate([
            'blog_name'=>'required',
            'blog_email'=>'required|email',
        ]);

        $update = $this->settings->update([
            'blog_name'=>$this->blog_name,
            'blog_email'=>$this->blog_email,
            'blog_description'=>$this->blog_description,
        ]);

        if($update){
            $this->dispatch('success', 'General setting have been successfully updated.');
            $this->dispatch('updateAuthorFooter');
        }else{
            $this->dispatch('error');
        }
    }
    
    public function render()
    {
        return view('livewire.author-general-settings');
    }
}
