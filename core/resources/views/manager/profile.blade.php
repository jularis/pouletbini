@extends('manager.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-4 mb-30">
            <div class="card b-radius--5 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-flex p-3 bg--primary align-items-center">
                        <div class="avatar avatar--lg">
                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $manager->image, getFileSize('userProfile')) }}"
                                alt="@lang('Image')">
                        </div>
                        <div class="ps-3">
                            <h4 class="text--white">{{ __($manager->fullname) }}</h4>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Nom')
                            <span class="fw-bold">{{ __($manager->fullname) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("Nom d'utilisateur")
                            <span class="fw-bold">{{ __($manager->username) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email')
                            <span class="fw-bold">{{ $manager->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Magasin')
                            <span class="fw-bold">{{ __($manager->magasin->name) }}</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang("Information Profil")</h5>

                    <form action="{{ route('manager.profile.update.data') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                    style="background-image: url({{ getImage(getFilePath('userProfile') . '/' . $manager->image, getFileSize('userProfile')) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                    id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--success">@lang("Charger Image")</label>
                                                <small class="mt-2  ">@lang("Fichiers Autorisés"): <b>@lang('jpeg'),
                                                        @lang('jpg').</b> @lang('Image will be resized into 400x400px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>@lang("Prénom(s)")</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ $manager->firstname }}" required>
                                </div>

                                <div class="form-group ">
                                    <label>@lang("Nom de Famille")</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ $manager->lastname }}" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Email')</label>
                                    <input class="form-control" type="email" name="email" value="{{ $manager->email }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang("Envoyer")</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
        <a href="{{ route('manager.password') }}" class="btn btn-sm btn-outline--primary">
            <i class="las la-key"></i>@lang("Paramètre Mot de Passe")
        </a>
    </div>
@endpush
