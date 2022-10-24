<?php

namespace App\Repositories;

use App\Http\Resources\FileResource;
use App\Models\File;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Throw_;

class FileRepository
{
    protected $model;

    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function search(
        $per_page = 15,
        $start_date = null,
        $end_date = null,
        $name = null,
        $user_id = null
    ) {
        $filters = [];
        $data = $this->model;
        if (isset($user_id)) {
            $data->whereRaw("created_at>='$start_date' AND created_at<='$end_date'");
        }
        if (isset($start_date) && isset($end_date)) {
            $data->whereRaw("created_at>='$start_date' AND created_at<='$end_date'");
            $filters['start_date'] = $start_date;
            $filters['end_date'] = $end_date;
        }
        if (isset($name)) {
            $data->where("name", 'like', "%" . $name . "%");
            $filters['name'] = $name;
        }
        $data =  $data->orderBy('created_at', 'desc')->paginate($per_page)->appends($filters);

        return FileResource::collection($data);
    }

    private function searchInStorageByFileName()
    {
    }

    public function softDelete(File $file)
    {
        if(!$file){
            throw new Exception("The file does not exist.");
        }
        $file->delete();
    }

    public function hardDelete(File $file)
    {
        if(!$file){
            throw new Exception("The file does not exist.");
        }
        $file->forceDelete();
    }

    public function getFileById($id): File
    {
        $data  = $this->model->find($id);
        return $data;
    }

    public function getFileByUserId($id,$user_id) : File
    {
        $data  = $this->model->where('id',$id)
            ->where('user_id',$user_id)->first();
        if(!$data){
            throw new Exception("The file does not exist.");
        }
        return $data;
    }

    public function getFileWithDeletedByUserId($id,$user_id): File
    {
        $data  = $this->model->withTrashed()->where('id',$id)
        ->where('user_id',$user_id)->first();
        if(!$data){
            throw new Exception("The file does not exist.");
        }
        return $data;
    }

    public function create(string $name, string $path, string $user_id): File
    {
        $data = $this->model->create(
            [
                'name' => $name,
                'path' => $path,
                'user_id' => $user_id
            ]
        );

        return $data;
    }

    public function insert(array $data): void
    {
        $data = $this->model->insert(
            $data
        );
    }

    public function update(File $file,string $name, string $path, string $user_id): File
    {
        $file->update(
            [
                'name' => $name,
                'path' => $path,
                'user_id' => $user_id
            ]
        );

        return $file;
    }

    public function putFile(UploadedFile $file): string
    {
        $path = Storage::putFile('public', $file);
        return $path;
    }

    public function putFiles(array $files): array
    {
        $data = [];
        foreach($files as $file){
            $path = Storage::putFile('public', $file);
            $data[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path
            ];
        }
        return $data;
    }


    public function softDeleteStorage(File $file): string
    {
        if(!Storage::exists($file->path)){
            throw new Exception("The file does not exist.");
        }
        $newPath = explode('/',$file->path)[1];
        Storage::move($file->path, "deleted/$newPath");
        return "deleted/$newPath";
    }

    public function hardDeleteStorage(File $file): void
    {
        if(!Storage::exists($file->path)){
            throw new Exception("The file does not exist.");
        }
        Storage::delete($file->path);
    }

}
