@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Entrer un nom')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                             
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filtrer')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Type fournisseur')</th>
                                    <th>@lang('Téléphone')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Adresse')</th>
                                    <th>@lang('Itinéraire')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fournisseurs as $fournisseur)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($fournisseur->nom) }}</span>
                                        </td>

                                        <td>
                                            <span>{{ __($fournisseur->type_fournisseur) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ __($fournisseur->phone) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ __($fournisseur->email) }}</span>
                                        </td>

                                        <td>
                                            <span>{{ __($fournisseur->address) }}</span>
                                        </td>
<td>
                                            @if($fournisseur->longitude)
                                            <span><a href="https://maps.google.com?daddr={{ __($fournisseur->latitude) }},{{ __($fournisseur->longitude) }}" target="_blank"><i class="fa fa-map-marker"></i> Voir l'itinéraire
    </a></span>@endif
                                        </td>
                                        <td>
                                            @php
                                                echo $fournisseur->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            <span class="d-block">{{ showDateTime($fournisseur->updated_at) }}</span>
                                            <span>{{ diffForHumans($fournisseur->updated_at) }}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary  updateCategorie"
                                                data-id="{{ $fournisseur->id }}" 
                                                data-name="{{ $fournisseur->nom }}"
                                                data-type="{{ $fournisseur->type_fournisseur }}"
                                                data-phone="{{ $fournisseur->phone }}"
                                                data-address="{{ $fournisseur->address }}"
                                                data-email="{{ $fournisseur->email }}"
                                                data-longitude="{{ $fournisseur->longitude }}"
                                                data-latitude="{{ $fournisseur->latitude }}"
                                                ><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($fournisseur->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.fournisseur.status', $fournisseur->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce fournisseur?')">
                                                    <i class="la la-eye"></i> @lang("Activer")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.livraison.categorie.fournisseur.status', $fournisseur->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce fournisseur?')">
                                                    <i class="la la-eye-slash"></i>@lang("Désactiver")
                                                </button>
                                            @endif
                                            <a href="javascript:void();"
                                                class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.livraison.categorie.fournisseur.delete', encrypt($fournisseur->id)) }}"
                                                data-question="@lang('Êtes-vous sûr de supprimer ce fournisseur?')"
                                                ><i
                                                    class="las la-trash"></i>@lang('Supprimer')</a>
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
                @if ($fournisseurs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($fournisseurs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div id="categorieModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Ajouter Livraison fournisseur')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="{{ route('admin.livraison.categorie.fournisseur.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label>@lang('Select Type')</label>
                            <select class="form-control" name="type_fournisseur" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                <option value="{{ __('Entreprise') }}">{{ __('Entreprise') }}</option> 
                                <option value="{{ __('Particulier') }}">{{ __('Particulier') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Nom & Prénom(s)/Entreprise')</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
 

                        <div class="form-group">
                            <label>@lang('Téléphone')</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="text" class="form-control" name="email" >
                        </div>
                        <div class="form-group">
                            <label>@lang('Adresse')</label>
                            <input type="text" class="form-control" name="address" >
                        </div>
                        <div class="form-group">
            {{ Form::label(__('Longitude'), null, ['class' => 'control-label']) }} 
            {!! Form::text('longitude', null, array('placeholder' => __('Longitude'),'class' => 'form-control','id'=>'longitude')) !!} 
    </div>
    <div class="form-group">
            {{ Form::label(__('Latitude'), null, ['class' => 'control-label']) }} 
            {!! Form::text('latitude', null, array('placeholder' => __('Latitude'),'class' => 'form-control','id'=>'latitude')) !!} 
    </div>
    <div class="form-group">
            {{ Form::label(__(''), null, ['class' => 'control-label']) }} 
            <p id="status"></p>
            <a href="javascript:void(0)" id="find-me" class="btn btn--info">Obtenir les coordonnées GPS</a> 
    </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45 ">@lang("Envoyer")</button>
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
            $('.addType').on('click', function() {
                $('#typeModel').modal('show');
            });
            $('.addCategorie').on('click', function() {
                $('#categorieModel').modal('show');
            });

            $('.updateCategorie').on('click', function() {
                var modal = $('#categorieModel');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('input[name=nom]').val($(this).data('name'));
                modal.find('input[name=phone]').val($(this).data('phone'));
                modal.find('input[name=email]').val($(this).data('email'));
                modal.find('input[name=address]').val($(this).data('address'));
                modal.find('select[name=type_fournisseur]').val($(this).data('type'));
                modal.find('input[name=latitude]').val($(this).data('latitude'));
                modal.find('input[name=longitude]').val($(this).data('longitude'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
