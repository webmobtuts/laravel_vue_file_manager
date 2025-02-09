<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Controllers\Controller;
use App\Services\FileActionsService;
use Illuminate\Http\Request;

class FileActionsController extends Controller
{
    function __construct(protected FileActionsService $fileActionsService)
    {
    }

    public function postNewFile(Request $request)
    {
        if(!$request->input('filename')) {
            return false;
        }

        if($request->input('type') === 'file') {
          return $this->fileActionsService->createFile($request->input('path'), $request->input('filename'), $request->input('overwrite'));
        } else {
          return $this->fileActionsService->createDirectory($request->input('path'), $request->input('filename'), $request->input('overwrite'));
        }
    }

    public function postUpload(Request $request)
    {
        return $this->fileActionsService->uploadFile($request);
    }

    public function postFilesForDownload(Request $request)
    {
        return $this->fileActionsService->prepareDownloadedFiles($request);
    }

    public function getDownloadFile(Request $request)
    {
        return $this->fileActionsService->downloadFile($request);
    }

    public function postDelete(Request $request)
    {
        return $this->fileActionsService->deleteFiles($request);
    }

    public function postRename(Request $request)
    {
        return $this->fileActionsService->renameFile($request);
    }

    public function postCopy(Request $request)
    {
        return $this->fileActionsService->copyFile($request);
    }

    public function postMove(Request $request)
    {
        return $this->fileActionsService->moveFile($request);
    }

    public function postShowEditedFile(Request $request)
    {
        return $this->fileActionsService->getFileToEdit($request);
    }

    public function postEditedFile(Request $request)
    {
        return $this->fileActionsService->saveEditedFile($request);
    }
}
