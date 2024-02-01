@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.magasin.manager.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>@lang('Selectionner Magasin')</label>
                                <select class="form-control" name="magasin" required>
                                    <option value="">@lang('Selectionner une Option')</option>
                                    @foreach ($magasins as $magasin)
                                        <option value="{{ $magasin->id }}" @selected(old('magasin'))>
                                            {{ __($magasin->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>
                        <div class="row">
                             
                            <div class="form-group col-lg-6">
                                <label>@lang("Nom de Famille")</label>
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>@lang("Prénom(s)")</label>
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>@lang("Adresse Email")</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required>
                            </div>

                            <div class="form-group col-lg-6">
                                <label>@lang('Téléphone')</label>
                                <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>@lang('Adresse')</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                             <div class="form-group col-lg-4">
                                <label>@lang("Nom d'utilisateur")</label>
                                <input type="text" class="form-control" name="username" value="{{ old("Nom d'utilisateur") }}"
                                    required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>@lang("Mot de Passe")</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang("Confirmerer Mot de Passe")</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
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
    <x-back route="{{ route('admin.magasin.manager.index') }}" />
@endpush
