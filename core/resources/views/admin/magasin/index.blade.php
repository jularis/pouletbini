@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang("Nom - Adresse")</th>
                                    <th>@lang('Email-Phone')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Date de création')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasins as $magasin)
                                    <tr>
                                        <td>
                                            <span class="fw-bold d-block">{{ __($magasin->name) }}</span>
                                            <small class="text-muted"> <i>{{ __($magasin->address) }}</i></span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ $magasin->email }}</span>
                                            <span>{{ $magasin->phone }}</span>
                                        </td>
                                        <td>  @php echo $magasin->statusBadge; @endphp </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($magasin->created_at) }}</span>
                                            <span>{{ diffForHumans($magasin->created_at) }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary editMagasin"
                                                data-id="{{ $magasin->id }}" data-name="{{ $magasin->name }}"
                                                data-email="{{ $magasin->email }}" data-phone="{{ $magasin->phone }}"
                                                data-address="{{ $magasin->address }}"><i
                                                    class="las la-pen"></i>@lang('Edit')</button>

                                            @if ($magasin->status == Status::DISABLE)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success  confirmationBtn"
                                                    data-action="{{ route('admin.magasin.status', $magasin->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir activer ce magasin?')">
                                                    <i class="la la-eye"></i>@lang("Activer")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.magasin.status', $magasin->id) }}"
                                                    data-question="@lang('Etes-vous sûr de vouloir désactiver ce magasin?')">
                                                    <i class="la la-eye-slash"></i>@lang("Désactiver")
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($magasins->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($magasins) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <div id="magasinModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang("Créer un nouveau Magasin")</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
                        <i class="las la-times"></i> </button>
                </div>
                <form action="{{ route('admin.magasin.store') }}" class="resetForm" method="POST">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Nom')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>@lang("Adresse Email")</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Téléphone')</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>


                        <div class="form-group">
                            <label>@lang('Adresse')</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang("Envoyer")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Recherche ici..." />
    <button class="btn  btn-outline--primary h-45 addNewMagasin"><i class="las la-plus"></i>@lang("Créer un nouveau")</button>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addNewMagasin').on('click', function() {
                $('.resetForm').trigger('reset');
                $('#magasinModel').modal('show');
            });
            $('.editMagasin').on('click', function() {
                let title = "@lang('Update Magasin')";
                var modal = $('#magasinModel');
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                let address = $(this).data('address');
                modal.find('.modal-title').text(title)
                modal.find('input[name=id]').val(id);
                modal.find('input[name=name]').val(name);
                modal.find('input[name=email]').val(email);
                modal.find('input[name=phone]').val(phone);
                modal.find('input[name=address]').val(address);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
