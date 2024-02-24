@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add new post')



@section('content')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Post
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('author.posts.update-post', ['post_id' => Request('post_id')]) }}" method="post" id="editPostForm"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label">Post title</label>
                            <input type="text" class="form-control" name="post_title" placeholder="Input post title"
                                value="{{ $post->post_title }}">
                            <span class="text-danger error-text post_title_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Post content</label>
                            <textarea class="form-control" name="post_content" rows="6" placeholder="Content.." id="post_content">{!! $post->post_content !!}</textarea>
                            <span class="text-danger error-text post_content_error"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="form-label">Post category</div>
                            <select class="form-select" name="post_category">
                                <option value="">No selected</option>
                                @foreach (\App\Models\SubCategory::all() as $category)
                                    <option
                                        value="{{ $category->id }}"{{ $post->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->subcategory_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text post_category_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Featured image</div>
                            <input type="file" class="form-control" name="featured_image" id="featured_image"
                                onchange="previewImage(this)">
                            <span class="text-danger error-text featured_image_error"></span>
                        </div>
                        <div class="image_holder mb-2" style="max-width:250px">
                            <img src="/storage/images/post_images/thumbnails/resized_{{ $post->featured_image }}"
                                alt="" class="img-thumbnail" id="image-previewer">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Post tags</label>
                            <input type="text" class="form-control" name="post_tags" value="{{ $post->post_tags }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update post</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('contentEXAMPLE')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Add new Post
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('author.posts.create') }}" method="post" id="addPostForm" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label">Post title</label>
                            <input type="text" class="form-control" name="post_title" placeholder="Input post title">
                            <span class="text-danger error-text post_title_error"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Post content</label>
                            <textarea class="form-control" name="post-content" rows="6" placeholder="Content.."></textarea>
                            <span class="text-danger error-text post_content_error"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="form-label">Post category</div>
                            <select class="form-select" name="post_category">
                                <option value="">No selected</option>
                                @foreach (\App\Models\SubCategory::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->subcategory_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text post_category_error"></span>
                        </div>
                        <div class="mb-3">
                            <div class="form-label">Featured image</div>
                            <input type="file" class="form-control" name="featured_image">
                            <span class="text-danger error-text featured_image_error"></span>
                        </div>
                        <div class="image_holder mb-2" style="max-width:250px">
                            <img src="" alt="" class="img-thumbnail" id="image-previewer"
                                data-ijabo-default-img=''>
                        </div>
                        <button type="submit" class="btn btn-primary">Save post</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        // Inisialisasi CKEditor secara asinkron
        ClassicEditor
            .create(document.querySelector('#post_content'), {

                ckfinder: {
                    uploadUrl: "{{ route('author.posts.ckeditor.upload', ['_token' => csrf_token()]) }}",
                }
            })
            .then(editor => {
                // Menetapkan instance CKEditor ke variabel
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        function previewImage(input) {
            var imagePreviewer = document.getElementById('image-previewer');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewer.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('form#editPostForm').on('submit', function(e) {
            e.preventDefault();
            toastr.remove();

            // Pastikan CKEditor telah dibuat sebelum mengakses datanya
            if (window.editor) {
                var post_content = window.editor.getData();
            } else {
                console.error('Instance CKEditor tidak tersedia');
                var post_content = ''; // Atur nilai default atau tangani kasus ini sesuai kebutuhan
            }

            var form = this;
            var formData = new FormData(form);
            formData.append('post_content', post_content);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    toastr.remove();
                    if (response.code == 1) {
                        $(form)[0].reset();
                        $('div.image_holder').find('img').attr('src', '');
                        if (window.editor) {
                            window.editor.setData(''); // Reset isi CKEditor
                        }
                        toastr.success(response.msg);
                        // Redirect ke halaman tujuan
                        console.log('Redirecting to:', response.redirect_url);
                        window.location.href = response.redirect_url;
                    } else {
                        toastr.error(response.msg);
                    }
                },
                error: function(response) {
                    toastr.remove();
                    $.each(response.responseJSON.errors, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val[0]);
                    });
                }
            });
        });
    </script>

    {{-- <script>
        ClassicEditor
            .create( document.querySelector( '#post_content' ) )
            .then( editor => {
                    console.log( editor );
            } )
            .catch( error => {
                    console.error( error );
            } );

        function previewImage(input) {
            var imagePreviewer = document.getElementById('image-previewer');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    imagePreviewer.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('form#addPostForm').on('submit', function(e){
                e.preventDefault();
                toastr.remove();
                var post_content = CKEDITOR.instances.post_content.getData();
                var form = this;
                var fromdata = new FormData(form);
                    fromdata.append('post_content', post_content);

                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:fromdata,
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(response){
                        toastr.remove();
                        if(response.code == 1){
                            $(form)[0].reset();
                            $('div.image_holder').find('img').attr('src','');
                            CKEDITOR.instances.post_content.setData('');
                            toastr.success(response.msg);
                        }else {
                            toastr.error(response.msg);
                        }
                    },
                    error:function(response){
                        toastr.remove();
                        $.each(response.responseJSON.errors, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    }
                })
            });
    </script> --}}

    {{-- <script>
        $(function(){
            $('input[type="file"][name="featured_image"]').ijaboViewer({
                preview:'#image-previewer',
                imageShape:'rectangular',
                allowedExtensions:['jpg','jpeg','png'],
                onErrorShape:function(message,element){
                    alert(message);
                }, 
                onInvalidType:function(message,element){
                    alert(message);
                }
            });


            $('form#addPostForm').on('submit', function(e){
                e.preventDefault();
                toastr.remove();
                var form = this;
                var fromdata = new FormData(form);

                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:fromdata,
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(response){
                        toastr.remove();
                        if(response.code == 1){
                            $(form)[0].reset();
                            $('div.image_holder').html('');
                            toastr.success(response.msg);
                        }else {
                            toastr.error(response.msg);
                        }
                    },
                    error:function(response){
                        toastr.remove();
                        $.each(response.responseJSON.errors, function(prefix,val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                    }
                })
            });

        });
    </script> --}}
@endpush
