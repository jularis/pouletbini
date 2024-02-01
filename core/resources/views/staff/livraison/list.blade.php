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
                                    <th>@lang("Date estimée d'envoi")</th>
                                    <th>@lang('Status de paiement')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonLists as $livraisonInfo)
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
                                                {{ __($livraisonInfo->receiver_phone) }}
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold d-block">
                                                {{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
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
                                            @if (@$livraisonInfo->paymentInfo->status == Status::PAID)
                                                <span class="badge badge--success">@lang('Payé')</span>
                                            @elseif(@$livraisonInfo->paymentInfo->status == Status::UNPAID)
                                                <span class="badge badge--danger">@lang('Impayé')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($livraisonInfo->status == Status::COURIER_QUEUE)
                                                <span class="badge badge--warning">@lang("En attente d'expédition")</span>
                                            @elseif ($livraisonInfo->status == Status::COURIER_DISPATCH)
                                                @if (auth()->user()->magasin_id == $livraisonInfo->sender_magasin_id)
                                                    <span class="badge badge--warning">@lang('Expédiée')</span>
                                                @else
                                                    <span class="badge badge--primary">@lang('Encours')</span>
                                                @endif
                                            @elseif ($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE)
                                                <span class="badge badge--danger">@lang('En attente de reception')</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang('Livré')</span>
                                            @endif
                                        </td>

                                        <td>
                                            
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang('Details')</a>
                                        @if($livraisonInfo->estimate_date < gmdate('Y-m-d H:i:s'))
                                        <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>

                                                    @else
                                            <span class="badge badge--dark">@lang('Prochaine livraison') le {{showDateTime($livraisonInfo->estimate_date, 'd M Y')}}</span>
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

                @if ($livraisonLists->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonLists) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <x-search-form placeholder="Recherche ici..." />
    <x-date-filter placeholder="Date de Début - Date de Fin" />
@endpush
