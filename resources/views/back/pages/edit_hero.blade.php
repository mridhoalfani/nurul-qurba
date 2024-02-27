@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add new post')



@section('content')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Hero
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('author.update-hero', ['hero_id' => Request('hero_id')]) }}" method="post" id="editHeroForm"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div class="form-label">Featured image</div>
                            <input type="file" class="form-control" name="hero_image" id="hero_image"
                                onchange="previewImage(this)">
                            <span class="text-danger error-text hero_image_error"></span>
                        </div>
                        <div class="image_holder mb-2" style="max-width:250px">
                            <img src="{{ asset('back/dist/img/hero-image/' . $hero->hero_image) }}" alt=""
                                class="img-thumbnail" id="image-previewer">
                        </div>
                        <button type="submit" class="btn btn-primary">Update hero</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
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

        $('form#editHeroForm').on('submit', function(e) {
            e.preventDefault();
            toastr.remove();

            var form = this;
            var formData = new FormData(form);

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
                        toastr.success(response.msg);
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
@endpush
