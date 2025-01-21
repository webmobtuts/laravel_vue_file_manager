<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManager\DirectoryNavigationController;

Route::get('navigate', [DirectoryNavigationController::class, 'navigate']);
