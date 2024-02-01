@extends('manager.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Staff')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang("Crée le")</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffs as $staff)
                                    <tr>
                                        <td>
                                            <span>{{ __($staff->fullname) }}</span>
                                            <br>
                                            <a href="{{ route('manager.staff.edit', encrypt($staff->id)) }}">
                                                <span>@</span>{{ __($staff->username) }}
                                            </a>
                                        </td>

                                        <td>
                                            <span>{{ $staff->email }}<br>{{$staff->mobile }}</span>
                                        </td>

                                        <td>
                                            {{ showDateTime($staff->created_at) }}
                                        </td>

                                        <td>
                                            @php
                                                echo $staff->statusBadge;
                                            @endphp
                                        </td>

                                        <td>
                                            <a href="{{ route('manager.staff.edit', encrypt($staff->id)) }}"
                                                class="btn btn-sm btn-outline--primary"><i
                                                    class="las la-pen"></i>@lang('Edit')</a>
                                                    <a href="{{ route('manager.staff.login', $staff->id) }}"
                                                class="btn btn-sm btn-outline--success" target="_blank"><i
                                                    class="las la-sign-in-alt"></i>
                                                @lang('Login')</a>
                                            @if ($staff->status == Status::BAN_USER)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--success confirmationBtn"
                                                    data-action="{{ route('manager.staff.status', $staff->id) }}"
                                                    data-question="@lang('Are you sure to active this staff?')">
                                                    <i class="la la-eye"></i> @lang("Activé")
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger  confirmationBtn"
                                                    data-action="{{ route('manager.staff.status', $staff->id) }}"
                                                    data-question="@lang('Are you sure to ban this staff?')">
                                                    <i class="la la-eye-slash"></i> @lang("Suspendu")
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
                        </table>
                    </div>
                </div>
                @if ($staffs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($staffs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <x-search-form placeholder="Recherche ici" />
    <a href="{{ route('manager.staff.create') }}" class="btn  btn-outline--primary h-45 addNewMagasin"><i
            class="las la-plus"></i>@lang("Créer un nouveau")</a>
@endpush
