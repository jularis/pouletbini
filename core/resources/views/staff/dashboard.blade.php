@extends('staff.layouts.app')
@section('panel')
<div class="row gy-4">
        <div class="col-md-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                             
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="dates form-control"
                                    placeholder="@lang('Date de début - Date de fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filtrer')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     <!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="{{ route('admin.livraison.info.index') . '?status=2' }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("En attente de Livraison du mois")</span>
                            <h2 class="text-white">{{ $deliveryInQueue }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end --> 
 
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="#" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-10 text-end">
                            <span class="text-white text--small">@lang("Revenus Journaliers")</span>
                            <h2 class="text-white">{{ showAmount($totalIncomeDays) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--green has-link box--shadow2">
                <a href="#" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <i class="las la-dolly-flatbed f-size--56"></i>
                        </div>
                        <div class="col-10 text-end">
                            <span class="text-white text--small">@lang('Total Livraison Journaliere')</span>
                            <h2 class="text-white">{{ $totalLivraisonDays }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="#" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-dolly-flatbed f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Livraison du mois')</span>
                            <h2 class="text-white">{{ $livraisonInfoCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="#" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-dolly-flatbed f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Annulé du mois')</span>
                            <h2 class="text-white">{{ $livraisonInfoCountCancel }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
      
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="#" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-10 text-end">
                            <span class="text-white text--small">@lang("Total Revenus du mois")</span>
                            <h2 class="text-white">{{ showAmount($totalIncome) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

    </div><!-- row end-->
 
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->magasin->name) }}</h3>
    </div>
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
 
            $('.dates').datepicker({
                maxDate: new Date(),
                range: true,
                multipleDatesSeparator: "-",
                language: 'fr'
            });
 

        })(jQuery)

        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
@endpush