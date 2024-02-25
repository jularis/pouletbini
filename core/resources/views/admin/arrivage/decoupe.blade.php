@extends('admin.layouts.app')
@section('panel')
<div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 mb-3">
                <div class="card-body">

                </div>
            </div>
            <div class="card b-radius--10 ">
                <div class="card-body  p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Ferme')</th>
                                    <th>@lang('Numero de Bande')</th> 
                                    <th>@lang('Découpe')</th>
                                    <th>@lang('Quantité')</th>
                                    <th>@lang('Date arrivage')</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($decoupes as $decoupe)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $decoupe->arrivage->bande->ferme->nom }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $decoupe->arrivage->bande->numero_bande }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $decoupe->categorie->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $decoupe->quantity_restante }}</span>
                                        </td>     
                                        <td>
                                            <span class="d-block">{{ showDateTime($decoupe->arrivage->date_arrivage) }}</span>
                                            <span>{{ diffForHumans($decoupe->arrivage->date_arrivage) }}</span>
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
                @if ($decoupes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($decoupes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
<x-back route="{{ route('admin.arrivage.index') }}" />
<a href="{{ route('admin.arrivage.create.decoupe', $id) }}" class="btn  btn-outline--primary h-45">
        <i class="las la-plus"></i>@lang('Ajouter une découpe')
    </a> 
@endpush
