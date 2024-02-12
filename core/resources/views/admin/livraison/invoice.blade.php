@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div id="printInvoice">
                <div class="content-header">
                    <h3>
                        @lang("Numéro Facture") :
                        <small>#{{ $livraisonInfo->invoice_id }}</small>
                        <br>
                        @lang('Date'):
                        {{ showDateTime($livraisonInfo->created_at) }}
                        <br>
                        @lang("Date estimée d'envoi") :
                        {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}

                    </h3>
                </div>

                <div class="invoice">
                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-center">
                        {!! QrCode::size(150)->generate($livraisonInfo->code) !!}
                        </div>
                        <div>
                            <b>@lang('N° Commande') : {{ $livraisonInfo->code }}</b><br>
                            <b>@lang('Status de paiement'):</b>
                            @if (@$livraisonPayment->status == Status::PAID)
                                <span class="badge badge--success">@lang('Payé')</span>
                            @else
                                <span class="badge badge--danger">@lang('Impayé')</span>
                            @endif
                            <br>
                            <b>@lang("Magasin d'Expédition"):</b> {{ __($livraisonInfo->senderMagasin->name) }}<br>
                            <b>@lang("Magasin de Recepiton"):</b> {{ __($livraisonInfo->receiverMagasin->name) }}

                        </div>
                    </div>
                    <hr>
                    <div class=" invoice-info d-flex justify-content-between">
                        <div>
                            @lang("De")
                            <address>
                                <strong>{{ __($livraisonInfo->sender_name) }}</strong><br>
                                {{ __($livraisonInfo->sender_address) }}<br>
                                @lang('Téléphone'): {{ $livraisonInfo->sender_phone }}<br>
                                @lang('Email'): {{ $livraisonInfo->sender_email }}
                            </address>
                        </div>
                        <div>
                            @lang("A")
                            <address>
                                <strong>{{ __($livraisonInfo->receiver_name) }}</strong><br>
                                {{ __($livraisonInfo->receiver_address) }}<br>
                                @lang('Téléphone'): {{ $livraisonInfo->receiver_phone }}<br>
                                @lang('Email'): {{ $livraisonInfo->receiver_email }}
                            </address>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('Produit')</th>
                                        <th>@lang("Date d'envoi")</th>
                                        <th>@lang('Prix')</th>
                                        <th>@lang('Qte')</th>
                                        <th> </th>
                                        <th>@lang('Sous-total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($livraisonInfo->products as $livraisonProductInfo)
                                        <tr @if($livraisonProductInfo->etat==0) style="opacity:0.5" @endif>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ __($livraisonProductInfo->produit->name) }}</td>
                                            <td>{{ showDateTime($livraisonProductInfo->created_at) }}</td>
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

                    <div class="row mt-30 mb-none-30">
                        <div class="col-lg-12 mb-30">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <th>@lang('Sous-total'):</th>
                                        <td>{{ showAmount(@$livraisonInfo->payment->amount) }} {{ $general->cur_sym }}
                                        </td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Reduction'):</th>
                                            <td>{{ showAmount(@showAmount($livraisonInfo->payment->discount)) }} {{ $general->cur_sym }}
                                                <small class="text--danger">
                                                    ({{ getAmount($livraisonInfo->payment->percentage) }}%)
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Frais de livraison'):</th>
                                            <td>{{ showAmount($livraisonInfo->payment->frais_livraison) }} {{ $general->cur_sym }} 
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Total'):</th>
                                            <td>{{ showAmount(@$livraisonInfo->payment->final_amount) }} {{ $general->cur_sym }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row no-print">
                <div class="col-sm-12">
                    <div class="float-sm-end">
                        <button class="btn btn-outline--primary printInvoice"><i
                                class="las la-download"></i>@lang("Imprimer")
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.livraison.info.index') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.printInvoice').click(function() {
                $("#printInvoice").printThis();
            });
        })(jQuery)
    </script>
@endpush
