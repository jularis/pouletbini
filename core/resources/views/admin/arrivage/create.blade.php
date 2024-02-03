@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => ['admin.arrivage.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
 
                    <div class="form-group row">
                            <label class="col-sm-4 control-label">@lang('Ferme')</label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="ferme" id="ferme" required>
                                <option value=""></option>
                                @foreach ($fermes as $ferme)
                                    <option value="{{ $ferme->id }}" @selected($ferme->id==old('ferme_id'))>{{ __($ferme->nom) }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                    <div class="form-group row autosaisie">
                        {{ Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                        <select class="form-control  select2-auto-tokenize" name="bande" id="bande" required>
                                 
                            </select> 
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Quantité de poulets arrivée'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('total', null, ['class' => 'form-control', 'id' => 'total', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivage', null, ['class' => 'form-control', 'id' => 'date_arrivage', 'required']); ?>
                        </div>
                    </div>
                    
                    <hr class="panel-wide">
                    <div class="form-group row">
                        {{ Form::label(__(''), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
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
        @foreach($categories as $data)
 <tr>
            <td> 
            {!! Form::hidden('unite[]', $data->unite_id, array()) !!} 
            {{ $data->unite->name }}
            </td>
            <td>
            {!! Form::hidden('categorie[]', $data->id, array()) !!}
            {{ $data->name}}
            </td>
            <td>
            {!! Form::hidden('price[]', $data->price, array()) !!}
            {{ $data->price}}
            </td>
            <td>
            {!! Form::number('quantite[]', null, array('placeholder' => __('Qté'),'class' => 'form-control', 'min'=>'0')) !!} 
        </td>
        </tr>
        @endforeach
    </tbody>

</table>
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
        $('#ferme').select2({});
         $('.select2-auto-tokenize').select2({
            dropdownParent: $('.autosaisie'),
            tags: true,
        });
         $('#ferme').change(function(){ 
var urlsend='{{ route("admin.arrivage.getBande") }}'; 
  $.ajax({
            type:'POST',
            url: urlsend,
            data: $('#flocal').serialize(),
            success:function(html){
            $('#bande').html(html);
            }

        });
});

$('.dates').datepicker({
                maxDate: new Date(),
                range: false,
                multipleDatesSeparator: "-",
                language: 'fr'
            });
    </script>
@endpush
