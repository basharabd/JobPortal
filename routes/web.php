<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobsController;
use App\Http\Controllers\Admin\JobTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware'=>'CheckRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/delete', [UserController::class, 'destroy'])->name('admin.user.destroy');

    // Admin Jobs Route
    Route::get('/jobs', [JobsController::class, 'index'])->name('admin.jobs');
    Route::get('/job/edit/{id}', [JobsController::class, 'edit'])->name('admin.jobs.edit');
    Route::put('/job/update/{id}', [JobsController::class, 'update'])->name('admin.jobs.update');
    Route::delete('/job/delete', [JobsController::class, 'destroy'])->name('admin.job.destroy');

    // Admin Job Applications
    Route::get('/job/application', [JobApplicationController::class, 'index'])->name('admin.jobApplication');
    Route::delete('/job/application/delete', [JobApplicationController::class, 'destroy'])->name('admin.jobApplication.destroy');


    // Admin Category Route
    Route::get('/job/categories', [CategoriesController::class, 'index'])->name('admin.categories.index');
    Route::get('/job/category/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
    Route::post('/job/category/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
    Route::get('/job/category/edit/{id}', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/job/category/update/{id}', [CategoriesController::class, 'update'])->name('admin.categories.update');
    Route::get('/job/category/delete', [CategoriesController::class, 'destroy'])->name('admin.categories.destroy');


        // Admin Job Type Route
        Route::get('/job/jobTypes', [JobTypeController::class, 'index'])->name('admin.jobTypes.index');
        Route::get('/job/jobType/create', [JobTypeController::class, 'create'])->name('admin.jobTypes.create');
        Route::post('/job/jobType/store', [JobTypeController::class, 'store'])->name('admin.jobTypes.store');
        Route::get('/job/jobType/edit/{id}', [JobTypeController::class, 'edit'])->name('admin.jobTypes.edit');
        Route::put('/job/jobType/update/{id}', [JobTypeController::class, 'update'])->name('admin.jobTypes.update');
        Route::get('/job/jobType/delete', [JobTypeController::class, 'destroy'])->name('admin.jobTypes.destroy');

});
Route::group(['prefix' => 'account'], function () {

    // Guest Routes
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });

    // Authenticated Routes
    Route::group(['middleware' => 'auth'], function () {
        // Add routes that require authentication here
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('/update/password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/update/profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update/pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/saved/jobs', [AccountController::class, 'savedJobs'])->name('account.savedJobs');
        Route::post('/remove/job', [AccountController::class, 'removeSavedJob'])->name('job.removeSavedJob');


    });
});

Route::group(['prefix' => 'job' ,'middleware' => 'auth'], function () {
    Route::get('/create', [JobController::class, 'create'])->name('job.create');
    Route::post('/save', [JobController::class, 'saveJob'])->name('job.saveJob');
    Route::get('/my/jobs', [JobController::class, 'myJob'])->name('job.myJob');
    Route::get('/edit/{jobId}', [JobController::class, 'edit'])->name('job.edit');
    Route::post('/update/{jobId}', [JobController::class, 'updateJob'])->name('job.update');
    Route::post('/delete', [JobController::class, 'deleteJob'])->name('job.delete');
    Route::get('/my/application', [JobController::class, 'myJobApplications'])->name('job.myJobApplications');
    Route::post('/remove/application', [JobController::class, 'removeJob'])->name('job.removeJobApplications');

});


// forgot Password
Route::get('/forgot/password', [AccountController::class, 'forgotPassword'])->name('account.forgotPassword');
Route::post('/process/forgot/password', [AccountController::class, 'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset/password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/process/reset/password', [AccountController::class, 'processResetPassword'])->name('account.processResetPassword');





Route::get('/jobs', [JobController::class, 'index'])->name('job.index');
Route::get('/job/detail/{id}', [JobController::class, 'detail'])->name('job.detail');
Route::post('/job/apply', [JobController::class, 'applyJop'])->name('job.apply');
Route::post('/job/saved', [JobController::class, 'savedJob'])->name('job.saved');

//