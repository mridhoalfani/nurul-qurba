@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Categories')

@section('content')

    <div class="page-header d-print-none">
        <div class="row alighn-items-center">
            <div class="col">
                <div class="page-title">
                    Categories & Subcategories
                </div>
            </div>
        </div>
    </div>

    @livewire('categories')
@endsection
@push('scripts')
    <script>
        window.addEventListener('hideCategoryModel', function(e) {
            jQuery('#categories_modal').modal('hide');
        });

        window.addEventListener('showcategoriesModal', function(e) {
            jQuery('#categories_modal').modal('show');
        });

        window.addEventListener('hideSubCategoriesModal', function(e) {
            jQuery('#subcategories_modal').modal('hide');
        });

        window.addEventListener('showSubCategoriesModal', function(e) {
            jQuery('#subcategories_modal').modal('show');
        });

        jQuery('#categories_modal,#subcategories_modal').on('hidden.bs.modal', function(e) {
            Livewire.dispatch('resetModalForm');
        });

        window.addEventListener('deleteCategory', function(event) {
            Swal.fire({
                title: "Are you sure?",
                html: "You want to delete this category.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                width: 300
            }).then(function(result) {
                if (result.value) {
                    Livewire.dispatch('deleteCategoryAction', event.detail.id);
                }
            });
        });

        window.addEventListener('categoryDeleted', function(event) {
            Swal.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
                icon: "success"
            });
        });

        window.addEventListener('deleteSubCategory', function(event) {
            Swal.fire({
                title: "Are you sure?",
                html: "You want to delete this Subcategory.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                width: 300
            }).then(function(result) {
                if (result.value) {
                    Livewire.dispatch('deleteSubCategoryAction', event.detail.id);
                }
            });
        });

        jQuery('table tbody#sortable_category').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr("data-ordering") != (index + 1)) {
                        $(this).attr("data-ordering", (index + 1)).addClass("updated");
                    }
                });
                var positions = [];
                $(".updated").each(function() {
                    positions.push([$(this).attr("data-index"), $(this).attr("data-ordering")]);
                    $(this).removeClass("updated");
                });
                // alert(positions);
                window.Livewire.dispatch("updateCategoryOrdering", positions);
            }
        });
    </script>

    {{-- HARUS MENAMBAHKAN CDN SWEETALERT SECARA MANUAL unutk memunculkan sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@endpush
