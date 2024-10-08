@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <input type="hidden" name="table" value="arrivages" />
                            <div class="flex-grow-1">
                                <label>@lang('Recherche par Mot(s) clé(s)')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Ferme')</label>
                                <select name="ferme" class="form-control" id="ferme">
                                    <option value="">@lang("Tous")</option>  
                                    @foreach($fermes as $ferme)
                                        <option value="{{ $ferme->id }}" {{ request()->ferme == $ferme->id ? 'selected' : '' }} >
                                        {{ $ferme->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Bande')</label>
                                <select name="bande" class="form-control" id="bande">
                                    <option value="">@lang("Tous")</option>  
                                    @foreach($bandes as $bande)
                                        <option value="{{ $bande->id }}" {{ request()->bande == $bande->id ? 'selected' : '' }} >
                                        {{ $bande->numero_bande }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="dates form-control"
                                    placeholder="@lang('Date de début - Date de fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Ferme')</th>
                                    <th>@lang('Numero de Bande')</th> 
                                    <th>@lang('Quantité')</th>
                                    <th>@lang('Date arrivage')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($arrivages as $arrivage)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $arrivage->bande->ferme->nom }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $arrivage->bande->numero_bande }}</span>
                                        </td> 
                                        <td>
                                            <span class="fw-bold">{{ $arrivage->total_poulet }}</span>
                                        </td>     
                                        <td>
                                            <span class="d-block">{{ showDateTime($arrivage->date_arrivage) }}</span>
                                            <span>{{ diffForHumans($arrivage->date_arrivage) }}</span>
                                        </td>
                                        <td> @php echo $arrivage->statusBadge; @endphp </td>
                                        <td>
                                        <a href="{{ route('admin.arrivage.decoupe', $arrivage->id) }}"
                                                class="btn btn-sm btn-outline--info"><i class="las la-pen"></i>
                                                    @lang('Découpes')</a>
                                    <a href="{{ route('admin.arrivage.show', $arrivage->id) }}"
                                                    class="btn btn-sm btn-outline--primary"><i class="la la-pen"></i>@lang('Stock')</a>
                                       <a href="{{ route('admin.arrivage.edit', $arrivage->id) }}"
                                                    class="btn btn-sm btn-outline--danger"><i class="la la-pen"></i>@lang('Edit')</a>
                                <a href="javascript:void();"
                                                class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="{{ route('admin.arrivage.delete', encrypt($arrivage->id)) }}"
                                                data-question="@lang('Êtes-vous sûr de supprimer cet arrivage?')"
                                                ><i
                                                    class="las la-trash"></i>@lang('Supprimer')</a>
                                            

                                            </div>
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
                @if ($arrivages->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($arrivages) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
 
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.arrivage.create') }}" class="btn  btn-outline--primary h-45 addNewCooperative">
        <i class="las la-plus"></i>@lang('Ajouter un arrivage')
    </a>  
@endpush
@push('style')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
    </style>
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
