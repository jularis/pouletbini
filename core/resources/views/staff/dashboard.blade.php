@extends('staff.layouts.app')
@section('panel')
    <div class="row gy-4">
        
        
 
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--pink has-link box--shadow2">
                <a href="{{ route('staff.livraison.delivery.queue') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lab la-accessible-icon f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang("En attente de reception")</span>
                            <h2 class="text-white">{{ $deliveryInQueue }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
 

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--deep-purple has-link box--shadow2">
                <a href="{{ route('staff.livraison.manage.delivered') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-list-alt f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Livré')</span>
                            <h2 class="text-white">{{ $totalDelivery }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->
      
        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--orange has-link box--shadow2">
                <a href="{{ route('staff.cash.livraison.income') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-money-bill-wave f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Total Revenus')</span>
                            <h2 class="text-white">{{ showAmount($cashCollection) }} {{ $general->cur_sym }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- dashboard-w1 end -->

        <div class="col-xxl-4 col-sm-6">
            <div class="card bg--teal has-link box--shadow2">
                <a href="{{ route('staff.livraison.manage.list') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las  la-shipping-fast f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text-white text--small">@lang('Toutes les Livraisons')</span>
                            <h2 class="text-white">{{ $totalLivraison }}</h2>
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


@push('script')
    <script>
        (function() {
            'use strict';
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });

            $('.delivery').on('click', function() {
                var modal = $('#deliveryBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery())
    </script>
@endpush
