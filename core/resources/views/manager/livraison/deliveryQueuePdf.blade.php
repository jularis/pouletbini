@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <h3>{{ $pageTitle }}</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Magasin Expéditeur</th>
                            <th>Staff Expéditeur</th>
                            <th>Destinataire</th>
                            <th>Client / Téléphone</th>
                            <th>Montant</th>
                            <th>Date estimée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($livraisonLists as $l)
                            <tr>
                                <td>{{ $l->code }}</td>
                                <td>{{ $l->senderMagasin->name ?? '' }}</td>
                                <td>{{ $l->senderStaff->fullname ?? '' }}</td>
                                <td>{{ $l->receiverMagasin->name ?? $l->receiver_name }}</td>
                                <td>{{ $l->receiverClient->name ?? '' }} / {{ $l->receiver_phone }}</td>
                                <td>{{ showAmount(@$l->paymentInfo->final_amount) }} {{ __($general->cur_text) }}</td>
                                <td>{{ showDateTime($l->estimate_date, 'd M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    window.addEventListener('load', function(){
        window.print();
    });
</script>
@endpush
