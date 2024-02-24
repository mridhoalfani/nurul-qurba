@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'All Posts')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
            <h2 class="page-title mb-5">
                All Posts
            </h2>
            </div>
        </div>
        </div>
    </div>
    @livewire('all-posts')
@endsection
@push('scripts')
    <script>
        window.addEventListener('deletePost', function(event){
            Swal.fire({
                title: "Are you sure?",
                html: "You want to delete this post.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                width:300
            }).then(function(result){
                if(result.value){
                    Livewire.dispatch('deletePostAction', event.detail.id);
                }
            });
        });

        window.addEventListener('postDeleted', function(event){
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
            });
        });
    </script>

    {{-- HARUS MENAMBAHKAN CDN SWEETALERT SECARA MANUAL unutk memunculkan sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush