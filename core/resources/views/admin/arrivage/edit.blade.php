@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($arrivage, [
                        'method' => 'POST',
                        'route' => ['admin.arrivage.store', $arrivage->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="id" value="{{ $arrivage->id }}">
                    <input type="hidden" name="ferme" value="{{ $arrivage->bande->ferme->id }}">
                    <input type="hidden" name="bande" value="{{ $arrivage->bande->numero_bande }}">
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
                            <?php echo Form::number('total', $arrivage->total_poulet, ['class' => 'form-control', 'id' => 'total', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivage', $arrivage->date_arrivage, ['class' => 'form-control', 'id' => 'date_arrivage', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <table class="table table-striped table-bordered">
    <thead>
            <tr>
                <th>Unité</th>
                <th>Categorie</th>
                <th>Prix(FCFA)</th>
                <th class="text-center">Quantité</th>
            </tr>
        </thead>
    <tbody>
        @foreach($arrivage->arrivageDetail as $data)
 <tr>
            <td> 
            {!! Form::hidden('unite[]', $data->categorie->unite_id, array()) !!} 
            {{ $data->categorie->unite->name }}
            </td>
            <td>
            {!! Form::hidden('categorie[]', $data->categorie_id, array()) !!}
            {{ $data->categorie->name}}
            </td>
            <td>
            {!! Form::hidden('price[]', $data->price, array()) !!}
            {{ $data->price}}
            </td>
            <td>
            {!! Form::number('quantite[]', $data->quantity, array('placeholder' => __('Qté'),'class' => 'form-control', 'min'=>'0')) !!} 
        </td>
        </tr>
        @endforeach
    </tbody>

</table>
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
    <x-back route="{{ route('admin.arrivage.index') }}" />
@endpush
 