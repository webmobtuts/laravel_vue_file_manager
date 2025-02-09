<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FileActionsService
{
    private $separator = "/";

    function __construct()
    {
        $this->separator = DIRECTORY_SEPARATOR;
    }

    public function createFile(string $path, string $filename, bool $overwrite = false)
    {
        try {
            $path = $this->getValidPath($path);

            if($overwrite) {
                File::delete($path . $filename);
            } else {
                if(File::exists($path . $filename)) {
                    throw new \Exception("File exists");
                }
            }

            File::put($path . $filename, '');

            return response()->json(data: ['status' => 'success', 'data' => $path . $filename]);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function createDirectory(string $path, string $directoryName, bool $overwrite = false)
    {
        try {
             $path = $this->getValidPath($path);

            if($overwrite) {
                File::deleteDirectory($path . $directoryName);
            }

            File::makeDirectory($path . $directoryName, 0755);

            return response()->json(data: ['status' => 'success', 'data' => $path . $directoryName]);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                "file" => "required|mimes:jpg,png,gif,jpeg,txt,json,php,zip,gz|max:10000",
                "path" => "required"
            ]);

            $path = $this->getValidPath($request->input('path'));
            $overwrite = $request->input('overwrite');

            $file = $request->file('file');

            $name = $file->getClientOriginalName();

            if($overwrite) {
                File::delete($path . $name);
            } else {
                if(File::exists($path . $name)) {
                    return response()->json(data: ['status' => 'error', 'is_duplicate' => 1, 'message' => 'File already exist'], status:500);
                }
            }

            $file->move($path, $name);

            return response()->json(data: ['status' => 'success', 'data' => $name]);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function prepareDownloadedFiles(Request $request)
    {
        if(count($request->input('files'))) {
            $files = [];
            $path = $request->input('path');

            $files = array_map(fn($file) => url("/api/download?filename=$file&path=$path"), $request->input('files'));

            return response()->json(data: ['status' => 'success', 'data' => $files]);
        }

        return response()->json(data: ['status' => 'error'], status: 500);
    }

    public function downloadFile(Request $request)
    {
        if($request->input('filename') && $request->input('path')) {
            $file = $this->getValidPath($request->input('path')) . $request->input('filename');

            return Response::download(public_path($file));
        }

        return false;
    }

    public function deleteFiles(Request $request)
    {
        try {
            if($request->input('files') && $request->input('file_path')) {
                $path = $this->getValidPath($request->input('file_path'));

                foreach ($request->input('files') as $file) {
                    if($file['type'] === 'directory') {
                        File::deleteDirectory(public_path($path.$file['name']));
                    } else {
                        File::delete(public_path($path.$file['name']));
                    }
                }
            }

            return response()->json(data: ['status' => 'success', 'message' => 'Deleted successfully']);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function renameFile(Request $request)
    {
        try {

            if($request->input('old_name') && $request->input('file_path')) {
                $path = $this->getValidPath($request->input('file_path'));

                if(File::exists(public_path($path.$request->input('new_name')))) {
                    throw new \Exception("A file already exist with this name");
                }

                if(File::exists(public_path($path.$request->input('old_name')))) {
                    rename(public_path($path.$request->input('old_name')), public_path($path.$request->input('new_name')));
                }

            }

            return response()->json(data: ['status' => 'success', 'message' => 'Renamed successfully']);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function copyFile(Request $request)
    {
        $startDir = env("UPLOADS_DIR");

        try {
            list($targetPath, $destPath) = $this->preCopyCheck($request, $startDir);

            $files = $request->input('files');

            foreach ($files as $file) {
                if($file['type'] === 'directory') {
                    if(File::exists(public_path($destPath.$file['name']))) {
                        File::deleteDirectory(public_path($destPath.$file['name']));
                    }

                    File::copyDirectory($targetPath.$file['name'], $destPath.$file['name']);
                } else {
                    if(File::exists(public_path($destPath.$file['name']))) {
                        File::delete(public_path($destPath.$file['name']));
                    }

                    File::copy($targetPath.$file['name'], $destPath.$file['name']);
                }

            }

            return response()->json(data: ['status' => 'success', 'message' => 'Copied successfully']);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function moveFile(Request $request)
    {
        $startDir = env("UPLOADS_DIR");

        try {

            list($targetPath, $destPath) = $this->preCopyCheck($request, $startDir);

            $files = $request->input('files');

            foreach ($files as $file) {
                if($file['type'] === 'directory') {
                    if(File::exists(public_path($destPath.$file['name']))) {
                        File::deleteDirectory(public_path($destPath.$file['name']));
                    }

                    File::moveDirectory($targetPath.$file['name'], $destPath.$file['name']);
                } else {
                    if(File::exists(public_path($destPath.$file['name']))) {
                        File::delete(public_path($destPath.$file['name']));
                    }

                    File::move($targetPath.$file['name'], $destPath.$file['name']);
                }

            }

            return response()->json(data: ['status' => 'success', 'message' => 'Moved successfully']);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function getFileToEdit(Request $request)
    {
        try {

            if(!$request->input("file_path")) {
                throw new \Exception("File path required");
            }

            $mimeType = $request->input("mime_type");

            if(!str_starts_with($mimeType, "text/") && !in_array($mimeType, ["application/x-empty", "application/json", "application/ld+json"])) {
                throw new \Exception("File can't be edited");
            }

            $contents = File::get(public_path($request->input("file_path")));

            return response()->json(data: ['status' => 'success', 'contents' => $contents]);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function saveEditedFile(Request $request)
    {
        try {

            if (!$request->input("file_path")) {
                throw new \Exception("File path required");
            }

            File::put(public_path($request->input("file_path")), $request->input("contents"));

            return response()->json(data: ['status' => 'success', 'message' => "File saved successfully"]);

        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    private function getValidPath($path) : string
    {
        return $path . (!str_ends_with($path, $this->separator) ? $this->separator : '');
    }

    private function preCopyCheck(Request $request, string $startDir)
    {
        if(!$request->input('dest_path')) {
            throw new \Exception("destination path required");
        }

        if(!$request->input('files')) {
            throw new \Exception("files required");
        }

        $destPath = $this->getValidPath($request->input('dest_path'));
        $targetPath = $this->getValidPath($request->input('target_path'));

        if($destPath == $targetPath) {
            throw new \Exception("Please choose a different path");
        }

        if(!str_starts_with($destPath, $startDir)) {
            if(File::exists(public_path($targetPath.$destPath))) {
                $destPath = $targetPath.$destPath;
            } else {
                throw new \Exception("invalid destination path");
            }
        }

        return [$targetPath, $destPath];
    }
}
