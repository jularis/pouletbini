@extends('manager.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Categorie')</th>
                                    <th>@lang('Prix')</th>
                                    <th>@lang('Quantite')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produits as $produit)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($produit->name) }}</span>
                                        </td>

                                        <td>
                                            <span>{{ __($produit->categorie->name) }}</span>
                                        </td>

                                        <td>
                                            <span>{{ showAmount($produit->price) }} {{ __($general->cur_text) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ __($produit->quantity) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                echo $produit->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            <span class="d-block">{{ showDateTime($produit->updated_at) }}</span>
                                            <span>{{ diffForHumans($produit->updated_at) }}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateCategorie"
                                                data-id="{{ $produit->id }}" data-name="{{ $produit->name }}"
                                                data-price="{{ getAmount($produit->price) }}"
                                                data-quantity="{{ getAmount($produit->quantity) }}"
                                                data-categorie="{{ $produit->categorie_id }}"><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($produit->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('manager.livraison.categorie.produit.status', $produit->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce produit?')">
                                                    <i class="la la-eye"></i> @lang("Activer")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('manager.livraison.categorie.produit.status', $produit->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce produit?')">
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
                @if ($produits->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($produits) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div id="categorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ajouter Livraison produit')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="{{ route('manager.livraison.categorie.produit.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                         
                        <div class="form-group">
                            <label>@lang('Select Categorie')</label>
                            <select class="form-control" name="categorie" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ __($categorie->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nom')</label>
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
                            <label>@lang('Quantity')</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="quantity" required></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang("Envoyer")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateCategorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Livraison produit')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('manager.livraison.categorie.produit.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                         
                        <div class="form-group">
                            <label>@lang('Select Categorie')</label>
                            <select class="form-control" name="categorie" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ __($categorie->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nom')</label>
                            <input type="text" class="form-control" name="name" placeholder="@lang('Entrer le nom du produit')"
                                required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Prix')</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="@lang('Entrer le Prix')" name="price"
                                    required>
                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Quantite')</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="@lang('Entrer la Quantité')" name="quantity"
                                    required> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang("Envoyer")</button>
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
            });

            $('.updateCategorie').on('click', function() {
                var modal = $('#updateCategorieModel');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=price]').val($(this).data('price'));
                modal.find('input[name=quantity]').val($(this).data('quantity'));
                modal.find('select[name=categorie]').val($(this).data('categorie'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
