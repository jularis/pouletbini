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
                                <th>@lang('Unite')</th>
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Prix')</th>
                                    <th>@lang('Niveau')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $categorie)
                                    <tr>
                                    <td>{{ __($categorie->unite->name) }}</td>
                                        <td>{{ __($categorie->name) }}</td>
                                        <td>{{ __($categorie->price) }}</td>
                                        <td>{{ __($categorie->niveau) }}</td>
                                        <td> @php  echo $categorie->statusBadge; @endphp </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary updateCategorie"
                                                data-id="{{ $categorie->id }}"
                                                data-unite="{{ $categorie->unite_id }}" 
                                                data-price="{{ $categorie->price }}" 
                                                data-niveau="{{ $categorie->niveau }}"
                                                data-name="{{ $categorie->name }}"><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($categorie->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.status', $categorie->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce categorie?')">
                                                    <i class="la la-eye"></i> @lang("Activer")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.status', $categorie->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce categorie?')">
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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="categorieModel" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ajouter Categorie')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i></button>
                </div>
                <form action="{{ route('admin.livraison.categorie.store') }}" class="resetForm" method="POST">
                    @csrf

                    <input type="hidden" name='id'>
                   
                    <div class="modal-body">
                    <div class="form-group">
                            <label>@lang('Unite')</label>
                            <select class="form-control" name="unite" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($unites as $unite)
                                    <option value="{{ $unite->id }}">{{ __($unite->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nom de la Categorie')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Prix')</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="price" required>
                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Niveau')</label>
                            <select class="form-control" name="niveau" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                <option value="0">0</option> 
                                <option value="1">1</option> 
                            </select>
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
    <button class="btn btn-sm btn-outline--primary addCategorie"><i class="las la-plus"></i>@lang("Créer un nouveau")</button>
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
                modal.find('input[name=price]').val($(this).data('price'));
                modal.find('select[name=unite]').val($(this).data('unite'));
                modal.find('select[name=niveau]').val($(this).data('niveau'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
