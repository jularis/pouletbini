@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
               <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Numéro de Commande')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Staff')</label>
                                <select name="staff" class="form-control">
                                    <option value="">@lang("Tous")</option>  
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}" {{ request()->staff == $staff->id ? 'selected' : '' }} >
                                        {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status de livraison')</label>
                                <select name="status" class="form-control">
                                    <option value="">@lang("Tous")</option> 
                                    <option value="2">@lang('En attente')</option>
                                    <option value="3">@lang('Livré')</option>
                                    <option value="1">@lang('Annulé')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status de paiement')</label>
                                <select name="payment_status" class="form-control">
                                    <option value="" selected>@lang("Tous")</option>
                                    <option value="1">@lang('Payé')</option>
                                    <option value="0">@lang('Impayé')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="date form-control" placeholder="@lang('Date de début - Date de Fin')" autocomplete="off" value="{{ request()->date }}">
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

                                    <th>@lang('Magasin Expéditeur - Staff')</th>
                                    <th>@lang('Destinataire - Client')</th>
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
                                            <span class="fw-bold d-block">
                                                {{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}
                                            </span>
                                            <span>{{ $livraisonInfo->code }}</span>
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
                                            @elseif ($livraisonInfo->status == Status::COURIER_CANCEL)
                                            <span class="badge badge--warning">@lang('Annulé')</span>
                                            @elseif ($livraisonInfo->status == Status::COURIER_DELIVERYQUEUE)
                                                <span class="badge badge--danger">@lang('En attente de reception')</span>
                                            @elseif($livraisonInfo->status == Status::COURIER_DELIVERED)
                                                <span class="badge badge--success">@lang('Livré')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('manager.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>
                                            <a href="{{ route('manager.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang('Details')</a>
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
    <badge class="btn btn-danger">{{ showAmount(@$sommeTotal) }} FCFA</badge>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/viseradmin/css/vendor/datepicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/viseradmin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{asset('assets/viseradmin/js/vendor/datepicker.fr.js')}}"></script>
    <script src="{{ asset('assets/viseradmin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            $('.date').datepicker({
                // maxDate:new Date(),
                range:true,
                multipleDatesSeparator:"-",
                language:'fr'
            });

            let url=new URL(window.location).searchParams;
            if(url.get('status') != undefined && url.get('status') != ''){
                $('select[name=status]').find(`option[value=${url.get('status')}]`).attr('selected',true);
            }
            if(url.get('payment_status') != undefined && url.get('payment_status') != ''){
                $('select[name=payment_status]').find(`option[value=${url.get('payment_status')}]`).attr('selected',true);
            }

        })(jQuery)

        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush