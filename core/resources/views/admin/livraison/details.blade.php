@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
         

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="row mb-30">
                <div class="col-lg-6 ">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Informations Expéditeur')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Magasin')
                                    <span>{{ __($livraisonInfo->receiverMagasin->name) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Nom')
                                    <span>{{ __($livraisonInfo->sender_name) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Email')
                                    <span>{{ $livraisonInfo->sender_email }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Téléphone')
                                    <span>{{ $livraisonInfo->sender_phone }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Adresse')
                                    <span>{{ __($livraisonInfo->sender_address) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Numéro de Commande')
                                    <spann class="fw-bold">{{ $livraisonInfo->code }}</spann>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Informations du Destinataire')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Nom')
                                    <span>{{ __($livraisonInfo->receiver_name) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Email')
                                    <span>{{ $livraisonInfo->receiver_email }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Téléphone')
                                    <span>{{ $livraisonInfo->receiver_phone }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Adresse')
                                    <span>{{ __($livraisonInfo->receiver_address) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status')
                                    @if ($livraisonInfo->status != Status::COURIER_DELIVERED)
                                        <span class="badge badge--primary fw-bold">@lang('En attente')</span>
                                    @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                        <span class="badge badge--success">@lang('Livré')</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Details de la livraison')</h5>
                        <div class="card-body">
                            <div class="table-responsive--md  table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('Produit')</th>
                                            <th>@lang('Prix')</th>
                                            <th>@lang('Qte')</th>
                                            <th> </th>
                                            <th>@lang('Sous-total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($livraisonInfo->products as $livraisonProductInfo)
                                            <tr @if($livraisonProductInfo->etat==0) style="opacity:0.5" @endif>
                                                <td>{{ __($livraisonProductInfo->produit->name) }}</td>
                                                <td>{{ showAmount($livraisonProductInfo->fee) }} {{ $general->cur_sym }}</td>
                                                <td>{{ $livraisonProductInfo->qty }}</td>
                                                <td>@if($livraisonProductInfo->etat==0) <span class="text-danger" style="font-size: 12px;"> Brouillon</span> @endif</td>
                                                <td>{{ showAmount($livraisonProductInfo->fee) }} {{ $general->cur_sym }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-lg-12 mt-2">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Informations de Paiement')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Paiement reçu par ')
                                    @if (!empty($livraisonInfo->paymentInfo->magasin_id))
                                        <span>{{ __(@$livraisonInfo->paymentInfo->magasin->name) }}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Date')
                                    @if (!empty($livraisonInfo->paymentInfo->date))
                                        <span>{{ showDateTime($livraisonInfo->date, 'd M Y') }}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Sous-total')
                                    <span>{{ showAmount($livraisonInfo->paymentInfo->amount) }}
                                        {{ __($general->cur_text) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Reduction')
                                    <span>
                                        {{ showAmount($livraisonInfo->paymentInfo->discount) }}
                                        {{ __($general->cur_text) }}
                                        <small class="text--danger">({{ getAmount($livraisonInfo->payment->percentage)}}%)</small>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Frais de livraison')
                                    <span>
                                        {{ showAmount($livraisonInfo->paymentInfo->frais_livraison) }}
                                        {{ __($general->cur_text) }} 
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Total')
                                    <span>{{ showAmount($livraisonInfo->paymentInfo->final_amount) }}
                                        {{ __($general->cur_text) }}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status')
                                    @if ($livraisonInfo->paymentInfo->status == Status::PAID)
                                        <span class="badge badge--success">@lang('Payé')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Impayé')</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.livraison.info.index') }}" />
@endpush
