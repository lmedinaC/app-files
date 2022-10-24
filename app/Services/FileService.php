<?php

namespace App\Services;

use App\Models\File;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function search($searchs)
    {
        [
            'per_page' ?? 15 => $per_page,
            'start_date' ?? null => $date_start,
            'end_date' ?? null => $date_end,
            'name' ?? null => $name,
            'user_id' ?? null => $user_id
        ] = $searchs;

        $data =  $this->fileRepository->search(
            $per_page,
            $date_start,
            $date_end,
            $name,
            $user_id
        );

        return $data;
    }

    public function createByUser(UploadedFile $file, $user_id): File
    {
        $path = $this->fileRepository->putFile($file);
        $data = $this->fileRepository->create(
            $file->getClientOriginalName(),
            $path,
            $user_id,
        );

        return $data;
    }

    public function createMultiplesByUser(array $files, $user_id): void
    {
        $filesStore = $this->fileRepository->putFiles($files);
        foreach($filesStore as $key=>$file){
            $file[$key]['user_id'] = $user_id;
        }
        $this->fileRepository->insert($filesStore);
    }


    public function softDestroyByUser(int $file_id, int $user_id)
    {
        $file = $this->fileRepository->getFileByUserId($file_id, $user_id);
        $deletedPath = $this->fileRepository->softDeleteStorage($file);
        $file = $this->fileRepository->update(
            $file,
            $file->name,
            $deletedPath,
            $file->user_id
        );
        $this->fileRepository->softDelete($file);
    }

    public function hardDestroyByUser(int $file_id, int $user_id)
    {
        $file = $this->fileRepository->getFileWithDeletedByUserId($file_id, $user_id);
        $this->fileRepository->hardDeleteStorage($file);
        $this->fileRepository->hardDelete($file);
    }
}
