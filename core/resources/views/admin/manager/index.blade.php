@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Magasin')</th>
                                    <th>@lang('Manager')</th>
                                    <th>@lang("Email - Téléphone")</th>
                                    <th>@lang("Crée le")</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasinManagers as $manager)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __($manager->magasin->name) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold d-block">{{ $manager->fullname }}</span>
                                            <span class="small">
                                                <a href="{{ route('admin.magasin.manager.edit', $manager->id) }}">
                                                    <span>@</span>{{$manager->username }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $manager->email }}<br>{{ $manager->mobile }}</span>
                                        </td>
                                        <td>
                                            <span class="d-block">{{ showDateTime($manager->created_at) }}</span>
                                            <span>{{ diffForHumans($manager->created_at) }}</span>
                                        </td>
                                        <td> @php echo $manager->statusBadge; @endphp </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="las la-ellipsis-v"></i>@lang('Action')
                                             </button>
                                            <div class="dropdown-menu p-0">
                                                <a href="{{ route('admin.magasin.manager.edit', $manager->id) }}"
                                                    class="dropdown-item"><i class="la la-pen"></i>@lang('Edit')</a>
                                                <a href="{{ route('admin.magasin.manager.staff.list', $manager->magasin_id) }}"
                                                    class="dropdown-item"><i class="las la-user-friends"></i>
                                                    @lang('Liste des Staffs')</a>
                                                @if ($manager->status == Status::DISABLE)
                                                    <button type="button" class="confirmationBtn  dropdown-item"
                                                        data-action="{{ route('admin.magasin.manager.status', $manager->id) }}"
                                                        data-question="@lang('Etes-vous sûr de vouloir activer ce manager?')">
                                                        <i class="la la-eye"></i> @lang("Activer")
                                                    </button>
                                                @else
                                                    <button type="button" class=" confirmationBtn   dropdown-item"
                                                        data-action="{{ route('admin.magasin.manager.status', $manager->id) }}"
                                                        data-question="@lang('Etes-vous sûr de vouloir désactiver ce manager?')">
                                                        <i class="la la-eye-slash"></i> @lang("Désactiver")
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.magasin.manager.dashboard', $manager->id) }}"
                                                    class="dropdown-item" target="_blank"><i class="las la-sign-in-alt"></i>
                                                    @lang('Login')
                                                </a>
                                            </div>
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
                @if ($magasinManagers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($magasinManagers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Recherche ici..." />
    <a href="{{ route('admin.magasin.manager.create') }}" class="btn  btn-outline--primary h-45 addNewMagasin">
        <i class="las la-plus"></i>@lang("Créer un nouveau")
    </a>
@endpush
@push('style')
    <style>
        .table-responsive {
            overflow-x: clip;
        }
    </style>
@endpush
