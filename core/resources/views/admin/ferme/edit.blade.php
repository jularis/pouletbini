@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($ferme, [
                        'method' => 'POST',
                        'route' => ['admin.ferme.store', $ferme->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $ferme->id }}">
                    <div class="form-group row">
                        {{ Form::label(__('Nom de la Ferme'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nom', null, ['class' => 'form-control', 'id' => 'ferme', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Lieu'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('lieu', null, ['class' => 'form-control', 'id' => 'lieu', 'required']); ?>
                        </div>
                    </div>
  
                        <div class="form-group row">
                            {{ Form::label(__('Responsable'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('responsable', null, ['id' => 'responsable', 'placeholder' => 'Année de régéneration', 'class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label(__('Contact'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('contact', null, ['id' => 'contact', 'class' => 'form-control']); ?>
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
    <x-back route="{{ route('admin.ferme.index') }}" />
@endpush
 