<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Hero;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AllHeros extends Component
{
    use WithPagination;
    public $perPage = 5;

    public $delete_id;

    protected $listeners = [
        'deleteHeroAction'
    ];

    public function mount()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.all-heros', [
            'heros' => auth()->user()->type == 1 ?
                Hero::paginate($this->perPage) :
                Hero::where('author_id', auth()->id())->paginate($this->perPage)
        ]);
    }

    public function deleteHero($id)
    {
        $this->delete_id = $id;
        $this->dispatch('deleteHero');
    }

    public function deleteHeroAction()
    {
        // Temukan hero yang akan dihapus berdasarkan ID
        $hero = Hero::find($this->delete_id);

        // Pastikan hero ditemukan
        if ($hero) {
            // Ambil path lengkap gambar
            $imagePath = public_path('back/dist/img/hero-image/' . $hero->hero_image);

            // Pastikan file gambar ada sebelum dihapus
            if (File::exists($imagePath)) {
                // Hapus gambar dari direktori
                File::delete($imagePath);
            }

            // Hapus hero dari database
            $hero->delete();
            if ($hero) {
                $this->dispatch('success');
            } else {
                $this->dispatch('error');
            }

            // Kirimkan event bahwa hero telah berhasil dihapus
            $this->dispatch('postDeleted');
        }
    }
}
