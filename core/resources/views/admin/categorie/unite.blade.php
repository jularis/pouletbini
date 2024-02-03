@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr> 
                                    <th>@lang('Nom')</th> 
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unites as $unite)
                                    <tr> 
                                        <td>{{ __($unite->name) }}</td> 
                                        <td> @php  echo $unite->statusBadge; @endphp </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary updateUnite"
                                                data-id="{{ $unite->id }}" 
                                                data-name="{{ $unite->name }}"><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($unite->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.unite.status', $unite->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce unite?')">
                                                    <i class="la la-eye"></i> @lang("Activer")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.unite.status', $unite->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce unite?')">
                                                    <i class="la la-eye-slash"></i>@lang("Désactiver")
                                                </button>
                                            @endif
                                        </td>
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
                @if ($unites->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($unites) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="uniteModel" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ajouter Unite')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i></button>
                </div>
                <form action="{{ route('admin.livraison.categorie.unite.store') }}" class="resetForm" method="POST">
                    @csrf

                    <input type="hidden" name='id'>
                   
                    <div class="modal-body"> 
                        <div class="form-group">
                            <label>@lang('Nom de l\'unité')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div> 
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang("Envoyer")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary addUnite"><i class="las la-plus"></i>@lang("Créer un nouveau")</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addUnite').on('click', function() {
                $('#uniteModel').modal('show');
                $('.resetForm').trigger('reset');
            });

            $('.updateUnite').on('click', function() {
                let title = "Update Unite"
                let id = $(this).data('id');
                let name = $(this).data('name');
                var modal = $('#uniteModel');
                modal.find('.modal-title').text(title);
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name); 
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
