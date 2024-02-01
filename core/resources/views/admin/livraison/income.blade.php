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
                                    <th>@lang('Magasin')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Revenus')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasinIncomes as $magasinIncome)
                                    <tr>
                                        <td>
                                            <span>{{ __(@$magasinIncome->magasin->name) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ showDateTime($magasinIncome->date, 'd M Y') }}</span>
                                        </td>
                                        <td>
                                            <span>{{ getAmount($magasinIncome->totalAmount) }}
                                                {{ __($general->cur_text) }}</span>
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
                @if ($magasinIncomes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($magasinIncomes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="" method="GET" class="d-flex gap-2">
        <div class="input-group">
            <select class="form-control" name="magasin_id">
                <option value="">@lang('Selectionner Magasin')</option>
                @foreach ($magasins as $magasin)
                    <option value="{{ $magasin->id }}" @selected(request()->magasin_id == $magasin->id)>
                        {{ __($magasin->name) }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
        </div>
        <x-date-filter placeholder="Date de Début - Date de Fin" />
    </form>
@endpush
