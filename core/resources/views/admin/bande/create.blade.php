@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['admin.bande.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
 
                    <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Ferme')</label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="ferme" required>
                                <option value="">@lang('Selectionner une Option')</option>
                                @foreach ($fermes as $ferme)
                                    <option value="{{ $ferme->id }}" @selected($ferme->id==old('ferme_id'))>{{ __($ferme->nom) }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    <div class="form-group row">
                        {{ Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('numero', null, ['class' => 'form-control', 'id' => 'bande', 'required']); ?>
                        </div>
                    </div>

                    
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
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

@push('script')
 
    <script>
        "use strict";
    </script>
@endpush
