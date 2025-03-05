<?php

use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\McqCategoryController;
use App\Http\Controllers\McqMasterController;
use App\Http\Controllers\McqSetsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProgrammingCategoryController;
use App\Http\Controllers\ProgrammingQuestionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[EmployerController::class,'index'])->name('login');
Route::get('/register',[EmployerController::class,'register'])->name('register');
Route::get('/subscription-employer',[EmployerController::class,'employer_subscription'])->name('subscription');

Route::get('/logout',[EmployerController::class,'logout'])->name('logout');
Route::get('/hiring',[EmployerController::class,'hiring'])->name('hiring');
Route::get('/view-job/{id}',[EmployerController::class,'view_job'])->name('view-job');
Route::get('/get-employer/{id}',[EmployerController::class,'get_employer'])->name('get-employer');
Route::get('/sub-industry/{id}',[EmployerController::class,'sub_industry'])->name('sub-industry');


Route::get('/plan-process/{plan_id}/{employer_id}', [EmployerController::class, 'plan_process'])->name('plan-process');
Route::post('/process-payment',[PaymentController::class,'process_payment'])->name('process-payment');
Route::get('/payment-success',[PaymentController::class,'payment_success'])->name('payment-success');
Route::get('/payment-failure',[PaymentController::class,'payment_failure'])->name('payment-failure');

Route::post('/save-application',[EmployerController::class,'save_application'])->name('save-application');
Route::post('/save-company-info',[EmployerController::class,'save_company_info'])->name('save-company-info');
Route::post('save-employer',[EmployerController::class,'save_employer'])->name('save-employer');
Route::post('verify-employer',[EmployerController::class,'verify_employer'])->name('verify-employer');

Route::post('auth-employer',[EmployerController::class,'auth_employer'])->name('auth-employer');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[EmployerController::class,'dashboard'])->name('dashboard');
    Route::get('/profile',[EmployerController::class,'profile'])->name('profile');
    Route::post('/update-employer',[EmployerController::class,'update_employer'])->name('update-employer');
    Route::post('/update-company-info',[EmployerController::class,'update_company_info'])->name('update-company-info');

    Route::resource('jobs',JobsController::class);
    Route::get('/jobs/applicant/{id}',[JobsController::class,'applicant'])->name('applicant');
    Route::get('/get-applicant/{id}',[JobsController::class,'get_applicant'])->name('get-applicant');
    Route::get('/subscriptions',[JobsController::class,'subscriptions'])->name('subscriptions');
    Route::get('/buy-subscription/{id}',[JobsController::class,'buy_subscription'])->name('buy-subscription');
    Route::resource('mcqs-category',McqCategoryController::class);

    Route::resource('mcqs',McqMasterController::class);    
    Route::post('/mcqs-import', [McqMasterController::class, 'import'])->name('mcqs-import');
    Route::get('mcqs-export', [McqMasterController::class, 'export'])->name('mcqs-export');
    Route::get('mcqs-question', [McqMasterController::class, 'fetch_question'])->name('mcqs-question');
    Route::resource('mcqs-sets',McqSetsController::class);
    Route::resource('program-category',ProgrammingCategoryController::class);
    Route::resource('programmng-question',ProgrammingQuestionsController::class);

});