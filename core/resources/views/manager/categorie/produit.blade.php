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
                                    <th>@lang('Categorie')</th>
                                    <th>@lang('Nom')</th>  
                                    <th>@lang('Prix Unitaire')</th> 
                                    <th>@lang('Quantite')</th>
                                    <th>@lang('Quantite Utilisée')</th>
                                    <th>@lang('Quantite Restante')</th>
                                    <th>@lang('Status')</th>  
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produits as $produit)
                                    <tr>
                                    <td>
                                            <span>{{ __($produit->categorie->name) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ __($produit->name) }}</span>
                                        </td>
                                        
                                        <td>
                                            <span>{{ showAmount($produit->price) }} {{ __($general->cur_text) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($produit->quantity) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($produit->quantity_use) }}</span>
                                        </td> 
                                        <td>
                                            <span class="fw-bold">{{ showAmount($produit->quantity - $produit->quantity_use) }}</span>
                                        </td> 
                                        <td>
                                            @php
                                                echo $produit->statusBadge;
                                            @endphp
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
