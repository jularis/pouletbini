@extends('staff.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-body">
            <div id="printInvoice">
                <div class="content-header">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">
                            @lang("Numéro Facture"):
                            <small>#{{ $livraisonInfo->invoice_id }}</small>
                            <br>
                            @lang('Date'):
                            {{ showDateTime($livraisonInfo->created_at, 'd M Y') }}
                            <br>
                            @lang("Date estimée d'envoi"):
                            {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}
                        </div>
                        <div>
                        </div>
                    </div>
                </div>

                <div class="invoice">
                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-center">
                        <?php echo QrCode::size(150)->generate($livraisonInfo->code); ?>
                             
                        </div>
                        <div>
                            <b>@lang("N° Commande"):</b> {{ $livraisonInfo->code }}<br>
                            <b>@lang('Status de paiement'):</b>
                            @if ($livraisonInfo->payment->status == Status::PAID)
                                <span class="badge badge--success">@lang('Payé')</span>
                            @else
                                <span class="badge badge--danger">@lang('Impayé')</span>
                            @endif
                            <br>
                            <b>@lang('Sender Magasin'):</b> {{ __($livraisonInfo->senderMagasin->name) }}<br>
                            <b>@lang('Receiver Magasin'):</b> {{ __($livraisonInfo->receiverMagasin->name) }}
                        </div>
                    </div>
                    <hr>
                    <div class="invoice-info d-flex justify-content-between">
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
                                        <th>@lang('Prix')</th>
                                        <th>@lang('Qte')</th>
                                        <th>@lang('Sous-total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($livraisonInfo->products as $livraisonProductInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ __(@$livraisonProductInfo->produit->name) }}</td>
                                            <td>{{ showAmount($livraisonProductInfo->fee) }} {{ $general->cur_sym }}</td>
                                            <td>{{ $livraisonProductInfo->qty }} {{ __(@$livraisonProductInfo->produit->categorie->name) }}</td>
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
                                        <tr>
                                            <th>@lang('Sous-total'):</th>
                                            <td>{{ showAmount($livraisonInfo->payment->amount) }} {{ $general->cur_sym }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Reduction'):</th>
                                            <td>{{ showAmount(showAmount($livraisonInfo->payment->discount)) }} {{ $general->cur_sym }}
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
                                            <td>{{ showAmount($livraisonInfo->payment->final_amount) }} {{ $general->cur_sym }}
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
                        @if ($livraisonInfo->paymentInfo->status == Status::UNPAID)
                            <button type="button" class="btn btn-outline--success m-1 payment"
                                data-code="{{ $livraisonInfo->code }}">
                                <i class="fa fa-credit-card"></i> @lang("Effectuer le Paiement")
                            </button>
                        @endif
                        <button class="btn btn-outline--primary m-1 printInvoice">
                            <i class="las la-download"></i>@lang("Imprimer")
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" a>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation de Paiement')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i> </button>
                </div>

                <form action="{{ route('staff.livraison.payment') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Etes-vous sûr d\'avoir encassé ce montant?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang("Non")</button>
                        <button type="submit" class="btn btn--primary">@lang("Oui")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.printInvoice').click(function() {
                $("#printInvoice").printThis();
            });
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
