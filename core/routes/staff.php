<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {

    //Staff Login
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    //Staff Password Forgot
    Route::controller('ForgotPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    //Manager Password Rest
    Route::controller('ResetPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('reset.form');
        Route::post('password/reset/change', 'reset')->name('change');
    });
});

Route::middleware('auth')->group(function () {
    Route::middleware(['check.status'])->group(function () {
        Route::middleware('staff')->group(function () {
            //Home Controller
            Route::controller('StaffController')->group(function () {
                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('password', 'password')->name('password');
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile/update', 'profileUpdate')->name('profile.update.data');
                Route::post('password/update', 'passwordUpdate')->name('password.update.data');
                Route::post('ticket/delete/{id}', 'ticketDelete')->name('ticket.delete');

                //Manage Magasin
                Route::name('magasin.')->prefix('magasin')->group(function () {
                    Route::get('list', 'magasinList')->name('index');
                    Route::get('income', 'magasinIncome')->name('income');
                });
            });
            Route::controller('LivraisonController')->name('livraison.')->prefix('livraison')->group(function () {
                
                Route::get('invoice/{id}', 'invoice')->name('invoice');
                Route::get('delivery/list', 'delivery')->name('delivery.list');
                Route::get('details/{id}', 'details')->name('details');
                Route::post('payment', 'payment')->name('payment');
                Route::post('delivery/store', 'deliveryStore')->name('delivery');
                Route::get('list', 'livraisonList')->name('manage.list');
                Route::get('date/search', 'livraisonDateSearch')->name('date.search');
                Route::get('search', 'livraisonSearch')->name('search');
                Route::get('send/list', 'sentLivraisonList')->name('manage.sent.list');
                Route::get('received/list', 'receivedLivraisonList')->name('received.list');
                //New Route
                Route::get('sent/queue', 'sentQueue')->name('sent.queue');
                Route::post('dispatch-all/', 'livraisonAllDispatch')->name('dispatch.all');
                Route::get('dispatch', 'livraisonDispatch')->name('dispatch');
                Route::post('status/{id}', 'dispatched')->name('dispatched');
                Route::get('upcoming', 'upcoming')->name('upcoming');
                Route::post('receive/{id}', 'receive')->name('receive');
                Route::get('delivery/queue', 'deliveryQueue')->name('delivery.queue');
                Route::get('delivery/list/total', 'delivered')->name('manage.delivered');
                
            });

            Route::controller('LivraisonController')->prefix('cash')->group(function () {
                Route::get('collection', 'cash')->name('cash.livraison.income');
            });

            Route::controller('StaffTicketController')->prefix('ticket')->name('ticket.')->group(function () {
                Route::get('/', 'supportTicket')->name('index');
                Route::get('new', 'openSupportTicket')->name('open');
                Route::post('create', 'storeSupportTicket')->name('store');
                Route::get('view/{ticket}', 'viewTicket')->name('view');
                Route::post('reply/{ticket}', 'replyTicket')->name('reply');
                Route::post('close/{ticket}', 'closeTicket')->name('close');
                Route::get('download/{ticket}', 'ticketDownload')->name('download');
            });
        });
    });
});
