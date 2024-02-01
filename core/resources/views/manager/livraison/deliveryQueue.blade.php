@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
        <div class="card b-radius--10 mb-3">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Numéro de Commande')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Staff')</label>
                                <select name="staff" class="form-control">
                                    <option value="">@lang("Tous")</option>  
                                    @foreach($staffs as $staff)
                                        <option value="{{ $staff->id }}" {{ request()->staff == $staff->id ? 'selected' : '' }} >
                                        {{ $staff->lastname }} {{ $staff->firstname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status de livraison')</label>
                                <select name="status" class="form-control">
                                    <option value="">@lang("Tous")</option> 
                                    <option value="2">@lang('En attente')</option>
                                    <option value="3">@lang('Livré')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Status de paiement')</label>
                                <select name="payment_status" class="form-control">
                                    <option value="" selected>@lang("Tous")</option>
                                    <option value="1">@lang('Payé')</option>
                                    <option value="0">@lang('Impayé')</option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="text" class="date form-control" placeholder="@lang('Date de début - Date de Fin')" autocomplete="off" value="{{ request()->date }}">
                            </div>
                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filtrer')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Magasin Expéditeur - Staff')</th>
                                    <th>@lang('Destinataire - Client')</th>
                                    <th>@lang('Montant - Numéro Commande')</th> 
                                    <th>@lang("Date estimée d'envoi")</th>
                                    <th>@lang('Status de paiement')</th>
                                    {{-- <th>@lang('Status')</th> --}}
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livraisonLists as $livraisonInfo)
                                    <tr>
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($livraisonInfo->senderMagasin->name) }}</span><br>
                                            {{ __($livraisonInfo->senderStaff->fullname) }}
                                        </td>

                                        <td>
                                            <span class="fw-bold">
                                            {{ __($livraisonInfo->receiverClient->address) }}
                                            </span>
                                            <br>
                                            @if ($livraisonInfo->receiver_client_id)
                                                {{ __($livraisonInfo->receiverClient->name) }}
                                                {{ __($livraisonInfo->receiverClient->phone) }}
                                            @else
                                                <span>@lang('N/A')</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ showAmount(@$livraisonInfo->paymentInfo->final_amount) }}
                                                {{ __($general->cur_text) }}</span><br>
                                            <span>{{ $livraisonInfo->code }}</span>
                                        </td>
 
                                        <td>
                                            {{ showDateTime($livraisonInfo->estimate_date, 'd M Y') }}
                                        </td>

                                        <td>
                                        @if($livraisonInfo->paymentInfo->status == Status::PAID)
                                                <span class="badge badge--success">@lang('Payé')</span>
                                            @elseif($livraisonInfo->paymentInfo->status == Status::PARTIAL)
                                                <span class="badge badge--primary">@lang('Partiel')</span>
                                            @else($livraisonInfo->paymentInfo->status == Status::UNPAID)
                                                <span class="badge badge--danger">@lang('Impayé')</span>
                                            @endif
                                        </td>
                                        <td>
                                        <a href="{{ route('manager.livraison.edit', encrypt($livraisonInfo->id)) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </a>
                                            @if($livraisonInfo->paymentInfo->status == Status::UNPAID)
                                            {!! Form::open(['method' => 'DELETE','route' => ['manager.livraison.delete', encrypt($livraisonInfo->id)],'style'=>'display:inline']) !!}
                                            <button class="btn btn-sm btn-outline--danger" type="submit"  onclick="return confirm('Etes vous sûr de vouloir supprimer cette commande?')"><i class="las la-trash"></i>Delete</button>
                    {!! Form::close() !!}
                    @endif
                                            <a href="{{ route('manager.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>
                                            <a href="{{ route('manager.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang('Details')</a>
                                         @if($livraisonInfo->paymentInfo->status == 0 || $livraisonInfo->paymentInfo->status == 2)
                                                <button class="btn btn-sm btn-outline--success  payment"
                                                    data-code="{{ $livraisonInfo->code }}" data-finalamount="{{ $livraisonInfo->paymentInfo->final_amount }}" data-partialamount="{{ $livraisonInfo->paymentInfo->partial_amount }}"><i class="las la-credit-card"></i>
                                                    @lang('Confirmer le Paiement')</button>
                                            @endif
                                            @if ($livraisonInfo->status == 2)
                                                <button class="btn btn-sm btn-outline--secondary  delivery"
                                                    data-code="{{ $livraisonInfo->code }}"><i class="las la-truck"></i>
                                                    @lang('Terminer la livraison')</button>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($livraisonLists->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($livraisonLists) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

 
    @if(session('codePaie'))
    <div class="modal fade" id="paymentByAuto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelAuto"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabelAuto">@lang('Payment Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('manager.livraison.payment') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code" value="{{ session('codePaie') }}">
                    <div class="modal-body">
                    <div class="swal2-header">
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant total:&nbsp;<span id="recu"></span>&nbsp;FCFA</h2>
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant restant:&nbsp;<span id="restant"></span>&nbsp;FCFA</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Entrer le montant reçu</h2>
                        </div> 
                       <div class="swal2-content">
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="1" max="" id="montant" required></p>
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
    @push('script')
    <script>
        $(document).ready(function(){
        $("#paymentByAuto").modal('show');
    });
 
</script>
@endpush
    @else
    <div class="modal fade" id="paymentBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Payment Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('manager.livraison.payment') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code">
                    <div class="modal-body">
                    <div class="swal2-header">
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant total:&nbsp;<span id="recu"></span>&nbsp;FCFA</h2>
                       <h2 class="swal2-title" id="swal2-title" style="display: flex;">Montant restant:&nbsp;<span id="restant"></span>&nbsp;FCFA</h2>
                        <h2 class="swal2-title" id="swal2-title" style="display: flex;">Entrer le montant reçu</h2>
                        </div> 
                       <div class="swal2-content">
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="1" max="" id="montant" required></p>
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
    @push('script')
    <script>
        (function($) {
  
            $('.payment').on('click', function() {
                var modal = $('#paymentBy');
                modal.find('input[name=code]').val($(this).data('code'))
                $('#recu').html($(this).data('finalamount')) 
                $('#restant').html($(this).data('finalamount')-$(this).data('partialamount'))
                modal.find('input[name=montant]').prop('max',$(this).data('finalamount')-$(this).data('partialamount'))
                modal.modal('show');
            }); 
        })(jQuery)
    </script>
@endpush
    @endif

    @if(session('code'))
     
    <div class="modal fade" id="deliveryByAuto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelAuto"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabelAuto">@lang('Confirmation de reception')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <form action="{{ route('manager.livraison.delivery') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code" value="{{ session('code')}}">
                    <div class="modal-body">
                        <p>@lang('Etre-vous sûr de vouloir confirmer la reception de cette livraison?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Fermer')</button>
                        <button type="submit" class="btn btn--primary">@lang('Confirmer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('script')
    <script>
        $(document).ready(function(){
        $("#deliveryByAuto").modal('show');
    });
 
</script>
@endpush 
    @else
    <div class="modal fade" id="deliveryBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Confirmation de reception')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <form action="{{ route('manager.livraison.delivery') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>@lang('Etre-vous sûr de vouloir confirmer la reception de cette livraison?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Fermer')</button>
                        <button type="submit" class="btn btn--primary">@lang('Confirmer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('script')
    <script>
        (function($) { 
            $('.delivery').on('click', function() {
                var modal = $('#deliveryBy');
                modal.find('input[name=code]').val($(this).data('code'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
    @endif
@endsection
@push('breadcrumb-plugins')
<badge class="btn btn-danger">{{ showAmount(@$sommeTotal) }} FCFA</badge> 
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
        
        (function($) {
            

            $('.date').datepicker({
                // maxDate:new Date(),
                range:true,
                multipleDatesSeparator:"-",
                language:'fr'
            });

           
        })(jQuery)
        $('form select').on('change', function(){
    $(this).closest('form').submit();
});
    </script>
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
 