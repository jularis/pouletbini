@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body" >
                    {!! Form::open([
                        'route' => ['admin.arrivage.store.decoupe'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
  
                    <hr class="panel-wide">
                    <div class="form-group row"> 
                    <input type="hidden" name="arrivage" value="{{ $id }}">
                        <div class="col-xs-12 col-sm-12">
                        <table class="table table-bordered">
    <thead>
            <tr> 
                <th>Categorie</th> 
                <th class="text-center">Quantité</th>
                <th class="text-center">Quantité Prélévée</th>
                <th class="text-center">Découpes</th>
            </tr>
        </thead>
    <tbody id="addedField">
        @foreach($categoriePoulets as $data)
 <tr class="single-item"> 
            <td> 
            {{ $data->categorie->name}}
            </td> 
            <td>
                <input type="number" name="quantiteInit[{{ $data->id }}]" value="{{$data->quantity_restante}}" class="form-control quantityInit" readonly /> 
        </td>
        <td>
        <input type="number" name="quantitePrev[{{ $data->id }}]" data-id="{{ $data->id }}" placeholder="Qté" min="0" max="{{$data->quantity_restante}}" class="form-control quantityPrev" /> 
        </td>
        <td>
        <table class="table table-striped table-bordered internalTable">
    <thead>
            <tr> 
                <th>Categorie</th>
                <th>Prix(FCFA)</th>
                <th class="text-center">Quantité</th> 
            </tr>
        </thead>
        <body>
        @foreach($categories as $data2)
        <tr class="single-item"> 
            <td>
                <input type="hidden" name="categorie[{{ $data->id}}][{{ $data2->id}}]" value="{{ $data2->id}}"/> 
            {{ $data2->name}}
            </td>
            <td> 
            <input type="hidden" name="price[{{ $data->id}}][{{ $data2->id}}]" value="{{ $data2->price}}"/> 
            {{ $data2->price}}
            </td> 
            <td>
            <input type="number" name="quantite[{{ $data->id }}][{{ $data2->id}}]" data-idparent="{{ $data->id }}" placeholder="Qté" min="0" class="form-control quantity somme quantityChild{{$data->id}}" readonly />  
            </td>
        </tr>
        @endforeach
        </body>
        </table>
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
    <x-back route="{{ route('admin.arrivage.decoupe', $id) }}" />
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


$('.quantityPrev').on('input', function (e) {
           
           let parent = $(this).closest('.single-item');
           let items    = $('#addedField').find('.single-item');
           let quantity = $(this).val();
           let subTotal = 0;
           let quantityInit = $(parent).find('.quantityInit').val();
           let idInit = $(this).data('id');
          
           if (quantity>0) {
            console.log(quantity)
            $(parent).find('.quantity').attr({     
             'min' : 0,
             'max':quantity,
             'readonly': false     
           });
               
           }else{
            $(parent).find('.quantity').attr({     
             'min' : 0,
             'max':quantity,
             'readonly': true     
           });
           }
          
       });
       
       $('.quantity').on('input', function() {
        let idInit = $(this).data('idparent');
        let maxqte = $(this).attr('max');
        let items = $('#addedField').find('.single-item');
        let totalInit = $('#total').val(); 
        let subTemp = 0;

        // var sum = 0; 
        //    $('.quantityChild'+idInit).each(function() {
        //     sum += parseInt($(this).val()) || 0;
        //     if(sum>maxqte){
        //         sum = sum - parseInt($(this).val());
        //         $(this).val(0);

        //     }
        //    });
           var subTotal = 0;
           $('.somme').each(function() { 
            subTotal += parseInt($(this).val()) || 0;   
             
             });

             $('.total').text(subTotal);
        
      });

    

// $('#addedField').on('input', '.somme', function (e) {
//             this.value = this.value.replace(/^\.|[^\d\.]/g, '');
 
//             calculation()
//         });

function calculation ( ) {
            let items    = $('#addedField').find('.single-item');
            let totalInit = $('#total').val();
            let subTotal = 0;
            let subTemp = 0;

            $.each(items, function (i, item) { 
                 
                let somme = parseFloat($(item).find('.somme').val() || 0);
                subTemp += somme;
                if(totalInit<subTemp){
                    $(item).find('.somme').val(0);
                    somme = 0;
                    subTemp = subTemp - somme;
                } 

                subTotal += somme;
            });
 

            // let discountAmount = (subTotal/100)*discount;
 
            $('.total').text(subTotal);
        };

$('.date').datepicker({
                maxDate: new Date(),
                multipleDatesSeparator: "-",
                language: 'fr'
            });
    </script>
    
@endpush
