<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TimeTracker;
use App\Http\Controllers\Admin\SaveTimerDataAuto;
use App\Http\Controllers\Admin\Pagination;
// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
       
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('time-tracker', [TimeTracker::class, 'showMessage'])->name('view.timetracker');
    Route::post('time-submit', [TimeTracker::class, 'savetime']);
    Route::post('auto-save-timerdata', [SaveTimerDataAuto::class, 'saveTimerData'])->name('save.timerdata');
    Route::get('set-page', [Pagination::class, 'setListPerPage'])->name('set.pegination');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
