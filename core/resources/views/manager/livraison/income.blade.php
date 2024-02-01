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
                                    <th>@lang('ID')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Revenus')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($magasinIncomes as $magasinIncome)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showDateTime($magasinIncome->date, 'd M Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ showAmount($magasinIncome->totalAmount) }}
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
