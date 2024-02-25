@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Hero setting')

@section('content')
    <div class="row align-item-center">
        <div class="col">
            <h2 class="page-title mb-4">
                Hero setting
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">

                <li class="nav-item">
                    <a href="#tabs-profile-ex1" class="active nav-link" data-bs-toggle="tab">Hero section</a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <div class="tab-pane active show fade" id="tabs-profile-ex1">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Set blog hero</h3>
                            <div class="mb-2" style="max-width: 200px">
                                <img src="" alt="" class="img-thumbnail" id="image-logo-previewer">
                            </div>
                            <form action="{{ route('author.add-hero') }}" method="post" id="changeBlogHeroForm"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <input type="file" id="hero_image" name="hero_image" class="form-control"
                                        onchange="previewImageLogo(this)">
                                </div>
                                <button class="btn btn-primary">Add</button>
                            </form>
                        </div>
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


        $('#changeBlogHeroForm').submit(function(e) {
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
                        $(form)[0].reset();
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        });
    </script>
@endpush
