@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body"> 
                    <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Ferme')</label>
                            <div class="col-xs-12 col-sm-8">
                             {{ $arrivage->bande->ferme->nom }}
                            </div>
                        </div>
                    <div class="form-group row">
                        {{ Form::label(__('Numero de la Bande'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                        {{ $arrivage->bande->numero_bande }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Quantité de poulets arrivée'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                           {{$arrivage->total_poulet}}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                           {{$arrivage->date_arrivage}}
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <table class="table table-striped table-bordered">
    <thead>
            <tr> 
                <th>Categorie</th>
                <th>Prix(FCFA)</th>
                <th class="text-center">Quantité</th>
                <th class="text-center">Découpes</th>
                <td class="text-center">  
        </td>
            </tr>
        </thead>
    <tbody>
        @foreach($arrivage->arrivageDetail->where('niveau',0) as $data)
 <tr> 
            <td> 
            {{ $data->categorie->name}}
            </td>
            <td> 
            {{ $data->price}}
            </td>
            <td class="text-center"> 
                {{ $data->quantity_restante }}
        </td>
        
        <td class="text-center"> 
            @if($data->decoupeDetail->count())
        <table class="table table-striped table-bordered internalTable">
                                            <thead>
                                                    <tr> 
                                                        <th>Categorie</th>
                                                        <th>Prix(FCFA)</th>
                                                        <th class="text-center">Quantité</th> 
                                                    </tr>
                                                </thead>
                                                <body>
                                                @foreach($data->decoupeDetail as $data2)
                                                    <tr class="single-item"> 
                                                        <td> 
                                                        {{ $data2->categorie->name}}
                                                        </td>
                                                        <td>  
                                                        {{ $data2->price}}
                                                        </td> 
                                                        <td>
                                                        {{ $data2->quantity_restante }} 
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </body>
                                            </table>
                @endif
        </td>
        <td class="text-center">  
            @if($data->send ==0)
        <button type="button" class="confirmationBtn btn btn-sm btn-outline--danger"
                                                        data-action="{{ route('admin.arrivage.send', $data->id) }}"
                                                        data-question="@lang('Etes-vous sûr d\'envoyer ce produit?')">
                                                        <i class="la la-eye"></i> @lang('Envoyer')
                                                    </button>
            @else
            <span class="badge badge--success">Envoyé au magasin</span>
            @endif
        </td>
        </tr>
        @endforeach
    </tbody> 
</table>  
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.arrivage.index') }}" />
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

$('.dates').datepicker({
                maxDate: new Date(),
                range: false,
                multipleDatesSeparator: "-",
                language: 'fr'
            });
    </script>
@endpush