<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {

    //Manager Login
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    //Manager Password Forgot
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
        Route::middleware('manager')->group(function () {
            //Home Controller
            Route::controller('ManagerController')->group(function () {
                Route::get('dashboard', 'dashboard')->name('dashboard');

                //Manage Profile
                Route::get('password', 'password')->name('password');
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile/update', 'profileUpdate')->name('profile.update.data');
                Route::post('password/update', 'passwordUpdate')->name('password.update.data');

                //Manage Magasin
                Route::name('magasin.')->prefix('magasin')->group(function () {
                    Route::get('list', 'magasinList')->name('index');
                    Route::get('income', 'magasinIncome')->name('income');
                });
            });
            //Manage Staff
            Route::controller('StaffController')->name('staff.')->prefix('staff')->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('list', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('dashboard/{id}', 'staffLogin')->name('login');
            });

            //Manage Livraison
            Route::controller('LivraisonController')->name('livraison.')->prefix('livraison')->group(function () {
                Route::get('send', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('get/produit', 'getProduit')->name('get.produit');
                Route::post('update/{id}', 'update')->name('update');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::get('details/{id}', 'details')->name('details');

                // Route::get('list', 'livraisonInfo')->name('index'); 
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
                Route::get('credit/list/total', 'credit')->name('manage.credit');
                Route::get('annule/list/total', 'annule')->name('manage.annule');
                Route::delete('delete/{id}', 'destroy')->name('delete');
            });

            Route::controller('LivraisonSettingController')->name('livraison.')->prefix('livraison')->group(function () {

                Route::name('categorie.')->prefix('manage')->group(function () {
                    Route::get('categorie', 'categorieIndex')->name('index');
                    Route::post('categorie/store', 'categorieStore')->name('store');
                    Route::post('status/{id}', 'status')->name('status');
        
                    Route::get('produit/', 'produitIndex')->name('produit.index');
                    Route::post('produit/store', 'produitStore')->name('produit.store');
                    Route::post('produit/status/{id}', 'produitStatus')->name('produit.status');
        
                    Route::get('client/', 'clientIndex')->name('client.index');
                    Route::post('client/store', 'clientStore')->name('client.store');
                    Route::post('client/status/{id}', 'clientStatus')->name('client.status');
                    Route::post('client/delete/{id}', 'clientDelete')->name('client.delete');
                    Route::get('/exportClientsExcel', 'exportExcel')->name('client.exportExcel.clientAll');
                    Route::post('/client/uploadcontent', 'uploadContent')->name('client.uploadcontent');
                }); 
            });

            Route::controller('ManagerTicketController')->prefix('ticket')->name('ticket.')->group(function () {
                Route::get('/', 'supportTicket')->name('index');
                Route::get('/new', 'openSupportTicket')->name('open');
                Route::post('/create', 'storeSupportTicket')->name('store');
                Route::get('/view/{ticket}', 'viewTicket')->name('view');
                Route::post('/reply/{ticket}', 'replyTicket')->name('reply');
                Route::post('/close/{ticket}', 'closeTicket')->name('close');
                Route::get('/download/{ticket}', 'ticketDownload')->name('download');
            });
        });
    });
});
