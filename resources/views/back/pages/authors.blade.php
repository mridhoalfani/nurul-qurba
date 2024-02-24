@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Authors')

@section('content')
    @livewire('authors')
@endsection

@push('scripts')
    <script>
        $(window).on('hidden.bs.modal', function(){
            Livewire.dispatch('resetForms');
        });

        window.addEventListener('hide_add_author_modal', function(event){
            $('#add_author_modal').modal('hide');
        });

        window.addEventListener('showEditAuthorModal', function(event){
            $('#edit_author_modal').modal('show');
        });

        window.addEventListener('hide_edit_author_modal', function(event){
            $('#edit_author_modal').modal('hide');
        });

        window.addEventListener('deleteAuthor', function(event){
            Swal.fire({
                title: "Are you sure?",
                html: "You want to delete this author",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                width:300
            }).then(function(result){
                if(result.value){
                    Livewire.dispatch('deleteAuthorAction', event.detail.id);
                }
            });
        });

        window.addEventListener('authorDeleted', function(event){
            Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
            icon: "success"
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush