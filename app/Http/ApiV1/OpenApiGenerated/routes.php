<?php

/**
 * NOTE: This file is auto generated by OpenAPI Generator.
 * Do NOT edit it manually. Run `php artisan openapi:generate-server`.
 */

use App\Http\ApiV1\Modules\Accounts\Controllers\AccountsController;
use App\Http\ApiV1\Modules\Nomenclatures\Controllers\NomenclaturesController;
use App\Http\ApiV1\Modules\ReceiptDocuments\Controllers\ReceiptDocumentsController;
use App\Http\ApiV1\Modules\ServiceGroups\Controllers\ServiceGroupsController;
use App\Http\ApiV1\Modules\Services\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::post('accounts', [AccountsController::class, 'create']);
Route::get('accounts/{id}', [AccountsController::class, 'get']);
Route::put('accounts/{id}', [AccountsController::class, 'replace']);
Route::delete('accounts/{id}', [AccountsController::class, 'delete']);
Route::patch('accounts/{id}', [AccountsController::class, 'patch']);
Route::post('accounts:mass-delete', [AccountsController::class, 'massDelete']);
Route::post('accounts:search', [AccountsController::class, 'search']);
Route::post('nomenclatures', [NomenclaturesController::class, 'create']);
Route::get('nomenclatures/{id}', [NomenclaturesController::class, 'get']);
Route::put('nomenclatures/{id}', [NomenclaturesController::class, 'replace']);
Route::delete('nomenclatures/{id}', [NomenclaturesController::class, 'delete']);
Route::patch('nomenclatures/{id}', [NomenclaturesController::class, 'patch']);
Route::post('nomenclatures:mass-delete', [NomenclaturesController::class, 'massDelete']);
Route::post('nomenclatures:search', [NomenclaturesController::class, 'search']);
Route::post('nomenclatures:search-one', [NomenclaturesController::class, 'searchOne']);
Route::post('service-groups', [ServiceGroupsController::class, 'create']);
Route::get('service-groups/{id}', [ServiceGroupsController::class, 'get']);
Route::put('service-groups/{id}', [ServiceGroupsController::class, 'replace']);
Route::delete('service-groups/{id}', [ServiceGroupsController::class, 'delete']);
Route::patch('service-groups/{id}', [ServiceGroupsController::class, 'patch']);
Route::post('service-groups:mass-delete', [ServiceGroupsController::class, 'massDelete']);
Route::post('service-groups:search', [ServiceGroupsController::class, 'search']);
Route::post('service-groups:tree', [ServiceGroupsController::class, 'getTree']);
Route::post('services', [ServicesController::class, 'create']);
Route::get('services/{id}', [ServicesController::class, 'get']);
Route::put('services/{id}', [ServicesController::class, 'replace']);
Route::delete('services/{id}', [ServicesController::class, 'delete']);
Route::patch('services/{id}', [ServicesController::class, 'patch']);
Route::post('services:mass-delete', [ServicesController::class, 'massDelete']);
Route::post('services:search', [ServicesController::class, 'search']);
Route::post('services:search-one', [ServicesController::class, 'searchOne']);
Route::post('receipt-documents', [ReceiptDocumentsController::class, 'create']);
Route::get('receipt-documents/{id}', [ReceiptDocumentsController::class, 'get']);
Route::put('receipt-documents/{id}', [ReceiptDocumentsController::class, 'replace']);
Route::delete('receipt-documents/{id}', [ReceiptDocumentsController::class, 'delete']);
Route::patch('receipt-documents/{id}', [ReceiptDocumentsController::class, 'patch']);
Route::post('receipt-documents/{id}:set-fix', [ReceiptDocumentsController::class, 'setStatusToFix']);
Route::post('receipt-documents/{id}:set-cancel', [ReceiptDocumentsController::class, 'setStatusToCancel']);
Route::post('receipt-documents/{id}:set-draft', [ReceiptDocumentsController::class, 'setStatusToDraft']);
Route::post('receipt-documents:search', [ReceiptDocumentsController::class, 'search']);
Route::post('receipt-documents:search-one', [ReceiptDocumentsController::class, 'searchOne']);
