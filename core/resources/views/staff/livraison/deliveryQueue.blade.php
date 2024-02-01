@extends('staff.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Magasin Expéditeur - Staff')</th>
                                    <th>@lang('Magasin Destinataire - Client')</th>
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
                                                @if ($livraisonInfo->receiver_magasin_id)
                                                    {{ __($livraisonInfo->receiverMagasin->name) }}
                                                @else
                                                    @lang('N/A')
                                                @endif
                                            </span>
                                            <br>
                                            @if ($livraisonInfo->receiver_client_id)
                                                {{ __($livraisonInfo->receiver_name) }}<br>
                                                <a href="tel:{{$livraisonInfo->receiver_phone}}">{{$livraisonInfo->receiver_phone}}</a>
                                                 
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
                                           
                                            <a href="{{ route('staff.livraison.details', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-info-circle"></i> @lang('Details')</a>
                                        @if($livraisonInfo->estimate_date < gmdate('Y-m-d H:i:s'))
                                             <a href="{{ route('staff.livraison.invoice', encrypt($livraisonInfo->id)) }}"
                                                title="" class="btn btn-sm btn-outline--info"><i
                                                    class="las la-file-invoice"></i> @lang('Facture')</a>
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
                                            @else
                                            <span class="badge badge--dark">@lang('Prochaine livraison') le {{showDateTime($livraisonInfo->estimate_date, 'd M Y')}}</span>
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
                <form action="{{ route('staff.livraison.payment') }}" method="POST">
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
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="500" max="" id="montant" required></p>
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
                <form action="{{ route('staff.livraison.payment') }}" method="POST">
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
                       <p><input class="swal2-input" placeholder="" name="montant" type="number" style="display: flex;" min="500" max="" id="montant" required></p>
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
                <form action="{{ route('staff.livraison.delivery') }}" method="POST">
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
                <form action="{{ route('staff.livraison.delivery') }}" method="POST">
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
    <x-search-form placeholder="Livraison Code" />
    <x-date-filter placeholder="Date de Début - Date de Fin" />
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
 