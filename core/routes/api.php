<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;  


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::match(['POST'],'getupdateapp', [AuthController::class, 'getUpdateapp']); 
Route::match(['POST'],'connexion', [AuthController::class, 'connexion']);
Route::match(['POST'],'update-info', [AuthController::class, 'updateInfo']);
Route::match(['POST'],'get-commandes', [AuthController::class, 'getCommandes']);
Route::match(['POST'],'update-commande-livraison', [AuthController::class, 'updateCommandeLivraison']);
Route::match(['POST'],'update-commande-paiement', [AuthController::class, 'updateCommandePaiement']);
Route::match(['POST'],'update-commande-annulation', [AuthController::class, 'updateCommandeAnnulation']);
Route::match(['POST'],'update-client', [AuthController::class, 'updateClient']);
Route::match(['POST'],'get-commandes-confirm', [AuthController::class, 'getCommandesConfirm']);
Route::match(['POST'],'get-commandes-credit', [AuthController::class, 'getCommandesCredit']);
Route::match(['POST'],'get-commandes-annulee', [AuthController::class, 'getCommandesAnnulee']);