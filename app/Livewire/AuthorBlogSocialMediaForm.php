<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BlogSocialMedia;

class AuthorBlogSocialMediaForm extends Component
{
    public $blog_social_media;

    public $facebook_url, $instagram_url, $tiktok_url, $youtube_url;

    public function mount(){
        $this->blog_social_media = BlogSocialMedia::find(1);
        $this->facebook_url = $this->blog_social_media->bsm_facebook;
        $this->instagram_url = $this->blog_social_media->bsm_instagram;
        $this->tiktok_url = $this->blog_social_media->bsm_tiktok;
        $this->youtube_url = $this->blog_social_media->bsm_youtube;
    }

    public function updateBlogSocialMedia(){
        $this->validate([
            'facebook_url'=>'nullable|url',
            'instagram_url'=>'nullable|url',
            'tiktok_url'=>'nullable|url',
            'youtube_url'=>'nullable|url',
        ]);

        $update = $this->blog_social_media->update([
            'bsm_facebook'=>$this->facebook_url,
            'bsm_instagram'=>$this->instagram_url,
            'bsm_tiktok'=>$this->tiktok_url,
            'bsm_youtube'=>$this->youtube_url,
        ]);

        if($update){
            $this->dispatch('success', ['message'=>'Your Profile info have been successfully updated.']);
        }else{
            $this->dispatch('error');
        }
    }

    public function render()
    {
        return view('livewire.author-blog-social-media-form');
    }
}
