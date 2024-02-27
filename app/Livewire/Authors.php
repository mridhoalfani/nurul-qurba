<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Mail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Authors extends Component
{
    use WithPagination;
    public $name, $email, $username, $author_type, $direct_publish;
    public $search;
    public $perPage = 4;
    public $selected_author_id;
    public $blocked = 0;

    public $delete_id;

    protected $listeners = [
        'resetForms',
        'deleteAuthorAction'
    ];

    // public function mount(){
    //     $this->resetPage();
    // }

    // public function updatingSearch(){
    //     $this->resetPage();
    // }

    public function resetForms()
    {
        $this->name = $this->email = $this->username = $this->author_type = $this->direct_publish = null;
        $this->resetErrorBag();
    }

    public function addAuthor()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username|min:6|max:20',
            'author_type' => 'required',
            'direct_publish' => 'required',
        ], [
            'author.type.required' => 'Choose author type',
            'direct_publish.required' => 'Specify author publication access',
        ]);


        if ($this->isOnline()) {

            $default_password = Random::generate(8);

            $author = new User();
            $author->name = $this->name;
            $author->email = $this->email;
            $author->username = $this->username;
            $author->password = Hash::make($default_password);
            $author->type = $this->author_type;
            $author->direct_publish = $this->direct_publish;
            $saved = $author->save();

            $data = array(
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $default_password,
                'url' => route('author.profile'),
            );

            $author_email = $this->email;
            $author_name = $this->name;

            if ($saved) {

                Mail::send('new-author-email-template', $data, function ($message) use ($author_email, $author_name) {
                    $message->from('noreply@example.com', 'Larablog');
                    $message->to($author_email, $author_name)
                        ->subject('Account creation');
                });

                $this->dispatch('success');
                $this->name = $this->email = $this->username = $this->author_type = $this->direct_publish = null;
                $this->dispatch('hide_add_author_modal');
            } else {
                $this->dispatch('error');
            }
        } else {
            $this->dispatch('error');
        }
    }

    public function editAuthor($author)
    {
        $this->selected_author_id = $author['id'];
        $this->name = $author['name'];
        $this->email = $author['email'];
        $this->username = $author['username'];
        $this->author_type = $author['type'];
        $this->direct_publish = $author['direct_publish'];
        $this->blocked = $author['blocked'];
        $this->dispatch('showEditAuthorModal');
    }

    public function updateAuthor()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->selected_author_id,
            'username' => 'required|min:6|max:20|unique:users,username,' . $this->selected_author_id,
        ]);

        if ($this->selected_author_id) {
            $author = User::find($this->selected_author_id);
            $author->update([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'type' => $this->author_type,
                'blocked' => $this->blocked,
                'direct_publish' => $this->direct_publish,
            ]);

            $this->dispatch('success');
            $this->dispatch('hide_edit_author_modal');
        }
    }

    public function deleteAuthor($id)
    {
        $this->delete_id = $id;
        $this->dispatch('deleteAuthor');
    }

    public function deleteAuthorAction()
    {
        $author = User::where('id', $this->delete_id)->first();
        $path = 'back/dist/img/authors/';
        $author_picture = $author->getAttributes()['picture'];
        $picture_full_path = $path . $author_picture;

        if ($author_picture != null || File::exists(public_path($picture_full_path))) {
            File::delete(public_path($picture_full_path));
        }

        $author->delete();
        $this->dispatch('authorDeleted');
    }


    public function isOnline($site = "https://youtube.com/")
    {
        if (@fopen($site, "r")) {
            return true;
        } else {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.authors', [
            'authors' => User::search(trim($this->search))
                ->orderBy('id', 'desc')
                ->where('id', '!=', auth()->id())->paginate($this->perPage),
        ]);
    }
}
