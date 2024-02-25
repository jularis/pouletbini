@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body" >
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
                            <div class="input-group">
                            <select class="form-control" name="ferme" id="ferme" required>
                                <option value=""></option>
                                @foreach ($fermes as $ferme)
                                    <option value="{{ $ferme->id }}" @selected($ferme->id==old('ferme_id'))>{{ __($ferme->nom) }}</option>
                                @endforeach
                            </select>
                            <button type="button"
                                                        class="btn btn-secondary add-ferme"
                                                        data-toggle="tooltip"
                                                        data-original-title="Ajouter une ferme"><i
                                                            class="fa fa-plus"></i></button> 
                    </div>
                            </div>
                        </div>
                    <div class="form-group row autosaisie">
                        {{ Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                        <div class="input-group">
                        <select class="form-control" name="bande" id="bande" required>
                                 
                            </select>
                            <button type="button"
                                                        class="btn btn-secondary add-bande"
                                                        data-toggle="tooltip"
                                                        data-original-title="Ajouter une bande"><i
                                                            class="fa fa-plus"></i></button> 
                             
                        </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Total poulets arrivé'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <div class="input-group">
                            <?php echo Form::number('total', null, ['class' => 'form-control', 'id' => 'total', 'required']); ?>
                           
                            <div class="input-group-append">
                                <span class="input-group-text qte_init" style="height: 45px;border-radius: 0;">00</span>
                            </div>
                    </div>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivage', null, ['class' => 'form-control', 'id' => 'date_arrivage', 'required','max'=>gmdate('Y-m-d')]); ?>
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
    <tbody id="addedField">
        @foreach($categories as $data)
 <tr class="single-item">
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
            {!! Form::number('quantite[]', null, array('placeholder' => __('Qté'),'class' => 'form-control quantity', 'min'=>'0')) !!} 
        </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"><div><span style="font-size: 18px;font-weight: bold;">Total</span></div></td>
            <td><div><span class="total" style="font-size: 18px;font-weight: bold;text-align:center;">0</span></div></td>
        </tr>
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
        // $('#ferme').select2({});
        //  $('.select2-auto-tokenize').select2({
        //     dropdownParent: $('.autosaisie'),
        //     tags: true,
        // });
        $('body').on('click', '.add-ferme', function() {
            var url = "{{ route('admin.ferme.fermeModal.index') }}";

            $(MODAL_XL + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_XL, url);
            $(MODAL_XL).modal('show');
        });
        $('body').on('click', '.add-bande', function() {
            var url = "{{ route('admin.bande.bandeModal.index') }}";

            $(MODAL_XL + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_XL, url);
            $(MODAL_XL).modal('show');
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

$('#bande').change(function(){ 
    let qte    = parseFloat($('#bande option:selected').data('qte') || 0);
    if(qte>0){
        $('#total').attr('max', qte);
        $('.qte_init').text(qte);
    }
   
});
$('#addedField').on('input', '.quantity', function (e) {
            this.value = this.value.replace(/^\.|[^\d\.]/g, '');

            let quantity = $(this).val();
            if (quantity <= 0) {
                quantity = 0;
            }

            calculation()
        });

function calculation ( ) {
            let items    = $('#addedField').find('.single-item');
            let totalInit = $('#total').val();
            let subTotal = 0;
            let subTemp = 0;

            $.each(items, function (i, item) { 
                 
                let quantity = parseFloat($(item).find('.quantity').val() || 0);
                subTemp += quantity;
                if(totalInit<subTemp){
                    $(item).find('.quantity').val(0);
                    quantity = 0;
                    subTemp = subTemp - quantity;
                } 

                subTotal += quantity;
            });
 

            // let discountAmount = (subTotal/100)*discount;
 
            $('.total').text(subTotal);
        };

$('.date').datepicker({
    language  : 'fr',
            dateFormat: 'yyyy-mm-dd',
            maxDate   : new Date()
            });
    </script>
@endpush
