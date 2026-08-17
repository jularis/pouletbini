@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Numero de commande')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Magasin')</label>
                                <select name="magasin" class="form-control">
                                    <option value="">@lang('Tous')</option>
                                    @foreach($magasins as $magasin)
                                        <option value="{{ $magasin->id }}" @selected(request()->magasin == $magasin->id)>{{ $magasin->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Supprime par')</label>
                                <select name="deleted_by_user_id" class="form-control">
                                    <option value="">@lang('Tous')</option>
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}" @selected(request()->deleted_by_user_id == $staff->id)>
                                            {{ $staff->lastname }} {{ $staff->firstname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date de suppression')</label>
                                <input name="date" type="text" class="date form-control" placeholder="@lang('Date de debut - Date de Fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filtrer')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Commande')</th>
                                    <th>@lang('Magasin expediteur')</th>
                                    <th>@lang('Magasin destinataire')</th>
                                    <th>@lang('Client')</th>
                                    <th>@lang('Montant')</th>
                                    <th>@lang('Supprime par')</th>
                                    <th>@lang('Date de suppression')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $history->code ?? 'N/A' }}</span><br>
                                            <small>@lang('Creee le') {{ showDateTime($history->order_created_at, 'd M Y') }}</small>
                                        </td>
                                        <td>{{ __(@$history->senderMagasin->name ?? 'N/A') }}</td>
                                        <td>{{ __(@$history->receiverMagasin->name ?? 'N/A') }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $history->receiver_name ?? 'N/A' }}</span><br>
                                            <small>{{ $history->receiver_phone }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($history->final_amount) }} {{ __($general->cur_text) }}</span><br>
                                            <small>{{ $history->products_count }} @lang('produit(s)')</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $history->deleted_by_name ?? 'N/A' }}</span><br>
                                            <small>{{ $history->deleted_by_type }}</small>
                                        </td>
                                        <td>
                                            {{ showDateTime($history->deleted_at, 'd M Y H:i') }}<br>
                                            {{ diffForHumans($history->deleted_at) }}
                                        </td>
                                        <td>
                                            @if($history->restored_at)
                                                <span class="badge badge--success">@lang('Restauree')</span><br>
                                                <small>{{ showDateTime($history->restored_at, 'd M Y H:i') }}</small>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('admin.livraison.delete.restore', $history->id) }}"
                                                    data-question="@lang('Etes-vous sur de vouloir restaurer cette commande?')">
                                                    <i class="las la-undo"></i> @lang('Restaurer')
                                                </button>
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
                @if ($histories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($histories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.livraison.info.index') }}" class="btn btn-sm btn-outline--primary"><i class="las la-list"></i> @lang('Commandes')</a>
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
                range: true,
                multipleDatesSeparator: "-",
                language: 'fr'
            });
        })(jQuery);

        $('form select').on('change', function(){
            $(this).closest('form').submit();
        });
    </script>
@endpush
