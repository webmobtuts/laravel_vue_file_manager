<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManager\FileActionsController;
use App\Http\Controllers\FileManager\DirectoryNavigationController;

Route::get('navigate', [DirectoryNavigationController::class, 'navigate']);
Route::controller(FileActionsController::class)->group(function () {
    Route::post('new-file', 'postNewFile');
    Route::post('upload', 'postUpload');
    Route::post('get-file-urls', 'postFilesForDownload');
    Route::get('download', 'getDownloadFile');
    Route::post('del-files', 'postDelete');
    Route::post('rename-file', 'postRename');
    Route::post('cp-file', 'postCopy');
    Route::post('mv-file', 'postMove');
    Route::post('edited-file', 'postShowEditedFile');
    Route::post('save-edited-file', 'postEditedFile');
});
