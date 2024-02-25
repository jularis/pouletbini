<div class="modal-header">
    <h5 class="modal-title">{{$pageTitle}}</h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
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
                                    <option value="{{ $fournisseur->id }}" @selected($fournisseur->id==old('fournisseur'))>{{ __($fournisseur->nom) }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivée'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivee', null, ['class' => 'form-control', 'id' => 'date_arrivee', 'required','max'=>gmdate('Y-m-d')]); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">

                    <div class="form-group row">
                        <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Envoyer')</button>
                    </div>
                    {!! Form::close() !!}
    </div>
   

</div>   
 