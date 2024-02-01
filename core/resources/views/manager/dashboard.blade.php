@extends('manager.layouts.app')
@section('panel')
    <div class="row gy-4">
         
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="{{ route('manager.livraison.delivery.queue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('En attente de reception')</span>
                            <h2 class="text-white">{{ $deliveryQueueCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
     

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="{{ route('manager.livraison.manage.delivered') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-list-alt f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Livré')</span>
                            <h2 class="text-white">{{ $livraisonDelivered }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--teal has-link box--shadow2">
                <a href="{{ route('manager.livraison.manage.list') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-shipping-fast f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Toutes les Commandes')</span>
                            <h2 class="text-white">{{ $livraisonInfoCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--primary has-link overflow-hidden box--shadow2">
                <a href="{{ route('manager.staff.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-user-friends f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Staff')</span>
                            <h2 class="text-white">{{ $totalStaffCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--lime has-link box--shadow2">
                <a href="{{ route('manager.magasin.index') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-university f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("Total Magasin")</span>
                            <h2 class="text-white">{{ $magasinCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="{{ route('manager.magasin.income') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Revenus')</span>
                            <h2 class="text-white">{{ showAmount($magasinIncome) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->





    </div><!-- row end-->

    <!-- <div class="row mt-50">
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
                                            <a href="{{ route('manager.staff.edit', encrypt($livraisonInfo->senderStaff->id)) }}"><span>@</span>{{ __($livraisonInfo->senderStaff->username) }}</a>
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
                                            @if ($livraisonInfo->receiver_staff_id)
                                                <a href="{{ route('manager.staff.edit', encrypt($livraisonInfo->receiverStaff->id)) }}"><span>@</span>{{ __($livraisonInfo->receiverStaff->username) }}</a>
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
                                            <span>{{ showDateTime($livraisonInfo->created_at, 'd M Y') }}</span><br>
                                            <span>{{ diffForHumans($livraisonInfo->created_at) }}</span>
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
                                                @if (auth()->user()->magasin_id == $livraisonInfo->sender_magasin_id)
                                                    <span class="badge badge--danger">@lang("En attente d'envoi")</span>
                                                @else
                                                    <span></span>
                                                @endif
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
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>
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
            </div>
        </div>
    </div> -->
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->magasin->name) }}</h3>
    </div>
@endpush
