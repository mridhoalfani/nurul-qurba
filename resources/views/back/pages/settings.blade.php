@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Settings')

@section('content')
    <div class="row align-item-center">
        <div class="col">
            <h2 class="page-title">
                Setting
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-home-ex1" class="nav-link active" data-bs-toggle="tab">General settings</a>
                </li>
                <li class="nav-item">
                    <a href="#tabs-profile-ex1" class="nav-link" data-bs-toggle="tab">Logo & Favicon</a>
                </li>
                <li class="nav-item">
                    <a href="#tabs-social-ex1" class="nav-link" data-bs-toggle="tab">Social media</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tabs-home-ex1">
                    @livewire('author-general-settings')
                </div>
                <div class="tab-pane fade" id="tabs-profile-ex1">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Set blog logo</h3>
                            <div class="mb-2" style="max-width: 200px">
                                <img src="{{ \App\Models\Setting::find(1)->blog_logo }}" alt=""
                                    class="img-thumbnail" id="image-logo-previewer">
                            </div>
                            <form action="{{ route('author.change-blog-logo') }}" method="post" id="changeBlogLogoForm">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" name="blog_logo" class="form-control"
                                        onchange="previewImageLogo(this)">
                                </div>
                                <button class="btn btn-primary">Change logo</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h3>Set blog favicon</h3>
                            <div class="mb-2" style="max-width: 100px">
                                <img src="{{ \App\Models\Setting::find(1)->blog_favicon }}" alt=""
                                    class="img-thumbnail" id="image-favicon-previewer">
                            </div>
                            <form action="{{ route('author.change-blog-favicon') }}" method="post"
                                id="changeBlogFaviconForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="File" name="blog_favicon" class="form-control"
                                        onchange="previewImageFavicon(this)">
                                </div>
                                <button class="btn btn-primary">Change favicon</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabs-social-ex1">
                    <div>
                        @livewire('author-blog-social-media-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function previewImageLogo(input) {
            var imagePreviewer = document.getElementById('image-logo-previewer');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewer.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewImageFavicon(input) {
            var imagePreviewer = document.getElementById('image-favicon-previewer');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreviewer.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#changeBlogLogoForm').submit(function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {},
                success: function(data) {
                    toastr.remove();
                    if (data.status == 1) {
                        toastr.success(data.msg);
                        $(form)[0].reset();
                        Livewire.dispatch('updateTopHeader');
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        })

        $('#changeBlogFaviconForm').submit(function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {},
                success: function(data) {
                    toastr.remove();
                    if (data.status == 1) {
                        toastr.success(data.msg);
                        // Redirect ke halaman tujuan
                        console.log('Redirecting to:', response.redirect_url);
                        window.location.href = response.redirect_url;
                        $(form)[0].reset();
                        Livewire.dispatch('updateTopHeader');
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        })
    </script>
@endpush
