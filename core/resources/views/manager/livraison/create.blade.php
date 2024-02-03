@extends('manager.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <form action="{{route('manager.livraison.store')}}" method="POST" id="flocal">
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="">@lang("Date estimée d'envoi")</label>
                            <div class="input-group">
                                <input name="estimate_date" value="{{ old('estimate_date') }}" type="text" autocomplete="off"  class="form-control date" placeholder="Date estimée d'envoi" required>
                                <span class="input-group-text"><i class="las la-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <label for="">@lang('Status de paiement')</label>
                            <div class="input-group">
                                <select class="form-control" required name="payment_status">
                                    <option value="0" selected>@lang('IMPAYE')</option>
                                    <option value="1">@lang('PAYE')</option>
                                </select>
                                <span class="input-group-text"><i class="las la-money-bill-wave-alt"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white">@lang('Informations du Destinataire')</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Selectionner Magasin')</label>
                                            <select class="form-control" name="magasin" id="magasin" required>
                                                <option value>@lang('Selectionner une Option')</option>
                                                @foreach($magasins as $magasin)
                                                <option value="{{$magasin->id}}" @selected(old('magasin') ==$magasin->id)>{{__($magasin->name)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Selectionner Client')</label>
                                            <select class="form-control select2-basic" name="client" onchange="getCustomer()" required>
                                                <option value="Autre">@lang('Autre')</option>
                                                @foreach($clients as $client)
                                                <option value="{{$client->id}}" @selected(old('client') ==$client->id)
                                                data-name="{{$client->name}}" 
                                                data-phone="{{$client->phone}}" 
                                                        data-email="{{$client->email}}" 
                                                        data-address="{{$client->address}}"
                                                    >{{__($client->name)}} - {{__($client->phone)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Nom du client')</label>
                                            <input type="text" class="form-control" name="receiver_name"
                                                value="{{old('receiver_name')}}" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Téléphone')</label>
                                            <input type="text" class="form-control" name="receiver_phone"
                                                value="{{old('receiver_phone')}}" required>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="receiver_email"
                                                value="{{old('receiver_email')}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Adresse')</label>
                                            <input type="text" class="form-control" name="receiver_address"
                                                value="{{old('receiver_address')}}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white">@lang('Informations Expéditeur')</h5>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="form-group col-lg-12">
                                            <label>@lang('Selectionner Staff')</label>
                                            <select class="form-control select2-basic" name="staff" id="staff" onchange="getStaff()" required>
                                                <option value>@lang('Selectionner une Option')</option>
                                                @foreach($staffs as $staff)
                                                <option value="{{$staff->id}}" @selected(old('staff') ==$staff->id) data-chained="{{ $staff->magasin_id }}" data-name="{{__($staff->firstname)}} {{__($staff->lastname)}}" data-phone="{{__($staff->mobile)}}" data-email="{{__($staff->email)}}" data-address="{{__($staff->address)}}">{{__($staff->lastname)}} {{__($staff->firstname)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label>@lang('Nom')</label>
                                            <input type="text" class="form-control" name="sender_name"
                                                value="{{old('sender_name')}}" readonly required>
                                        </div>
                                        <div class=" form-group col-lg-6">
                                            <label>@lang('Téléphone')</label>
                                            <input type="text" class="form-control" value="{{old('sender_phone')}}"
                                                name="sender_phone" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="sender_email" readonly
                                                value="{{old('sender_email')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label>@lang('Adresse')</label>
                                            <input type="text" class="form-control" name="sender_address"
                                                value="{{old('sender_address')}}" readonly >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <div class="row mb-30">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                            <div class="card-header bg--primary">
                            
                            <div class="form-group row">
                            <div class="col-xs-12 col-sm-8">
                            <h5 class="text-white">@lang('Informations de Livraison')</h5>
                            </div>
                        <div class="col-xs-12 col-sm-4">
                        <label class="control-label">@lang('NUMERO DE BANDE')</label>
                                <select class="form-control select2-multi-select float-end" name="bande[]" id="bande" multiple required> 
                                                @foreach($produits as $produit)
                                                <option value="{{$produit->arrivage->bande_id}}" @selected(in_array(@$produit->arrivage->bande_id, @request()->bande ?? array()))>{{__($produit->arrivage->bande->numero_bande)}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                    </div>
                                
          </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                    <input type="hidden" name="total" id="total" class="form-control" />
                                    <input type="hidden" name="qtecommande" id="qtecommande" class="form-control" />
                                    <input type="hidden" name="somme" id="somme" class="form-control" />
                                    <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                        <th>Unite</th>
                        <th>Categorie</th>
                        <th>Numero de Bande</th>
                        <th>Prix unitaire</th>
                        <th>Quantité initiale</th>
                        <th>Quantité commandée</th>
                         </tr></thead> 
                                <tbody id="listeproduits" style="text-align: center;">

                                </tbody>
                            </table>

                                    </div><br><br>
                                    <div class="border-line-area">
                                        <h6 class="border-line-title">@lang('Resume')</h6>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text">@lang('Reduction')</span>
                                                <input type="number" name="discount"  value="{{ old('discount') }}" class="form-control bg-white text-dark discount" value="0">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-4  d-flex justify-content-between">
                                            <span class="fw-bold">@lang('Sous-total'):</span>
                                            <div><span class="subtotal">0.00</span> {{$general->cur_sym}}</div>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-4  d-flex justify-content-between">
                                            <span class="fw-bold">@lang('Total'):</span>
                                            <div><span class="total">0.00</span> {{$general->cur_sym}}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-30">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary text-white">@lang('Frais de Livraison') 
                                </h5>
                                <div class="card-body">
                                    <div class="row">
									<div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="number"  class="form-control" name="frais_livraison" value="{{ old('frais_livraison') ?? '1000' }}" required>
                                                        <span class="input-group-text">{{__($general->cur_text)}}</span>
                                                    </div>
                                                </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--primary h-45 w-100 Submitbtn"> @lang("Envoyer")</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
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
    $("#staff").chained("#magasin");
    function getStaff() {
        let name = $("#staff").find(':selected').data('name');
        let phone = $("#staff").find(':selected').data('phone');
        let email = $("#staff").find(':selected').data('email');
        let address = $("#staff").find(':selected').data('address');
        $('input[name=sender_name]').val(name);
        $('input[name=sender_email]').val(email);
        $('input[name=sender_phone]').val(phone); 
        $('input[name=sender_address]').val(address); 
    }
    function getCustomer() {
        var staffSelect = $("#client").val();
        
        var receiver_name = $("#client").find(':selected').data('name');
        var receiver_phone = $("#client").find(':selected').data('phone');
        var receiver_email = $("#client").find(':selected').data('email');
        var receiver_address = $("#client").find(':selected').data('address'); 
        if(staffSelect !='Autre'){
        $('input[name=receiver_name]').val(receiver_name).attr("readonly",'readonly');
        $('input[name=receiver_email]').val(receiver_email).attr("readonly",'readonly');
        $('input[name=receiver_phone]').val(receiver_phone).attr("readonly",'readonly');
        $('input[name=receiver_address]').val(receiver_address).attr("readonly",'readonly');
        }else{
        $('input[name=receiver_name]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_email]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_phone]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_address]').val('').removeAttr("readonly",'readonly');
        }
        
    }
    $('#bande').change(function() {

var urlsend = '{{ route('manager.livraison.get.produit') }}';

$.ajax({
    type: 'GET',
    url: urlsend,
    data: $('#flocal').serialize(),
    success: function(html) {
        $('#listeproduits').html(html.tableau);
        $("#total").val(html.total);
        $("#qtecommande").val(html.total);
        $("#somme").val(0);
    }

});
});
let discount=0;
function getQuantite(id, k, s)
{
update_amounts(id, k, s);
calculation();
}
function update_amounts(id, k, s) {
            let total = $('#total').val();
            var sum = 0;
            let qtecommande = parseInt($('#qtecommande').val());
            let max = $('.quantity-' + id).attr('max');

            let quantite = 0;
            let somme = 0;
 

            $('.quantity-' + id).each(function() {
                var qty = $(this).val();
                quantite = parseInt(quantite) + parseInt(qty);
                //  if(quantite>max){
                //     $('#qte-'+k).val(0); 
                //     } 
            });

            $('.totaux').each(function() {
                var nb = $(this).val();
                // update Quantite survecue 
                sum = parseInt(sum) + parseInt(nb);
            });

            if (sum > total) {
                $('#qte-' + k).val(0);
                $('.totaux').each(function() {
                    var nb = $(this).val();
                    sum = parseInt(sum) + parseInt(nb);
                });
            } else {
                $('#qtecommande').val(sum);
            }
            for (let i = 1; i < 6; i++) {
                var soustotal = 0;

                $('.st-' + i).each(function() {
                    var nb = $(this).val();
                    soustotal = parseInt(soustotal) + parseInt(nb);

                });
                $('#soustotal-' + i).val(soustotal);

            }

            $("#qtecommande").attr({
                "max": total,
                "min": 0
            });
        }
        

$('.discount').on('input',function (e) {
    this.value = this.value.replace(/^\.|[^\d\.]/g, '');
    discount=parseFloat($(this).val() || 0); 

    calculation();
});
        function calculation ( ) {
            let items    = $('#listeproduits').find('.single-item');
            let subTotal = 0;

            $.each(items, function (i, item) {
                let price = parseFloat($(item).find('.price').val() || 0);
                let quantity = parseFloat($(item).find('.totaux').val() || 0);
                
                subTotal+=price*quantity;
            });

            subTotal=parseFloat(subTotal);

            // let discountAmount = (subTotal/100)*discount;
            let discountAmount = discount;
            let total = subTotal-discountAmount;

            $('.subtotal').text(subTotal.toFixed(2));
            $('.total').text(total.toFixed(2));
        };

        $('.date').datepicker({
            language  : 'fr',
            dateFormat: 'yyyy-mm-dd',
            minDate   : new Date()
        });
</script>
@endpush

@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }
        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
    </style>
@endpush
