@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($bande, [
                        'method' => 'POST',
                        'route' => ['admin.bande.store', $bande->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $bande->id }}">
                    <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Ferme')</label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="ferme" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($fermes as $ferme)
                                    <option value="{{ $ferme->id }}" @selected($ferme->id==$bande->ferme_id)>{{ __($ferme->nom) }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    <div class="form-group row">
                        {{ Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('numero', $bande->numero_bande, ['class' => 'form-control', 'id' => 'bande', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Nombre de poussins'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('nombre_poussins', null, ['class' => 'form-control', 'id' => 'bande', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Fournisseur')</label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control select2-auto-tokenize" name="fournisseur" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" @selected($fournisseur->id==$bande->fournisseur_id)>{{ __($fournisseur->nom) }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivée'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('date_arrivee', null, ['class' => 'form-control date', 'id' => 'date_arrivee', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">


                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block h-45 w-100">@lang('Envoyer')</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.bande.index') }}" />
@endpush
@push('script-lib')
<script src="{{asset('assets/viseradmin/js/vendor/datepicker.min.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/vendor/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/vendor/datepicker.en.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/jquery.chained.js')}}"></script>
@endpush

@push('style-lib')
<link  rel="stylesheet" href="{{asset('assets/viseradmin/css/vendor/datepicker.min.css')}}">
@endpush

@push('script')
 
    <script>
        "use strict";
        $('.date').datepicker({
            language  : 'fr',
            dateFormat: 'yyyy-mm-dd',
            maxDate   : new Date()
        });
    </script>
@endpush