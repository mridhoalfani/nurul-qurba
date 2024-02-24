<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use App\Models\Setting;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Facades\Image;

use App\Models\Hero;



class AuthorController extends Controller
{
    public function index(Request $request)
    {
        return view('back.pages.home');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('author.login');
    }

    public function ResetForm(Request $request, $token = null)
    {
        $data =  [
            'pageTitle' => 'Reset Password'
        ];
        return view('back.pages.auth.reset', $data)->with(['token' => $token, 'email' => $request->email]);
    }

    public function changeProfilePicture(Request $request)
    {
        $user = User::find(auth('web')->id());
        $path = 'back/dist/img/authors/';
        $file = $request->file('file');
        $old_picture = $user->getAttributes()['picture'];
        $file_path = $path . $old_picture;
        $new_picture_name = 'AIMG' . $user->id . time() . rand(1, 100000) . '.jpg';


        if ($old_picture != null && File::exists(public_path($file_path))) {
            File::delete(public_path($file_path));
        }
        $upload = $file->move(public_path($path), $new_picture_name);
        if ($upload) {
            $user->update([
                'picture' => $new_picture_name
            ]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully updated.']);
        } else {
            return response()->json(['status' => 0, 'Something went wrong.']);
        }
    }

    public function changeBlogLogo(Request $request)
    {
        $settings = Setting::find(1);
        $logo_path = 'back/dist/img/logo-favicon';
        $old_logo = $settings->getAttributes()['blog_logo'];
        $file = $request->file('blog_logo');
        $filename = time() . '_' . rand(1, 100000) . '_larablog_logo.png';

        if ($request->hasFile('blog_logo')) {
            if ($old_logo != null && File::exists(public_path($logo_path . $old_logo))) {
                File::delete(public_path($logo_path . $old_logo));
            }
            $upload = $file->move(public_path($logo_path), $filename);

            if ($upload) {
                $settings->update([
                    'blog_logo' => $filename
                ]);
                return response()->json(['status' => 1, 'msg' => 'Larablog logo has been successfully updated.']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function changeBlogFavicon(Request $request)
    {
        $settings = Setting::find(1);
        $favicon_path = 'back/dist/img/logo-favicon';
        $old_favicon = $settings->getAttributes()['blog_favicon'];
        $file = $request->file('blog_favicon');
        $filename = time() . '_' . rand(1, 2000) . '_larablog_favicon.ico';

        if ($old_favicon != null && File::exists(public_path($favicon_path . $old_favicon))) {
            File::delete(public_path($favicon_path . $old_favicon));
        }

        $upload  = $file->move(public_path($favicon_path), $filename);
        if ($upload) {
            $settings->update([
                'blog_favicon' => $filename
            ]);
            return response()->json(['status' => 1, 'msg' => 'Blog favicon has been successfully updated']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'post_title' => 'required|unique:posts,post_title',
            'post_content' => 'required',
            'post_category' => 'required|exists:sub_categories,id',
            'featured_image' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($request->hasFile('featured_image')) {
            $path = "images/post_images/";
            $file = $request->file('featured_image');
            $filename = $file->getClientOriginalName();
            $new_filename = time() . '_' . $filename;


            $upload = Storage::disk('public')->put($path . $new_filename, (string) file_get_contents($file));

            $post_thumbnails_path = $path . 'thumbnails';
            if (!Storage::disk('public')->exists($post_thumbnails_path)) {
                Storage::disk('public')->makeDirectory($post_thumbnails_path, 0755, true, true);
            }

            //create square thumbnail
            Image::make(storage_path('app/public/' . $path . $new_filename))
                ->fit(200, 200)
                ->save(storage_path('app/public/' . $path . 'thumbnails/' . 'thumb_' . $new_filename));

            Image::make(storage_path('app/public/' . $path . $new_filename))
                ->fit(500, 350)
                ->save(storage_path('app/public/' . $path . 'thumbnails/' . 'resized_' . $new_filename));


            if ($upload) {
                $post = new Post();
                $post->author_id = auth()->id();
                $post->category_id = $request->post_category;
                $post->post_title = $request->post_title;
                // $post->post_slug = Str::slug($request->post_title);
                $post->post_content = $request->post_content;
                $post->featured_image = $new_filename;
                $post->post_tags = $request->post_tags;
                $saved = $post->save();

                if ($saved) {
                    return response()->json(['code' => 1, 'msg' => 'New post has been successfully created.']);
                } else {
                    return response()->json(['code' => 3, 'msg' => 'Something went wrong ins saving data.']);
                }
            } else {
                return response()->json(['code' => 3, 'msg' => 'Something went wrong for uploadingfeatured image.']);
            }
        }
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $uploadedFile = $request->file('upload');

            // Mengatur dimensi yang diinginkan
            $width = 600;
            $height = 400;

            // Mendapatkan ekstensi file
            $extension = $uploadedFile->getClientOriginalExtension();

            // Menambahkan timestamp ke nama file untuk memastikan keunikan
            $fileName = time() . '.' . $extension;

            // Memindahkan file yang diunggah ke direktori public/media dengan nama baru
            $uploadedFile->move(public_path('media'), $fileName);

            // Membuat path lengkap untuk file yang baru diunggah
            $filePath = public_path('media/' . $fileName);

            // Memanipulasi gambar menggunakan Intervention Image
            Image::make($filePath)
                ->fit($width, $height)
                ->save();

            // Membuat URL lengkap untuk file yang baru diunggah
            $url = asset('media/' . $fileName);

            // Mengembalikan respons JSON dengan informasi file yang diunggah
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    public function editPost(Request $request)
    {
        if (!request()->post_id) {
            return abort(404);
        } else {
            $post = Post::find(request()->post_id);
            $data = [
                'post' => $post,
                'pageTitle' => 'Edit Post',
            ];
            return view('back.pages.edit_post', $data);
        }
    }

    public function updatePost(Request $request)
    {
        if ($request->hasFile('featured_image')) {
            $request->validate([
                'post_title' => 'required|unique:posts,post_title,' . $request->post_id,
                'post_content' => 'required',
                'post_category' => 'required|exists:sub_categories,id',
                'featured_image' => 'mimes:jpeg,jpg,png|max:1024',
            ]);

            $path = "images/post_images/";
            $file = $request->file('featured_image');
            $filename = $file->getClientOriginalName();
            $new_filename = time() . '_' . $filename;

            $upload = Storage::disk('public')->put($path . $new_filename, (string) file_get_contents($file));

            $post_thumbnails_path = $path . 'thumbnails';
            if (!Storage::disk('public')->exists($post_thumbnails_path)) {
                Storage::disk('public')->makeDirectory($post_thumbnails_path, 0755, true, true);
            }

            Image::make(storage_path('app/public/' . $path . $new_filename))
                ->fit(200, 200)
                ->save(storage_path('app/public/' . $path . 'thumbnails/' . 'thumb_' . $new_filename));

            Image::make(storage_path('app/public/' . $path . $new_filename))
                ->fit(500, 350)
                ->save(storage_path('app/public/' . $path . 'thumbnails/' . 'resized_' . $new_filename));

            if ($upload) {
                $old_post_image = Post::find($request->post_id)->featured_image;

                if ($old_post_image != null && Storage::disk('public')->exists($path . $old_post_image)) {
                    Storage::disk('public')->delete($path . $old_post_image);

                    if (Storage::disk('public')->exists($path . 'thumbnails/resized_' . $old_post_image)) {
                        Storage::disk('public')->delete($path . 'thumbnails/resized_' . $old_post_image);
                    }

                    if (Storage::disk('public')->exists($path . 'thumbnails/thumb_' . $old_post_image)) {
                        Storage::disk('public')->delete($path . 'thumnails/thumb_' . $old_post_image);
                    }
                }

                $post = Post::find($request->post_id);
                $post->category_id = $request->post_category;
                $post->post_title = $request->post_title;
                $post->post_slug = null;
                $post->post_content = $request->post_content;
                $post->featured_image = $new_filename;
                $post->post_tags = $request->post_tags;
                $saved = $post->save();

                if ($saved) {
                    // return response()->json(['code'=>1,'msg'=>'Post has been successfully updating.']);
                    return response()->json([
                        'code' => 1,
                        'redirect_url' => route('author.posts.all_posts'),
                        'msg' => 'Post has been successfully updating.',

                    ]);
                } else {
                    return response()->json(['code' => 3, 'msg' => 'Something went wrong for updating post.']);
                }
            } else {
                return response()->json(['code' => 3, 'msg' => 'Error in uploading new featured image.']);
            }
        } else {
            $request->validate([
                'post_title' => 'required|unique:posts,post_title,' . $request->post_id,
                'post_content' => 'required',
                'post_category' => 'required|exists:sub_categories,id'
            ]);

            $post = Post::find($request->post_id);
            $post->category_id = $request->post_category;
            $post->post_slug = null;
            $post->post_content = $request->post_content;
            $post->post_title = $request->post_title;
            $post->post_tags = $request->post_tags;
            $saved = $post->save();

            if ($saved) {
                // return response()->json(['code'=>1,'msg'=>'Post has been successfully updating.']);
                return response()->json([
                    'code' => 1,
                    'redirect_url' => route('author.posts.all_posts'),
                    'msg' => 'Post has been successfully updating.',

                ]);
            } else {
                return response()->json(['code' => 3, 'msg' => 'Something went wrong for updating post.']);
            }
        }
    }

    public function addHero(Request $request)
    {
    }
}
