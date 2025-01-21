<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class DirectoryNavigationService
{
    protected string $startDir = "";

    public function __construct()
    {
        $this->startDir = env("UPLOADS_DIR");
    }

    public function navigate($directory)
    {
        try {
            $directory = strtolower($directory);

            if($directory === DIRECTORY_SEPARATOR
                || $directory === DIRECTORY_SEPARATOR . $this->startDir
                || $directory === str_replace(DIRECTORY_SEPARATOR, "", $this->startDir)
                ) {
                $directory = $this->startDir;
            }

            // Confirming that user must not go outside of uploads dir
            if(!str_starts_with($directory, $this->startDir))
                throw new \Exception('Invalid directory!');

            if(!File::exists($directory)) {
                throw new \Exception('Directory not exists');
            }

            if(!File::isDirectory($directory)) {
                throw new \Exception('Not a directory');
            }

            $fileList = $this->listFiles($directory);

            return response()->json(data: ['status' => 'success', 'data' => $fileList]);
        } catch (\Throwable $ex) {
            return response()->json(data: ['status' => 'error', 'message' => $ex->getMessage()], status:500);
        }
    }

    public function listFiles($directory)
    {
        $dirs = [];
        $files = [];

        foreach (new \FilesystemIterator(public_path($directory), 0) as $file) {
            if($file->getFileName() == '.') continue;       // Skip dot

            if($directory == $this->startDir && $file->getFileName() == '..') continue;

            if($file->isDir()) {
                $dirs[] = $this->toFileArr($file);
            }

            if($file->isFile()) {
                $files[] = $this->toFileArr($file);
            }
        }

        return [...$dirs, ...$files];
    }

    private function toFileArr(\SplFileInfo $file)
    {
        $type = $file->getType() === 'dir' ? 'directory' : (@is_array(getimagesize($file->getPathname()))? 'file-image' : 'file-' . pathinfo($file->getFilename(), PATHINFO_EXTENSION));

        return [
            'name' => $file->getFilename(),
            'type' => $type,
            'size' => $this->formatBytes($file->getSize()),
            'size_in_bytes' => $file->getSize(),
            'modified_date' => date('Y-m-d h:i:s', $file->getMTime()),
            'mime_type' => mime_content_type($file->getPathname()),
            'permission' => substr(sprintf('%o', $file->getPerms()), -4),
        ];
    }

    private function formatBytes($bytes, $precision = 2) {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;

        if ($bytes < $kilobyte) {
            return $bytes . ' B';
        } elseif ($bytes < $megabyte) {
            return round($bytes / $kilobyte, $precision) . ' KB';
        } elseif ($bytes < $gigabyte) {
            return round($bytes / $megabyte, $precision) . ' MB';
        } else {
            return round($bytes / $gigabyte, $precision) . ' GB';
        }
    }
}
