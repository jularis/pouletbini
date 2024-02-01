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
                                    <th>@lang('Magasin Expéditeur - Staff')</th>
                                    <th>@lang('Magasin Destinataire - Client')</th>
                                    <th>@lang('Montant - Numéro Commande')</th>
                                    <th>@lang('Date de création')</th>
                                    <th>@lang('Status de paiement')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonInfos as $livraisonInfo)
                                    <tr>
                                        <td>
                                            <span>{{ __($livraisonInfo->senderMagasin->name) }}</span><br>
                                            <a class="text--primary" href="{{ route('manager.livraison.edit', encrypt($livraisonInfo->senderStaff->id)) }}">
                                                <span class="text--primary">@</span>{{ __($livraisonInfo->senderStaff->username) }}
                                            </a>
                                        </td>
                                        <td>
                                        <span class="fw-bold">
                                            {{ __($livraisonInfo->receiverClient->address) }}
                                            </span>
                                            <br>
                                            @if ($livraisonInfo->receiver_client_id)
                                                {{ __($livraisonInfo->receiverClient->name) }}
                                                {{ __($livraisonInfo->receiverClient->phone) }}
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}</span><br>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($livraisonInfo->created_at, 'd M Y') }}<br>
                                            {{ diffForHumans($livraisonInfo->created_at) }}
                                        </td>

                                        <td>
                                            @if ($livraisonInfo->paymentInfo->status == Status::PAID)
                                                <span class="badge badge--success">@lang('Payé')</span>
                                            @elseif($livraisonInfo->paymentInfo->status == Status::UNPAID)
                                                <span class="badge badge--danger">@lang('Impayé')</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($livraisonInfo->status == Status::COURIER_QUEUE)
                                                <span class="badge badge--danger">@lang("En attente d'envoi")</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DISPATCH)
                                                @if (auth()->user()->magasin_id == $livraisonInfo->sender_magasin_id)
                                                    <span class="badge badge--warning">@lang('Expédiée')</span>
                                                @else
                                                    <span class="badge badge--warning">@lang('Encours')</span>
                                                @endif
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE)
                                                <span class="badge badge--primary">@lang("En attente de reception")</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang("Livré")</span>
                                            @endif
                                        </td>
                                        <td>
                                        <a href="{{ route('manager.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i>@lang('Facture')</a>
                                            <a href="{{ route('manager.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i>@lang('Details')</a>
                                            <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                data-action="{{ route('manager.livraison.dispatched', $livraisonInfo->id) }}"
                                                data-question="@lang('Etre-vous sûr de vouloir expédier cette livraison?')">
                                                <i class="las la-arrow-circle-right"></i>@lang('Expédiée')
                                            </button>
                                            <a href="{{ route('manager.livraison.edit', encrypt($livraisonInfo->id)) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </a>
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
                @if ($livraisonInfos->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonInfos) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Livraison Code" />
<x-date-filter placeholder="Date de Début - Date de Fin"/>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $(".childCheckBox").on('change', function(e) {
                let totalLength = $(".childCheckBox").length;
                let checkedLength = $(".childCheckBox:checked").length;
                if (totalLength == checkedLength) {
                    $('.checkAll').prop('checked', true);
                } else {
                    $('.checkAll').prop('checked', false);
                }
                if (checkedLength) {
                    $('.dispatch').removeClass('d-none')
                } else {
                    $('.dispatch').addClass('d-none')
                }
            });

            $('.checkAll').on('change', function() {
                if ($('.checkAll:checked').length) {
                    $('.childCheckBox').prop('checked', true);
                } else {
                    $('.childCheckBox').prop('checked', false);
                }
                $(".childCheckBox").change();
            });
            $('#dispatch_all').on('click', function() {
                let ids = [];
                $('.childCheckBox:checked').each(function() {
                    ids.push($(this).attr('data-id'))
                })
                let id = ids.join(',')
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('manager.livraison.dispatch') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        notify('success', 'Livraison dispatched successfully')
                        location.reload();
                    }
                })
            });

        })(jQuery)
    </script>
@endpush

