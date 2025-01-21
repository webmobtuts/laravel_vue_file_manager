<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Controllers\Controller;
use App\Services\DirectoryNavigationService;
use Illuminate\Http\Request;

class DirectoryNavigationController extends Controller
{
    public function __construct(protected DirectoryNavigationService $directoryNavigationService)
    {
    }

    public function navigate(Request $request)
    {
        return $this->directoryNavigationService->navigate($request->input('dir'));
    }
}
