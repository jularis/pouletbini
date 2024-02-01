@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('manager.staff.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $staff->id }}">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang("Nom de Famille")</label>
                                <input type="text" class="form-control" value="{{ __($staff->lastname) }}"
                                    name="lastname" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang("Prénom(s)")</label>
                                <input type="text" class="form-control" name="firstname"
                                    value="{{ __($staff->firstname) }}" required>
                            </div>
                            
                           
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang("Adresse Email")</label>
                                <input type="email" class="form-control" name="email" value="{{ $staff->email }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang('Téléphone')</label>
                                <input type="text" class="form-control" name="mobile" value="{{ $staff->mobile }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>@lang('Adresse')</label>
                                <input type="text" class="form-control" name="address" value="{{ $staff->address }}"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                             <div class="form-group col-lg-4">
                                <label>@lang("Nom d'utilisateur")</label>
                                <input type="text" class="form-control" name="username"
                                    value="{{ __($staff->username) }}" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang("Mot de Passe")</label>
                                <input type="password" class="form-control" name="password" >
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang("Confirmerer Mot de Passe")</label>
                                <input type="password" class="form-control" name="password_confirmation" >
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45"> @lang("Envoyer")</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('manager.staff.index') }}"/>
@endpush
