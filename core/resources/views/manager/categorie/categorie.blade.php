@extends('manager.layouts.app')
@section('panel')
<div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                <th>@lang('Unite')</th>
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Prix')</th>
                                    <th>@lang('Status')</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $categorie)
                                    <tr>
                                    <td>{{ __($categorie->unite->name) }}</td>
                                        <td>{{ __($categorie->name) }}</td>
                                        <td>{{ __($categorie->price) }}</td>
                                        <td> @php  echo $categorie->statusBadge; @endphp </td>
                                    
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


 
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins') 
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addCategorie').on('click', function() {
                $('#categorieModel').modal('show');
                $('.resetForm').trigger('reset');
            });

            $('.updateCategorie').on('click', function() {
                let title = "Update Categorie"
                let id = $(this).data('id');
                let name = $(this).data('name');
                var modal = $('#categorieModel');
                modal.find('.modal-title').text(title);
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
