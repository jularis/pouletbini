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
                                    <th>@lang('Nom')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Téléphone')</th>
                                    <th>@lang('Adresse')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasins as $magasin)
                                    <tr>
                                        <td>
                                            <span>{{ __($magasin->name) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $magasin->email }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $magasin->phone }}</span>
                                        </td>

                                        <td>
                                            <span>{{ __($magasin->address) }}</span>
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
                @if ($magasins->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($magasins) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Recherche ici" />
@endpush
