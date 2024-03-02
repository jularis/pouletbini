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
                    <div class="form-group row">
                    <div class="col-xs-12 col-sm-12">
                    <table class="table table-striped table-bordered table-responsive">
    <thead>
            <tr> 
                <th>Categorie</th>
                <th>Prix(FCFA)</th>
                <th class="text-center">Qté initiale</th> 
                <th class="text-center">Qté prélévée</th> 
                <th class="text-center">Qté restante</th> 
                <td class="text-center">   </td>
                <th class="text-center">Découpes</th>
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
                {{ $data->quantity }}
        </td>
        <td class="text-center"> 
                {{ $data->quantity_prelevee }}
        </td>
            <td class="text-center"> 
                {{ $data->quantity_restante }}
        </td>
        <td class="text-center">  
            @if($data->quantity_restante >0)
        <button type="button" class="modeEnvoi btn btn-sm btn-outline--danger"
        data-action="{{ route('admin.arrivage.send', $data->id) }}"
        data-initial="{{ $data->quantity }}" 
        data-restant="{{ $data->quantity_restante }}">
                                                        <i class="la la-eye"></i> @lang('Envoyer')
                                                    </button>
            @else
            <span class="badge badge--success">Envoyé au magasin</span>
            @endif
        </td>
        <td class="text-center"> 
            @if($data->decoupeDetail->count())
        <table class="table table-striped table-bordered table-responsive internalTable">
                                            <thead>
                                                    <tr> 
                                                        <th>Categorie</th>
                                                        <th>Prix(FCFA)</th>
                                                        <th>Qté initiale</th>
                                                        <th class="text-center">Qté restante</th> 
                                                        <th class="text-center"></th> 
                                                    </tr>
                                                </thead>
                                                <body>
                                                @php 
                                                    $total = 0;
                                                @endphp
                                                @foreach($data->decoupeDetail as $data2)
                                                       
                                                        <tr class="single-item"> 
                                                            <td> 
                                                            {{ $data2->categorie->name}}
                                                            </td>
                                                            <td>  
                                                            {{ $data2->price}}
                                                            </td> 
                                                            <td class="text-center">
                                                            {{ $data2->quantity }} 
                                                            </td>
                                                            <td class="text-center">
                                                            {{ $data2->quantity_restante }} 
                                                            </td>
                                                            <td class="text-center">  
                                                            @if($data2->quantity_restante >0)
                                                        <button type="button" class="modeEnvoi btn btn-sm btn-outline--danger"
                                                        data-action="{{ route('admin.arrivage.send', $data2->id) }}"
                                                        data-initial="{{ $data2->quantity }}" 
                                                        data-restant="{{ $data2->quantity_restante }}">
                                                            <i class="la la-eye"></i> @lang('Envoyer')
                                                        </button>
                                                            @else
                                                            <span class="badge badge--success">Envoyé au magasin</span>
                                                            @endif
                                                        </td>
                                                        </tr>
                                                        @php 
                                                            $total = $total + $data2->quantity
                                                        @endphp
                                                        @endforeach
                                                        <tr class="bg bg-info">
                                                            <td colspan="2">TOTAL</td>
                                                            <td>{{ $total }}</td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                </body>
                                            </table>
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
        </div>
    </div>
    <div class="modal fade" id="sendBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelAuto"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabelAuto">Confirmation d'envoi</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    @method('POST') 
                    <div class="modal-body">
                         
                        <div class="swal2-header">
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Quantité total:&nbsp;<span id="recu"></span>&nbsp;</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Quantité restant:&nbsp;<span id="restant"></span>&nbsp;</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Entrer la Quantité à envoyer</h2>
                        </div> 
                       <div class="swal2-content">
                       <p><input class="swal2-input" placeholder="" name="qte" type="number" style="display: flex;" min="1" max="" id="montant" required></p>
                       </div> 
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
@push('style')
<style type="text/css">
.swal2-input[type=number] {
    min-width: 15em;
    margin: 0px auto;
}
.swal2-file, .swal2-input, .swal2-textarea {
    box-sizing: border-box; 
    transition: border-color .3s,box-shadow .3s;
    border: 1px solid #d9d9d9;
    border-radius: 0.1875em;
    background: inherit;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.06);
    color: inherit;
    font-size: 20px !important;
}
.swal2-input {
    height: 4.625em;
    padding: 0 0.75em;
}
.swal2-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1.8em;
}
.swal2-title {
    position: relative;
    max-width: 100%;
    margin: 0 0 0.4em;
    padding: 0;
    color: #595959;
    font-size: 1.4em;
    font-weight: 600;
    text-align: center;
    text-transform: none;
    word-wrap: break-word;
}
.swal2-content {
    z-index: 1;
    justify-content: center;
    margin: 0;
    padding: 0 1.6em;
    color: #545454;
    font-size: 1.125em;
    font-weight: 400;
    line-height: normal;
    text-align: center;
    word-wrap: break-word;
}
</style>

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
    <script>
        (function($) {
  
            $('.modeEnvoi').on('click', function() {
                var modal = $('#sendBy'); 
                modal.find('form').attr('action', $(this).data('action'));
                $('#recu').html($(this).data('initial')) 
                $('#restant').html($(this).data('restant'))
                modal.find('input[name=qte]').prop('max',$(this).data('restant'))
                modal.modal('show');
            }); 
        })(jQuery)
    </script>
@endpush