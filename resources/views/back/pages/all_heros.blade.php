@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'All Heros')

@section('content')
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title mb-2">
                    All Hero
                </h2>
            </div>
        </div>
    </div>

    @livewire('all-heros')

@endsection

@push('scripts')
    <script>
        window.addEventListener('deleteHero', function(event) {
            Swal.fire({
                title: "Are you sure?",
                html: "You want to delete this hero.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                width: 300
            }).then(function(result) {
                if (result.value) {
                    Livewire.dispatch('deleteHeroAction', event.detail.id);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endpush
