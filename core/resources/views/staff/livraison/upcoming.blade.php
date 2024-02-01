@extends('staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Magasin Expéditeur - Staff')</th>
                                    <th>@lang('Magasin Destinataire - Client')</th>
                                    <th>@lang('Montant - Numéro Commande')</th>
                                    <th>@lang('Date de création')</th>
                                    <th>@lang("Date estimée d'envoi")</th>
                                    <th>@lang('Status de paiement')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonLists as $livraisonInfo)
                                    <tr>
                                    <tr>
                                        <td>
                                            <span>{{ __($livraisonInfo->senderMagasin->name) }}</span><br>
                                            {{ __($livraisonInfo->senderStaff->fullname) }}
                                        </td>

                                        <td>
                                            <span>
                                                @if ($livraisonInfo->receiver_magasin_id)
                                                    {{ __($livraisonInfo->receiverMagasin->name) }}
                                                @else
                                                    @lang('N/A')
                                                @endif
                                            </span>
                                            <br>
                                            @if ($livraisonInfo->receiver_client_id)
                                                {{ __($livraisonInfo->receiver_name) }}
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold d-block">
                                                {{ getAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}
                                            </span>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($livraisonInfo->created_at, 'd M Y') }}
                                        </td>
                                        <td>
                                            {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}
                                        </td>
                                        <td>
                                            @if ($livraisonInfo->paymentInfo->status == Status::PAID)
                                                <span class="badge badge--success">@lang('Payé')</span>
                                            @elseif($livraisonInfo->paymentInfo->status == Status::UNPAID)
                                                <span class="badge badge--danger">@lang('Impayé')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang('Details')</a>
                                            @if($livraisonInfo->paymentInfo->status == Status::UNPAID)
                                                <button type="button" class="btn btn-outline--success m-1 payment"
                                                    data-code="{{ $livraisonInfo->code }}">
                                                    <i class="fa fa-credit-card"></i> @lang("Confirmer le Paiement")
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                data-action="{{ route('staff.livraison.receive', $livraisonInfo->id) }}"
                                                data-question="@lang('Etre-vous sûr de vouloir confirmer la reception de cette livraison?')">
                                                <i class="las la-check-circle"></i> @lang('Reçu')
                                            </button>
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

                @if ($livraisonLists->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonLists) }}
                    </div>
                @endif
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
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Livraison Code" />

    <x-date-filter placeholder="Date de Début - Date de Fin" />
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush